<?php
require('../engine/init.php');
require('common.php');

foreach ($dirs as $k => $d) {
    if (is_writable('../' . $d['dir'])) {
        $dirs[$k]['st'] = true;
    } else {
        $error = true;
    }
}


// JSON CHECK
if (! function_exists('json_encode')) {
    $GLOBALS['errors'][] = 'Sisteminizde PHP JSON kütüphanesi bulunamadı. Vizra kendi JSON kütüphanelerini kullanacak ama sistemin daha hızlı çalışması için PHP JSON kütüphanesini yüklemenizi tavsiye ediyoruz.';
}


if ($_POST) {
    $GLOBALS['errors'] = array();
    $fp = fopen('../engine/config/config.php', 'w+');
    if (! $fp) {
        die('Yazmak için engine/config/config.php dosyası açılamadı, yükseltme başarısız oldu');
    } else {
        fputs($fp, "<?php\n");
        $DEBUG = (DEBUG != true) ? 'false' : 'true';
        fputs($fp, "define('DEBUG'," . $DEBUG . ");\n\n");
        fputs($fp, "define('DBHOST','" . DBHOST . "');\n");
        fputs($fp, "define('DBNAME','" . DBNAME . "');\n");
        fputs($fp, "define('DBUSER','" . DBUSER . "');\n");
        fputs($fp, "define('DBPASS','" . DBPASS . "');\n");

        $MPC = (MYSQL_PCONNECT != false) ? 'true' : 'false';
        fputs($fp, "define('MYSQL_PCONNECT'," . $MPC . ");\n\n");

        $ENCLIB = (ENCLIB != 'phpseclib') ? 'mcrypt' : 'phpseclib';
        fputs($fp, "define('ENCLIB','" . $ENCLIB . "');\n");
        fputs($fp, "define('ENCRYPT_SALT','" . ENCRYPT_SALT . "');\n\n");
        fputs($fp, "define('BASEDIR','" . BASEDIR . "');\n");
        fputs($fp, "define('BASEHOST','" . BASEHOST . "');\n");
        fputs($fp, "define('VVERSION','" . $latest['version'] . "');\n");
        fputs($fp, "define('DEMO',false);\n");

        $USE_SSL_ACP = defined('USE_SSL_ACP') ? USE_SSL_ACP : '0';
        $USE_SSL_UCP = defined('USE_SSL_UCP') ? USE_SSL_UCP : '0';
        fputs($fp, "define('USE_SSL_ACP','" . $USE_SSL_ACP . "');\n");
        fputs($fp, "define('USE_SSL_UCP','" . $USE_SSL_UCP . "');\n");

        $do = false;
        $versions = array_merge($versions, array($latest['version'] => $latest['title']));
        foreach ($versions as $v => $t) {
            if ($v == $_POST['curver']) {
                $do = true;
                continue;
            }
            if ($do) {
                $v = str_replace('.', '', $v);
                if (function_exists('upgrade_' . $v)) {
                    eval('upgrade_' . $v . '();');
                }
            }
        }

        $upgraded = true;
        fclose($fp);
    }
}

function upgrade_300()
{
    mysql_import_file('upgrade_300.sql');
    global $db, $config;
    // set service rowOrder data
    $groups = $db->query("SELECT groupID FROM service_groups ORDER BY parentID,groupID ASC", SQL_KEY, 'groupID');
    foreach ($groups as $groupID) {
        $services = $db->query(
            "SELECT serviceID FROM services WHERE addon = '0' AND groupID = " . $groupID,
            SQL_KEY,
            'serviceID'
        );
        $cnt = 1;
        foreach ($services as $serviceID) {
            $sql = "UPDATE services SET rowOrder = " . (int)$cnt ++ . " WHERE serviceID = " . $serviceID;
            $db->query($sql);
        }
    }
    // service price option defaults
    $price_options = $db->query("SELECT * FROM service_price_options ORDER BY period ASC", SQL_MKEY, 'serviceID');
    $db->query("UPDATE service_price_options SET `default` = '0' WHERE 1=1");
    foreach ($price_options as $serviceID => $options) {
        foreach ((array)$options as $option) {
            $db->query(
                "UPDATE service_price_options SET `default` = '1' WHERE period = '" . $option['period'] . "' AND serviceID = " . $serviceID
            );
            break;
        }
    }
}

function upgrade_307()
{
    mysql_import_file('upgrade_307.sql');
    global $db, $config;

    $payments = $db->query("SELECT * FROM payments", SQL_ALL);
    foreach ($payments as $p) {
        $ratio = 0;
        $_cur2 = $db->query(
            "SELECT ratio FROM currency_history WHERE curID = " . MAIN_CUR_ID . " AND day = '" . date(
                'Y-m-d',
                $p['dateAdded']
            ) . "'",
            SQL_INIT,
            'ratio'
        );
        if ($_cur2 > 0) {
            $_cur1 = $db->query(
                "SELECT ratio FROM currency_history WHERE curID = " . $p['paycurID'] . " AND day = '" . date(
                    'Y-m-d',
                    $p['dateAdded']
                ) . "'",
                SQL_INIT,
                'ratio'
            );
            $ratio = $_cur1 / $_cur2;
        }
        $ratio = ($ratio > 0) ? $ratio : $config['CURTABLE'][$p['paycurID']]['ratio'];
        $db->query(
            "UPDATE payments SET xamount = " . ($p['amount'] * $ratio) . " WHERE paymentID = " . $p['paymentID']
        );
    }

    $bills = $db->query("SELECT * FROM order_bills", SQL_ALL);
    foreach ($bills as $b) {
        $ratio = 0;
        $_cur2 = $db->query(
            "SELECT ratio FROM currency_history WHERE curID = " . MAIN_CUR_ID . " AND day = '" . date(
                'Y-m-d',
                $b['dateDue']
            ) . "'",
            SQL_INIT,
            'ratio'
        );
        if ($_cur2 > 0) {
            $_cur1 = $db->query(
                "SELECT ratio FROM currency_history WHERE curID = " . $b['paycurID'] . " AND day = '" . date(
                    'Y-m-d',
                    $b['dateDue']
                ) . "'",
                SQL_INIT,
                'ratio'
            );
            $ratio = $_cur1 / $_cur2;
        }
        $ratio = ($ratio > 0) ? $ratio : $config['CURTABLE'][$b['paycurID']]['ratio'];
        $db->query("UPDATE order_bills SET xamount = " . ($b['amount'] * $ratio) . " WHERE billID = " . $b['billID']);
    }

    $db->query("DROP table currency_history");

    $GLOBALS['warnings'][] = 'Lütfen Servis Özellikleri bölümünden Vitrin ayarlarınızı kontrol ediniz';

}

function upgrade_309()
{
    mysql_import_file('upgrade_309.sql');
}

function upgrade_310()
{
    mysql_import_file('upgrade_310.sql');
}

function upgrade_311()
{
    if (getSetting('shop_banner_size') == false) {
        setSetting('shop_banner_size', '700x200');
    }

    mysql_import_file('upgrade_311.sql');
}

function upgrade_312()
{
    mysql_import_file('upgrade_312.sql');
}

function upgrade_320()
{
    mysql_import_file('upgrade_320.sql');
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


                <? if (VERSION == $latest['version']) { ?>
                    <span class="attention_message br_5">
        Şu an en son sürümü kullanmaktasınız, sıfırdan kurulum yapmak için config.php dosyanızı yedeğini aldıktan sonra siliniz.
    </span>

                <? } elseif ($upgraded) { ?>
                    <?
                    if ($GLOBALS['warnings']) {
                        echo '<span class="attention_message br_5"> ';
                        foreach ($GLOBALS['warnings'] as $wrn) {
                            echo $wrn . '<br>';
                        }
                        echo '</span>';
                    }
                    ?>
                    <span class="success_message br_5">
        Yükseltme tamamlandı. Lütfen güvenlik için install klasörünü siliniz.
        <br/><br/>
        <a href="../acp">Yönetim paneline girmek için tıklayınız</a>
        <br/>
        </span>
                <? } else { ?>

                    <? if ($error) { ?>
                        <br/><h2 class="title700">Dizin Yazma Ayarları</h2>
                        <div class="content_right">
            <span class="attention_message br_5"> 
                Aşağıdaki dizinlerin, Web Sunucunuz tarafından yazılabilir olması gerekmektedir.
            </span>

                            <form method="post" class="cmxform">
                                <fieldset>
                                    <ol>
                                        <? foreach ($dirs as $d) { ?>
                                            <li><label><?= $d['dir'] ?></label> <strong><? if ($d['st']){ ?><font
                                                            color='green'>OK<? } else { ?> <font
                                                                color='red'>HATA<? } ?></font></strong></li>
                                        <? } ?>
                                    </ol>
                                </fieldset>
                            </form>
                        </div>
                        <span class="error_message br_5">
        Kuruluma devam edebilmek için yukarıdaki hataları gidermeniz gerekmektedir
        </span>
                    <? } else { ?>

                        <h2 class="title700">Yükseltme Ayarları</h2>
                        <div class="content_right">
                            <form method="post" class="cmxform">
                                <fieldset>
                                    <ol>
                                        <li>
                                            <label>Kullandığınız Sürüm</label>
                                            <? if (false): ?>
                                                <select name="curver">
                                                    <? foreach ($versions as $ver => $title) { ?>
                                                        <option value="<?= $ver ?>" <? if (VERSION == $ver) {
                                                            echo 'selected'; } ?>><?= $title ?></option>
                                                    <? } ?>
                                                </select>
                                            <? endif; ?>
                                            <input type="hidden" name="curver" value="<?= VERSION ?>">
                                            <?= $versions[VERSION] ?>
                                        </li>
                                        <li><label>Son Sürüm:</label>
                                            <?= $latest['title'] ?>
                                        </li>
                                        <?php if (! defined('LICENSE')) { ?>
                                            <li><label>Lisans Anahtarı</label>
                                                <input type="text" name="license">
                                            </li>
                                        <? } ?>

                                    </ol>
                                    <p align="right">
                                        <input type="submit" value="Yükselt">
                                    </p>
                                </fieldset>
                            </form>
                        </div>

                    <? } ?>


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