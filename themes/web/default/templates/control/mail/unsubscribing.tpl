<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Отписавшиеся</h2>
                </div>
                
                <div class="contbody_forms">
                
                <? if(!empty($users)): ?>
                    <? foreach($users as $user): ?>
                        <a href="/user/profile?id=<?= $user['id'] ?>"><?= $user['login'] ?></a><br/>
                    <? endforeach; ?>
                
                <? else: ?>
                    Нет
                <? endif; ?>
                
                </div>
        </td>
        <? temp::include('control/mail/menu.tpl') ?>
    </tr>
</table>
