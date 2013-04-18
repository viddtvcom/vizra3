<ul class="section_menu left">
    <li>
        <a {if $tab == 'general' || $tab == ''}class="selected_lk"{/if}
           href="?p=116&serviceID={$Service->serviceID}&tab=general">
            <span class="l"><span></span></span><span class="m"><em>Genel</em><span></span></span><span
                    class="r"><span></span></span>
        </a>
    </li>
    <li>
        <a {if $tab == 'attrs'}class="selected_lk"{/if} href="?p=116&serviceID={$Service->serviceID}&tab=attrs">
            <span class="l"><span></span></span><span class="m"><em>Özellikler</em><span></span></span><span
                    class="r"><span></span></span>
        </a>
    </li>
    {if !$Service->domain}
        <li>
        <a {if $tab == 'payment'}class="selected_lk"{/if} href="?p=116&serviceID={$Service->serviceID}&tab=payment">
            <span class="l"><span></span></span><span class="m"><em>Ödeme Seçenekleri</em><span></span></span><span
                    class="r"><span></span></span>
        </a>
        </li>{/if}
    <li>
        <a {if $tab == 'files'}class="selected_lk"{/if} href="?p=116&serviceID={$Service->serviceID}&tab=files">
            <span class="l"><span></span></span><span class="m"><em>Dosyalar</em><span></span></span><span
                    class="r"><span></span></span>
        </a>
    </li>
</ul>