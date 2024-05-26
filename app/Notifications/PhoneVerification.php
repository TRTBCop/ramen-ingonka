<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsChannel;
use App\Notifications\Messages\MessagemeMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PhoneVerification extends Notification
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
안녕하세요 리딩수학입니다.
회원님의 인증번호는 #{인증번호}입니다.
SMS;

        return (new MessagemeMessage())
            ->code('SJT_098265')
            ->subject('휴대폰인증')
            ->morphs($notifiable)
            ->to($notifiable->phone)
            ->variables([$notifiable->code])
            ->line($msg);
    }
}
