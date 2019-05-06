<?php
session_start();
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

require(ROOT.'controller/Controller.php');


if (isset($_GET['action'])) {
    if ($_GET['action'] == 'listPosts') {
        $Controller->listPosts();
    }
    elseif ($_GET['action'] == 'post') {
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            $Controller->post();
        }
        else {
            echo 'Erreur : aucun identifiant de billet envoyé';
        }
    }
    elseif ($_GET['action'] == 'login' || $_GET['action'] == 'loginFail'){
        $Controller->getLoginPage();
    }
    elseif ($_GET['action'] == 'admin'){
        $Controller->getAdminPanel();
    }
}
else {
    $Controller->listPosts();
}