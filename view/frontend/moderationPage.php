<?php 
    $title = "Gest. Commentaires";
    $script = "<script src='./assets/js/admin.js'></script><script src='assets/js/ajax.js'></script><script src='assets/js/comment.js'></script>";
    $headContent = "<link href='./assets/css/adminpanel.css' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.8.2/css/all.css' integrity='sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay' crossorigin='anonymous'>";
?>
<?php ob_start(); ?>
<h1 class="center"> Gestion des commentaires </h1>


    
    <div id="admin-options">
        <a href="index.php?action=create"><button><i class="far fa-newspaper"></i> Créer nouvel article</button></a>
        <a href="index.php?action=editarticle"><button><i class="far fa-edit"></i> Modifier un article</button></a>        
        <a href="index.php?action=moderation"><button><i class="far fa-comment-dots"></i> Gestion des commentaires</button></a>
        <a href="index.php?action=users"><button><i class="fas fa-user-graduate"></i> Gestion des utilisateurs</button></a>
    </div>
    
    <div id='blog-comments' class="container">
        <?php
            for($i = 0; $i < sizeof($comments); $i++){
                echo "<div class='commentGroup'>";
                    echo "<div class='comment node-depth-".$comments[$i]['depth']."' id='post".$comments[$i]['id']."'>";
                    echo    "<p class='comment-content'>".$comments[$i]['comment']."</p>";
                    echo    "<p class='comment-signature'>Rédigé par: <strong>".$comments[$i]['author']."</strong>, le [".$comments[$i]['comment_date']."] <button onclick='displayForm(".$comments[$i]['id'].",".$comments[$i]['depth'].")' class='comment-answer btn btn-primary' data-comment-id=".$comments[$i]['id'].">Répondre</button><button)' class='btn-report btn btn-warning'><i class='far fa-bell'></i> Signaler</button></p><br>";
                echo "</div>";
            }
        ?>
            
            
        <nav id="nav-Pagination">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <?php
                for($i = 1; $i <= $nbPage ;$i++){
                    echo "<li class='page-item'><a class='page-link' href='#'>".$i."</a></li>";
                }
                ?>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav> 
    </div>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>