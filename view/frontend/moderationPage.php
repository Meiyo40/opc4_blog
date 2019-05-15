<?php 
    $title = "Gest. Commentaires";
    $script = "<script src='./assets/js/admin.js'></script><script src='assets/js/ajax.js'></script><script src='assets/js/comment.js'></script>";
    $headContent = "<link href='assets/css/adminpanel.css' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.8.2/css/all.css' integrity='sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay' crossorigin='anonymous'>";
?>
<?php ob_start(); ?>
<h1 class="center"> Gestion des commentaires </h1>


    
    <div id="admin-options">
        <a href="index.php?action=create"><button><i class="far fa-newspaper"></i> Créer nouvel article</button></a>
        <a href="index.php?action=editarticle"><button><i class="far fa-edit"></i> Modifier un article</button></a>        
        <a href="index.php?action=users"><button><i class="fas fa-user-graduate"></i> Gestion des utilisateurs</button></a>
    </div>
        
    <div id="btnCommentPanel">
        <a href="index.php?action=moderation"><button><i class="far fa-comment-dots"></i> Liste des commentaires</button></a>
        <a href="index.php?action=report"><button><i class="far fa-comment-dots"></i> Liste des signalements</button></a>
    </div>
    
    <div id='blog-comments' class="container">
        <?php
            if(isset($_GET['page'])){
                $page = $_GET['page'];
            }
            else{

                $page = 1;
            }
            for($i = 0+($sizePage*($page-1)); $i < $sizePage*$page; $i++){
                if(array_key_exists($i, $comments)){
                    echo "<div class='commentGroup'>";
                        echo "<div class='comment node-depth-".$comments[$i]['depth']."' id='post".$comments[$i]['id']."'>";
                        echo    "<p class='comment-content'>".$comments[$i]['comment']."</p>";
                        echo    "<p class='comment-signature'>Rédigé par: <strong>".$comments[$i]['author']."</strong>, le [".$comments[$i]['comment_date']."] 
                        <div class='comment-control-panel'>
                            <button onclick='displayForm(".$comments[$i]['id'].",".$comments[$i]['depth'].")' class='comment-answer btn btn-primary' data-comment-id=".$comments[$i]['id'].">Répondre</button>
                            <button onclick='requestDel(".$comments[$i]['id'].")' class='btn btn-danger'><i class='far fa-times-circle'></i> Supprimer</button>
                            <button class='reportCount' disabled><strong>Signalements: <i class='countNb'>".$comments[$i]['report']."</i></strong></button>
                            </div><br>";
                    echo "</div>";
                }
            }
        ?>
            
            
        <nav id="nav-Pagination">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <?php
                for($i = 1; $i <= $nbPage ;$i++){
                    echo "<li id='page-link-".$i."' class='page-item'><a class='page-link' href='index.php?action=moderation&page=".$i."'>".$i."</a></li>";
                }
                ?>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav> 
    </div>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>