<?php

namespace App\Notifications\Messages;


use App\Models\Sms;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


class MessagemeMessage
{
    protected Logger $log;
    protected string $to;
    protected array $lines;
    protected string $apiKey;
    protected string $code;
    protected array $variables = [];
    protected string $subject;
    protected int $callback;
    protected bool $debug = false;
    protected object $morphs;


    /**
     * 예약발송 유무 -  0: 즉시발송, 1: 예약발송
     *
     */
    protected int $sendReserve = 0;


    /**
     * 예약발송일 - 2020|10|10|22|31|00
     * $send_reserve 1 인경우 발송
     *
     */
    protected string $sendReserveDate = '';


    /**
     * 대치문자 발송 - 0: 대치문자 없음, 1: 대치문자 발송
     *
     */
    protected int $nextType = 1;

    /**
     * SMS 연동 발송 및 문자 메시지 대치 발송
     */
    protected const SMS_URL = '221.139.14.136/APIV2/API/sms_send';

    /**
     * 알림톡 연동 발송 및 문자 메시지 대치 발송
     * @var string
     */
    protected const ALIMTALK_URL = '221.139.14.136/APIV2/API/alimtalk_send';


    /**
     * SmsMessage constructor.
     * @param array $lines
     */
    public function __construct(array $lines = [])
    {
        $this->log = new Logger('message');
        $this->log->pushHandler(new StreamHandler(storage_path('logs/message.log')));

        $this->lines = $lines;
        $this->debug = app()->environment('local');

        // Pull in config from the config/services.php file.
        $this->apiKey = config('services.messageme.api_key');
        $this->callback = config('services.messageme.callback');
    }

    public function code(string $code = ''): self
    {
        $this->code = $code;

        return $this;
    }

    public function variables(array $variables = []): self
    {
        $this->variables = $variables;

        return $this;
    }

    public function line($line = ''): self
    {
        $this->lines[] = $line;

        return $this;
    }

    public function to($to): self
    {
        $this->to = $to;

        return $this;
    }

    public function morphs($morphs): self
    {
        $this->morphs = $morphs;
        return $this;
    }

    public function subject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function debug($debug): self
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function send(): PromiseInterface|Response|bool
    {
        if (!$this->to || !$this->code) {
            throw new \Exception('필수 항목이 누락되었습니다.');
        }
        $msg = implode(PHP_EOL, $this->lines);

        foreach ($this->variables as $variable) {
            $msg = preg_replace('/#{.*}/Ui', $variable, $msg, 1);
        }

        $data = [
            'api_key' => $this->apiKey,
            'template_code' => $this->code,
            'variable' => implode('|', $this->variables),
            'callback' => $this->callback,
            'dstaddr' => $this->to,
            'send_reserve' => $this->sendReserve,
            'send_reserve_date' => $this->sendReserveDate,
            'next_type' => $this->nextType,
            'subject' => $this->subject,
            'msg' => $msg,
        ];


        /*
         * error code
        Code 설명 비고
        100 데이터 전송 성공 메시지 발송 성공
        155 API key/회원 상태
        175 카카오채널 오류 친구톡
        185 Cash 부족 알림톡/친구톡의 경우 대치발송 포함
        215 Template 관련 오류 알림톡
        225 Message length 오류
        235 대치내용 변수 오류 알림톡
        315 발신번호 오류
        */
        if (!$this->debug) { // 실환경에서만
            try {
                $response = Http::asForm()->post(self::ALIMTALK_URL, $data);
                $result = $response->json();
                if (!isset($result['result']) || $result['result'] != '100') {
                    $this->log->info('result', ['data' => $data, 'result' => $result]);
                    throw new Exception('발송실패 result('.($result['result'] ?? 0).')');
                }
            } catch (Exception $e) {
                $response = false;
            }
        } else {
            $response = true;
        }

        (new Sms())->fill([
            'model_type' => $this->morphs::class ?? null,
            'model_id' => $this->morphs->id ?? null,
            'subject' => $this->subject ?? '',
            'send_phone' => $this->callback,
            'dest_phone' => $this->to,
            'template_code' => $this->code,
            'msg' => $msg,
            'is_debug' => $this->debug
        ])->setActivitylogOptions([
            'description' => '문자발송'
        ])->save();


        return $response;
    }
}