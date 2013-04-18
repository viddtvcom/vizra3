{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $("#groupID").change(function () {
                var groupID = $(this).val();
                window.location = 'index.php?p=120&groupID=' + groupID;
            });
        });
    </script>
{/literal}
<div id="selectbox">
    <label>Servis Grubu: </label>
    <select id="groupID">
        <option value="">Bütün Servisler</option>
        {foreach from=$groups item=g}
            <option value="{$g.groupID}" {if $smarty.get.groupID == $g.groupID }selected{/if}>{$g.group_name}</option>
        {/foreach}
    </select>
</div>

<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th scope="col">Özellik</th>
                <th scope="col">Grup</th>
                <th width="20">&nbsp;</th>
            </tr>
            {foreach from=$attrs item=a}
                <tr {cycle values=',class=alt'}>
                    <td><a href="?p=120&act=details&settingID={$a.settingID}">{$a.label}</a></td>
                    <td>{if $a.group_name == ""}Genel{else}{$a.group_name}{/if}</td>
                    <td>
                        {if $a.settingID > 100}
                            <a href="index.php?p=120&act=del&setting={$a.setting}"
                               onclick="return confirm('Bu özellik bütün servis ve siparişlerden silinecek. Emin misiniz?');">
                                <img src="{$turl}images/ico_delete.png">
                            </a>
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>

