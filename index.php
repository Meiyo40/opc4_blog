<?php
session_start();
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

require_once __DIR__.'/vendor/autoload.php';
require(ROOT.'controller/Controller.php');
require(ROOT.'controller/LoginController.php');


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
            $img_name = $_FILES['image']['name'];
            $Controller->addPost($title, $content, $author, $img_name);
        }
    }
    if(isset($_GET['report'])){
        $commentId = $_GET['report'];
        $Controller->reportComment($commentId);
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

        case 'moderation':
            $Controller->getModerationPage();
            break;
    }

}
else {
    $Controller->listPosts();
}