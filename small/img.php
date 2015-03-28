<?
Error_Reporting(E_ALL & ~ E_NOTICE);

$maxh=950;
$maxw= 950;

//Автозагрузка
spl_autoload_register('autoload');
function autoload($name)
{
  $file = '../dscore/classes/' . $name . '.php';
  include_once($file);
}

function getextension($string)
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

function cut_ext($string)
{
  $n=strrpos($string,".");
  $ext=substr($string,0,$n);
  return $ext;
}

$image  =  trim($_GET['i']);
if(preg_match('/[^0-9a-z\-\_\.]+/',$image))
{
  echo file_get_contents('../data/img_error.png');
  exit();  
}

$smallext = getextension($image);
$imgfile = $image;
if(file_exists('../data/att_post/'.cut_ext($imgfile).'.dat'))
  $image='../data/att_post/'.cut_ext($imgfile).'.dat';
else
  $image='../data/att_art/'.$imgfile.'.dat';

try
{
  $img = new koeimg($image, $smallext);
  
  $h=intval(abs($_GET['h']));
  $w=intval(abs($_GET['w']));
  
  if(!$w and !$h)
    $img->remax($maxw, $maxh);
  elseif($w and $h)
  {
    if($w > $maxw OR $h > $maxh)
      $img->remax($maxw, $maxh);
    else 
      $img->resize($w, $h);  
  }
  elseif($h and !$w)
  {
    if($h > $maxh)
      $h = $maxh;  
    $img->reheight($h);  
  }    
  elseif($w and !$h)
  {
    if($w > $maxw)
      $w = $maxw; 
    $img->rewidth($w);  
  }
  
  $args = array('type' => $smallext, 'newfile' => '../data/att_img/'.$imgfile.'.'.$w.'x'.$h.'');
  $img->out($args);
  $args = array('type' => $smallext); 
  $img->out($args);  
}
catch (Exception $e)
{
  echo $e->getMessage();
  //echo file_get_contents('../data/img_error.png');
  //exit();
}


