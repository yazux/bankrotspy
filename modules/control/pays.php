<?php
defined('DS_ENGINE') or die('web_demon laughs');


new nav; //Постраничная навигация
//nav::$kmess = 50;

$total = core::$db->query('SELECT COUNT(*) FROM `ds_paid`;')->count();
$res = core::$db->query('SELECT * FROM `ds_paid` WHERE `summ` > 0 ORDER BY `paytime` DESC ;');

$i = 0;
$arr = array();
$month_names = array(
  1 => 'Январь',
  2 => 'Февраль',
  3 => 'Март',
  4=> 'Апрель',
  5=> 'Май',
  6=> 'Июнь',
  7=> 'Июль',
  8=> 'Август',
  9=> 'Сентябрь',
  10=> 'Октябрь',
  11=> 'Ноябрь',
  12=> 'Декабрь'
);

$month_summ = array();
while($data = $res->fetch_assoc())
{
  $out = array();

  $out['id'] =  $data['id'];
  $out['userid'] = $data['userid'];
  $out['username'] = $data['username'];
  $out['paidid'] =  $data['paidid'];
  $out['paytime'] =  ds_time($data['paytime'], '%d %B2 %Y');
  $out['time'] =  date('h:m:s', $data['paytime']);
  $out['summ'] =  $data['summ'];
  $out['comm'] =  $data['comm'];



  $month = date('n', $data['paytime']);
  $year = date('Y', $data['paytime']);

  $month_summ[$month] = @$month_summ[$month] + $data['summ'];

  $arr[$year][$month][] = $out;
  $i++;
}

engine_head(lang('u_online'));
temp::HTMassign('out', $arr);
temp::HTMassign('marr', $month_names);
temp::HTMassign('msumm', $month_summ);
temp::assign('uall', $total);

temp::display('control.pays');
engine_fin();