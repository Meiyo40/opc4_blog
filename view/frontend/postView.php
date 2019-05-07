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
                
                if(!$comments[$i]['comment_parent']){
                echo "<div class='commentGroup'>";
                    echo "<div class='comment node-depth-".$comments[$i]['depth']."' id='post".$comments[$i]['id']."'>";
                    echo    "<p class='comment-content'>".$comments[$i]['comment']."</p>";
                    echo    "<p class='comment-signature'>Rédigé par: <strong>".$comments[$i]['author']."</strong>, le [".$comments[$i]['comment_date']."] <button onclick='displayForm(".$comments[$i]['id'].",".$comments[$i]['depth'].")' class='comment-answer' data-comment-id=".$comments[$i]['id'].">Répondre</button></p><br>";
                    echo "</div>";
                echo "<ul>";
                }
                
                for($j = 0; $j < sizeof($comments);$j++){
                    if($comments[$j]['comment_parent'] == $comments[$i]['id'] && $comments[$j]['depth'] == 1){
                        echo "<li class='subCommentContainer'>";
                        echo    "<div class='subComment node-depth-".$comments[$j]['depth']."' id='post".$comments[$j]['id']."'";
                        echo    "<p>En réponse à <strong>".$comments[$i]['author']."</strong></p>";
                        echo    "<p class='comment-content'>".$comments[$j]['comment']."</p>";
                        echo    "<p class='comment-signature'>Rédigé par: <strong>".$comments[$j]['author']."</strong>, le [".$comments[$j]['comment_date']."] <button onclick='displayForm(".$comments[$j]['id'].",".$comments[$j]['depth'].")' class='comment-answer' data-comment-id=".$comments[$j]['id'].">Répondre</button></p><br>";
                        echo    "</div>";
                        echo "<ul>"; 
                        for($k = 0; $k < sizeof($comments);$k++){
                            if(($comments[$k]['comment_parent'] == $comments[$j]['id'] || $comments[$k]['comment_parent'] == $comments[$k]['id']) && $comments[$k]['depth'] == 2){
                                echo "<li>";
                                echo    "<div class='subSubComment node-depth-".$comments[$k]['depth']."' id='post".$comments[$k]['id']."'>";
                                echo    "<p>En réponse à <strong>".$comments[$j]['author']."</strong></p>";
                                echo    "<p class='comment-content'>".$comments[$k]['comment']."</p>";
                                echo    "<p class='comment-signature'>Rédigé par: <strong>".$comments[$k]['author']."</strong>, le [".$comments[$k]['comment_date']."] <button onclick='displayForm(".$comments[$k]['id'].",".$comments[$k]['depth'].")' class='comment-answer' data-comment-id=".$comments[$k]['id'].">Répondre</button></p><br>";
                                echo    "</div>";
                                echo "</li>";
                            }
                            for($x = 0; $x < sizeof($comments);$x++){
                                if($comments[$x]['comment_parent'] == $comments[$k]['id'] && $comments[$k]['depth'] == 2){
                                    echo "<li>";
                                    echo    "<div class='subSubComment node-depth-".$comments[$x]['depth']."' id='post".$comments[$x]['id']."'>";
                                    echo    "<p>En réponse à <strong>".$comments[$k]['author']."</strong></p>";
                                    echo    "<p class='comment-content'>".$comments[$x]['comment']."</p>";
                                    echo    "<p class='comment-signature'>Rédigé par: <strong>".$comments[$x]['author']."</strong>, le [".$comments[$x]['comment_date']."] <button onclick='displayForm(".$comments[$x]['id'].",".$comments[$x]['depth'].")' class='comment-answer' data-comment-id=".$comments[$x]['id'].">Répondre</button></p><br>";
                                    echo    "</div>";
                                    echo "</li>";
                                }
                            }
                        }
                        echo "</ul>"; 
                        echo "</li>";
                    }
                }
                if(!$comments[$i]['comment_parent']){
                    echo "</ul></div>";
                }
                
            }
        }
    ?>
    <?php 
    
     echo "<form id='comment-form' class='comment-form' method='POST' value=".$post['id'].">";
    ?>
        <label for="name">Nom/Pseudo: </label>
        <input type='text' name='name' required>
        <textarea form='comment-form' name="commentContent"></textarea>
        <input type='hidden' id="node-depth-form" name='depth' value="">
        <input type='submit' value='Envoyer'>
    </form>
        
            
    
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>