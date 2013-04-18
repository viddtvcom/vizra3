{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $("#notifyOnOrder").change(function () {
                if ($(this).attr('checked')) {
                    $("#notifyOnOrderLI").show();
                } else {
                    $("#notifyOnOrderLI").hide();
                }

            });
        });
    </script>
{/literal}
<img src="{$vurl}?p=image&t=service&f={$Service->getAvatarName()}&w=300&h=200" style="float:right;">
<h3>Genel Servis Detayları</h3>

<form method="post" class="cmxform" style="width:99%;" enctype="multipart/form-data">
    <input type="hidden" name="action" value="update">
    <fieldset>
        <ol>
            <li>
                <label>Servis Adı</label>
                <input type="text" name="service_name" value="{$Service->service_name|langneutr}" style="width:300px"/>
            </li>
            {if $Service->addon == '0' }
                <li>
                    <label>SEO Link</label>
                    <input type="text" name="seolink" value="{$Service->seolink}" style="width:300px"/>.html
                </li>
            {/if}
            <li>
                <label>Durum</label>
                <select name="status">
                    <option value="active" {if $Service->status == 'active' }selected{/if}>##Active##</option>
                    <option value="inactive" {if $Service->status == 'inactive' }selected{/if}>##Inactive##</option>
                </select>
            </li>
            <li>
                <label>Sipariş Tipi</label>
                {if $Service->addon == '1' }Eklenti{else}Ana Sipariş{/if}
            </li>
            <li>
                <label>Servis Tipi</label>
                <!--                          <select name="type">
                          {foreach from=$vars.SERVICE_TYPES item=i key=k}
                              <option value="{$k}" {if $k == $Service->type}selected{/if}>{$i}</option>
                          {/foreach}
                      </select>-->
                {assign var=type value=$Service->type}
                {$vars.SERVICE_TYPES.$type}
            </li>
            <li>
                <label>Grup</label>
                {$select_groups}
            </li>
            <li>
                <label>Açılış Sonrası Uyarı</label>
                <input type="checkbox" name="notifyOnOrder" id="notifyOnOrder" value="1"
                       {if $Service->notifyOnOrderDepID}checked{/if}> Bu servisten sipariş edildiğinde haber ver
            </li>
            <li id='notifyOnOrderLI' style="display:{if !$Service->notifyOnOrderDepID}none{/if}"><label>&nbsp;</label>
                <select name="notifyOnOrderDepID">
                    {foreach from=$deps item=d key=depID}
                        <option value="{$depID}"
                                {if $Service->notifyOnOrderDepID  == $depID}selected{/if}>{$d.depTitle}</option>
                    {/foreach}
                </select>
            </li>
            <li>
                <label>Sipariş Linki</label>
                <input type="text" value="{$vurl}?p=cart&s=srvconf&a=add&ID={$Service->serviceID}" style="width:400px"/>
            </li>
            <li>
                <label>Hesap Açılış Maili</label>
                <select name="templateID">
                    <option value="0">Yok</option>
                    {foreach from=$emails item=e}
                        <option value="{$e.templateID}"
                                {if $Service->templateID  == $e.templateID}selected{/if}>{$e.title}</option>
                    {/foreach}
                </select>
            </li>
            <li>
                <label>Destek</label>
                <input type="checkbox" name="has_support" {if $Service->has_support == '1'}checked{/if} value="1"> Bu
                servisten sipariş eden müşteri destek almaya hak kazanır. (Kısıtlı Destek Modunda)
            </li>
            <li>
                <label>Ürün Resmi</label>
                <input type="file" name="file">
            </li>
            {if $Service->addon == '0' }
                <li>
                    <label>Vitrin Sırası</label>
                    <input type="text" name="sfOrder" value="{$Service->sfOrder}" style="width:50px"/> Vitrinde
                    çıkmasını istemiyorsanız 0 giriniz
                </li>
            {/if}
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Güncelle"/></p>
    <br/><br/>

    <h3>Açıklama</h3>
    <textarea id="description" name="description" class="wysiwyg2" rows="1q"
              style="width:90%">{$Service->description}</textarea>
    <br/><br/>

    <h3>Detay Sayfası</h3>
    <textarea id="details" name="details" class="wysiwyg2" rows="11" style="width:90%;">{$Service->details}</textarea>
</form>


{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $('.wysiwyg2').wysiwyg();
        });
    </script>
{/literal}
<link rel="stylesheet" href="{$vurl}/js/jwysiwyg/jquery.wysiwyg.css" type="text/css"/>
<script type="text/javascript" src="{$vurl}/js/jwysiwyg/jquery.wysiwyg.js"></script>
