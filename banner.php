<?php
require('engine/init.php');
if (isset($_get['img'])) {
    $sourcePath = $config['UPLOADS_DIR'] . 'banners' . DIRECTORY_SEPARATOR . $_get['img'] . '.jpg';
    require_once($config['LIB_DIR'] . '3rdparty' . DIRECTORY_SEPARATOR . 'thumbnailer' . DIRECTORY_SEPARATOR . 'ThumbLib.inc.php');

    if (! file_exists($sourcePath)) {
        $sourcePath = $config["UPLOADS_DIR"] . $_get['t'] . DIRECTORY_SEPARATOR . 'default.gif';
    }
    $thumb = PhpThumbFactory::create($sourcePath);
    //$thumb->adaptiveResize(700,200);
    $thumb->show();
    exit;
}
header("Content-Type: text/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<main

        stageWidth=""
        stageHeight=""
        stageBgColor=""
        stageBgTransparency=""

        imageWidth=""
        imageHeight=""
        imageTransitionType="auto"
        imageResize="none"
        imageAlign="TL"
        imageRandomDisplay="no"
        imageAutoPlay="yes"

        thumbnails="yes"
        thumbnailWidth="0"
        thumbnailHeight="0"
        thumbnailToThumbnailSpacing="3"
        thumbnailsArrangeHorizontal="yes"
        thumbnailsAutoHide="yes"
        thumbnailsMaskSize="0"
        thumbnailsScrollSpeed="95"
        thumbnailsAlign="BR"
        thumbnailsLeftSpace="0"
        thumbnailsRightSpace="90"
        thumbnailsTopSpace="0"
        thumbnailsBottomSpace="7"

        textEmbedFont="yes"
        textShadowTransparency="70"
        textShadowDistance="2"

        buttonAutoHide="yes"
        buttonBgColor="ffffff"
        buttonIconColor="000000"
        buttonAutoPosition="yes"

        >
    <?
    $items = $db->query("SELECT * FROM settings_banners ORDER BY rowOrder ASC", SQL_ALL);
    foreach ($items as $i) {
        ?>
        <slide
                image="<?= $config['HTTP_HOST'] ?>banner.php?img=image<?= $i["bannerID"] ?>"
                thumbnailText="<?= ++$row ?>"
                textTransitionType="<?= $i["trans_type"] ?>"
                textWidth=""
                textAlign="BL"
                textLeftSpace="45"
                textRightSpace="0"
                textTopSpace=""
                textBottomSpace="30"
                slideShowDelay="4"
                <?= ($i["url"]) ? 'url="http://' . $i["url"] . '" target="_self"' : '' ?>
                >
            <text font="2" leftMargin=""><![CDATA[<b><font size="<?= $i["title_size"] ?>"
                                                           color="#<?= $i["title_color"] ?>"><?= $i["title"] ?></font></b>]]>
            </text>
            <text font="1" leftMargin=""><![CDATA[<font size="<?= $i["spot_size"] ?>"
                                                        color="#<?= $i["spot_color"] ?>"><?= $i["spot"] ?></font>]]>
            </text>
        </slide>
    <? } ?>

</main>



  
