<form method="post" class="cmxform" style="width:90%;">
    <input type="hidden" name="action" value="update_domain_details">
    <fieldset>
        <ol>
            <li><label>Alan Adı</label><input type="text" name="domain" value="{$Domain->domain}">
                <a href="http://whois.domaintools.com/{$Domain->domain}" target="_blank">[ WHOIS ]</a>
            </li>
            <li><label>Modül</label>
                <select name="moduleID">
                    <option value="">##None##</option>
                    {foreach from=$registrars item=title key=moduleID}
                        <option value="{$moduleID}" {if $Domain->moduleID == $moduleID}selected{/if}>{$title}</option>
                    {/foreach}
                </select>
            </li>
            <li><label>Durum</label>
                <select name="status">
                    {foreach from=$vars.DOMAIN_STATUS_TYPES item=s}
                        <option value="{$s}" {if $s == $Domain->status }selected{/if}>##DomainDetails%{$s}##</option>
                    {/foreach}
                </select>
            </li>


            {if $Domain->moduleID}
                <li><label>Tescil Tarihi</label>{format_date date=$Domain->dateReg}</li>
                <li><label>Tescil Bitiş</label>{format_date date=$Domain->dateExp}</li>
                <li><label>NS 1</label><input type="text" name="ns1" value="{$Domain->ns1}"></li>
                <li><label>NS 2</label><input type="text" name="ns2" value="{$Domain->ns2}"></li>
            {else}
                <li><label>Tescil Tarihi</label>
                    <input type="text" name="dateReg" class="datepicker" style="width:80px" value="{$Domain->_dateReg}">
                </li>
                <li><label>Tescil Bitiş</label>
                    <input type="text" name="dateExp" class="datepicker" style="width:80px" value="{$Domain->_dateExp}">
                </li>
            {/if}
        </ol>
        <p align="right" id='pbutton'><input type="submit" value="Güncelle" class="butupdate"></p>

        <p align="right" id='ploading' style="display: none;"><img src={$turl}images/loading.gif></p>
    </fieldset>
</form>
<br/><br/>

{if $Domain->moduleID != ''}
    {if $Domain->status == 'pending'}
        <h3>Tescil</h3>
        <form method="post" class="cmxform" style="width:90%;">
            <input type="hidden" name="action" value="register_domain">
            <fieldset>
                <ol>
                    <li><label>Tescil Süresi</label>
                        <select name="period">
                            {section name=i start=1 loop=10}
                                <option value="{$smarty.section.i.index}">{$smarty.section.i.index}</option>
                            {/section}
                        </select>&nbsp; senelik
                    </li>
                </ol>
                <p align="right" id='pbutton'><input type="submit" class="butupdate" value="Tescil Et"></p>

                <p align="right" id='ploading' style="display: none;"><img src={$turl}images/loading.gif></p>
            </fieldset>
        </form>
        <br/>
        <br/>
    {elseif $Domain->status == 'active'}
        <h3>Yenileme</h3>
        <form method="post" class="cmxform" style="width:90%;">
            <input type="hidden" name="action" value="renew_domain">
            <fieldset>
                <ol>
                    <li><label>Yenileme Süresi</label>
                        <select name="period">
                            {section name=i start=1 loop=10}
                                <option value="{$smarty.section.i.index}">{$smarty.section.i.index}</option>
                            {/section}
                        </select>&nbsp; senelik
                    </li>
                </ol>
                <p align="right" id='pbutton'><input type="submit" class="butupdate" value="Yenile"></p>

                <p align="right" id='ploading' style="display: none;"><img src={$turl}images/loading.gif></p>
            </fieldset>
        </form>
        <br/>
        <br/>
        <h3>Registrar Senkronizasyonu</h3>
        <form method="post" class="cmxform" style="width:90%;">
            <input type="hidden" name="action" value="refresh_domain">
            <fieldset>
                <p align="right" id='pbutton'><input type="submit" class="butupdate"
                                                     value="Alan adı bilgilerini Registrar firmadan güncelle"></p>

                <p align="right" id='ploading' style="display: none;"><img src={$turl}images/loading.gif></p>
            </fieldset>
        </form>
        <br/>
        <br/>
    {/if}

    {if $Domain->domlock == '1'}
        <h3>Transfer Kilidi</h3>
        <form method="post" class="cmxform" style="width:90%;">
            <input type="hidden" name="action" value="update_domlock">
            <fieldset>
                <ol>
                    <li><label>Kilit Durumu</label>
                        <select name="domlock">
                            <option value="1" {if $Domain->locked == '1'}selected{/if}>Kilitli</option>
                            <option value="0" {if $Domain->locked == '0'}selected{/if}>Kilit Açık</option>
                        </select>
                    </li>
                </ol>
                <p align="right" id='pbutton'><input type="submit" class="butupdate" value="Kilit Durumunu Güncelle">
                </p>

                <p align="right" id='ploading' style="display: none;"><img src={$turl}images/loading.gif></p>
            </fieldset>
        </form>
        <br/>
        <br/>
    {/if}

    {if $Domain->authcode == '1'}
        <h3>Transfer Şifresi</h3>
        <form method="post" class="cmxform" style="width:90%;">
            <input type="hidden" name="action" value="get_authcode">
            <fieldset>
                <ol>
                    <li><label>Müşteriye Gönder</label>
                        <input type="checkbox" name="send2client" value="1"> Transfer şifresini müşteriye email ile
                        gönder.
                    </li>
                </ol>
                <p align="right" id='pbutton'><input type="submit" class="butupdate" value="Transfer Şifresini Al"></p>

                <p align="right" id='ploading' style="display: none;"><img src={$turl}images/loading.gif></p>
            </fieldset>
        </form>
        <br/>
        <br/>
    {/if}
{/if}


<script src="{$vurl}js/jquery-ui.min.js" type="text/javascript"></script>
<script language="JavaScript">
    var dID = '{$Domain->domainID}';
    {literal}

    $(document).ready(function () {
        $(".datepicker").datepicker({dateFormat: 'dd-mm-yy'});
        $('.butupdate').click(function () {
            $(this).parent().hide();
            $(this).parent().next().show();
            return true;
        });
    });

</script>{/literal} 

