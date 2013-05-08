{if $show_cron_note}
    <ul class="system_messages">
        <li class="yellow"><span class="ico"></span><strong class="system_title">
                Otomasyon sisteminin çalışabilmesi için, DAKİKADA 1 DEFA çalışacak şekilde aşağıdaki cron ayarını yapmış
                olmanız gerekmektedir.
                <br/><input type="text" value="php {$config.BASE_PATH}engine/cron/minutely.php"
                            style="width: 500px; margin:10px 20px;">
                <br/>veya
                <br/><input type="text" value="GET {$config.HTTP_HOST}scripts/cron.php?t=minutely&key={''|get_cron_key}"
                            style="width: 500px; margin:10px 20px;">

                <br/>Son çalışma zamanı: {'minutely.php'|cronLastRun}</strong>

        </li>
    </ul>
{/if}
<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <th></th>
            <th>Port</th>
            <th>İsim</th>
            <th>Sunucu</th>
            <th width="20"></th>
            {foreach from=$probes item=p}
                <tr {cycle values=',class=alt'}>
                    <td width="20"><img src="{$turl}images/led_{if $p.status == 'on'}green{else}white{/if}.png"</td>
                    <td width="50">{$p.port}</td>
                    <td>{$p.title}</td>
                    <td width="250">{$p.serverName}</td>
                    <td><a href="index.php?p=150&act=delProbe&probeID={$p.probeID}"
                           onclick="return confirm('Emin misiniz?');">
                            <img src="{$turl}images/ico_delete.png">
                        </a></td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>