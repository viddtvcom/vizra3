{if $pag.total > 1}
    <style type="text/css" media="all">@import "{$turl}/css/paging.css";</style>
    <div id="paging">
        {if $pag.cpage > 1}
            <a id="{$pag.page}" href="#{$pag.page}">&laquo;</a>
        {else}
            <span class="disabled">&laquo;</span>
        {/if}
        {section name=grp start=1 loop=$pag.total+1 step=1}
            {if $pag.cpage == $smarty.section.grp.index}
                <span class="active">{$smarty.section.grp.index}</span>
            {else}
                <a id="{$smarty.section.grp.index}" href="#{$smarty.section.grp.index}">{$smarty.section.grp.index}</a>
            {/if}
        {/section}
        {if $pag.cpage < $pag.total}
            <a id="{$pag.npage}" href="#{$pag.npage}">&raquo;</a>
        {else}
            <span class="disabled">&raquo;</span>
        {/if}
    </div>
{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $("#paging > a").click(function () {
                target = $(this).attr("id");
                window.location = '{/literal}{if $pag.url != ""}{$pag.url}{else}?p={$smarty.get.p}{/if}{literal}&page=' + target
                return false;
            });
        });
    </script>
{/literal}

{/if}
