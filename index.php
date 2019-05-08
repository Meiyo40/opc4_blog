<?php
session_start();
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

require(ROOT.'controller/Controller.php');


if (isset($_GET['action'])) {
    if(isset($_GET['comment'])){
        
        if($_GET['comment'] == 'primary'){
            $postId = $_GET['id'];
            $commentAuthor = $_POST['name'];
            $content = $_POST['commentContent'];
            $Controller->addComment($postId, $commentAuthor, $content);
        }
        else{
            $postId = $_GET['id'];
            $commentId = $_GET['comment'];
            $commentAuthor = $_POST['name'];
            $content = $_POST['commentContent'];
            $depth = $_POST['depth'];
            $Controller->addCommentToComment($postId, $commentAuthor, $content, $commentId, $depth);
        }
    }
    if(isset($_GET['addPost'])){
        if($_GET['addPost'] == 'true'){
            $title = $_POST['title'];
            $content = $_POST['content'];
            $author = $_POST['author'];
            $Controller->addPost($title, $content, $author);
        }
    }
    switch($_GET['action']){
        case 'listPosts':
            $Controller->listPosts();
            break;

        case 'post':
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $Controller->post();
                break;
            }
            else {
                echo 'Erreur : aucun identifiant de billet envoyé';
                break;
            }

        case 'login':
        case 'loginFail':
            $Controller->getLoginPage();
            break;

        case 'admin':
            $Controller->getAdminPanel();
            break;

        case 'create':
            $Controller->getCreatePage();
            break;
    }
    /*
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
    elseif ()
    */
}
else {
    $Controller->listPosts();
}