<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_organizer
{
  private $organizer;
  private $contact_person;
  private $manager;
  private $manager_card;
  private $inn;

  function __construct($params)
  {
    $this->organizer = isset($params[0]) ?  trim($params[0]) : '';
    $this->contact_person = isset($params[1]) ?  trim($params[1]) : '';
    $this->manager = isset($params[2]) ?  trim($params[2]) : '';
    $this->manager_card = isset($params[3]) ?  trim($params[3]) : '';
    $this->inn = isset($params[4]) ?  trim($params[4]) : '';

    $this->organizer = $this->good_quotes($this->organizer);
    $this->contact_person = $this->good_quotes($this->contact_person);
    $this->manager = $this->good_quotes($this->manager);

    if(!$this->organizer)
      $this->organizer = $this->manager;
  }

  private function delete_inn($dname, $inn)
  {
    return(trim(str_replace($inn, '',$dname)));
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

  private function get_inn($dname)
  {
    preg_match('/([0-9]{10,12})/siu', $dname, $find);
    return isset($find[1]) ? trim($find[1]) : '';
  }

  public function process()
  {
    $inn = $this->get_inn($this->organizer);

    if(!preg_match('/^[0-9]+$/', $this->inn))
      $this->inn = $inn;

    $org = $this->delete_inn($this->organizer, $this->inn);

    $req = core::$db->query('SELECT * FROM `ds_maindata_organizers` WHERE `org_name` = "'.core::$db->res($org).'" ;');
    if($req->num_rows)
    {
      $data = $req->fetch_assoc();
      $org_id = $data['id'];

      //Тут дописать обновление если будет нужно
    }
    else
    {
      core::$db->query('INSERT INTO `ds_maindata_organizers` SET
           `org_name` = "'.core::$db->res($org).'",
           `contact_person` = "'.core::$db->res($this->contact_person).'",
           `manager` = "'.core::$db->res($this->manager).'",
           `org_profile` = "'.core::$db->res($this->manager_card).'",
           `inn` = "'.core::$db->res($this->inn).'"
           ;');

      $org_id = core::$db->insert_id;
    }

    return $org_id;
  }
}