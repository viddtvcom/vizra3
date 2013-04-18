<form method="post">

    <input type="hidden" name="action" value="update">

    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th width="20"></th>
                    <th width="50">##Extension##</th>
                    <th width="50">##Max Period##</th>
                    <th width="50">##Register##</th>
                    <th width="50">##Renew##</th>
                    <th width="50">##Transfer##</th>
                    <th width="30">##Currency##</th>
                    <th width="80">##Module##</th>
                    <th width="40">##Status##</th>
                    <th width="40">Kilit</th>
                    <th width="60">Transfer Kodu</th>

                    <th width="20"></th>
                    <th width="20"></th>
                </tr>
                {foreach from=$exts item=e}
                    <input type="hidden" name="extensions[{$e.serviceID}]" value="1">
                    <tr  {cycle values=',class=alt'}>
                        <td><img src="{$turl}images/status_{$e.status}.png" width="13"></td>
                        <td>{$e.extension}</td>
                        <td><select name="periodMax[{$e.serviceID}]">
                                {section name=i start=1 loop=11}
                                    <option value="{$smarty.section.i.index}"
                                            {if $smarty.section.i.index == $e.periodMax}selected{/if}>{$smarty.section.i.index}
                                        ##Years##
                                    </option>
                                {/section}
                            </select>
                        </td>
                        <td><input type="text" name="priceRegister[{$e.serviceID}]" style="width: 50px;"
                                   value="{$e.priceRegister}"></td>
                        <td><input type="text" name="priceRenew[{$e.serviceID}]" style="width: 50px;"
                                   value="{$e.priceRenew}"></td>
                        <td><input type="text" name="priceTransfer[{$e.serviceID}]" style="width: 50px;"
                                   value="{$e.priceTransfer}"></td>
                        <td>
                            <select name="paycurID[{$e.serviceID}]">
                                {foreach from=$config.CURTABLE item=c key=curID}
                                    <option value="{$curID}"
                                            {if $e.paycurID == $curID}selected{/if}>{$c.symbol}</option>
                                {/foreach}
                            </select></td>
                        <td><select name="moduleID[{$e.serviceID}]">
                                <option value="">##None##</option>
                                {foreach from=$registrars item=title key=moduleID}
                                    <option value="{$moduleID}"
                                            {if $e.moduleID == $moduleID}selected{/if}>{$title}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td><select name="status[{$e.serviceID}]">
                                <option value="active" {if $e.status == "active"}selected{/if}>##Active##</option>
                                <option value="inactive" {if $e.status == "inactive"}selected{/if}>##Inactive##</option>
                            </select>
                        </td>
                        <td align="center">
                            <input type="checkbox" name="domlock[{$e.serviceID}]" value="1"
                                   {if $e.domlock == '1'}checked{/if}>
                        </td>
                        <td align="center">
                            <input type="checkbox" name="authcode[{$e.serviceID}]" value="1"
                                   {if $e.authcode == '1'}checked{/if}>
                        </td>
                        <td align="center">
                            <a href="index.php?p=145&act=move&dir=up&serviceID={$e.serviceID}">
                                <img src="{$turl}/images/up.png">
                            </a>
                        </td>
                        <td align="center">
                            <a href="index.php?p=145&act=move&dir=down&serviceID={$e.serviceID}">
                                <img src="{$turl}/images/down.png">
                            </a>
                        </td>
                    </tr>
                {/foreach}
                <tr>
                    <td><img src="{$turl}/images/add.png"></td>
                    <td colspan="2">Uzantı Ekle: <input type="text" name="new_extension" style="width: 50px;"></td>
                    <td colspan="10"><p align="right"><input type="submit" value="##Update##"></p></td>
                </tr>
            </table>
        </div>
    </div>
</form>


