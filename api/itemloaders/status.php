<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_status
{
  private $status;

  function __construct($params)
  {
    $this->status = isset($params[0]) ?  trim($params[0]) : '';
  }

  private function replace_not_valid($data)
  {
    //В этот массив добавлять пару старое значение - новое значение
    $replace_arr = array(

    );

    $new_replace_arr = array();
    foreach ($replace_arr AS $key=>$value)
    {
      $new_replace_arr[mb_strtolower(trim($key))] = mb_strtolower(trim($value));
    }

    return strtr(mb_strtolower($data), $new_replace_arr);
  }

  private function get_status()
  {
    $allstatses = array();
    $res = core::$db->query('SELECT * FROM  `ds_maindata_status` ;');
    while($data = $res->fetch_array())
    {
      $allstatses[$data['id']] = mb_strtolower(trim($data['status_name']));
    }
    return $allstatses;
  }

  public function process()
  {
    $temp_status = $this->replace_not_valid($this->status);
    $allstatses = $this->get_status();

    if(!$this->status)
      $status = '';
    elseif(in_array($temp_status, $allstatses))
      $status = array_search($temp_status, $allstatses);
    else
    {
      //Добавляем статус автоматом если он не найден
      core::$db->query('INSERT INTO `ds_maindata_status` SET
          `status_name` = "'.core::$db->res($this->status).'" ;');

      $status = core::$db->insert_id;
    }

    return $status;
  }
}