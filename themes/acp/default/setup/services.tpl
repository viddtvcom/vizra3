<div id="filterbox">
    <form method="post" class="cmxform" id="filterform">
        <fieldset style="width: 40%; float: left;">
            <ol class='alt'>
                <li><label>Servis Grubu:</label>
                    <select id="groupID">
                        <option value="">Bütün Servisler</option>
                        {foreach from=$groups item=group_name key=groupID}
                            <option value="{$groupID}"
                                    {if $smarty.get.groupID == $groupID }selected{/if}>{$group_name}</option>
                        {/foreach}
                    </select>
                </li>
                <li><label>Servis Durumu:</label>
                    <select id="srv_status">
                        <option value="">Bütün Servisler</option>
                        <option value="active" {if $smarty.get.srv_status == "active" }selected{/if}>Sadece Aktif
                            Servisler
                        </option>
                    </select>
                </li>
            </ol>
        </fieldset>
        <fieldset style="width: 40%; float: left;">
            <ol class='alt'>
                <li><label>Sipariş Tipi:</label>
                    <select id="order_type">
                        <option value="">Bütün Servisler</option>
                        <option value="main" {if $smarty.get.order_type == "main" }selected{/if}>Sadece Ana Sipariş
                        </option>
                        <option value="addon" {if $smarty.get.order_type == "addon" }selected{/if}>Sadece Eklentiler
                        </option>
                    </select>
                </li>
            </ol>
        </fieldset>

    </form>
    <div class="clear"></div>
</div>

{foreach from=$servgrps item=services key=groupID}
    <div class="module_top"><a href="?p=117&groupID={$groupID}" style="float: left;">{$groups.$groupID}</a>
        <span style="float:right;">
        <a href="index.php?p=115&act=delGroup&groupID={$groupID}">
            <img src="{$turl}/images/ico_delete.png">
        </a>&nbsp;&nbsp;&nbsp;
        <a href="index.php?p=115&act=move&dir=up&groupID={$groupID}">
            <img src="{$turl}/images/up.png">
        </a>
        <a href="index.php?p=115&act=move&dir=down&groupID={$groupID}">
            <img src="{$turl}/images/down.png">
        </a>
        </span></div>
    <div class="clear"></div>
    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th width="20">&nbsp;</th>
                    <th width="20">&nbsp;</th>
                    <th>Servis</th>
                    <th width="100">Sipariş Tipi</th>
                    <th width="150">Servis Tipi</th>
                    <th width="50"></th>
                </tr>
                {foreach from=$services item=s key=serviceID}
                    <tr class="{cycle values='first,second'}">
                        <td><a href="index.php?p=115&act=duplicateService&serviceID={$serviceID}"
                               onclick="return confirm('Servis kopyalanacak. Emin misiniz?');">
                                <img src="{$turl}images/save-copy.png">
                            </a></td>
                        <td><img src="{$turl}images/led_{if $s.status == 'active'}green{else}white{/if}.png" width="13">
                        </td>
                        <td><a href="?p=116&serviceID={$serviceID}">{$s.service_name}</a></td>
                        <td>{if $s.addon == '1'}Eklenti{else}Ana Sipariş{/if}</td>
                        <td>{assign var=stype value=$s.type}{$vars.SERVICE_TYPES.$stype}</td>

                        <td><a href="index.php?p=115&act=delService&serviceID={$serviceID}"
                               onclick="return confirm('Servis silinecek. Emin misiniz?');">
                                <img src="{$turl}images/delete_s.png">
                            </a>
                            {if $s.addon == '0'}&nbsp;&nbsp;
                                <a href="index.php?p=115&act=move_service&dir=up&serviceID={$serviceID}&groupID={$groupID}">
                                    <img src="{$turl}/images/up_s.png">
                                </a>
                                <a href="index.php?p=115&act=move_service&dir=down&serviceID={$serviceID}&groupID={$groupID}">
                                    <img src="{$turl}/images/down_s.png">
                                </a>
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
    <br/>
    <br/>
{/foreach}


{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $("#groupID, #srv_status, #order_type").change(function () {
                var groupID = $('#groupID').val();
                var srv_status = $('#srv_status').val();
                var order_type = $('#order_type').val();
                window.location = 'index.php?p=115&groupID=' + groupID + '&srv_status=' + srv_status + '&order_type=' + order_type;
            });
        });
    </script>
{/literal}