{if $errors || $messages || $warnings}
    <div class="section">
        <ul class="system_messages">

            {if $errors}
                <li class="red">
                    <span class="ico"></span>
                    <strong class="system_title">
                        {foreach from=$errors item=e}
                            {$e}
                            <br/>
                        {/foreach}
                    </strong>
                </li>
            {/if}

            {if $messages}
                <li class="green">
                    <span class="ico"></span>
                    <strong class="system_title">
                        {foreach from=$messages item=m}
                            {$m}
                            <br/>
                        {/foreach}
                    </strong>
                </li>
            {/if}

            {if $warnings}
                <li class="yellow">
                    <span class="ico"></span>
                    <strong class="system_title">
                        {foreach from=$warnings item=w}
                            {$w}
                            <br/>
                        {/foreach}
                    </strong>
                </li>
            {/if}
        </ul>
    </div>
{/if}
<div class="section" id="msg_container" style="display:none;">
    <ul class="system_messages">
        <li class="red">
            <span class="ico"></span>
            <strong class="system_title"></strong>
        </li>
    </ul>
</div>