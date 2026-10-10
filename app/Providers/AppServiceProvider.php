<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory())->create(
                Dsn::fromString('brevo+api://' . config('services.brevo.key') . '@default')
            );
        });
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Restablecer contraseña — Clínica FIMOVE')
                ->greeting('Hola')
                ->line('Recibió este correo porque se solicitó restablecer la contraseña de su cuenta.')
                ->action('Restablecer contraseña', $url)
                ->line('El enlace vence en 60 minutos.')
                ->line('Si usted no lo solicitó, no tiene que hacer nada.')
                ->salutation('Clínica FIMOVE');
        });
    }
}