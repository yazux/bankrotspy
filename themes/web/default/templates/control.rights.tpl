<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Группы и доступ</h2>
                </div>

            <?if($rmenu):?>

            <?foreach($rmenu as $rmenu): ?>
            <div class="contbody_forms">
                <table>
                    <tr>
                        <td style="width: 10px;"></td>
                        <td>
                            <b><?=$rmenu['name']?></b> (id: <?=$rmenu['id']?>)<br/>
                            <a target="_blank" href="<?=$rmenu['link']?>"><?=$rmenu['link']?></a>
                        </td>
                        <?if($rmenu['id'] != 0 AND $rmenu['id'] != 100):?>
                        <td class="cont_act"><a title="<?=lang('edit_link')?>" href="<?=$home?>/control/editgroup?id=<?=$rmenu['id']?>"><i class="icon-edit"></i></a></td>
                        <td class="cont_act"><a title="<?=lang('del_link')?>" onclick="sup(<?=$rmenu['id']?>)" ><i class="icon-delete"></i></a></td>
                        <?endif?>
                    </tr>
                </table>
                <hr style="margin: 3px 6px 3px 8px"/>
                <span class="addtext"><?=$rmenu['undtext']?></span>
            </div>
            <?endforeach?>

            <?else:?>
            <div class="contbody_forms">Нет ни одного пункта меню.</div>
            <?endif?>

                <div class="contfin">
                    <a class="urlbutton" href="<?=$home?>/control/creategroup">Добавить группу</a>
                </div>
            </div>

        </td>
        <td class="right_back_menu">
            <? temp::include('control.index.right.tpl') ?>
        </td>
    </tr>
</table>


<div id="popup_overlay_delnews" class="popup_overlay">
    <div class="popup_table">
        <div class="close_modal_img"><span id="icon_close_butt"><i class="icon-cancel-circled"></i></span></div>
        <div class="main_pop_head">
            Удаление группы
        </div>
        <div class="main_pop_body" id="platform_table">
            Удалить группу?
        </div>
        <div class="main_pop_bottom">
            <form method="post" id="form_act" action="<?=$home?>/control/delgroup?id=">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <input  name="submit" type="submit" value="Удалить" />
                <span id="delnews_popup_close" class="urlbutton_index">Отмена</span>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '#delnews_popup_close', function(){
        $('#popup_overlay_delnews').fadeOut(200);
    });

    function sup(delid)
    {
        $("#form_act").attr("action", "<?=$home?>" + "/control/delgroup?id=" + delid);
        $('#popup_overlay_delnews').fadeIn(200);
    }
</script>