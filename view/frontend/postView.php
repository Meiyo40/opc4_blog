<?php $title = "My news"; ?>

<?php ob_start(); ?>
<?php echo "<h1>".htmlspecialchars($post['title'])."</h1>" ?>

    <div class="news">
        <?php
            echo "<article>";
            echo "<p>";
            echo "<div class='post".$post['id']."'>";
            echo    "<p class='article-content'>".$post['content']."</p>";
            echo    "<p class='article-signature'>Publié par: ".$post['author'].", le [".$post['date']."] <em><a href='post.php?id=".$post['id']."'>Commenter</a></em></p><br>";
            echo "</div></p></article>";
        ?>
            
    </div>
    <?php 
        echo "<h6>Commentaires de l'article:</h6><br>";
        if($comments){
            for($i = 0; $i < sizeof($comments);$i++){
                echo "<div class='comment post".$i."'>";
                echo    "<p class='article-content'>".$comments[$i]['comment']."</p>";
                echo    "<p class='article-signature'>Rédigé par: ".$comments[$i]['author'].", le [".$comments[$i]['comment_date']."] <em><a href='post.php?id=".$comments[$i]['id']."'>Répondre</a></em></p><br>";
                echo "</div>";
            }
        }
            
    ?>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>