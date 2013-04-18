<script src="{$vurl}js/jquery.maskedinput.js" type="text/javascript"></script>
<h2 class="title2">{if $smarty.get.a == 'ndc'}##NewDomainContact##{else}##UpdateDomainContact##{/if}</h2>
<div class="article">

    {if $smarty.get.a == 'ec'}
        <span class="attention_message">Bu kayıt üzerinde yapacağınız değişiklikler, bu kaydı kullanan bütün alan adlarınızda geçerli olacaktır</span>
    {/if}

    {literal}
        <script language="JavaScript">
            $(document).ready(function () {
                $(".phone").mask('999 999 99 99');
                $("#zip").mask('99999');
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
                    <select name="country">
                        {foreach from=$ccodes item=name key=code}
                            <option value="{$code}" {if $code == 'TR'}selected{/if}>{$name}</option>
                        {/foreach}
                    </select>
                </li>
                <li><label>##ClientDetails%Phone##</label>
                    <input type="text" name="phone" value="{$Contact->phone}"
                           class="phone tinput_2 w_100  {if $lerrors.phone}error{/if}">
                    <span> 212 333 XX XX</span>
                </li>
                <li><label>##ClientDetails%Cell##</label>
                    <input type="text" name="cell" value="{$Contact->cell}"
                           class="phone tinput_2 w_100  {if $lerrors.cell}error{/if}">
                    <span> 532 333 XX XX</span>
                </li>

            </ol>
        </fieldset>
        <p>
            <input type="submit" value="##Continue##" class="button right"/>
        </p>
    </form>
</div>