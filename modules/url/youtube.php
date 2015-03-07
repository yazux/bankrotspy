<?php  
defined('DS_ENGINE') or die('web_demon laughs');

$link=GET('link');
if(!preg_match('/[^0-9a-z\-\_]+/i', $link) and mb_strlen($link)==11)
{
  $page = file_get_contents('http://m.youtube.com/watch?gl=US&hl=ru&client=mv-google&v='.htmlentities($link, ENT_QUOTES, 'UTF-8'));
  preg_match('/<a href="(.*?)" alt="video">Смотреть видео<\/a>/i', $page, $s);
  $to_file=$s[1];
  header('Location: ' . htmlentities($to_file, ENT_QUOTES, 'UTF-8'));
}
else                   
  denied();


