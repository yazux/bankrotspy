<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Заявки на ЭЦП/КЦП</h2>
                </div>
                
                <div class="contbody_forms">
                    <b>Настройки операторов</b>
                </div>
                
                <div class="contbody_forms">
                    <? foreach( $companies as $company ) :?>
                    <div style="width: 600px; margin-top: 20px;">
                        <form method="get" action="<?=$home?>/control/requests">
                            <div style="float:left; width: 120px; margin-top: 7px;">
                                <input type="hidden" name="companyId" value="<?=$company['id']?>">
                                <?=$company['name']?>:
                            </div>
                            <div style="float:left; width: 300px;">
                                <input type="text" name="email" value="<?=$company['email']?>" style="height: 15px;">
                            </div>
                            <div style="float:left; width: 50px; margin-top: 10px;">
                                <input type="checkbox" name="status" value="1" style="height: 15px;" <? if ($company['status']==1) echo "checked";?> >
                            </div>
                            <div style="float:left; width: 80px;">
                                <input class="button_no_top_index" type="submit" value="Установить">
                            </div>
                        </form>
                    </div>
                    <br style="clear: both;"/>
                    <? endforeach; ?>
                    
                </div>
                
                <div class="contbody_forms">
                    <b>Поиск</b>
                    <form style="display:block;margin-bottom:20px;" method="get" id="searchForm">
                        <input type="text" placeholder="email, ФИО, город, телефон" name="search" value="<?=$search?>" style="height: 15px;">
                        <!--input type="text" name="date" value="<?=$date?>" style="margin-left: 10px; margin-right: 10px;width:100px; height: 15px;"-->
                        <input class="button_no_top_index" type="submit" value="искать">
                    </form>
                </div>
                
                <div class="contbody_forms">
                    <?if($out):?>
                        <b><?=lang('uall')?> <?=$uall?></b><br/>
                    <?else:?>
                        <br/>Нет заявок<br/><br/>
                    <?endif?>
                </div>
            </div>

            <?if($out):?>
                <?foreach($out as $key_year => $year): ?>

                <?if($year):?>
                <?foreach($year as $key_month => $month): ?>

                <div class="content">
                    <div class="conthead">
                        <h2><?=$marr[$key_month]?> <?=$key_year?></h2>
                    </div>
                    <div class="contbody_forms">
                        <style>
                            .pays_t td {
                                /*border-bottom:1px solid #eee;*/
                            }
                            .pays_t tr:hover{
                                background:#f9f8f8;
                            }
                        </style>
                        <table class="pays_t">

                        <?if($month):?>
                            <tr>
                                <th>№</th>
                                <th>Оператор</th>
                                <th>Заказчик</th>
                                <th>Телефон</th>
                                <th>e-mail</th>
                                <th>Город</th>
                                <th>ИНН</th>
                                <th>Создана</th>
                            </tr>
                            <?foreach($month as $id => $data): ?>
                            <tr id="<?=$data['id']?>">
                                <td width="10" style="text-align:center; pading:0 3px;"><?= $id+1 ?></td>
                                <td style="text-align:left;"><b><?=$data['cname']?></b></td>
                                <td style="text-align:left;"><b><?=$data['username']?></b></td>
                                <td style="text-align:left;"><?=$data['phone']?></td>
                                <td style="text-align:left;"><?=$data['email']?></td>
                                <td style="text-align:left;"><?=$data['city']?></td>
                                <td style="text-align:left;"><?=$data['inn']?></td>
                                <td style="text-align:left;"><?=$data['created']?></td>
                            </tr>
                            <?endforeach?>
                        <?endif?>
                        </table>
                    </div>
                </div>

                    <?endforeach?>
                <?endif?>
                <?endforeach?>

                <!--/div-->
            <?endif?>

            <?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>

        </td>
        <td class="right_back_menu">
            <? temp::include('control.index.right.tpl') ?>
        </td>
    </tr>
</table>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">

<script>
    $(document).ready(function() {
        $('[name="date"]').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText, inst){
                //alert(dateText);
                $('#searchForm').submit();
            }
        });
    });
    
    function confirmDelete( id ) {
	    if (confirm("Вы действительно хотите удалить транзакцию с ID="+id+"?")) {
	        $.get(
                "/control/paydelete",
                {
                    id: id
                },
                function(data) {
                    if ( data == 'ok' ) {
                        create_notify( "Транзакция с ID" + id + " успешно удалена." );
                        //$("#"+id).remove();
                        location.reload();
                    } else {
                        create_notify( data );
                    }
                }
            );
	    } else {
	        return false;
	    }
	}
</script>