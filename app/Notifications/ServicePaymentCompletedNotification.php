<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsChannel;
use App\Notifications\Messages\MessagemeMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ServicePaymentCompletedNotification extends Notification
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

    /**
     * 이용권 결제 완료
     *
     * [리딩수학]
     * 주문 결제가 정상적으로 완료되었습니다.
     *
     * 〈결제안내〉
     * ▶ 상품정보 : {주문번호}
     * ▶ 주문번호 : {결제금액}
     * ▶ 결제금액 : {결제날짜}
     * ▶ 결제확인 : 마이페이지 〉 주문정보
     *
     * @param object $notifiable
     * @return MessagemeMessage
     */
    public function toSms(object $notifiable): MessagemeMessage
    {
        $msg = <<<SMS
 [리딩수학]
 이용권 결제가 정상적으로 완료되었습니다.
 
 〈결제안내〉
 ▶ 상품정보 : #{productName}
 ▶ 주문번호 : #{orderId}
 ▶ 결제금액 : #{amount}
 ▶ 결제확인 : 마이페이지 〉 주문정보
SMS;

        return (new MessagemeMessage())
            ->code('SJT_098643')
            ->subject('이용권 결제 완료')
            ->morphs($notifiable)
            ->to($notifiable->parents_phone)
            ->line($msg);
    }
}
