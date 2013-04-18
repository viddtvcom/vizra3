<h2 class="title700">{$group.group_name}</h2>
<div class="content_right">
    <div class="title_topic">
        {$group.description}
    </div>

    <ul class="hl">
        {foreach from=$services item=s key=serviceID}
            <li>
                <ul>
                    <li class="hl_price">
                        {if !$s.price.display && $s.setup == 0}
                            <span class="onsale">##Free##!</span>
                            <br/>
                            <br/>
                        {else}
                            {if $s.onsale}<span class="onsale">##OnSale##!</span><br/>{/if}

                            {$s.price.display}
                            <br/>
                        {/if}
                        {if $s.setup > 0}
                        {$s.setup|number_format:2} {$s.paycurID|getCurrencyById} ##Setup##

                        {/if}<br/>
                        <input type="button" onclick="window.location='{$vurl}?p=cart&s=srvconf&a=add&ID={$serviceID}';"
                               value="##AddToCart##" class="button br_5">
                    </li>
                    <li class="hl_img"><img src="{$vurl}?p=image&t=service&f={$s.avatar}.jpg&w=64&h=64" alt=""/></li>
                    <li class="hl_title">
                        <h3>
                            {if $s.details}
                                <a href="{$vurl}{if $seo}{$s.seolink}.html{else}?p=shop&s=srv&sID={$serviceID}{/if}">{$s.service_name}</a>
                            {else}
                                {$s.service_name}
                            {/if}
                        </h3>
                    </li>
                    <li class="hl_details">{$s.description}
                        {if $s.details}
                            <a href="{$vurl}{if $seo}{$s.seolink}.html{else}?p=shop&s=srv&sID={$serviceID}{/if}"><br/>##ClickHereForDetails##</a>
                        {/if}
                    </li>
                </ul>
            </li>
        {/foreach}
    </ul>


</div>




   
