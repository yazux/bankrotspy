<?php
defined('DS_ENGINE') or die('web_demon laughs');

class forum
{
  public static function renew_section($section_id)
  {
    //Кол-во постов в разделе
    $secid_count_posts = core::$db->query('SELECT COUNT(*) FROM `ds_forum_posts` LEFT JOIN `ds_forum_topics` ON `ds_forum_posts`.`topid` = `ds_forum_topics`.`id` WHERE `ds_forum_posts`.`secid` = "'.$section_id.'" AND `ds_forum_topics`.`hide` = "0";')->count();
    //Колво тем в разделе
    $secid_count_topics = core::$db->query('SELECT COUNT(*) FROM `ds_forum_topics` WHERE `refid` = "'.$section_id.'" AND `hide` = "0" ;')->count();
    //Последний обновленный пост в разделе (вдруг наш - это он, нужно обновить)
    $resret_post_q = core::$db->query('SELECT `ds_forum_posts`.*, `ds_forum_topics`.`hide` FROM `ds_forum_posts` LEFT JOIN `ds_forum_topics` ON `ds_forum_posts`.`topid` = `ds_forum_topics`.`id` WHERE `ds_forum_posts`.`secid` = "'.$section_id.'" AND `ds_forum_topics`.`hide` = "0" ORDER BY `id` DESC LIMIT 1 ;');
    $resret_post = $resret_post_q->fetch_assoc();
    //обновляем данные для подраздела
    core::$db->query('UPDATE `ds_forum_section` SET `topcount` = "'.$secid_count_topics.'", `postcount` = "'.$secid_count_posts.'", `lastpost` = "'.$resret_post['id'].'" WHERE `id` = "'.$section_id.'";');
  }
}