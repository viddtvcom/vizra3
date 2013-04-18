{php}
    $this->_tpl_vars['banner_size'] = explode('x', getSetting('shop_banner_size'));
{/php}
<script src="{$vurl}js/swfobject.js" type="text/javascript"></script>
<div align="center" style="padding:0px; margin-bottom: 10px; border-top: 1px solid #D0D0D0;" class="content_right">
    <div id="flashcontent">
        <script type="text/javascript">
            var so = new SWFObject("images/banner.swf", "mymovie", "{$banner_size.0}", "{$banner_size.1}", "9");
            so.addParam("menu", "false");
            so.addVariable("dataPath", vurl + "banner.php");
            so.write("flashcontent");
        </script>
    </div>
</div>