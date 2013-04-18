<h3>Yeni Alan Ekle</h3>
<br/>
<form method="post" class="cmxform" style="width:100%;">
    <input type="hidden" name="action" value="add">
    <fieldset>
        <ol>
            <li>
                <label>Görünen Adı</label>
                <input type="text" name="label" value="{$CS->label}"/>
            </li>
            <li>
                <label>Görünürlük</label>
                <select name="visibility">
                    <option value="required" {if $CS->visibility == 'required' }selected{/if}>Zorunlu</option>
                    <option value="hidden" {if $CS->visibility == 'hidden' }selected{/if}>Gizli</option>
                    <option value="optional" {if $CS->visibility == 'optional' }selected{/if}>Opsiyonel</option>
                </select>
            </li>
            <li>
                <label>Müşteri Tipi</label>
                <select name="client_type">
                    <option value="all" {if $CS->client_type == 'all' }selected{/if}>Hepsi</option>
                    <option value="individual" {if $CS->client_type == 'individual' }selected{/if}>Bireysel</option>
                    <option value="corporate" {if $CS->client_type == 'corporate' }selected{/if}>Kurumsal</option>
                </select>
            </li>
            <li>
                <label>Alan Tipi</label>
                <select name="type">
                    {foreach from=$types item=title key=type}
                        <option value="{$type}" {if $CS->type == $type}selected{/if}>{$title}</option>
                    {/foreach}
                </select>
            </li>
            <li><label>Şifreleme</label>
                <input type="checkbox" name="encrypted" value="1" {if $CS->encrypted == '1'}checked{/if}> Bu veriyi
                veritabanında şifrelenmiş olarak sakla
            </li>
            <li>
                <label>Seçenekler</label>
                <input type="text" name="options" value="{$CS->options}"/>
            </li>
            <li>
                <label>Açıklama</label>
                <input type="text" name="description" value="{$CS->description}"/>
            </li>
            <li>
                <label>Genişlik</label>
                <input type="text" name="width" value="{$CS->width}" style="width: 100px;"/> px
            </li>
            <li>
                <label>Yükseklik</label>
                <input type="text" name="height" value="{$CS->height}" style="width: 100px;"/> px
            </li>
            <li>
                <label>Doğrulama (RegEx)</label>
                <input type="text" name="validation" value="{$CS->validation}" style="width: 300px;"/>
                Ör: ^[a-zA-Z0-9_]$
            </li>
            <li>
                <label>Doğrulama (Fonksiyon)</label>
                <select name="validation_function">
                    <option value="">Yok</option>
                    {foreach from=$functions item=func}
                        <option value="{$func}" {if $CS->validation_function == $func}selected{/if}>{$func}</option>
                    {/foreach}
                </select> &nbsp; (Doğrulama Fonksiyon olarak seçildiğinde RegEx iptal olur)
            </li>
            <li>
                <label>Doğrulama Bilgisi</label>
                <textarea name="validation_info" style="width: 300px;" rows="4"/>{$CS->validation_info}</textarea>
            </li>
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Ekle"/></p>
</form>
<br/>
<ul class="system_messages">
    <li class="blue"><span class="ico"></span><strong class="system_title">
            engine/lib/extend/class.attrs.php dosyasına kendi doğrulama fonksiyonlarınızı ekleyebilirsiniz.
        </strong></li>
</ul>
