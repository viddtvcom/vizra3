<style type="text/css" media="all">@import "{$turl}/css/tickets.css";</style>

<div id="filterbox">
    <form method="post" class="cmxform" id="filterform">
        <fieldset style="width: 60%; float: left;">
            <ol class='alt'>
                <li><label>Bilet Durumu:</label>
                    <select id="show_tickets">
                        <option value="0" {if $show_tickets == 0}selected{/if}>Atanmamış Biletleri Göster</option>
                        <option value="1" {if $show_tickets == 1}selected{/if}>Sadece Bana Atanmış Biletleri Göster
                        </option>
                        <option value="2" {if $show_tickets == 2}selected{/if}>Diğer Yöneticilerdeki Biletleri Göster
                        </option>
                    </select>
                </li>
            </ol>
        </fieldset>
    </form>
    <div class="clear"></div>
</div>


{if $admin_deps}
<div class="module_top"><h5>Yeni Biletler</h5></div>

<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th width="20"></th>
                <th id="center" width="60">Bilet ID</th>
                <th width="120">Departman</th>
                <th width="140">Müşteri</th>
                <th>Konu</th>
                <th id="right" width="100">Açılış</th>
                <th id="right" width="100">Son Güncelleme</th>
                <th width="80"></th>
            </tr>
            <tbody id="new">
            </tbody>
        </table>
    </div>
</div>
<br/><br/>
<div class="module_top"><h5>Cevap Bekleyen</h5></div>
<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th width="20"></th>
                <th id="center" width="60">Bilet ID</th>
                <th width="120">Departman</th>
                <th width="140">Müşteri</th>
                <th>Konu</th>
                <th id="right" width="100">Açılış</th>
                <th id="right" width="100">Son Güncelleme</th>
                <th width="80"></th>
            </tr>
            <tbody id="client-responded">
            </tbody>
        </table>
    </div>
    {else}
    <p class="msg_warn">
        Bağlı olduğunuz bir departman bulunmuyor.
    </p>
    {/if}


    {if $admin_deps}
        <script src="{$vurl}js/acp.tickets.js" type="text/javascript"></script>
    {/if}
