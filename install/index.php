<?php
// Latest
header('Content-Type: text/html; charset=utf-8');
require('common.php');

if (file_exists('../engine/config/config.php')) {
    header('location:upgrade.php');
    exit();
}
$reqs = array(
    0 => array('title' => 'PHP (5+)'),
    1 => array('title' => 'MySQL '),
    2 => array('title' => 'CURL')
);

if (PHP_VERSION >= '5.0.0') {
    $reqs[0]['st'] = true;
}
if (function_exists('mysql_connect')) {
    $reqs[1]['st'] = true;
}
if (function_exists('curl_exec')) {
    $reqs[2]['st'] = true;
}

foreach ($dirs as $k => $d) {
    if (is_writable('../' . $d['dir'])) {
        $dirs[$k]['st'] = true;
    }
}

// JSON CHECK
if (! function_exists('json_encode')) {
    $GLOBALS['errors'][] = 'Sisteminizde PHP JSON kütüphanesi bulunamadı. Vizra kendi JSON kütüphanelerini kullanacak ama sistemin daha hızlı çalışması için PHP JSON kütüphanesini yüklemelisiniz';
}

foreach ($reqs as $r) {
    if (! $r['st']) {
        $error = true;
    }
}
foreach ($dirs as $d) {
    if (! $d['st']) {
        $error = true;
    }
}

if ($_POST) {
    if (! @mysql_connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'])) {
        $dberr = 'Bu bilgiler ile veritabanı sunucusuna bağlanılamadı';
    } elseif (! @mysql_select_db($_POST['dbname'])) {
        $dberr = $_POST['dbname'] . ' adında bir veritabanı bulunamadı';
    } else {

        @mysql_query("ALTER DATABASE `" . $_POST['dbname'] . "` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
        $ret = mysql_import_file('install.sql');
        if (! $ret['st']) {
            $dberr = $ret['msg'];
            die($dberr);
        } else {
            $fp = fopen('../engine/config/config.php', 'w+');
            if (! $fp) {
                die('Yazmak için engine/config/config.php dosyası açılamadı, kurulum başarısız oldu');
            } else {
                fputs($fp, "<?php\n");

                fputs($fp, "define('DEBUG',false);\n");

                fputs($fp, "define('VERSION','" . $latest['version'] . "');\n");
                fputs($fp, "define('LICENSE','');\n");
                fputs($fp, "define('DBHOST','" . $_POST['dbhost'] . "');\n");
                fputs($fp, "define('DBNAME','" . $_POST['dbname'] . "');\n");
                fputs($fp, "define('DBUSER','" . $_POST['dbuser'] . "');\n");
                fputs($fp, "define('DBPASS','" . $_POST['dbpass'] . "');\n");

                // MYSQL
                if (function_exists('mysql_pconnect')) {
                    fputs($fp, "define('MYSQL_PCONNECT',true);\n");
                } else {
                    fputs($fp, "define('MYSQL_PCONNECT',false);\n");
                }
                // Encryption Library
                if (function_exists('mcrypt_decrypt')) {
                    fputs($fp, "define('ENCLIB','mcrypt');\n");
                    define('ENCLIB', 'mcrypt');
                } else {
                    fputs($fp, "define('ENCLIB','phpseclib');\n");
                    define('ENCLIB', 'phpseclib');
                }

                fputs($fp, "define('DEMO',false);\n");

                $salt = generateCode(16);
                fputs($fp, "define('ENCRYPT_SALT','" . $salt . "');\n");

                $basedir = strtolower(ltrim(str_replace('/install/', '', $_SERVER['PHP_SELF']), '/'));
                $basedir = str_replace('index.php', '', $basedir);

                fputs($fp, "define('BASEDIR','" . $basedir . "');\n");
                fputs($fp, "define('BASEHOST','" . $_SERVER['HTTP_HOST'] . "');\n");
                fputs($fp, "define('USE_SSL_ACP','0');\n");
                fputs($fp, "define('USE_SSL_UCP','0');\n");

                // set admin pass;
                $pass = core::encrypt('admin', $salt);
                mysql_query("UPDATE admins SET adminPassword =  '$pass' WHERE adminID = 1");


                $installed = true;
                $config['BASE_PATH'] = str_replace("install", "", realpath(dirname(__FILE__)));
                fclose($fp);
            }


        }
    }
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
<head>
    <title>Vizra3</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="../themes/client/default/css/screen.css" charset="utf-8"/>
    <!--[if IE 6]>
    <link rel="stylesheet" type="text/css" href="../themes/client/default/css/ie6.css" charset="utf-8"/><![endif]-->
</head>
<body>
<div id="wrapper">
    <div id="header">
        <h1 class="left logo"><a href="http://www.vizra.com" target="_blank"><img
                        src="../themes/client/default/images/vizra_logo.png" alt="Vizra"/></a></h1>
        <ul class="nav_top right"></ul>
    </div>
    <div class="user_bar"></div>
    <div id="content">
        <div class="left w200">
            <h2 class="title200">Uyarılar</h2>

            <div class="content_left">
                <?
                if ($GLOBALS['errors']) {
                    foreach ($GLOBALS['errors'] as $err) {
                        echo '<span class="error_message br_5">';
                        echo $err . '<br>';
                        echo '</span>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="right w700">

            <? if ($installed) { ?>

                <div class="success_message br_5">
                    Kurulum başarı ile tamamlandı. Lütfen güvenlik için install klasörünü siliniz.<br/>

                    <br/><br/>

                    <div class="msg_warn">
                        Aşağıdaki cron ayarlarını yapmanız gerekmektedir:
                        <br/>
                        Günlük CRON:
                        <br/><input type="text" value="php <?= $config['BASE_PATH'] ?>engine/cron/daily.php"
                                    style="width: 500px; margin:10px 20px;">
                        <br/>veya
                        <br/><input type="text"
                                    value="GET <?= $config['HTTP_HOST'] ?>scripts/cron.php?t=daily&key=<?= get_cron_key(
                                    ) ?>" style="width: 500px; margin:10px 20px;">
                        <br/>

                        Dakikalık CRON:
                        <br/><input type="text" value="php <?= $config['BASE_PATH'] ?>engine/cron/minutely.php"
                                    style="width: 500px; margin:10px 20px;">
                        <br/>veya
                        <br/><input type="text"
                                    value="GET <?= $config['HTTP_HOST'] ?>scripts/cron.php?t=minutely&key=<?= get_cron_key(
                                    ) ?>" style="width: 500px; margin:10px 20px;">
                    </div>
                    <br/><br/>
                    Yönetim paneli giriş bilgileri:<br/>
                    Email: admin@vizra.com<br/>
                    Şifre: admin
                    <br/><br/>
                    <a href="../acp">Yönetim paneline girmek için tıklayınız</a>
                    <br/>
                </div>
            <? } else { ?>

                <? if ($error) { ?>
                    <span class="error_message br_5">
        Kuruluma devam edebilmek için yukarıdaki hataları gidermeniz gerekmektedir
        </span>
                <? } else { ?>

                    <h2 class="title700">Veritabanı ve Lisans ayarları</h2>
                    <div class="content_right">
                        <form method="post" class="cmxform">
                            <fieldset>
                                <? if ($dberr) { ?>
                                    <span class="error_message br_5"><?= $dberr ?></span>
                                <? } ?>
                                <ol>
                                    <li><label>Sunucu Host</label> <input type="text" name="dbhost"
                                                                          value="<?= $_POST['dbhost'] ?>"></li>
                                    <li><label>Veritabanı</label> <input type="text" name="dbname"
                                                                         value="<?= $_POST['dbname'] ?>"></li>
                                    <li><label>Kullanıcı</label> <input type="text" name="dbuser"
                                                                        value="<?= $_POST['dbuser'] ?>"></li>
                                    <li><label>Şifre</label> <input type="text" name="dbpass"
                                                                    value="<?= $_POST['dbpass'] ?>"></li>
                                </ol>
                                <p align="right">
                                    <input type="submit" value="Kurulumu Tamamla" class="button">
                                </p>
                            </fieldset>
                        </form>
                    </div>
                    <br/>
                <? } ?>

                <h2 class="title700">Sistem Gereklilikleri</h2>
                <div class="content_right">
                    <form method="post" class="cmxform">
                        <fieldset>
                            <ol>
                                <?php foreach ($reqs as $r) { ?>
                                    <li><label><?= $r['title'] ?></label><strong> <? if ($r['st']){ ?><font
                                                    color='green'>OK<? } else { ?> <font color='red'>HATA<? } ?></font></strong>
                                    </li>
                                <? } ?>
                            </ol>
                        </fieldset>
                    </form>
                </div>

                <br/><h2 class="title700">Dizin Yazma Ayarları</h2>
                <div class="content_right">
        <span class="attention_message br_5"> 
            Aşağıdaki dizinlerin, Web Sunucunuz tarafından yazılabilir olması gerekmektedir.
        </span>

                    <form method="post" class="cmxform">
                        <fieldset>
                            <ol>
                                <? foreach ($dirs as $d) { ?>
                                    <li><label><?= $d['dir'] ?></label> <strong><? if ($d['st']){ ?><font color='green'>OK<? } else { ?>
                                                <font color='red'>HATA<? } ?></font></strong></li>
                                <? } ?>
                            </ol>
                        </fieldset>
                    </form>
                </div>

            <? } ?>
            <br/><br/><br/><br/><br/><br/>
        </div>
    </div>
    <div class="content_b">&nbsp;</div>
</div>
<div class="footer">
    &copy; Vizra
</div>
</body>
</html>