<?php 
    $title = "My news";
    $script = "<script src='assets/js/comment.js'></script>";
    $headContent = '';
?>
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
                echo    "<p class='article-signature'>Rédigé par: ".$comments[$i]['author'].", le [".$comments[$i]['comment_date']."] <em><button class='comment-answer' data-comment-id=".$comments[$i]['id'].">Répondre</button></em></p><br>";
                echo "</div>";
            }
        }
    ?>
    <form id='comment-form' class='comment-form' method='POST'>
        <label>Nom/Pseudo: </label>
        <input type='text' name='Nom' placerholder='Nom/Pseudo' required>
        <textarea form='comment-form' placeholder='Ecrivez votre commentaire ici...' required></textarea>
        <input type='submit' value='Envoyer'>
    </form>
        
            
    
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>