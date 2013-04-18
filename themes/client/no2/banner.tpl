<script src="{$vurl}js/swfobject.js" type="text/javascript"></script>
<div class="banner_alani">

    <div class="ba_div">
        <div class="left">
            <div id="flashcontent">
                <script type="text/javascript">
                    var so = new SWFObject("images/banner.swf", "mymovie", "700", "200", "9");
                    so.addParam("menu", "false");
                    so.addVariable("dataPath", vurl + "banner.php");
                    so.write("flashcontent");
                </script>
            </div>
        </div>
        <img src="images/layout/img_download.gif" alt="" class="right"/>
    </div>

</div>
<!-- /banner_alani -->