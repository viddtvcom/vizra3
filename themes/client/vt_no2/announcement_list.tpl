{if $announcements}
    <h2 class="title200" style="margin-bottom:10px;">##Nav%Announcements##</h2>
    <ul class="announcement_list">
        {foreach from=$announcements  item=a}
            <li>
                <h3><a href="{$vurl}?p=announcements#{$a.recID}"> {$a.title}</a></h3>
                {$a.dateAdded|formatDate:datetime:short}
            </li>
        {/foreach}
    </ul>
{/if}
