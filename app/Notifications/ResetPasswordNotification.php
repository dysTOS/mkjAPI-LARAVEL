<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    /**
     * The password reset url.
     *
     * @var string
     */
    public $url;

    /**
     * Create a notification instance.
     *
     * @param  string  $url
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via(User $notifiable)
    {
        return ['mail'];
    }

    public function toMail(User $notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Benachrichtigung zum Zurücksetzen des Passworts'))
            ->greeting('Hallo '.$notifiable->name.'!')
            ->line(Lang::get('Du erhältst diese E-Mail, weil wir eine Anfrage zum Zurücksetzen des Passworts für deinen Account erhalten haben.'))
            ->action(Lang::get('Passwort zurücksetzen'), $this->url)
            ->line(Lang::get('Dieser Link verliert seine Gültigkeit in :count Minuten.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('Wenn du kein Zurücksetzen des Passworts angefordert hast, ist keine weitere Aktion erforderlich.'))
            ->salutation(Lang::get('Mit freundlichen Grüßen, Roland Sams (Admin)'));
    }
}
