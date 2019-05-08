<?php 
    $title = "Page d'administration";
    $script = "<script src='./assets/js/admin.js'></script>";
    $headContent = "<link href='./assets/css/adminpanel.css' rel='stylesheet' type='text/css'>";
?>
<?php ob_start(); ?>
<h1 class="center"> Panneau d'administration </h1>
    
    <div id="admin-options">
        <a href="edit.php"><button>Modifier un article</button></a>
        <a href="create.php"><button>Créer nouvel article</button></a>
        <a href="users.php"><button>Gestion des utilisateurs</button></a>
    </div>
    
    <div id='blog-infos'>
        
            <table>
                <th>Les 5 derniers articles</th>
                <th>Les 10 derniers commentaires</th>
            <?php 
                
                //display last 5 posts loop
                echo "<tr><td id='displayPosts'>";
                for($i = 0; $i < sizeof($posts);$i++){
                     
                    echo "<article><button class='displayContent' onclick='toggleContent(".$posts[$i]['id'].")'>Afficher l'article</button><a class='article-link' href='index.php?action=post&id=".$posts[$i]['id']."'>";
                    echo "<h3>". htmlspecialchars($posts[$i]['title']);
                    echo "</h3><p>";
                    echo "<div class='post".$i."'>";
                    echo    "<p class='article-content' id='article-".$posts[$i]['id']."'>".$posts[$i]['content']."</p>";
                    echo    "<p class='article-signature'>Rédigé par: ".$posts[$i]['author'].", le [".$posts[$i]['date']."] <em><a href='index.php?action=post&id=".$posts[$i]['id']."'>Commentaires</a></em></p><br>";
                    echo "</div></p></a></article>";
                }
                echo "</td>";
                echo "<td id='displayComments'>";
                //display last 10 comments loop
                for($x = 0; $x < sizeof($comments);$x++){
                    echo    "<div class='subSubComment node-depth-".$comments[$x]['depth']."' id='post".$comments[$x]['id']."'>";
                    echo    "<p>En réponse à <strong>".$comments[$x]['author']."</strong></p>";
                    echo    "<p class='comment-content'>".$comments[$x]['comment']."</p>";
                    echo    "<p class='comment-signature'>Rédigé par: <strong>".$comments[$x]['author']."</strong>, le [".$comments[$x]['comment_date']."] <button onclick='displayForm(".$comments[$x]['id'].",".$comments[$x]['depth'].")' class='comment-answer' data-comment-id=".$comments[$x]['id'].">Répondre</button></p><br>";
                    echo    "</div>";
                }
                echo "</td></tr>";
            
            ?>
            </table>
            
        </p>
    </div>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>