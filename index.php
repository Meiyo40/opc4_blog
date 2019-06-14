<?php
session_start();
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';
require_once __DIR__.'/controller/controller.php';
require_once __DIR__.'/controller/AdminController.php';
require_once __DIR__.'/controller/Security.php';

use manager\PostManager;

$loader = new Twig_Loader_Filesystem(__DIR__.'/view');
$twig = new Twig_Environment($loader, [
    'cache'=> false,
]);

$config = HTMLPurifier_Config::createDefault(); 
$config->set('Core.Encoding', 'ISO-8859-1'); 
$config->set('Cache.DefinitionImpl', null); // TODO: remove this later! 
$config->set('HTML.Allowed', 'a[href],i,b,img[src],font[style|size],ol,ul,li,br'); 
$purifier = new HTMLPurifier($config);


if (isset($_GET['action'])) {
    if(isset($_GET['comment'])){
        
        if($_GET['comment'] == 'primary'){
            $postId = $_GET['id'];
            $commentAuthor = $_POST['name'];
            $content = $_POST['commentContent'];
            $clean_comment = $purifier->purify($content);
            $Controller->addComment($postId, $commentAuthor, $clean_comment);
        }
        else{
            $postId = $_GET['id'];
            $commentId = $_GET['comment'];
            $commentAuthor = $_POST['name'];
            $content = $_POST['commentContent'];
            $depth = $_POST['depth'];
            $clean_comment = $purifier->purify($content);
            $Controller->addCommentToComment($postId, $commentAuthor, $clean_comment, $commentId, $depth);
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
            $AdminController->getAdminPanel($twig);
            break;

        case 'adminanswer':
            $AdminController->addAdminComment($_GET['answered'], $_POST['message'], $_POST['author']);
            break;

        case 'applymoderation':
            $AdminController->setModeration($_GET['Comment'], $_GET['mod']);            
            break;

        case 'create':
            $AdminController->getCreatePage($twig);
            break;

        case 'deletearticle':
            $AdminController->deletePost($_GET['article']);
            break;

        case 'deletecomment':
            $AdminController->deleteComment($_GET['delComment']);
            break;

        case 'deleteuser':
            $AdminController->deleteUser($_GET['user']);
            break;

        case 'displayarticle':
            $AdminController->displayArticle($_GET['article']);
            break;

        case 'editarticle':
            $AdminController->getPostEditPage($twig);
            break;

        case 'getArticleContent':
            $PostManager = new PostManager();
            $Post = $PostManager->getPost($_GET['article']);
            echo json_encode($Post);
            break;

        case 'hidearticle':
            $AdminController->hideArticle($_GET['article']);
            break;

        case 'listArticles':
            if(isset($_GET['page'])){
                $page = $_GET['page'];
                $AdminController->getListsPostsToEdit($twig, $page);
            }else{
                $page = 1;
                $AdminController->getListsPostsToEdit($twig, $page);
            }
            break;

        case 'listPosts':
            if(isset($_GET['page'])){
                $page = $_GET['page'];
                $Controller->listPosts($twig, $page);
            }
            else{
                $page = 1;
                $Controller->listPosts($twig, $page);
            }
            break;        

        case 'login':
        case 'loginFail':
            session_unset();
            session_destroy();
            $Security->getLoginPage($twig);
            break;

        case 'logout':
            $Security->logout();
            break;

        case 'manageuser':
            $AdminController->manageUser($_GET['rankaction'] ,$_GET['user']);
            break;

        case 'moderation':
            $AdminController->getModerationPage($twig);
            break;

        case 'modlist':
            $AdminController->getModeratedComPage($twig);
            break;

        case 'policy':
            $Controller->getPolicyPage($twig);
            break;

        case 'post':
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $Controller->post($twig);
                break;
            }
            else {
                echo 'Erreur : aucun identifiant de billet envoyé';
                break;
            }
            
        case 'report':
            $AdminController->getReportedComPage($twig);
            break;

        case 'users':
            if(isset($_GET['newuser'])){
                $AdminController->newUser();
            }
            else{
                $AdminController->getUsersPage($twig);
            }
            break;
    }

}
else {
    if(isset($_GET['page'])){
        $page = $_GET['page'];
        $Controller->listPosts($twig, $page);
    }
    else{
        $page = 1;
        $Controller->listPosts($twig, $page);
    }
}