<?php
defined('DS_ENGINE') or die('web_demon laughs');

class mail
{
    private static $name_from = 'BankrotSpy';
    private static $mail_from = 'no-reply@bankrot-spy.ru';

    public static function send($mail, $subject, $mail_body)
    {
        $subject = '=?utf-8?B?'.base64_encode($subject).'?=';

        $adds = array();
        $adds[] = 'MIME-Version: 1.0';
        $adds[] = 'From: =?utf-8?B?'.base64_encode(self::$name_from).'?= <'.self::$mail_from.'>';
        $adds[] = 'Reply-To: =?utf-8?B?'.base64_encode(self::$name_from).'?= <'.self::$mail_from.'>';
        $adds[] = 'Content-Type: text/html; charset="utf-8"';
        $adds = implode("\r\n", $adds);

        mail($mail, $subject, $mail_body, $adds);
    }
}