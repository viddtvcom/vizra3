{if $announcements}
    <h2 class="title1" style="margin-top:10px;">##Nav%Announcements##</h2>
    <ul class="article announcement_list">
        {foreach from=$announcements  item=a}
            <li>
                <p class="date left">
                    <em class="d_ay_yil">Kas 10</em>
                    <em class="d_gun">2</em>
                    <!--{$a.dateAdded|formatDate:datetime:short}-->
                </p>

                <p id="title"><a href="{$vurl}?p=announcements#{$a.recID}"> {$a.title}</a></p>
            </li>
        {/foreach}
    </ul>
{/if}
