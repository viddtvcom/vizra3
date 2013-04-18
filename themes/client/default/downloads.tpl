<style type="text/css" media="all">@import "{$turl}/css/kb.css";</style>
<h2 class="title700">##TopMenu%DownloadCenter##</h2>
<div class="content_right">

    <!-- Search -->
    <form class="search" method="post" action="{$vurl}?p=dc&act=search">
        <input type="text" class="tinput w50" name="query"/>
        <input type="submit" value="##Search##" class="button"/>
    </form>
    <!-- /Search -->

    {if $smarty.get.act != 'search'}

        <!-- Categories -->
        <div id="categories_wrapper">
            <h2 class="main_title"><a href="{$vurl}?p=dc">Genel</a> {$bcrumbs}</h2>

            <div class="clear"></div>
            <ul class="categories">
                {foreach from=$cats item=c key=catID}
                    <li>
                        <h3><a href="{$vurl}?p=dc&catID={$catID}">{$c.title}</a> <em class="f3">({$c.entries})</em></h3>

                        <p>{$c.description}</p>
                    </li>
                {/foreach}
            </ul>
        </div>
        <!-- /Categories -->

    {else}
        {if !$files}
            Arama kriterinize uyan hiç bir sonuç bulunamadı.
        {/if}
    {/if}

    {if $files}
        <br/>
        <br/>
        <!-- Article List -->
        <div id="article_list_wrapper">
            {if $smarty.get.act == 'search'}
                <h2 class="main_title">"{$smarty.post.query}" arama sonuçları</h2>
            {else}
                <h2 class="main_title">"{$cat.title}" kategorisindeki {if $entry}diğer{/if} dosyalar</h2>
            {/if}
            <ul class="article_list">
                {foreach from=$files item=e key=fileID}
                    <li>
                        <h3><a href="{$vurl}file.php?src=dc&id={$fileID}">{$e.title}</a></h3>
                        <a href="{$vurl}file.php?src=dc&id={$fileID}"
                           style="float: right; vertical-align: top; position:relative; top:-10px;"><img
                                    src="images/download.png" width="32px"></a>

                        <p>{$e.description}</p><br/>
                        <em>Dosya Adı: {$e.origname}</em><br/>
                        <em>Dosya Boyutu: {$e.size|formatFilesize}</em><br/>
                        <em>İndirme: {$e.downloads}</em>
                    </li>
                {/foreach}
            </ul>
        </div>
        <!-- /Article List -->
    {/if}

</div>