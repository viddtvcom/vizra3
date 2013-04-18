<!-- /content_right -->
</div>
<!-- /right w700 -->


</div>
<div class="content_b">&nbsp;</div>
<!-- /content -->
<div class="languages">
    <form method="post">
        ##Language##: <select id="langsel" name="langsel">
            {foreach from=$config.LANGS item=lang}
                <option value="{$lang}" {if $lang  == $config.LANG}selected{/if}>{$lang}</option>
            {/foreach}
        </select>
    </form>
</div>

</div>
<!-- /wrapper -->

<div class="clear"></div>
<div class="footer">
    Vizra {$VVERSION}
</div>

</body>
</html>
{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $('#langsel').change(function () {
                $(this).parent().submit();
            });
        });
    </script>
{/literal}