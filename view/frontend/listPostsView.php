<?php $title = "My news"; ?>

<?php ob_start(); ?>
<h1>Mes dernières nouvelles</h1>
    <div class="news">
        
            <?php 

                for($i = 0; $i < sizeof($posts);$i++){
                     
                    echo "<article>";
                    echo "<h3>". htmlspecialchars($posts[$i]['title']);
                    echo "</h3><p>";
                    echo "<div class='post".$i."'>";
                    echo    "<p class='article-content'>".$posts[$i]['content']."</p>";
                    echo    "<p class='article-signature'>Rédigé par: ".$posts[$i]['author'].", le [".$posts[$i]['date']."] <em><a href='post.php?id=".$posts[$i]['id']."'>Commentaires</a></em></p><br>";
                    echo "</div></p></article>";
                }
            
            ?>
            
        </p>
    </div>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>