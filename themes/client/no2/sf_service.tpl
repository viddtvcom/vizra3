<h2 class="title2">{$Service->service_name}</h2>
<div class="article">
    <!--
	<ul class="hl">
		<li>
			<ul>
				<li class="hl_price">
				  {if $Service->price.price == 0 && $Service->setup == 0}
				  <span class="onsale">Ücretsiz!</span><br /><br />
				  {else}
				      {if $Service->onsale}<span class="onsale">İndirimde!</span><br />{/if}
				      {if $Service->price.price}{$Service->price.display}<br />{/if} 
				  {/if}
				  {if $Service->setup > 0}
				      {$Service->setup|number_format:2} {$Service->paycurID|getCurrencyById} ##Setup##
				  {/if}<br />
				  <input type="button" onclick="window.location='{$vurl}?p=cart&s=srvconf&a=add&ID={$Service->serviceID}';" value="##AddToCart##" class="button br_5">
				</li>
				<li class="hl_img"><img src="{$vurl}?p=image&t=service&f={$Service->avatar}.jpg&w=64&h=64" alt="" /></li>
				<li class="hl_details">{$Service->details}</li>
			</ul>
		</li>
	</ul>
-->

    <!-- URUN Detay -->
    <h3 class="s_title">Linux Hosting Paketleri (PHP, MYSQL)</h3>

    <div class="service_info">
        <div class="s_img left">
            <img src="images/layout/urun_detay.jpg" alt="Urun Detay" class="si_main"/>
            <ul class="si_sub">
                <li><img src="images/layout/urun_detay.jpg" alt="Urun Detay" width="45" height="45"/></li>
                <li style="margin:0 8px;"><img src="images/layout/urun_detay.jpg" alt="Urun Detay" width="45"
                                               height="45"/></li>
                <li><img src="images/layout/urun_detay.jpg" alt="Urun Detay" width="45" height="45"/></li>
            </ul>
            <a href="#" style="font-size:11px;">Tümünü Görüntüle >></a>
        </div>

        <table cellpadding="0" cellspacing="0" class="s_info right">
            <tr>
                <td style="width:150px;"><b>Fiyat</b></td>
                <td>: 399.00 <span class="onsale right">İndirimde!</span></td>
            </tr>

            <tr>
                <td><b>İlk Ödeme</b></td>
                <td>: 399.00</td>
            </tr>

            <tr>
                <td><b>Havale</b></td>
                <td>: 399.00</td>
            </tr>

            <tr>
                <td colspan="2">
                    <p>Eklentiler ;</p>

                    <p><label><input type="checkbox" class="vm"/> Ekstra Disk Alanı 1GB - PLESK - (5.00 TL -
                            Aylık)</label></p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <select class="left" style="margin:5px 5px 0 0;">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                    </select>
                    <input type="submit" value="Gönder" class="button br_5 left"/>
                </td>
            </tr>
        </table>
    </div>


    <div class="clear" id="container-1" style="margin-top:20px;">
        <ul>
            <li><a href="#fragment-1" class="br_t_5">Ürün Açıklaması</a></li>
            <li><a href="#fragment-2" class="br_t_5">Kampanya</a></li>
            <li><a href="#fragment-3" class="br_t_5">Yorumlar</a></li>
            <li><a href="#fragment-4" class="br_t_5">Tartışma</a></li>
            <li><a href="#fragment-5" class="br_t_5">Taksit/İndirim</a></li>
            <li><a href="#fragment-6" class="br_t_5">Ürün Görselleri</a></li>
        </ul>
        <div id="fragment-1">


            <table cellpadding="0" cellspacing="0" class="main_table">
                <tr>
                    <td>Disk Alanı</td>
                    <td>1.250 MB</td>
                </tr>

                <tr>
                    <td>Aylık Trafik</td>
                    <td>10.000 MB</td>
                </tr>

                <tr>
                    <td>Pop3 E-Mail Adresi</td>
                    <td>15 Adet</td>
                </tr>

                <tr>
                    <td>MySQL Veritabanı</td>
                    <td>1 Adet</td>
                </tr>

                <tr>
                    <td>Sub-Domain</td>
                    <td>Opsiyonel</td>
                </tr>

                <tr>
                    <td>Platform</td>
                    <td>Windows/Linux</td>
                </tr>
            </table>


        </div>
        <div id="fragment-2">
            Kampanya
        </div>
        <div id="fragment-3">
            Yorumlar
        </div>
        <div id="fragment-4">
            Tartışma
        </div>
        <div id="fragment-5">
            Taksit/İndirim
        </div>
        <div id="fragment-6">
            Ürün Görselleri
        </div>
    </div>


    <!-- /URUN Detay -->


</div>


