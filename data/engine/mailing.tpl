<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>
</head>
<body style="background-color:#d3d3d3;padding:25px;margin:0;font-size:14px;">
<div style="max-width:700px;margin:20px auto;box-shadow: 0 0 14px rgba(0, 0, 0, 0.5);">
<div style="padding:20px;border-bottom: 1px solid #c7c7c7;background-color:#ffffff;">
    <div style="max-width:200px;margin:0 auto;">
        <a target="_blank" href="{$host}">
        <img style="width: 100%;height: auto; " src="{$host}/data/mailing/logo.png"/>
        </a>
    </div>
</div>
<div class="content">
    <div style="padding:25px;border-bottom: 1px solid #c7c7c7;background-color:#ffffff;">
    {$text}
    </div>
</div>
<div class="footer">
    <div style="position:relative;padding:25px;border-bottom: 1px solid #c7c7c7;background-color:#ffffff;">
        <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
            <tr>
                <td align="left"><a style="text-decoration:none;font-size:14px;" target="_blank" href="{$host}">Команда Банкротный шпион</a></td>
                <td align="right"><a target="_blank" href="https://vk.com/bankrotspy"><img src="{$host}/data/mailing/vk.png"/></a></td>
            </tr>
        </table>
    </div>
</div>
</div>
<div style="max-width: 700px;margin:0 auto;margin:25px auto;text-align:center;">
<a style="text-decoration:none;" href="{$host}/unsubscribe?id={$hash}">Отключить рассылку</a>
</div>
</body>
</html>