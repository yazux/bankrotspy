<?php

require_once 'dscore/libs/phpmailer/PHPMailerAutoload.php';

class Mailer extends Phpmailer
{
    protected $path;
    protected $template;
    
    public static function factory($path = './data/engine/')
    {
        return new Mailer($path);
    }
    
    public function __construct($path)
    {
        parent::__construct();
        //$this->SMTPDebug = 3;
        $this->path = $path;
        
        $this->isSMTP(); 
        
        $this->Host = 'mail.bankrot-spy.ru';
        $this->SMTPAuth = true;
        $this->Username = 'no-reply@bankrot-spy.ru';
        $this->Password = 'C7q3H3u9';
        //$this->SMTPSecure = 'tls';
        
        $this->Port = 25;
        $this->isHTML(true);
        $this->CharSet = 'UTF-8';
        $this->setFrom('no-reply@bankrot-spy.ru', 'Bankrot-Spy');
    }
    
    public function setBody($template, array $data = array())
    {
        $file = $this->path . $template . '.tpl';
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