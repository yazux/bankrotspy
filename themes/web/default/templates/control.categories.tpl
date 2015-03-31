<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Категории и ключевые слова</h2>
                </div>

            <?if($rmenu):?>

            <?foreach($rmenu as $rmenu): ?>
            <div class="contbody_forms">
                <table>
                    <tr>
                        <td valign="top" style="width: 30px;padding: 4px 0 4px 0"><i class="icon-tags"></i></td>
                        <td>
                            <b><?=$rmenu['name']?></b> (id: <?=$rmenu['id']?>)<br/>
                            <hr style="margin: 3px 6px 3px 8px"/>
                            <span style="font-size: 13px;"><span style="color: #949498">Ключевые слова:</span> <?=$rmenu['undtext']?></span>
                        </td>
                        <td valign="top" class="cont_act"><a title="<?=lang('edit_link')?>" href="<?=$home?>/control/editcategory?id=<?=$rmenu['id']?>"><i class="icon-edit"></i></a></td>
                    </tr>
                </table>

            </div>
            <?endforeach?>

            <?else:?>
            <div class="contbody_forms">Нет ни одного пункта меню.</div>
            <?endif?>

                <div class="contfin_forms">
                  <br/>
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