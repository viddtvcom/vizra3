<h2 class="title700">##Nav%Announcements##</h2>

<ul class="announcements">
    {foreach from=$announcements  item=a}
        <li>
            <a id="{$a.recID}"></a>

            <p id="title"><a href="{$vurl}?p=announcements&rID={$a.recID}"> {$a.title}</a></p>

            <p id="body">{$a.body}</p>

            <p id="date">{$a.dateAdded|formatDate:datetime:short}</p>
        </li>
    {/foreach}
</ul>