<?php
session_start();
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

require_once __DIR__.'/vendor/autoload.php';
require(ROOT.'controller/Controller.php');
require(ROOT.'controller/AdminController.php');

use manager\PostManager;
use entity\Comment;
use services\DAO;


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
    if(isset($_GET['edit'])){
        if($_GET['edit'] == 'true'){
            $id = $_GET['article'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $author = $_POST['author'];
            $img_name = $_FILES['image']['name'] ? $_FILES['image']['name'] : null;
            $Controller->updatePost($id, $title, $content, $author, $img_name);
        }
        
    }
    switch($_GET['action']){

        case 'admin':
            $AdminController->getAdminPanel();
            break;

        case 'applymoderation':
            $AdminController->setModeration($_GET['Comment'], $_GET['mod']);
            break;

        case 'create':
            $AdminController->getCreatePage();
            break;

        case 'deletecomment':
            $AdminController->deleteComment($_GET['delComment']);
            break;

        case 'editarticle':
            $AdminController->getPostEditPage();
            break;

        case 'getArticleContent':
            $PostManager = new PostManager();
            $Post = $PostManager->getPost($_GET['article']);
            echo json_encode($Post);
            break;

        case 'listArticles':
            $AdminController->getListsPostsToEdit();
            break;

        case 'listPosts':
            $Controller->listPosts();
            break;        

        case 'login':
        case 'loginFail':
            $AdminController->getLoginPage();
            break;

        case 'moderation':
            $AdminController->getModerationPage('list');
            break;

        case 'modlist':
            $AdminController->getModerationPage('modlist');
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
            
        case 'report':
            $AdminController->getModerationPage('priority');
            break;

        case 'users':
            $AdminController->getUsersPage();
            break;
    }

}
else {
    $Controller->listPosts();
}