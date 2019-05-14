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
                    if($posts[$i]['img_content']){
                        echo "<img class='smallImg' src='./resources/img/".$posts[$i]['img_content']."/".$posts[$i]['img_content'].".".$posts[$i]['img_ext']."'></img>";
                    }
                    echo "<div class='post".$i."'>";
                    echo    "<p class='article-content'>".$posts[$i]['content']."</p>";
                    echo    "<p class='article-signature'>Rédigé par: ".$posts[$i]['author'].", le [".$posts[$i]['date']."] <em><a href='index.php?action=post&id=".$posts[$i]['id']."'>[".$posts[$i]['nb_comments']."] Commentaires</a></em></p><br>";
                    echo "</div></a></article>";
                }

            ?>
            
        </p>
    </div>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>