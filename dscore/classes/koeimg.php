<?php
 
/**
* Koeimg
*
* Обработка изображения, возможность изменения размеров пропорционально, задав ширину или длину, не пропорциональное изменение размера указать ширину и длину,
* изменение размера пропорционально в процентном соотношении, зеркальное отражение исходного изображения, получить размеры ширины или высоты изображенияб
* поворот изображение на определенный угол, сделать чб, наложение копирайта на левый верхний или левый нижний углы
*
* @author Koenig <http://johncms.com/users/profile.php?user=6565>
* @version 1.0
*/
 
class koeimg
{      
    /**
   * ширина изображения
   *
   * @var integer
    */
    public $width;
    /**
   * высота изображения
   *
   * @var integer
   */
    public $height;
    /**
   * тип изображения
   *
   * @var string
   */
    private $type;
    /**
   * ресурс изображения
   *
   * @var resource
   */
    private $img;
    /**
   * массив типов
   *
   * @var array
   */
    private $imgtypes = array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    );
    /**
   * конструктор
   *
   * @param string ссылка на файл изображения
   * @return resource
   */
    public function __construct($imgfile, $real_ext = '')
    {
        if (!file_exists($imgfile)) {
            throw new Exception('file not exists');
        }
        $imgtypes = array(
            'jpg',
            'jpeg',
            'gif',
            'png'
        );
        if($real_ext)
          $ext = $real_ext;
        else
        {
          $ext = explode('.', $imgfile);
          $ext = end($ext);
        }
        if (!in_array($ext, $this->imgtypes)) {
            throw new Exception('unsupported image type');
        }
        $info = getimagesize($imgfile);
        $this->width = $info[0];
        $this->height = $info[1];
        $this->type = $info['mime'];
        $this->type = strtolower(substr($this->type, strpos($this->type, '/') + 1));
        $imgfunc = 'imagecreatefrom' . $this->type;
        if (!function_exists($imgfunc)) {
            throw new Exception('unsupported function ' . $imgfunc);
        }
        $this->img = $imgfunc($imgfile);
        $newimg = imagecreatetruecolor($this->width, $this->height);
        if ($this->type == 'gif') {
            $newimg = imagecreate($this->width, $this->height);
            imagecolortransparent($newimg, imagecolorallocate($newimg, 255, 255, 255));
        }
        if ($this->type == 'png') {
            imagefill($newimg, 0, 0, imagecolorallocate($newimg, 255, 255, 255));
        }
        imagecopyresampled($newimg, $this->img, 0, 0, 0, 0, $this->width, $this->height, $this->width, $this->height);
        $this->img = $newimg;
    }
   /**
   * вывод
   *
   * @param array массив с параметрами
   * @return вывод изображение на страницу через заголовок или сохранить локально
   */
    public function out($mode = array())
    {
   /**
   * массив параметров по умолчанию
   * тип
   * имя файла без расширения
   * качество изображения только для png и jpg
   * фильтр только для png
   *
   * @var array
   */
        if($mode['type'] == 'jpg')
          $mode['type'] = 'jpeg';
        
        if($mode['type'] == 'jpeg')
        {
          $defaultmode = array(
            'type' => 'jpeg',
            'newfile' => null,
            'quality' => 80,
            'filters' => PNG_ALL_FILTERS
          );
        }
        else
        {
           $defaultmode = array(
            'type' => 'png',
            'newfile' => null,
            'quality' => 9,
            'filters' => PNG_ALL_FILTERS
          );  
        }
        
        foreach($defaultmode as $k => $v) {
            if (!isset($mode[$k])) {
                $mode[$k] = $v;
            }
        }
        
        $imgfuncout = 'image' . $mode['type'];
        if (!function_exists($imgfuncout)) {
            throw new Exception('unsupported function ' . $imgfuncout);
        }
        if ($mode['newfile'] == null) {
            header('Content-Type: image/' . $mode['type']);
        }
        else {
            $mode['newfile'] = $mode['newfile'] . '.' . ($mode['type'] == 'jpeg' ? 'jpg' : $mode['type']);
        }
        switch ($mode['type']) {
        case 'gif':
            $imgfuncout($this->img, $mode['newfile']);
            break;
 
        case 'jpeg':
            $imgfuncout($this->img, $mode['newfile'], $mode['quality']);
            break;
 
        default:
            $imgfuncout($this->img, $mode['newfile'], $mode['quality'], $mode['filters']);
            break;
        }
    }
    public function copyright($text, $angle = 1)
    {
   /**
   * наложение копирайта
   *
   * @param string текст копирайта
   * @param interer угол
   * 1 - левый нижний
   * 2 - левый верхний
   * @return resource
   */    
        $newimg = imagecreatetruecolor($this->width, 17);
        $white = imagecolorallocate($newimg, 0, 0, 0);
        $grey = imagecolorallocate($newimg, 128, 128, 128);
        $blue = imagecolorallocate($newimg, 0, 128, 128);
        $red = imagecolorallocate($newimg, 255, 0, 0);
        $black = imagecolorallocate($newimg, 255, 255, 255);
        imagecolortransparent($newimg, $white);
        imagefilledrectangle($newimg, 0, 0, 69, 14, $white);
        $font = 'arial.ttf';
        imagettftext($newimg, 12, 0, 5, 14, $red, $font, $text);
        imagettftext($newimg, 12, 0, 4, 13, $blue, $font, $text);
        imagettftext($newimg, 12, 0, 3, 12, $black, $font, $text);
        imagecopymerge($this->img, $newimg, 0, ($angle == 1 ? $this->height - 17 : 0) , 0, 0, $this->width, $this->height, 100);
    }
    public function mirroring()
    {
    /**
   * отображение в зеркале
   * @return resource
   */    
        $newimg = imagecreatetruecolor($this->width, $this->height);
        foreach(range($this->width, 0) as $range) {
            imagecopy($newimg, $this->img, $this->width - $range - 1, 0, $range, 0, 1, $this->height);
        }
        $this->img = $newimg;
    }
    public function rotate($angle = 90)
    {
   /**
   * поворот изображения
   * @param integer угл наклона 0 - 359
   * default 90
   * @return resource
   */    
        $white = imagecolorallocate($this->img, 255, 255, 255);
        imagecolortransparent($this->img, $white);
        $this->img = imagerotate($this->img, $angle, $white);
        $this->width = $this->newx();
        $this->height = $this->newy();
    }
    public function grayscale()
    {
   /**
   * создание чернобелого изображения
   * @return resource
   */    
        imagefilter($this->img, IMG_FILTER_GRAYSCALE);
    }
    public function remax($max_width, $max_height)
    {
   /**
   * пропорциональное изменение по ширине и высоте
   * Изменяет масштаб, только если высота или ширина больше максимальных
   * @param integer новая ширина
   * @return resource
   */  
      if($this->height >= $this->width) {
        //высота больше  
        if($this->height > $max_height) {
           $width = ceil($this->width * ($max_height / $this->height));
           $this->resize($width, $max_height); 
        }  
      } else {
        //Ширина больше
        if($this->width > $max_width) {
          $height = ceil($this->height * ($max_width / $this->width));
          $this->resize($max_width, $height); 
        }  
      }
    }
    public function reheight($height)
    {
   /**
   * пропорциональное изменение по высоте
   * @param integer новая длина
   * @return resource
   */    
        $width = $this->newx() * ($height / $this->newy());
        $this->resize($width, $height);
    }
    public function rewidth($width)
    {
   /**
   * пропорциональное изменение по ширине
   * @param integer новая ширина
   * @return resource
   */    
        $height = $this->newy() * ($width / $this->newx());
        $this->resize($width, $height);
    }
    public function scale($scale)
    {
   /**
   * пропорциональное изменение по ширине и высоте в процентах
   * @param integer процент
   * @return resource
   */    
        $width = $this->newx() * ($scale / 100);
        $height = $this->newy() * ($scale / 100);
        $this->resize($width, $height);
    }
    public function resize($width, $height)
    {
   /**
   * изменение по ширине и высоте
   * @param integer новая ширина
   * @param integer новая высота
   * @return resource
   */    
        $newimg = imagecreatetruecolor($width, $height);
        imagecopyresampled($newimg, $this->img, 0, 0, 0, 0, $width, $height, $this->newx() , $this->newy());
        $this->img = $newimg;
        $this->width = $this->newx();
        $this->height = $this->newy();
    }
    public function newx()
    {
   /**
   * получение ширины изображения
   * @return integer
   */    
        return imagesx($this->img);
    }
    public function newy()
    {
   /**
   * получение высоты изображения
   * @return integer
   */    
        return imagesy($this->img);
    }
    public function __destruct()
    {
   /**
   * деструктор
   * @return null
   */    
        imagedestroy($this->img);
        foreach($this as $key => $value) {
            unset($this->$key);
        }
    }
}

