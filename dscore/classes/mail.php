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
     $adds[] = 'Content-Type: text/plain; charset="utf-8"';
     $adds[] = 'From: =?utf-8?B?'.base64_encode(self::$name_from).'?= <'.self::$mail_from.'>';
     $adds[] = 'Reply-To: =?utf-8?B?'.base64_encode(self::$name_from).'?= <'.self::$mail_from.'>';
     $adds[] = 'Subject: '.$subject;
     $adds[] = 'Content-Type: text/plain; charset="utf-8"';
     $adds = implode("\n", $adds);

     mail($mail, $subject, $mail_body, $adds);
  }
}