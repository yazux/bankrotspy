<?php
defined('DS_ENGINE') or die('web_demon laughs');

$res = core::$db->query('SELECT * FROM `ds_maindata` ;');

$load = new bcdata();

while($data = $res->fetch_array())
{
  if(!is_numeric($data['debtor']))
  {
    $vval = trim($data['debtor']);
    $first_symbol = mb_substr($vval, 0, 1);
    $len = mb_strlen($vval);
    $end_symbol = mb_substr($vval, ($len-1), 1);
    if($first_symbol == '"' AND $end_symbol == '"')
    {
      $data['debtor'] = mb_substr($vval, 1, ($len-2));
    }

    echo $data['debtor'].'<br/>';

    $debtor = $load->debtor($data['debtor']);
    $organizer = $load->organizer($data['organizer'], $data['contact_person'], $data['manager'], $data['man_card'], $data['inn']);


    core::$db->query('UPDATE `ds_maindata` SET `debtor` = "'.core::$db->res($debtor).'", `organizer` ="'.core::$db->res($organizer).'" WHERE `id` = "'.core::$db->res($data['id']).'" ;');
  }
}

echo '<br/><br/><hr/><br/><br/>';

$res = core::$db->query('SELECT * FROM `ds_maindata` ;');
while($data = $res->fetch_array())
{
  if(!is_numeric($data['organizer']))
  {
    $vval = trim($data['organizer']);
    $first_symbol = mb_substr($vval, 0, 1);
    $len = mb_strlen($vval);
    $end_symbol = mb_substr($vval, ($len-1), 1);
    if($first_symbol == '"' AND $end_symbol == '"')
    {
      $data['organizer'] = mb_substr($vval, 1, ($len-2));
    }

    $vval = trim($data['manager']);
    $first_symbol = mb_substr($vval, 0, 1);
    $len = mb_strlen($vval);
    $end_symbol = mb_substr($vval, ($len-1), 1);
    if($first_symbol == '"' AND $end_symbol == '"')
    {
      $data['manager'] = mb_substr($vval, 1, ($len-2));
    }

    echo $data['organizer'].' - '.$data['manager'].'<br/>';

    $organizer = $load->organizer($data['organizer'], $data['contact_person'], $data['manager'], $data['man_card'], $data['inn']);


    core::$db->query('UPDATE `ds_maindata` SET `organizer` ="'.core::$db->res($organizer).'" WHERE `id` = "'.core::$db->res($data['id']).'" ;');
  }



}
