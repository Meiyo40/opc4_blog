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
        <a href="index.php?action=modlist"><button><i class="far fa-comment-dots"></i> Liste des modérés</button></a>
    </div>
    
    <div id='blog-comments' class="container">
        <?php
            if(isset($_GET['page'])){
                $page = $_GET['page'];
            }
            else{

                $page = 1;
            }
            if(is_array($comments)){
                for($i = 0+($sizePage*($page-1)); $i < $sizePage*$page; $i++){
                if(array_key_exists($i, $comments)){
                    echo "<div class='commentGroup'>
                        <div class='comment node-depth-".$comments[$i]->getDepth()."' id='post".$comments[$i]->getId()."'>
                        
                            <p class='comment-content'>";
                            if($comments[$i]->getModeration()){
                                echo '<strong style="color:red">Commentaire Modéré</strong><br>';
                            }
                            echo "".$comments[$i]->getComment()."  
                            </p>

                            <p class='comment-signature'>
                                Rédigé par: <strong>".$comments[$i]->getAuthor()."</strong>, le [".$comments[$i]->getComment_date()."] 
                            </p>

                            <div class='comment-control-panel'>
                                <button onclick='displayForm(".$comments[$i]->getId().",".$comments[$i]->getDepth().")' class='comment-answer btn btn-primary' data-comment-id=".$comments[$i]->getId().">Répondre</button>
                                <button onclick='applyModeration(".$comments[$i]->getId().", true)' class='btn btn-warning'><i class='fas fa-user-secret'></i> Modérer</button>
                                <button onclick='applyModeration(".$comments[$i]->getId().", false)' class='btn btn-success'><i class='far fa-thumbs-up'></i> Rétablir</button>
                                <button onclick='requestDel(".$comments[$i]->getId().")' class='btn btn-danger'><i class='far fa-times-circle'></i> Supprimer</button>                                
                                <button class='reportCount' disabled><strong>Signalements: <i class='countNb'>".$comments[$i]->getReport()."</i></strong></button>
                            </div>
                            <br>
                    </div>";
                }
            }
            }
        ?>
            
            
        <nav id="nav-Pagination">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <?php
                if(!isset($nbPage)){
                    $nbPage = 1;
                }
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