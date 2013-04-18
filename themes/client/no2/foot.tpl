{if $smarty.get.p != ''}
    </div>
    <!-- /section -->
{/if}


</div>
<!-- /content -->


</div> <!-- /main -->
</div><!-- /wrapper -->

<div id="footer">

    <p style="text-align:center;"><img src="images/layout/footer_logos.gif" alt=""/></p>

    <div style="background:#c4c4c4 url(images/layout/bg_footer.gif) repeat-x top; padding:20px 10px;">
        <table cellpadding="0" cellspacing="0" style="width:960px; margin:auto;">
            <tr>
                <td>
                    <ul class="fnav left">
                        <li><a href="#">SEPET</a></li>
                        <li><a href="#">BİLGİLERİM</a></li>
                        <li><a href="#">DESTEK</a></li>
                        <li><a href="#">SİPARİŞLERİM</a></li>
                        <li><a href="#">FİNANS</a></li>
                        <li><a href="#">BİLGİ BANKASI</a></li>
                        <li><a href="#">DOSYA MERKEZİ</a></li>
                    </ul>
                </td>
                <td>
                    <form method="post" class="languages right">
                        ##Language##: <select id="langsel" name="langsel">
                            {foreach from=$config.LANGS item=lang}
                                <option value="{$lang}" {if $lang  == $config.LANG}selected{/if}>{$lang}</option>
                            {/foreach}
                        </select>
                    </form>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="text-align:right; color:#666; padding-top:10px; ">Vizra {$VVERSION}</td>
            </tr>
        </table>
    </div>
</div>

<a href="http://www.vizra.com" class="copyright_vizra" style="display:block; text-align:center;">&copy; Vizra</a>

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