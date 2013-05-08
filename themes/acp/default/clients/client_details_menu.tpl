<ul class="section_menu right">
    <li>
        <a {if $tab == 'general' || $tab == ''}class="selected_lk"{/if}
           href="?p=311&clientID={$client->clientID}&tab=general">
            <span class="l"><span></span></span><span class="m"><em>Bilgiler</em><span></span></span><span
                    class="r"><span></span></span>
        </a>
    </li>
    <li>
        <a {if $tab == 'orders'}class="selected_lk"{/if} href="?p=311&clientID={$client->clientID}&tab=orders">
            <span class="l"><span></span></span><span class="m"><em>Siparişler</em><span></span></span><span
                    class="r"><span></span></span>
        </a>
    </li>
    <li>
        <a {if $tab == 'domains'}class="selected_lk"{/if} href="?p=311&clientID={$client->clientID}&tab=domains">
            <span class="l"><span></span></span><span class="m"><em>Alan Adları</em><span></span></span><span class="r"><span></span></span>
        </a>
    </li>
    <li>
        <a {if $tab == 'bills'}class="selected_lk"{/if} href="?p=311&clientID={$client->clientID}&tab=bills">
            <span class="l"><span></span></span><span class="m"><em>Borçlar</em><span></span></span><span
                    class="r"><span></span></span>
        </a>
    </li>
    <li>
        <a {if $tab == 'payments'}class="selected_lk"{/if} href="?p=311&clientID={$client->clientID}&tab=payments">
            <span class="l"><span></span></span><span class="m"><em>Ödemeler</em><span></span></span><span
                    class="r"><span></span></span>
        </a>
    </li>
    <li>
        <a {if $tab == 'accflow'}class="selected_lk"{/if} href="?p=311&clientID={$client->clientID}&tab=accflow">
            <span class="l"><span></span></span><span class="m"><em>Hesap Akışı</em><span></span></span><span class="r"><span></span></span>
        </a>
    </li>
    <li>
        <a {if $tab == 'tickets'}class="selected_lk"{/if} href="?p=311&clientID={$client->clientID}&tab=tickets">
            <span class="l"><span></span></span><span class="m"><em>Biletler</em><span></span></span><span
                    class="r"><span></span></span>
        </a>
    </li>
</ul>
