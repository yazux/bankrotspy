<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_debtor
{
  private $dname;

  function __construct($params)
  {
    $this->dname = isset($params[0]) ?  trim($params[0]) : '';

    $this->dname = $this->good_quotes($this->dname);
  }

  private function delete_inn($dname, $inn)
  {
    return(trim(str_replace($inn, '',$dname)));
  }

  private function get_inn($dname)
  {
    preg_match('/([0-9]{10,12})/siu', $dname, $find);
    return trim($find[1]);
  }

  private function good_quotes($dname)
  {
    $replace_reg_arr =array(
      '«' => '"',
      '»' => '"',
      '""' => '"',
      '----------------------------------------------' => ''
    );

    return trim(strtr($dname, $replace_reg_arr));
  }

  public function process()
  {
    $inn = $this->get_inn($this->dname);
    $debt = $this->delete_inn($this->dname, $inn);

    $req = core::$db->query('SELECT * FROM `ds_maindata_debtors` WHERE `dept_name` = "'.core::$db->res($debt).'" ;');

    if(!$debt)
      $debt_id = '';
    elseif($req->num_rows)
    {
      $data = $req->fetch_assoc();
      $debt_id = $data['id'];

      //Обновляем inn если он вдруг пришел
      if(!$data['inn'] AND $inn)
        core::$db->query('UPDATE `ds_maindata_debtors` SET `inn` = "'.core::$db->res($inn).'" WHERE `id` = "'.$data['id'].'" ;');
    }
    else
    {
      core::$db->query('INSERT INTO `ds_maindata_debtors` SET
           `dept_name` = "'.core::$db->res($debt).'",
           `debt_profile` = "www1",
           `inn` = "'.core::$db->res($inn).'"
           ;');

      $debt_id = core::$db->insert_id;
    }

    return $debt_id;
  }
}