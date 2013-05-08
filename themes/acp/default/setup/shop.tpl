<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="update">

    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table cellpadding="0" cellspacing="0" width="100%">

                <tr>
                    <td width="16" height="50"></td>
                    <td>
                        <strong>Banner Boyutları:</strong>
                        <input type="text" name="banner[0]" value="{$banner_size.0}" style="width: 30px;"> x
                        <input type="text" name="banner[1]" value="{$banner_size.1}" style="width: 30px;">px
                        &nbsp;&nbsp;&nbsp; (Banner boyutlarını değiştirdikten sonra, imajları tekrar upload etmeniz
                        gerekmektedir)
                    </td>
                    <td width="16"></td>
                    <td width="16"></td>
                </tr>

                {foreach from=$banners item=b key=bannerID}
                    <tr>
                        <td align="center"><a href="?p=185&act=del&bannerID={$bannerID}"><img
                                        src="{$turl}images/ico_delete.png"></a></td>
                        <td align="center">
                            <input type="hidden" name="banners[{$bannerID}]" value="1">
                            <table class="table_wrapperi">
                                <tr>
                                    <th width="80">Başlık</th>
                                    <td><input type="text" name="title[{$bannerID}]" style="width: 380px;"
                                               value="{$b.title}"></td>
                                    <th width="40">Boyut</th>
                                    <td><input type="text" name="title_size[{$bannerID}]" style="width: 30px;"
                                               value="{$b.title_size}">px
                                    </td>
                                    <th width="40">Renk</th>
                                    <td>#<input type="text" name="title_color[{$bannerID}]" style="width: 50px;"
                                                value="{$b.title_color}"></td>

                                </tr>
                                <tr>
                                    <th>Spot</th>
                                    <td><textarea name="spot[{$bannerID}]" style="width: 380px;">{$b.spot}</textarea>
                                    </td>
                                    <th width="40">Boyut</th>
                                    <td><input type="text" name="spot_size[{$bannerID}]" style="width: 30px;"
                                               value="{$b.spot_size}">px
                                    </td>
                                    <th width="40">Renk</th>
                                    <td>#<input type="text" name="spot_color[{$bannerID}]" style="width: 50px;"
                                                value="{$b.spot_color}"></td>

                                </tr>
                                <tr>
                                    <th>URL</th>
                                    <td colspan="5">
                                        http:// <input type="text" name="url[{$bannerID}]" style="width: 262px;"
                                                       value="{$b.url}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Resim</th>
                                    <td colspan="5">
                                        <input type="file" name="img[{$bannerID}]">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Geçiş Tipi</th>
                                    <td colspan="5">
                                        <select name="trans_type[{$bannerID}]">
                                            {section name=i start=1 loop=6}
                                                <option value="{$smarty.section.i.index}"
                                                        {if $b.trans_type  == $smarty.section.i.index}selected{/if}>{$smarty.section.i.index}</option>
                                            {/section}
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <br/>

                            <p align="center"><img src="../banner.php?img=image{$bannerID}"
                                                   style="border: 1px solid #999;"></p><br/><br/>
                        </td>
                        <td align="center">
                            <a href="index.php?p=185&act=move&dir=up&bannerID={$bannerID}">
                                <img src="{$turl}/images/up.png">
                            </a>
                        </td>
                        <td align="center">
                            <a href="index.php?p=185&act=move&dir=down&bannerID={$bannerID}">
                                <img src="{$turl}/images/down.png">
                            </a>
                        </td>
                    </tr>
                {/foreach}
                <tr>
                    <td><img src="{$turl}/images/add.png"></td>
                    <td>Yeni Banner Ekle, Başlık: <input type="text" name="new_banner" style="width: 300px;"></td>
                    <td>&nbsp;</td>
                    <td colspan="10"></td>
                </tr>
            </table>
        </div>
    </div>
    <p align="right"><input type="submit" value="##Update##"></p>
</form>


