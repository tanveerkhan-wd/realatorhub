<?php

namespace App\Providers;

use App\Models\SettingModel;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
        $google_map_api_key=SettingModel::where('text_key',config('config.smtp_settings.google_map_api_key'))->first();
        $mail_driver=SettingModel::where('text_key',config('config.smtp_settings.mail_driver'))->first();
        $mail_host=SettingModel::where('text_key',config('config.smtp_settings.mail_host'))->first();
        $mail_port=SettingModel::where('text_key',config('config.smtp_settings.mail_port'))->first();
        $mail_encryption=SettingModel::where('text_key',config('config.smtp_settings.mail_encryption'))->first();
        $mail_username=SettingModel::where('text_key',config('config.smtp_settings.mail_username'))->first();
        $mail_password=SettingModel::where('text_key',config('config.smtp_settings.mail_password'))->first();
        $mail_from_address=SettingModel::where('text_key',config('config.smtp_settings.mail_from_address'))->first();
        if(!empty($mail_username)){
            $name_value =$mail_username->text_value;
            $mail_user_name =  trim($name_value,'"');
        }
        else{
            $mail_user_name = env('MAIL_USERNAME');
        }
        if(!empty($mail_password)) {
            $pwd = Crypt::decrypt($mail_password->text_value);
        }
        else{
            $pwd = env('MAIL_PASSWORD');
        }
        define('GOOGLE_MAP_KEY',$google_map_api_key ? $google_map_api_key->text_value : env('GOOGLE_MAPS_API_KEY'));
        define('EMAIL_DRIVER',$mail_driver ? $mail_driver->text_value : env('MAIL_DRIVER'));
        define('EMAIL_HOST',$mail_host ? $mail_host->text_value : env('MAIL_HOST'));
        define('EMAIL_PORT',$mail_port ? $mail_port->text_value : env('MAIL_PORT'));
        define('EMAIL_ENCRYPTION',$mail_encryption ? $mail_encryption->text_value : '');
        define('EMAIL_USERNAME',$mail_user_name);
        define('EMAIL_PASSWORD',$pwd);
        define('FROM_NAME', env('MAIL_FROM_NAME'));
        define('FROM_EMAIL', $mail_from_address?$mail_from_address->text_value: env('MAIL_FROM_ADDRESS'));

        $configuration = [
            'google_map_key'=>GOOGLE_MAP_KEY,
            'smtp_driver'    => EMAIL_DRIVER,
            'smtp_host'    => EMAIL_HOST,
            'smtp_port'    => EMAIL_PORT,
            'smtp_username'  => EMAIL_USERNAME,
            'smtp_password'  => EMAIL_PASSWORD,
            'smtp_encryption'  => EMAIL_ENCRYPTION,
            'from_email'    =>FROM_EMAIL,
            'from_name'    => FROM_NAME,
        ];

        $mailer = app()->makeWith('user.mailer', $configuration);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('user.mailer', function ($app, $parameters) {
            $google_map_key= Arr::get($parameters, 'google_map_key');
            $smtp_driver= Arr::get($parameters, 'smtp_driver');
            $smtp_host = Arr::get($parameters, 'smtp_host');
            $smtp_port = Arr::get($parameters, 'smtp_port');
            $smtp_username = Arr::get($parameters, 'smtp_username');
            $smtp_password = Arr::get($parameters, 'smtp_password');
            $smtp_encryption = Arr::get($parameters, 'smtp_encryption');

            $from_email = Arr::get($parameters, 'from_email');
            $from_name = Arr::get($parameters, 'from_name');

            $from_email = $parameters['from_email'];
            $from_name = $parameters['from_name'];
            $mail_user_name1 =  str_replace('"','\'',$smtp_username);
            //$userName = "'".$smtp_username."'";
//            echo $userName;exit;
            Config::set('services.google.map_key',$google_map_key);
            Config::set('mail.driver',$smtp_driver);
            Config::set('mail.host',$smtp_host);
            Config::set('mail.port',$smtp_port);
            Config::set('mail.encryption',$smtp_encryption);
            Config::set('mail.username',$smtp_username);
            Config::set('mail.password',$smtp_password);
            Config::set('mail.from.name',$from_name);
            Config::set('mail.from.address',$from_email);
            Config::set('services.stripe.secret',env('STRIPE_SECRET'));

            $transport = new \Swift_SmtpTransport($smtp_host, $smtp_port);
            $transport->setUsername($smtp_username);
            $transport->setPassword($smtp_password);
            $transport->setEncryption($smtp_encryption);

            $swift_mailer = new \Swift_Mailer($transport);

            $mailer = new Mailer($app->get('view'), $swift_mailer, $app->get('events'));
            $mailer->alwaysFrom($from_email, $from_name);
            $mailer->alwaysReplyTo($from_email, $from_name);

            return $mailer;
        });
    }
}
