
<div class="content" style="width: 70%; margin: 0 auto; margin-top: 30px;">
    <div class="conthead">
        <table>
            <tr>
                <td>
                    <center><b><?=lang('delete_st')?></b></center>
                </td>
            </tr>
        </table>
    </div>
    <div class="contbodytext">
        <center><?if($last_theme_post):?><?=lang('del_text_last')?><?else:?><?=lang('del_text')?><?endif?></center>
    </div>
    <div class="contfintext">
        <form name="mess" action="<?=$home?>/control/menudel?id=<?=$id?>" method="post">
            <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <center><input name="submit" type="submit" value="<?=lang('delete_act')?>" />  <a class="urlbutton" href="<?=$home?>/control/menu"><?=lang('cancel')?></a></center>
        </form>
    </div>
</div>