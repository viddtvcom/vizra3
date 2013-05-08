<script language="javascript">
    var clientID = '{$client->clientID}';
    {literal}

    function show_div(divID) {
        $('.subform').hide();
        $('#' + divID).fadeIn();
        doParentIframe();
        return false;
    }
</script>{/literal}

<div class="dashboard_menu_wrapper">
    <ul class="dashboard_menu">
        <li><a class="d1" href="?p=311&clientID={$client->clientID}&act=login" target="panel">Müşteri Paneli</a></li>
        <li><a class="d2" href="?p=412&clientID={$client->clientID}">Sipariş Ekle</a></li>
        <li><a class="d3" href="/" onclick="show_div('add_bill'); return false;">Genel Borç Ekle</a></li>
        <li><a class="d4" href="/" onclick="show_div('add_payment'); return false;">Ödeme Ekle</a></li>
        <li><a class="d5" href="/" onclick="show_div('add_ticket'); return false;">Bilet Ekle</a></li>
        <li><a class="d6" href="/" onclick="show_div('sendmail'); return false;">Mail Gönder</a></li>
    </ul>
</div>
<div class="hidden_form_container" style="margin-bottom:10px;">
    <div class="module subform" id="add_bill" style="display:none;">
        <div class="module_top">
            <h5>Genel Borç Ekle</h5>
        </div>
        <div class="module_bottom">
            <div class="forms_wrapper">
                <form method="post" class="search_form general_form">
                    <input type="hidden" name="action" value="add_gen_bill">
                    <fieldset>
                        <div class="forms">
                            <div class="row">
                                <label>Miktar</label>

                                <div class="inputs">
                                    <span class="input_wrapper">
                                        <input type="text" name="amount" style="width: 50px;">
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <label>Kur</label>

                                <div class="inputs">
                                    <span class="input_wrapper select_wrapper">
                                        {$select_currencies}
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="inputs">
                                    <input value="Ekle" type="submit"/>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="module subform" id="add_payment" style="display:none;">
        <div class="module_top">
            <h5>Ödeme Ekle</h5>
        </div>
        <div class="module_bottom">
            <form method="post" action="?p=311&clientID={$client->clientID}&tab=payments">
                <input type="hidden" name="action" value="add_payment">
                <input type="text" name="amount" style="width: 50px;"> {$select_currencies}
                <input type="submit" value="Ekle">
            </form>
        </div>
    </div>
    <div class="module subform" id="add_ticket" style="display:none;">
        <div class="module_top">
            <h5>Bilet Ekle</h5>
        </div>
        <div class="module_bottom">
            <form method="post" class="cmxform">
                <input type="hidden" name="action" value="add_ticket">
                <fieldset>
                    <ol>
                        <li>
                            <label>##TicketDetails%Department##</label>
                            <select name="depID" class="required">
                                {foreach from=$deps item=d}
                                    <option value="{$d.depID}"
                                            {if $smarty.post.depID == $d.depID}selected{/if}>{$d.depTitle}</option>
                                {/foreach}
                            </select>
                        </li>
                        <li>
                            <label>##TicketDetails%Subject##</label>
                            <input type="text" name="subject" class="w_250 required"
                                   value="{$smarty.post.subject|formdisplay}" minlength="5" maxlength="60">
                        </li>
                        <li>
                            <label>##TicketDetails%Priority##</label>
                            <select name="priority">
                                {foreach from=$vars.PRIORITY_OPTIONS item=p key=k}
                                    <option value="{$k}" {if $k==3}selected{/if}>##TicketDetails%{$p}##</option>
                                {/foreach}
                            </select>
                        </li>
                        <li class="last">
                            <label>Mesaj</label>
                            <textarea rows="5" name="response" id="response"
                                      style="width:400px;">{$smarty.post.response|stripslashes}</textarea>
                        </li>
                    </ol>
                </fieldset>
                <p align="right"><input type="submit" value="##TicketDetails%AddTicket##"/></p>
            </form>
        </div>
    </div>
    <div class="module subform" id="sendmail" style="display:none;">
        <div class="module_top">
            <h5>Mail Gönder</h5>
        </div>
        <div class="module_bottom">
            <div class="forms_wrapper">
                <form method="post" class="search_form general_form">
                    <input type="hidden" name="action" value="sendmail">
                    <fieldset>
                        <div class="forms">
                            <div class="row">
                                <label>Şablon</label>

                                <div class="inputs">
                                    <span class="input_wrapper select_wrapper">
                                        <select name="templateID">
                                            {foreach from=$emails item=e key=templateID}
                                                <option value="{$templateID}">{$e.title}</option>
                                            {/foreach}
                                        </select>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Gönder">
                    </fieldset>
                </form>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div class="clear"></div>

{include file=$subtpl}


