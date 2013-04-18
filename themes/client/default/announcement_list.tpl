{if $announcements}
    <br/>
    <br/>
    <br/>
    <h2 class="title200">##Nav%Announcements##</h2>
    <ul class="announcement_list nav_left">
        {foreach from=$announcements  item=a}
            <li>
                <p id="title"><a href="{$vurl}?p=announcements#{$a.recID}"> {$a.title}</a></p>

                <p class="date">{$a.dateAdded|formatDate:datetime:short}</p>
            </li>
        {/foreach}
    </ul>
{/if}
