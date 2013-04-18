<?php

if ($_POST['action'] == 'update') {

    if (DEMO == true) {
        core::raise('Demo modunda bu bilgileri değiştiremezsiniz', 'e', 'rt');
    }

    if ($_POST['new_banner'] != '') {
        $maxRowOrder = $db->query("SELECT MAX(rowOrder) as maxro FROM settings_banners", SQL_INIT, "maxro") + 1;
        $db->query(
            "INSERT INTO settings_banners (title,title_size,title_color,rowOrder) VALUES ('" . $_POST["new_banner"] . "','25','585858'," . $maxRowOrder . ")"
        );
    }
    setSetting('shop_banner_size', (int)$_POST['banner'][0] . 'x' . (int)$_POST['banner'][1]);

    foreach ((array)$_POST['banners'] as $bannerID => $v) {
        $sql = "UPDATE settings_banners 
                SET title = '" . $_POST['title'][$bannerID] . "',
                    title_size = '" . $_POST['title_size'][$bannerID] . "',
                    title_color = '" . $_POST['title_color'][$bannerID] . "',
                    spot = '" . $_POST['spot'][$bannerID] . "',
                    spot_size = '" . $_POST['spot_size'][$bannerID] . "',
                    spot_color = '" . $_POST['spot_color'][$bannerID] . "',
                    url = '" . $_POST['url'][$bannerID] . "',
                    trans_type = '" . $_POST['trans_type'][$bannerID] . "'
                    WHERE bannerID = " . $bannerID;
        $db->query($sql);
    }


    // as it is multiple uploads, we will parse the $_FILES array to reorganize it into $files
    $files = array();
    foreach ((array)$_FILES['img'] as $k => $l) {
        foreach ($l as $i => $v) {
            if (! array_key_exists($i, $files)) {
                $files[$i] = array();
            }
            $files[$i][$k] = $v;
        }
    }
    require_once('3rdparty/class.upload_0.29/class.upload.php');
    $dir_dest = $config['UPLOADS_DIR'] . 'banners';
    // now we can loop through $files, and feed each element to the class
    foreach ($files as $bannerID => $file) {
        if ($file['name'] == '') {
            continue;
        }
        $handle = new Upload($file);

        if ($handle->uploaded) {
            $handle->image_x = (int)$_POST['banner'][0];
            $handle->image_y = (int)$_POST['banner'][1];
            $handle->file_overwrite = true;
            $handle->file_new_name_body = 'image' . $bannerID;
            $handle->image_resize = true;

            $handle->image_convert = 'jpg';

            $handle->Process($dir_dest);
            if ($handle->processed) {
                core::raise('Resim yüklendi (' . $bannerID . ')', 'm');
            } else {
                core::raise($handle->error, 'e');
            }

        } else {
            core::raise($handle->error, 'e');
        }
    }


} elseif ($_GET['act'] == 'move') {
    core::moveRow('settings_banners', 'bannerID', $_GET['bannerID'], $_GET['dir']);
    redirect('?p=185');
} elseif ($_GET['act'] == 'del') {
    $db->query("DELETE FROM settings_banners WHERE bannerID = " . $_GET['bannerID']);
    @unlink($config['UPLOADS_DIR'] . 'banners' . DIRECTORY_SEPARATOR . 'image' . $_GET['bannerID'] . '.jpg');
    core::raise('Banner silindi', 'm', '?p=185');
}

$banners = $db->query("SELECT * FROM settings_banners ORDER BY rowOrder ASC", SQL_KEY, 'bannerID');
$core->assign('banners', $banners);

// banner size
$banner_size = getSetting('shop_banner_size');

if ($banner_size == false) {
    $banner_size = array(700, 200);
} else {
    $banner_size = explode('x', $banner_size);
}
$core->assign('banner_size', $banner_size);

$tpl_content = "shop"; 

