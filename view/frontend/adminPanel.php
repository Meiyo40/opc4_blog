<?php 
    $title = "Page d'administration";
    $script = "<script src='./assets/js/admin.js'></script><script src='assets/js/ajax.js'></script><script src='assets/js/comment.js'></script>";
    $headContent = "<link href='./assets/css/adminpanel.css' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.8.2/css/all.css' integrity='sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay' crossorigin='anonymous'>";
?>
<?php ob_start(); ?>
<h1 class="center"> Panneau d'administration </h1>
    
    <div id="admin-options">
        <a href="index.php?action=create"><button><i class="far fa-newspaper"></i> Créer nouvel article</button></a>      
        <a href='index.php?action=listArticles'><button class='edit-btn'><i class='far fa-edit'></i> Editer article</button></a>
        <a href="index.php?action=moderation"><button><i class="far fa-comment-dots"></i> Gestion des commentaires</button></a>
        <a href="index.php?action=users"><button><i class="fas fa-user-graduate"></i> Gestion des utilisateurs</button></a>
    </div>
    
    <div id='blog-infos'>
        
            <table>
                <th>Les 5 derniers articles</th>
                <th>Les 10 derniers commentaires</th>
            
                <tr><td id='displayPosts'>
                <?php 
                for($i = 0; $i < sizeof($posts);$i++){
                    echo "<div class='btn-control'> 
                            <button class='displayContent' onclick='toggleContent(".$posts[$i]->getId().")'><i class='far fa-eye-slash'></i> Afficher l'article</button> 
                            <a href='index.php?action=editarticle&article=".$posts[$i]->getId()."'><button class='edit-btn'><i class='far fa-edit'></i> Editer article</button></a> 
                            <a href='index.php?action=deletearticle&article=".$posts[$i]->getId()."'><button class='deletePost-btn'><i class='far fa-trash-alt'></i> Supprimer article</button></a>
                        </div>
                    <div class='article'>";
                    if($posts[$i]->getImg_key()){
                        echo "<div><img class='smallImg' src='./resources/img/".$posts[$i]->getImg_key().".".$posts[$i]->getImg_ext()."'></img></div>";
                    }

                    echo "<article>
                        <a class='article-link' href='index.php?action=post&id=".$posts[$i]->getId()."'>
                        <h3>".$posts[$i]->getTitle()."</h3>
                        
                        <div class='post".$i."'>
                            <div class='article-content' id='article-".$posts[$i]->getId()."'>
                                ".$posts[$i]->getContent()."
                            </div>
                            <p class='article-signature'>
                                Rédigé par: ".$posts[$i]->getAuthor().", le [".$posts[$i]->getDate()."] <em><a href='index.php?action=post&id=".$posts[$i]->getId()."'>Commentaires</a></em>
                            </p>
                            <br>
                        </div></a></article>
                        <div class='divider'></div>
                    </div>";
                }
                echo "</td>
                <td id='displayComments'>";
                for($x = 0; $x < sizeof($comments);$x++){
                    echo"<div class='subSubComment node-depth-".$comments[$x]->getDepth()."' id='post".$comments[$x]->getId()."'>
                            <p>En réponse à <strong>".$comments[$x]->getAuthor()."</strong></p>
                            <p class='comment-content'>".$comments[$x]->getComment()."</p>
                            <p class='comment-signature'>Rédigé par: <strong>".$comments[$x]->getAuthor()."</strong>, le [".$comments[$x]->getComment_date()."] <button onclick='displayForm(".$comments[$x]->getId().",".$comments[$x]->getDepth().")' class='comment-answer' data-comment-id=".$comments[$x]->getId().">Répondre</button></p><br>
                        </div>";
                }
                ?>
                </td></tr>
            
            
            </table>
            
        </p>
    </div>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>