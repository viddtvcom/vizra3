<style type="text/css" media="all">@import "{$turl}/css/probes.css";</style>

<div class="probes">
    {foreach from=$right_probes item=s key=serverID}
        <div class="probe_server">
            <h6><a href="?p=125&act=server_details&serverID={$serverID}"
                   {if $iframe}target="mainframe"{/if}>{$s.serverName}</a></h6>
            {if $s.loadmon}
                <div class="loadavg">
                    %<span id="{$serverID}_avg1" title="Ortalama Yük (Son 1 dk)"></span>
                    %<span id="{$serverID}_avg5" title="Ortalama Yük (Son 5 dk)"></span>
                    %<span id="{$serverID}_avg15" title="Ortalama Yük (Son 15 dk)"></span>
                </div>
            {/if}
            {if $s.probes}
                <ul>
                    <li>H<br><img id="{$serverID}_http" src="{$turl}/images/status_inactive.png"
                                  title="{$s.serverName} üzerinde HTTP Servisi"></li>
                    <li>M <br><img id="{$serverID}_mysql" src="{$turl}/images/status_inactive.png"
                                   title="{$s.serverName} üzerinde MYSQL Servisi"></li>
                    <li>F <br><img id="{$serverID}_ftp" src="{$turl}/images/status_inactive.png"
                                   title="{$s.serverName} üzerinde FTP Servisi"></li>
                    <li>P <br><img id="{$serverID}_pop" src="{$turl}/images/status_inactive.png"
                                   title="{$s.serverName} üzerinde POP3 Servisi"></li>
                    <li>S <br><img id="{$serverID}_smtp" src="{$turl}/images/status_inactive.png"
                                   title="{$s.serverName} üzerinde SMTP Servisi"></li>
                </ul>
            {/if}
            <div class="clear"></div>
        </div>
    {/foreach}
</div>

{literal}
    <script type="text/javascript">
        var timestamp = 0;
        var chatTimer;
        getProbes();

        function getProbes() {
            $.post('ajax.php', {action: 'getProbes'}, function (data) {
                if (data) {
                    if (data['probes']) {
                        var probes = data['probes'];
                        var count = probes.length;
                        for (var i = 0; i < count; i++) {
                            $('#' + probes[i].probe).attr('src', turl + '/images/' + probes[i].status);
                        }
                    }
                    if (data['loads']) {
                        var loads = data['loads'];
                        count = loads.length;
                        for (var i = 0; i < count; i++) {
                            var larr = loads[i]['loadavg'].split(':');
                            var serverID = loads[i]['serverID'];
                            $('#' + serverID + '_avg1').html(larr[0]);
                            $('#' + serverID + '_avg5').html(larr[1]);
                            $('#' + serverID + '_avg15').html(larr[2]);
                        }
                    }
                }

            }, "json");
            chatTimer = setTimeout('getProbes();', 20000);
            //$("span[title]").tooltip('#tooltip');
            //$("img[title]").tooltip('#tooltip');
        }

    </script>
{/literal}