<form method="post" class="cmxform" style="width:700px;">

    <fieldset>
        <ol>
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
                &nbsp;&nbsp;<input type="submit" value="Tekrar Yükle">
            </li>

            <li>
                <label>Hostname</label>
                <input type="text" name="hostname" value="{$licdata.hostname}"/>
            </li>
        </ol>
    </fieldset>
</form>
        