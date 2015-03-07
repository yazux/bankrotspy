<?php
defined('DS_ENGINE') or die('web_demon laughs');


class download
{
  private static $all_mime = array();
  private static $name;
  private static $path;
  private static $mime;
  private static $ext;
  private static $dispos;  
    
  function __construct($path_to_file, $out_filename)
  {  
    if(!file_exists($path_to_file))
      self::denied(); 
    if(!$out_filename)
      self::denied();
    
    self::$name = $out_filename;
    self::$path = $path_to_file;
    
    //Определяем mime-тип
    self::set_type();
    //Очищаем буфер (Важно!!!)
    self::cleab_ob();
    //Определяем как посылать файл  
    self::disposition();
    //Посылаем заголовки
    self::send_headers();
    //Читаем файл
    self::read();  
  }
  
  private static function read()
  {
    if ($fd = fopen(self::$path, 'rb'))
    {
      while (!feof($fd)) {
        print fread($fd, 1024);
      }
      fclose($fd);
    }
    exit(); 
  }
  
  private static function send_headers()
  {
    header('Content-Type: '.self::$mime);
    header('Content-Disposition: '.self::$dispos.'; filename="' . self::$name.'"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Pragma: public');
    header('Content-Length: ' . filesize(self::$path));
    
    return true;
  }
  
  private static function disposition()
  {
    if(in_array(self::$ext,array('jpg', 'jpeg', 'gif', 'png', 'jpe')))
      self::$dispos = 'inline';
    else
      self::$dispos = 'attachment';
      
    return true;  
  }
  
  private static function cleab_ob()
  {
    if (ob_get_level())
      ob_end_clean();
      
    return true;  
  }
  
  private static function get_ext($string)
  {
    $n=strrpos($string,".");
    if($n)
    {
      $n=$n+1;
      $ext=mb_strtolower(substr($string,$n));
      return $ext;
    }
    else    
     return '';
  }
  
  private static function set_type()
  {
    self::all_mime_types();  
    self::$ext = self::get_ext(self::$name); 
    //Если не удалось определить тип
    if (!isset(self::$all_mime[self::$ext]))
      self::$mime = 'application/octet-stream';
    else
      self::$mime = (is_array(self::$all_mime[self::$ext])) ? self::$all_mime[self::$ext][0] : self::$all_mime[self::$ext];
      
    return true;  
  }
  
  private static function denied()
  {
    denied();    
  }
  
  private static function all_mime_types()
  {
    self::$all_mime = array(
    'hqx'    =>    'application/mac-binhex40',
    'cpt'    =>    'application/mac-compactpro',
    'csv'    =>    array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
    'bin'    =>    'application/macbinary',
    'class'    =>    'application/octet-stream',
    'psd'    =>    'application/x-photoshop',
    'oda'    =>    'application/oda',
    'pdf'    =>    array('application/pdf', 'application/x-download'),
    'ai'    =>    'application/postscript',
    'eps'    =>    'application/postscript',
    'ps'    =>    'application/postscript',
    'smi'    =>    'application/smil',
    'smil'    =>    'application/smil',
    'mif'    =>    'application/vnd.mif',
    'xls'    =>    array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
    'ppt'    =>    array('application/powerpoint', 'application/vnd.ms-powerpoint'),
    'wbxml'    =>    'application/wbxml',
    'wmlc'    =>    'application/wmlc',
    'dcr'    =>    'application/x-director',
    'dir'    =>    'application/x-director',
    'dxr'    =>    'application/x-director',
    'dvi'    =>    'application/x-dvi',
    'gtar'    =>    'application/x-gtar',
    'gz'    =>    'application/x-gzip',
    'php'    =>    'application/x-httpd-php',
    'php4'    =>    'application/x-httpd-php',
    'php3'    =>    'application/x-httpd-php',
    'phtml'    =>    'application/x-httpd-php',
    'phps'    =>    'application/x-httpd-php-source',
    'js'    =>    'application/x-javascript',
    'swf'    =>    'application/x-shockwave-flash',
    'sit'    =>    'application/x-stuffit',
    'tar'    =>    'application/x-tar',
    'tgz'    =>    'application/x-tar',
    'xhtml'    =>    'application/xhtml+xml',
    'xht'    =>    'application/xhtml+xml',
    'zip'    =>  array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
    'mid'    =>    'audio/midi',
    'midi'    =>    'audio/midi',
    'mpga'    =>    'audio/mpeg',
    'mp2'    =>    'audio/mpeg',
    'mp3'    =>    array('audio/mpeg', 'audio/mpg'),
    'aif'    =>    'audio/x-aiff',
    'aiff'    =>    'audio/x-aiff',
    'aifc'    =>    'audio/x-aiff',
    'ram'    =>    'audio/x-pn-realaudio',
    'rm'    =>    'audio/x-pn-realaudio',
    'rpm'    =>    'audio/x-pn-realaudio-plugin',
    'ra'    =>    'audio/x-realaudio',
    'rv'    =>    'video/vnd.rn-realvideo',
    'wav'    =>    'audio/x-wav',
    'bmp'    =>    'image/bmp',
    'gif'    =>    'image/gif',
    'jpeg'    =>    array('image/jpeg', 'image/pjpeg'),
    'jpg'    =>    array('image/jpeg', 'image/pjpeg'),
    'jpe'    =>    array('image/jpeg', 'image/pjpeg'),
    'png'    =>    array('image/png',  'image/x-png'),
    'tiff'    =>    'image/tiff',
    'tif'    =>    'image/tiff',
    'css'    =>    'text/css',
    'html'    =>    'text/html',
    'htm'    =>    'text/html',
    'shtml'    =>    'text/html',
    'txt'    =>    'text/plain',
    'text'    =>    'text/plain',
    'log'    =>    array('text/plain', 'text/x-log'),
    'rtx'    =>    'text/richtext',
    'rtf'    =>    'text/rtf',
    'xml'    =>    'text/xml',
    'xsl'    =>    'text/xml',
    'mpeg'    =>    'video/mpeg',
    'mpg'    =>    'video/mpeg',
    'mpe'    =>    'video/mpeg',
    'qt'    =>    'video/quicktime',
    'mov'    =>    'video/quicktime',
    'avi'    =>    'video/x-msvideo',
    'movie'    =>    'video/x-sgi-movie',
    'doc'    =>    'application/msword',
    'docx'    =>    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xlsx'    =>    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'word'    =>    array('application/msword', 'application/octet-stream'),
    'xl'    =>    'application/excel',
    'eml'    =>    'message/rfc822',
    'jar'   =>     'application/java-archive',
    'jad'   =>     'text/vnd.sun.j2me.app-descriptor;charset=UTF-8',
    'sis'   =>     'application/vnd.symbian.install',
    'thm'    =>     'application/vnd.eri.thm'
    );
    
    return true;
  }  
}
