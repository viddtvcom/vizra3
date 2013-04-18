<style type="text/css" media="all">@import "{$turl}/css/kb.css";</style>
<h2 class="title700">##TopMenu%KnowledgeBase##</h2>
<div class="content_right">
    <!-- Search -->
    <form class="search" method="post" action="{$vurl}?p=kb&act=search">
        <input type="text" class="tinput w50" name="query"/>
        <input type="submit" value="##Search##" class="button"/>
    </form>
    <!-- /Search -->

    {if $smarty.get.act != 'search'}

        <!-- Categories -->
        <div id="categories_wrapper">
            <h2 class="main_title"><a href="{$vurl}?p=kb">##TopMenu%KnowledgeBase##</a> {$bcrumbs} </h2>

            <div class="clear"></div>
            <ul class="categories">
                {foreach from=$cats item=c key=catID}
                    <li>
                        <h3><a href="{$vurl}?p=kb&catID={$catID}">{$c.title}</a> <em class="f3">({$c.entries})</em></h3>

                        <p>{$c.description}</p>
                    </li>
                {/foreach}
            </ul>
        </div>
        <!-- /Categories -->

        {if $entry}
            <!-- Article -->
            <div id="article">
                <h2 class="article_title">{$entry->title}</h2>

                <p class="description f3"><span>Makale</span> No: {$entry->entryID} -
                    <span>Eklenme:</span> {$entry->dateAdded|formatDate:datetime} -
                    <span>Güncelleme:</span> {$entry->dateUpdated|formatDate:datetime}</p>

                <div class="a_post">
                    {$entry->body}
                    <div class="foot f3">
                        <span class="left">Ekleyen: {$entry->adminID|getAdminNickFromAdminId}</span>
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
        {if !$entries}
            Arama kriterinize uyan hiç bir sonuç bulunamadı.
        {/if}
    {/if}

    {if $entries}
        <br/>
        <br/>
        <!-- Article List -->
        <div id="article_list_wrapper">
            {if $smarty.get.act == 'search'}
                <h2 class="main_title">"{$smarty.post.query}" arama sonuçları</h2>
            {else}
                <h2 class="main_title">"{$cat.title}" kategorisindeki {if $entry}diğer{/if} makaleler</h2>
            {/if}
            <ul class="article_list">
                {foreach from=$entries item=e key=entryID}
                    <li>
                        <h3><a href="{$vurl}?p=kb&catID={$e.catID}&entryID={$entryID}">{$e.title}</a></h3>

                        <p>{$e.body|substr:0:100}</p>
                        <em>Görüntülenme: {$e.views}</em>
                    </li>
                {/foreach}
            </ul>
        </div>
        <!-- /Article List -->
    {/if}


</div>
