<h3>Yeni Özellik Ekle</h3>
<form method="post" class="cmxform" style="width:100%;">
    <input type="hidden" name="action" value="addSetting">
    <fieldset>
        <ol>
            <li>
                <label>Servis Grubu</label>
                {$select_groups}
            </li>
            <li>
                <label>Sistem adı</label>
                <input type="text" name="setting" value="{$data.setting}"/> (Boşluk içeremez, sadece harf)
            </li>
            <li>
                <label>Görünen Adı</label>
                <input type="text" name="label" value="{$data.label}"/>
            </li>
            <li>
                <label>Özelliğin Değeri</label>
                <select name="valueBy">
                    <option value="client" {if $data.valueBy == 'client' }selected{/if}>Kullanıcıdan alınır</option>
                    <option value="service" {if $data.valueBy == 'service' }selected{/if}>Servis planı tarafından
                        belirlenir
                    </option>
                    <option value="module" {if $data.valueBy == 'module' }selected{/if}>Modül tarafından belirlenir
                    </option>
                </select>
            </li>
            <li>
                <label>Tipi</label>
                <select name="type">
                    {foreach from=$types item=title key=type}
                        <option value="{$type}" {if $data.type == $type}selected{/if}>{$title}</option>
                    {/foreach}
                </select>
            </li>
            <li><label>Şifreleme</label>
                <input type="checkbox" name="encrypted" value="1" {if $data.encrypted == '1'}checked{/if}> Bu veriyi
                veritabanında şifrelenmiş olarak sakla
            </li>
            <li>
                <label>Seçenekler</label>
                <input type="text" name="options" value="{$data.options}"/>
            </li>
            <li>
                <label>Açıklama</label>
                <input type="text" name="description" value="{$data.description}"/>
            </li>
            <li>
                <label>Genişlik</label>
                <input type="text" name="width" value="{$data.width}" style="width: 100px;"/> px
            </li>
            <li>
                <label>Yükseklik</label>
                <input type="text" name="height" value="{$data.height}" style="width: 100px;"/> px
            </li>
            <li>
                <label>Doğrulama (RegEx)</label>
                <input type="text" name="validation" value="{$data.validation}" style="width: 300px;"/>
                Ör: ^[a-zA-Z0-9_]$
            </li>
            <li>
                <label>Doğrulama Bilgisi</label>
                <textarea name="validation_info" style="width: 300px;" rows="4"/>{$data.validation_info}</textarea>
            </li>
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Ekle"/></p>
</form>
