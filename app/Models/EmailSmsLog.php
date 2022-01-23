<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSmsLog extends Model
{
    protected $table = 'email_log';
    public $timestamps = true;
    protected $fillable = ['user_id','email_id','subject',
        'email_content','email_status','email_error_message',
       'email_attempt'];

    const PENDING_EMAIL_STATUS = '1';

    const FAILED_EMAIL_STATUS = '2';

    const SUCCESS_EMAIL_STATUS = '3';

    const PENDING_SMS_STATUS = '1';

    const FAILED_SMS_STATUS = '2';

    const SUCCESS_SMS_STATUS = '3';
}
