<div id="inner_content">
    <table width="100%" border="0" id="datalist" cellspacing="0" cellpadding="0">
        <tr>
            <th width="30">#</th>
            <th width="30"></th>
            <th>Sayfa</th>
            <th width="150">Dosya Adı</th>
            <th width="50">Modül#</th>
            <th width="50">Bit</th>
        </tr>
        {foreach from=$pages item=p}
            <tr  {cycle values=',class=alt'}>
                <td>{$p.pageID}</td>
                <td align="center"><a href="?p=130&act=page_details&pageID={$p.pageID}"><img
                                src="{$turl}images/file-edit.png"></a></td>
                <td>##page_{$p.pageID}##</td>
                <td>{$p.filename}</td>
                <td>{$p.moduleID}</td>
                <td>{$p.bit}</td>
            </tr>
        {/foreach}
    </table>
</div>