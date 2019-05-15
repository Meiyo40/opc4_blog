<?php 
    $title = "My news";
    $script = '';
    $headContent = '';
?>
<?php ob_start(); ?>
<h1>Mes dernières nouvelles</h1>
    <div class="news">
        
            <?php 

                for($i = 0; $i < sizeof($posts);$i++){
                     
                    echo "<article class='smallArticle'><a class='article-link' href='index.php?action=post&id=".$posts[$i]['id']."'>";
                    echo "<h3>". htmlspecialchars($posts[$i]['title']);
                    echo "</h3>";
                    
                    if($posts[$i]['img_key']){
                        echo "<img class='smallImg' src='./resources/img/".$posts[$i]['img_key']."/".$posts[$i]['img_key'].".".$posts[$i]['img_ext']."'></img>";
                    }
                    echo "<div class='post".$i."'>";
                    echo    "<div class='article-content'>".$posts[$i]['content']."</div>";
                    echo    "<p class='article-signature'>Rédigé par: ".$posts[$i]['author'].", le [".$posts[$i]['date']."] <em><a href='index.php?action=post&id=".$posts[$i]['id']."'>[".$posts[$i]['nb_comments']."] Commentaires</a></em></p><br>";
                    echo "</div></a></article>";
                }

            ?>
            
        </p>
    </div>
    <form id="newsletterForm" action="index.php?newsletter=true" method="POST">
        <label for="email">S'inscrire à la Newsletter: </label>
        <input type="mail" name="email" placeholder="Votre email">
        <input type="submit" value="S'inscrire">
    </form>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>