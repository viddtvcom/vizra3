<script src="{$vurl}js/jquery.maskedinput.js" type="text/javascript"></script>


<h2 class="title700">{if $smarty.get.a == 'ndc'}##NewDomainContact##{else}##UpdateDomainContact##{/if}</h2>
<div class="content_right">

    {if $smarty.get.a == 'ec'}
        <span class="attention_message">Bu kayıt üzerinde yapacağınız değişiklikler, bu kaydı kullanan bütün alan adlarınızda geçerli olacaktır</span>
    {/if}

    {literal}
        <script language="JavaScript">
            $(document).ready(function () {
                $("#country").change(function () {
                    $('.ccode').html($('option:selected', this).attr('cc'));
                    if ($('option:selected', this).attr('ccmask') != '') {
                        $(".phone").mask($('option:selected', this).attr('ccmask'));
                    }
                    else {
                        $(".phone").unmask();
                    }
                });
                $("#country").change();
            });
        </script>
    {/literal}

    <form method="post" class="cmxform">
        <input type="hidden" name="action" value="add">
        <fieldset>
            <ol>
                <li>
                    <label>##ClientDetails%NameSurname##</label>
                    <input type="text" name="name" value="{$Contact->name}"
                           class="tinput_2 w_300 {if $lerrors.name}error{/if}">
                </li>
                <li><label>##ClientDetails%CompanyName##</label>
                    <input type="text" name="company" value="{$Contact->company}"
                           class="tinput_2 w_300 {if $lerrors.company}error{/if}">
                </li>
                <li><label>##ClientDetails%Email##</label>
                    <input type="text" name="email" value="{$Contact->email}"
                           class="tinput_2 w_300 {if $lerrors.email}error{/if}">
                </li>

                <li><label>##ClientDetails%Address##</label>
                    <input type="text" name="address" value="{$Contact->address|formdisplay}"
                           class="tinput_2 w_300 {if $lerrors.address}error{/if}">
                </li>
                <li><label>##ClientDetails%City##</label>
                    <input type="text" name="city" value="{$Contact->city}"
                           class="tinput_2 w_300 {if $lerrors.city}error{/if}">
                </li>
                <li><label>##ClientDetails%State##</label>
                    <input type="text" name="state" value="{$Contact->state}"
                           class="tinput_2 w_300  {if $lerrors.state}error{/if}">
                </li>
                <li><label>##ClientDetails%Zip##</label>
                    <input type="text" name="zip" id="zip" value="{$Contact->zip}"
                           class="tinput_2 w_300  {if $lerrors.zip}error{/if}">
                </li>
                <li><label>##ClientDetails%Country##</label>
                    <select name="country" id="country" class="tinput_2">
                        {foreach from=$countries item=country }
                            <option cc="{$country.calling_code}" ccmask="{$country.calling_code_mask}"
                                    value="{$country.country_code}"
                                    {if $country.country_code  == $Client->country && $Contact->country == ''}selected{/if}
                                    {if $country.country_code  == $Contact->country}selected{/if}
                                    >
                                {$country.country}</option>
                        {/foreach}
                    </select>
                </li>
                <li><label>##ClientDetails%Phone##</label>
                    + <span class="ccode" style="font-size: 13px;"></span>
                    <input type="text" name="phone" value="{$Contact->phone}" style="width: 200px;" maxlength="15"
                           class="phone tinput_2  {if $lerrors.phone}error{/if}">

                </li>
                <li><label>##ClientDetails%Cell##</label>
                    + <span class="ccode" style="font-size: 13px;"></span>
                    <input type="text" name="cell" value="{$Contact->cell}" style="width: 200px;" maxlength="15"
                           class="phone tinput_2  {if $lerrors.cell}error{/if}">
                </li>

            </ol>
        </fieldset>
        <p>
            <input type="submit" value="##Continue##" class="button right"/>
        </p>
    </form>

</div>
