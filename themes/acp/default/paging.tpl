{if $pag.total > 1}
    <div class="pagination_wrapper">
        <span class="pagination_top"></span>

        <div class="pagination_middle">
            <div class="pagination">
                <span class="page_no">{$pag.cpage} / {$pag.total}</span>

                <ul class="pag_list" id="paging">
                    {if $pag.cpage > 1}
                        <li><a id="{$pag.ppage}" href="#{$pag.ppage}"
                               class="pag_nav"><span><span>##Previous##</span></span></a></li>
                    {else}
                    {/if}

                    <li><a id="1" href="#1"
                           {if $pag.cpage == 1}class="current_page"{/if}><span><span>1</span></span></a></li>
                    {section name=grp start=$pag.start loop=$pag.end step=1}
                        <li>
                            <a id="{$smarty.section.grp.index}" href="#{$smarty.section.grp.index}"
                               {if $pag.cpage == $smarty.section.grp.index}class="current_page"{/if}>
                                <span><span>{$smarty.section.grp.index}</span></span></a>
                        </li>
                    {/section}
                    {if $pag.total - $pag.cpage >  7}
                        <li>[...]</li>{/if}

                    <li><a id="{$pag.total}" href="#{$pag.total}"
                           {if $pag.cpage == $pag.total}class="current_page"{/if}>
                            <span><span>{$pag.total}</span></span></a></li>
                    {if $pag.cpage < $pag.total}
                        <li><a id="{$pag.npage}" href="#{$pag.npage}" class="pag_nav"><span><span>##Next##</span></span></a>
                        </li>
                    {else}
                    {/if}


                </ul>
            </div>
        </div>
        <span class="pagination_bottom"></span>
    </div>
{/if}
{literal}
<script language="JavaScript">
$(document).ready(function () {
    $("#paging > li").each(function () {
        $(this).children(':first').click(function () {
            loadpage($(this).attr("id"));
            return false;
        });
    });
    function loadpage(p) {
        var filter = '';
        $(".filter").each(function () {
            if ($(this).val() != '') {
                filter += '&' + $(this).attr('name') + '=' + $(this).val();
            }
        });
        window.location = '{/literal}{if $pag.url != ""}{$pag.url}{else}?p={$smarty.get.p}{/if}{literal}&page=' + p + filter;
    }

    $("#butfilter").click(function () {
        loadpage(1);
        return false;
    });

});
</script>{/literal}


