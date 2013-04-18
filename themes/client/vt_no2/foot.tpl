{if $smarty.get.p != ''}

    </div>
    <!-- /left w700 -->
    </div>
    <!-- /content -->


{/if}

<div class="push"></div>

</div>
</div>
<!-- /wrapper -->

<div class="clear"></div>
<div class="footer">

    <div style="width:980px; margin:auto; padding:10px;">


        <ul style="margin:auto; width:980px; overflow:hidden;">
            <li class="left" style="width:180px;">
                <ul>
                    <li><a href="#">Hosting</a></li>
                    <li><a href="#">Linux Hosting</a></li>
                    <li><a href="#">Windows Hosting</a></li>
                    <li><a href="#">Web Tasarım</a></li>
                    <li><a href="#">İçerik Yönetim Sistemi</a></li>
                </ul>
            </li>

            <li class="left" style="width:180px;">
                <ul>
                    <li><a href="#">Domain</a></li>
                    <li><a href="#">Domain Kayıt</a></li>
                    <li><a href="#">Domain Transfer</a></li>
                    <li><a href="#">Domain Satışı</a></li>
                    <li><a href="#">Domain Fiyatları</a></li>
                    <li><a href="#">Whois Sorgulama</a></li>
                </ul>
            </li>

            <li class="left" style="width:180px;">
                <ul>
                    <li><a href="#">Destek</a></li>
                    <li><a href="#">Online Destek</a></li>
                    <li><a href="#">Ticket Sistemi</a></li>
                    <li><a href="#">S.S.S</a></li>
                    <li><a href="#">Mail Kurulumu</a></li>
                </ul>
            </li>

            <li class="left" style="width:180px;">
                <ul>
                    <li><a href="#">Hesaplar</a></li>
                    <li><a href="#">Yeni Hesap</a></li>
                    <li><a href="#">Şifre Hatırlatma</a></li>
                    <li><a href="#">Alış-Veriş Sepeti</a></li>
                </ul>
            </li>

            <li style="text-align:right;" class="right">
                <ul>
                    <li><a href="#">İletişim</a></li>
                    <li><a href="#">Banka Hesap Bilgileri</a></li>
                    <li><a href="#">Hakkımızda</a></li>
                    <li><a href="#">Gizlilik Politikası</a></li>
                    <li><a href="#">Hizmet Sözleşmesi</a></li>

                    <li>
                        <form method="post">
                            ##Language##: <select id="langsel" name="langsel">
                                {foreach from=$config.LANGS item=lang}
                                    <option value="{$lang}" {if $lang  == $config.LANG}selected{/if}>{$lang}</option>
                                {/foreach}
                            </select>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>

    </div>
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