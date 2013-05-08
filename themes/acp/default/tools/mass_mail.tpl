<ul class="system_messages">
    <li class="yellow"><span class="ico"></span><strong class="system_title">
            Mail Gönderim Sisteminin çalışabilmesi için dakikalık çalışan (minutely.php) cron ayarının yapılmış olması
            gerekmektedir.
    </li>
</ul>

<form method="post" class="cmxform" style="width:100%;">

    <table cellpadding="0" cellspacing="0" class="datagrid" width="100%">
        <tr>
            <th width="200">Müşteri Hesap Durumu</th>
            <td><select name="status">
                    <option value="all">Hepsi</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Aktif Olmayan</option>
                </select>
            </td>
        </tr>
        <tbody id="ostatus_tr" style="display: none;">
        <tr>
            <th width="200">Sipariş Durumu</th>
            <td><select name="ostatus">
                    <option value="all">Hepsi</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Aktif Olmayan</option>
                </select>
            </td>
        </tr>
        </tbody>
        <tr>
            <th>Hizmet Seçimi</th>
            <td>
                <label><input type="radio" name="service" value="all" checked="checked"
                              onclick="$('#services').slideUp(); $('#ostatus_tr').hide();"> Hepsi </label>
                <label><input type="radio" name="service" value="selected"
                              onclick="$('#services').slideDown(); $('#ostatus_tr').show();"> Seçilenler </label>
                <select name="services[]" multiple="multiple" style="display: none;" id="services" size="7">
                    {foreach from=$services item=s}
                        <option value="{$s.serviceID}">{$s.service_name}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <th>Sunucu Seçimi</th>
            <td>
                <label><input type="radio" name="server" value="all" checked="checked"
                              onclick="$('#servers').slideUp();  $('#ostatus_tr').hide();"> Hepsi </label>
                <label><input type="radio" name="server" value="selected"
                              onclick="$('#servers').slideDown(); $('#ostatus_tr').show();"> Seçilenler </label>
                <select name="servers[]" multiple="multiple" style="display: none;" id="servers" size="7">
                    {foreach from=$servers item=s}
                        <option value="{$s.serverID}">{$s.serverName}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <th>Test Emaili</th>
            <td><input type="text" name="test_email" class="w_200" value=""> (Bu email adresi girerseniz sadece bu
                emaile gönderim yapılır)
            </td>
        </tr>
        <tr>
            <th>Kimden</th>
            <td>
                <input type="text" name="from_name" class="w_150" value="{'compinfo_name'|getSetting}">
                <input type="text" name="from_mail" class="w_200" value="{'compinfo_email'|getSetting}">
            </td>
        </tr>

        <tr>
            <th>Konu</th>
            <td><input type="text" name="subject" class="w_450"></td>
        </tr>
        <tr>
            <th>Mesaj</th>
            <td>
                <textarea name="body" rows="3" class="wysiwyg2" style="width: 100%;">{literal}Sayın {$Client_name},
                        <br/>
                        <br/>
                        {$vurl}
                        <br/>
                        <br/>
                        <br/>
                        {$signature}{/literal}</textarea>
            </td>
        </tr>
        <tr>
            <th>Başlama Zamanı</th>
            <td> Gönderime <input type="text" name="start_after" value="0" style="width: 25px;"> dakika sonra başla.
                (Hemen başlamak için 0 giriniz)
            </td>
        </tr>
        <tr>
            <th>Mesajlar Arası Bekleme</th>
            <td> Her <input type="text" name="pause_mcount" value="0" style="width: 25px;"> mesajdan sonra
                <input type="text" name="pause_time" value="0" style="width: 25px;"> dakika bekle. (Beklemeyi iptal
                etmek için değerlere 0 giriniz)
            </td>
        </tr>

    </table>
    <p align="right"><input type="submit" value="Gönderime Başla"/></p>
</form>


{literal}
<script language="JavaScript">
$(document).ready(function () {
    $('.wysiwyg2').wysiwyg();
});
</script>{/literal}
<link rel="stylesheet" href="{$vurl}/js/jwysiwyg/jquery.wysiwyg.css" type="text/css"/>
<script type="text/javascript" src="{$vurl}/js/jwysiwyg/jquery.wysiwyg.js"></script>
<br/><br/>

{include file="setup/email_template_vars.tpl" page="mass_mail"}

<br/><br/><br/>


        