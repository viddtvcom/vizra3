<ul class="section_menu right">
    <li>
        <a {if $tab == 'gen' || $tab == ''}class="selected_lk"{/if} href="?p=111&adminID={$eAdmin->adminID}&tab=gen">
            <span class="l"><span></span></span><span class="m"><em>Genel</em><span></span></span><span
                    class="r"><span></span></span>
        </a>
    </li>
    <li>
        <a {if $tab == 'deps'}class="selected_lk"{/if} href="?p=111&adminID={$eAdmin->adminID}&tab=deps">
            <span class="l"><span></span></span><span class="m"><em>Departmanlar</em><span></span></span><span
                    class="r"><span></span></span>
        </a>
    </li>
    {if $eAdmin->type != 'super-admin'}
        <li>
            <a {if $tab == 'privs'}class="selected_lk"{/if} href="?p=111&adminID={$eAdmin->adminID}&tab=privs">
                <span class="l"><span></span></span><span class="m"><em>Yetkiler</em><span></span></span><span
                        class="r"><span></span></span>
            </a>
        </li>
    {/if}
</ul>