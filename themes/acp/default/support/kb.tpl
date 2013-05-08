<style type="text/css" media="all">@import "{$turl}/css/kb.css";</style>
<!-- Search -->
<form class="search" method="post" action="?p=220&act=search">
    <input type="text" class="s_input" name="query"/>
    <input type="submit" value="Ara"/>
</form>
<!-- /Search -->

{if $smarty.get.act != 'search'}

    <!-- Categories -->
    <div id="categories_wrapper">
        <div class="main_title"><a href="?p=220">Bilgi Bankası</a> {$bcrumbs}</div>
        <div class="main_submenu"><a href="?p=220&act=add_cat&catID={$cat.catID}">Alt Kategori Ekle </a>
            {if $cat.catID} |
                <a href="?p=220&act=add_entry&catID={$cat.catID}">Makale Ekle</a>
                |
                <a href="?p=220&act=edit_cat&catID={$cat.catID}">Düzenle</a>
                |
                <a href="?p=220&act=del_cat&catID={$cat.catID}"
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
                            <h3 class="{$c.visibility}"><a href="?p=220&catID={$catID}">{$c.title}</a> <em
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



    {if $entry && $smarty.get.act != 'edit_entry'}
        <!-- Article -->
        <div id="article">
            <div class="article_title">{$entry->title}
                <a href="?p=220&act=edit_entry&entryID={$entry->entryID}"><img src="{$turl}images/file-edit.png"></a>
                <a href="?p=220&act=del_entry&entryID={$entry->entryID}"
                   onclick="return confirm('Makale silinecek. Emin misiniz?');"><img src="{$turl}images/ico_delete.png"></a>
            </div>
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
    <!-- Article List -->
    {if $smarty.get.act == 'search'}
        <div class="kb_main_title">"{$smarty.post.query}" arama sonuçları</div>
    {else}
        <div class="kb_main_title">"{$cat.title}" kategorisindeki {if $entry}diğer{/if} makaleler</div>
    {/if}
    <ul class="article_list">
        {foreach from=$entries item=e key=entryID}
            <li>
                <h3><a href="?p=220&catID={$e.catID}&entryID={$entryID}">{$e.title}</a></h3>

                <p>{$e.body|substr:0:100}</p>
                <em>Görüntülenme: {$e.views}</em>
            </li>
        {/foreach}
    </ul>
    <!-- /Article List -->
{/if}

{if $smarty.get.act == 'add_cat'}
    {include file='support/add_kb_cat.tpl'}
{elseif $smarty.get.act == 'add_entry'}
    {include file='support/add_kb_entry.tpl'}
{elseif $smarty.get.act == 'edit_cat'}
    {include file='support/edit_kb_cat.tpl'}
{elseif $smarty.get.act == 'edit_entry'}
    {include file='support/edit_kb_entry.tpl'}
{/if}

{include file="text_editor.tpl"}
