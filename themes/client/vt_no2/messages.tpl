{if $errors}
    <span class="error_message br_5">
    {foreach from=$errors item=e}
        {$e}
        <br/>
    {/foreach}
    </span>
{/if}

{if $messages}
    <span class="success_message br_5">
    {foreach from=$messages item=m}
        {$m}
        <br/>
    {/foreach}
    </span>
{/if}

{if $warnings}
    <span class="attention_message br_5">
    {foreach from=$warnings item=w}
        {$w}
        <br/>
    {/foreach}
    </span>
{/if}
