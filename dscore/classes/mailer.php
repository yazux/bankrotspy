<?php

require_once 'dscore/libs/phpmailer/PHPMailerAutoload.php';

class Mailer extends Phpmailer
{
    protected $config;
    protected $template;
    
    public static function factory($config = './data/engine/')
    {
        return new Mailer($config);
    }
    
    public function __construct($config)
    {
        parent::__construct();
        //$this->SMTPDebug = 3;
        $this->config = $config;
        
        $this->isSMTP();                                      // Set mailer to use SMTP
        
        $this->Host = 'mail.bankrot-spy.ru';  // Specify main and backup SMTP servers
        $this->SMTPAuth = true;                               // Enable SMTP authentication
        $this->Username = 'no-reply@bankrot-spy.ru';                 // SMTP username
        $this->Password = 'C7q3H3u9';                           // SMTP password
        $this->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $this->Port = 25;
        $this->isHTML(true);
        $this->CharSet = 'UTF-8';
        $this->setFrom('no-reply@bankrot-spy.ru', 'Bankrot-Spy');
    }
    
    public function setBody($template, array $data = array())
    {
        $file = $this->config . $template . '.tpl';
        if(file_exists($file)) {
            $template = file_get_contents($file);
        }
        
        $this->Body = $this->parseStr($template, $data);
    }
    
    public function setSubject($str, array $data = array())
    {
        $this->Subject = $this->parseStr($str, $data);
    }
    
    public function parseStr($str, $data)
    {
        if(!empty($data)) {
            foreach($data as $key => $value) {
                $str = $this->replace($str, $key, $value);
            }
        }
        return $this->clear($str);
    }
    
    public function replace($str, $key, $value)
    {
        return str_replace('{$'.$key.'}', $value, $str);
    }
    
    public function clear($str)
    {
        return preg_replace('#{[^}]+}#is', '', $str);
    }
}