<style type="text/css" media="all">@import "{$turl}/css/kb.css";</style>
<div id="inner_content" class="pad">
    <!-- Search -->
    <form class="search" method="post" action="?p=230&act=search">
        <input type="text" class="s_input" name="query"/>
        <input type="submit" value="Ara"/>
    </form>
    <!-- /Search -->

    {if $smarty.get.act != 'search'}

        <!-- Categories -->
        <div id="categories_wrapper">
            <div class="main_title"><a href="?p=230">Dosya Merkezi</a> {$bcrumbs}</div>
            <div class="main_submenu"><a href="?p=230&act=add_cat&catID={$cat.catID}">Alt Kategori Ekle </a>
                {if $cat.catID} |
                    <a href="?p=230&act=add_file&catID={$cat.catID}">Dosya Ekle</a>
                    |
                    <a href="?p=230&act=edit_cat&catID={$cat.catID}">Düzenle</a>
                    |
                    <a href="?p=230&act=del_cat&catID={$cat.catID}"
                       onclick="return confirm('Kategori ve altındaki bütün kayıtlar silinecek. Emin misiniz?');">Sil</a>
                {/if}</div>
            <div class="clear"></div>

            {if $smarty.get.act != 'edit_cat'}
                {if $cat.title}
                    <div class="main_title">"{$cat.title}" altındaki kategoriler</div>{/if}
                {if $cats}
                    <ul class="categories">
                        {foreach from=$cats item=c key=catID}
                            <li style="width:280px;">
                                <h3 class="{$c.visibility}"><a href="?p=230&catID={$catID}">{$c.title}</a> <em
                                            class="f3">({$c.entries})</em></h3>

                                <p>{$c.description}</p>
                            </li>
                        {/foreach}
                    </ul>
                {else}Alt kategori kaydı bulunmuyor
                    <br/>
                    <br/>
                {/if}
            {/if}
        </div>
        <!-- /Categories -->



        {if $file}
            <!-- Article -->
            <div id="article">
                <div class="article_title">{$file->title}
                    <a href="?p=230&act=del_file&fileID={$file->fileID}"
                       onclick="return confirm('Dosya silinecek. Emin misiniz?');" style="float:right"><img
                                src="{$turl}images/ico_delete.png"></a>
                </div
                <p class="description f3">
                    <span>Dosya Adı:</span> {$file->origname}<br/>
                    <span>Dosya</span> No: {$file->fileID} -
                    <span>Eklenme:</span> {$file->dateAdded|formatDate:datetime}</p>

                <div class="a_post">
                    <a href="file.php?fileID={$file->fileID}&src=dc" style="float:right;"><img
                                src="{$turl}images/download.png"></a>
                    {$file->description}

                    <div class="foot f3 clear">
                        <span class="left">Ekleyen: {$file->adminID|getAdminNickFromAdminId}</span>

                    </div>
                    <!-- /foot -->
                </div>
                <!-- /a_post -->
                <div class="clear"></div>
            </div>
            <br/>
            <br/>
            <!-- /Article -->
        {/if}

    {else}
        {if !$files}
            Arama kriterinize uyan hiç bir sonuç bulunamadı.
        {/if}
    {/if}

    {if $files}
        <!-- Article List -->
        {if $smarty.get.act == 'search'}
            <div class="kb_main_title">"{$smarty.post.query}" arama sonuçları</div>
        {else}
            <div class="kb_main_title">"{$cat.title}" kategorisindeki {if $file}diğer{/if} dosyalar</div>
        {/if}
        <ul class="article_list">
            {foreach from=$files item=e key=fileID}
                <li>
                    <h3><a href="?p=230&catID={$e.catID}&fileID={$fileID}">{$e.title}</a></h3>

                    <p>{$e.body|substr:0:100}</p>
                    <em>İndirilme: {$e.downloads}</em>
                </li>
            {/foreach}
        </ul>
        <!-- /Article List -->
    {else}
        {if !$file}
            <hr>
            Bu kategoride dosya bulunmuyor{/if}
    {/if}

    {if $smarty.get.act == 'add_cat'}
        {include file='support/add_dc_cat.tpl'}
    {elseif $smarty.get.act == 'add_file'}
        {include file='support/add_dc_file.tpl'}
    {elseif $smarty.get.act == 'edit_cat'}
        {include file='support/edit_dc_cat.tpl'}
    {elseif $smarty.get.act == 'edit_file'}
        {include file='support/edit_dc_file.tpl'}
    {/if}
</div>
