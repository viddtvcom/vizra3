<div id="inner_content">
    <h2>Sayfa Detayları</h2>

    <form method="post" class="cmxform" style="width:500px;">
        <input type="hidden" name="action" value="update">
        <fieldset>
            <ol>
                <li>
                    <label>#</label>{$Page->pageID}
                </li>
                <li>
                    <label>Başlık</label>
                    ##page_{$Page->pageID}##
                </li>
                <li>
                    <label>Parent</label>
                    <select name="parentID">
                        <option value="0">Yok</option>
                        {foreach from=$pages item=p}
                            <option value="{$p.pageID}" {if $p.pageID == $Page->parentID}selected{/if}>
                                ##page_{$p.pageID}##
                            </option>
                        {/foreach}
                    </select>
                </li>
                <li>
                    <label>Sol Menüde</label>
                    <select name="showOnSubmenu">
                        <option value="0" {if $Page->showOnSubmenu == "0"}selected{/if}>Gösterme</option>
                        <option value="1" {if $Page->showOnSubmenu == "1"}selected{/if}>Göster</option>
                    </select>
                </li>
                <li>
                    <label>Dosya Adı</label>
                    <input type="text" name="filename" value="{$Page->filename}"/>
                </li>
                <li>
                    <fieldset>
                        <legend>İşlemler</legend>
                        {foreach from=$vars.ADMIN_ACTIONS item=action key=k}
                            <label><input type="checkbox" name="act[{$k}]" value="1"
                                          {if in_array($k,$Page->actions)}checked{/if}> {$action}</label>
                        {/foreach}
                    </fieldset>
                </li>
            </ol>
        </fieldset>
        <p align="right"><input type="submit" value="Güncelle"/></p>
    </form>

</div>
