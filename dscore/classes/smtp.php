<?php
  
class smtp
{
  private static $from_mail =  'analytic-spy@mail.ru';
  private static $from_mailer =  'Bankrot-Spy';
  
  private static function get_header($from_name, $from_mail, $to_name, $to_mail, $subject, $html = 0)
  {
    $header="Date: ".date("D, j M Y G:i:s")." +0700\r\n";
    $header.="From: =?utf-8?B?".base64_encode($from_name)."?= <".$from_mail.">\r\n";
    $header.="X-Mailer: The Bat! (v3.99.3) Professional\r\n";
    $header.="Reply-To: =?utf-8?B?".base64_encode($from_name)."?= <".$from_mail.">\r\n";
    $header.="X-Priority: 3 (Normal)\r\n";
    $header.="Message-ID: <172562218.".date("YmjHis")."@mail.ru>\r\n";
    $header.="To: =?utf-8?B?".base64_encode($to_name)."?= <".$to_mail.">\r\n";
    $header.="Subject: =?utf-8?B?".base64_encode($subject)."?=\r\n";
    $header.="MIME-Version: 1.0\r\n";
    $header.="Content-Type: ".($html ? "text/html" : "text/plain")."; charset=utf-8\r\n";
    $header.="Content-Transfer-Encoding: 8bit\r\n";
    
    return $header;  
  }
    
  private static function get_data($smtp_conn)
  {
    $data='';
    while($str = fgets($smtp_conn,515))
    {
      $data .= $str;
      if(substr($str,3,1) == " ") { break; }
    }
    return $data;
  } 
  
  private static function error($err_text,$smtp_conn)
  {
     fclose($smtp_conn);
     echo $err_text; 
  }
    
  public static function mail($to_mail, $to_name, $subject, $text, $html = 0)
  {
    $header = self::get_header(self::$from_mailer, self::$from_mail, $to_name, $to_mail, $subject, $html);  
      
    $smtp_conn = fsockopen("ssl://smtp.mail.ru", 465,$errno, $errstr, 10);
    if(!$smtp_conn)
      self::error("соединение с серверов не прошло", $smtp_conn);
      
    $data = self::get_data($smtp_conn);
    fputs($smtp_conn,"EHLO vasya\r\n");
    $code = substr(self::get_data($smtp_conn),0,3);
    if($code != 250)
      self::error("ошибка приветсвия EHLO", $smtp_conn);
    
    fputs($smtp_conn,"AUTH LOGIN\r\n");
    $code = substr(self::get_data($smtp_conn),0,3);
    if($code != 334)
      self::error("сервер не разрешил начать авторизацию", $smtp_conn);
      
    fputs($smtp_conn,base64_encode("analytic-spy")."\r\n");
    $code = substr(self::get_data($smtp_conn),0,3);
    if($code != 334)
      self::error("ошибка доступа к такому юзеру", $smtp_conn);

    fputs($smtp_conn,base64_encode("kndfl8787gdfgd-dfgrjvdf")."\r\n");
    $code = substr(self::get_data($smtp_conn),0,3);
    if($code != 235)
      self::error("не правильный пароль", $smtp_conn);
      
    $size_msg=strlen($header."\r\n".$text);

    fputs($smtp_conn,"MAIL FROM:<".self::$from_mail."> SIZE=".$size_msg."\r\n");

    $code = substr(self::get_data($smtp_conn),0,3);
    if($code != 250)
      self::error("сервер отказал в команде MAIL FROM", $smtp_conn);
      
    fputs($smtp_conn,"RCPT TO:<".$to_mail.">\r\n");
    $code = substr(self::get_data($smtp_conn),0,3);
    if($code != 250 AND $code != 251)
      self::error("Сервер не принял команду RCPT TO", $smtp_conn);
      
    fputs($smtp_conn,"DATA\r\n");
    $code = substr(self::get_data($smtp_conn),0,3);
    if($code != 354)
      self::error("сервер не принял DATA", $smtp_conn);
      
    fputs($smtp_conn,$header."\r\n".$text."\r\n.\r\n");
    $code = substr(self::get_data($smtp_conn),0,3);
    if($code != 250)
      self::error("ошибка отправки письма", $smtp_conn);
      
    fputs($smtp_conn,"QUIT\r\n");
    fclose($smtp_conn);
  }   
}