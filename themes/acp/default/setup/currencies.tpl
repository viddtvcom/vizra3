<form method="post">

    <input type="hidden" name="action" value="update">

    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th width="50">Ana Kur</th>
                    <th width="30"></th>
                    <th>Açıklama</th>
                    <th width="50">ISO Kod</th>
                    <th width="50">Sembol</th>
                    <th width="50">Oran</th>
                    <th width="20"></th>
                    <th width="20"></th>
                </tr>
                {foreach from=$curs item=c key=k}
                    <input type="hidden" name="currencies[{$c.curID}]" value="1">
                    <tr  {cycle values=',class=alt'}>
                        <td align="center"><input type="radio" name="main_cur_id" value="{$c.curID}"
                                                  {if $main_cur_id == $c.curID}checked{/if}></td>
                        <td>
                            <select name="status[{$c.curID}]">
                                <option value="active" {if $c.status == "active"}selected{/if}>##Active##</option>
                                <option value="inactive" {if $c.status == "inactive"}selected{/if}>##Inactive##</option>
                            </select>
                        </td>
                        <td><input type="text" name="description[{$c.curID}]" style="width: 90%;"
                                   value="{$c.description}"></td>
                        <td><input type="text" name="code[{$c.curID}]" style="width: 50px;" value="{$c.code}"></td>
                        <td><input type="text" name="symbol[{$c.curID}]" style="width: 50px;" value="{$c.symbol}"></td>
                        <td><input type="text" name="ratio[{$c.curID}]" style="width: 50px;" value="{$c.ratio}"></td>
                        <td align="center">
                            <a href="index.php?p=165&act=move&dir=up&curID={$c.curID}">
                                <img src="{$turl}/images/up.png">
                            </a>
                        </td>
                        <td align="center">
                            <a href="index.php?p=165&act=move&dir=down&curID={$c.curID}">
                                <img src="{$turl}/images/down.png">
                            </a>
                        </td>
                    </tr>
                {/foreach}
                <tr>
                    <td><img src="{$turl}/images/add.png"></td>
                    <td>&nbsp;</td>
                    <td><input type="text" name="new_currency" style="width: 100%;"></td>
                    <td colspan="10"></td>
                </tr>
            </table>
        </div>
    </div>
    <p align="right"><input type="submit" value="##Update##"></p>
</form>


