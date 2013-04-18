<h2 class="title700">{$Service->service_name}</h2>
<div class="content_right">


    <ul class="hl">

        <li>
            <ul>
                <li class="hl_price">
                    {if $Service->price.price == 0 && $Service->setup == 0}
                        <span class="onsale">Ücretsiz!</span>
                        <br/>
                        <br/>
                    {else}
                        {if $Service->onsale}<span class="onsale">İndirimde!</span><br/>{/if}
                        {if $Service->price.price}{$Service->price.display}<br/>{/if}
                    {/if}
                    {if $Service->setup > 0}
                    {$Service->setup|number_format:2} {$Service->paycurID|getCurrencyById} ##Setup##
                    {/if}<br/>
                    <input type="button"
                           onclick="window.location='{$vurl}?p=cart&s=srvconf&a=add&ID={$Service->serviceID}';"
                           value="##AddToCart##" class="button br_5">
                </li>
                <li class="hl_img"><img src="{$vurl}?p=image&t=service&f={$Service->avatar}.jpg&w=64&h=64" alt=""/></li>
                <li class="hl_details">{$Service->details}</li>
            </ul>
        </li>
    </ul>


</div>






   
