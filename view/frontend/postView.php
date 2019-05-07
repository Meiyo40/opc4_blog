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
            echo    "<p class='article-signature'>Publié par: ".$post['author'].", le [".$post['date']."] <button id='btnPost' value='".$post['id']."'>Commenter</button></p><br>";
            echo "</div></p></article>";
        ?>
            
    </div>
    <?php 
        echo "<h6>Commentaires de l'article:</h6><br>";
        if($comments){
            for($i = 0; $i < sizeof($comments);$i++){
                echo "<div class='comment post".$i."'>";
                echo    "<p class='article-content'>".$comments[$i]['comment']."</p>";
                echo    "<p class='article-signature'>Rédigé par: ".$comments[$i]['author'].", le [".$comments[$i]['comment_date']."] <button onclick='displayForm(".$comments[$i]['id'].")' class='comment-answer' data-comment-id=".$comments[$i]['id'].">Répondre</button></p><br>";
                echo "</div>";
            }
        }
    ?>
    <?php 
    
     echo "<form id='comment-form' class='comment-form' method='POST' value=".$post['id'].">";
    ?>
        <label>Nom/Pseudo: </label>
        <input type='text' name='name' placerholder='Nom/Pseudo' required>
        <textarea form='comment-form' name="commentContent"></textarea>
        <input type='submit' value='Envoyer'>
    </form>
        
            
    
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>