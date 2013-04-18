{if $summary}
    Siparişinizin toplam tutarı: {$summary.total}
    <br/>
    <br/>
    Sipariş No:
    {foreach from=$summary.orders item=o}
        {$o}
    {/foreach}
    <br/>
    <br/>
{/if}

{$html}