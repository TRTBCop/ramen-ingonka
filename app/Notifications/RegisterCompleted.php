<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsChannel;
use App\Notifications\Messages\MessagemeMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RegisterCompleted extends Notification
{
    use Queueable;

    protected array $variables;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $variables)
    {
        $this->variables = $variables;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return [SmsChannel::class];
    }

    public function toSms(object $notifiable): MessagemeMessage
    {
        $msg = <<<SMS
[리딩수학] 
개념으로 문제를 해결하는 1등들의 바른 수학 공부법, 리딩수학에서 시작하세요!

무료체험을 신청해 주셔서 감사합니다.
#{회원명}회원님은 #{시작일}부터 #{종료일}까지 무료체험을 할 수 있습니다.

무료체험은 크롬 브라우저 사용을 권장합니다.

▶학습지원센터 (1800-5039)
SMS;

        return (new MessagemeMessage())
            ->code('SJT_098264')
            ->subject('가입완료')
            ->morphs($notifiable)
            ->to($notifiable->parents_phone)
            ->line($msg);
    }
}
