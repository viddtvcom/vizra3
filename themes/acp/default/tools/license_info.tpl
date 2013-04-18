<form method="post" class="cmxform" style="width:700px;">

    <fieldset>
        <ol>
            {if $license != FALSE && $licdata.lictype != 'owned'}
                <li>
                    <label>Lisans Tipi</label>
                    ##License%{$licdata.lictype}##
                </li>
                <li>
                    <label>Lisans Bitiş Tarihi</label>
                    {$licdata.license_expires|formatDate}
                </li>
                <li>
                    <label>Önbellek Geçerlilik</label>
                    {$licdata.cache_expires|formatDate:datetime}
                    &nbsp;&nbsp;
                    <input type="hidden" name="action" value="reload">
                    <input type="submit" value="Tekrar Yükle">
                </li>
            {else}
                <li>
                    <label>Lisans Tipi</label>
                    ##License%owned##
                </li>
            {/if}
        </ol>
    </fieldset>
</form>
        