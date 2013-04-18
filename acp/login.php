<?php
require('../engine/init.php');
require_once("func.admin.php");
set_include_path(get_include_path() . PATH_SEPARATOR . $config["BASE_PATH"] . 'engine/admin');
$core = new vSmarty("acp");

if ($_GET['p'] == '1') {
    $core->display('login.tpl');
    exit;
}

if (isset($_SESSION["vadmin"]) && isset($_GET['out'])) {
    unset($_SESSION["vadmin"]);
    unset($_SESSION['settings_general']);
    Admin::delCookies();

} elseif (isset($_COOKIE['_vap']) && isset($_COOKIE['_vae']) && Admin::loginWithCookie()) {
    redirect("index.php");

} elseif ($_POST["action"] == "authenticate" && $_POST["token"] == $_SESSION["form_token"][1]) {
    $adminID = Admin::authenticate($_POST['email_a'], $_POST['password_a']);
    if ($adminID) {
        $_SESSION["vadmin"] = new Admin($adminID);
        $_SESSION["vadmin"]->login($_POST['remember']);
        redirect("index.php");
    } else {
        $core->assign('error', true);
    }
}

$core->display('login.tpl');



