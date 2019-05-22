<?php 
    $title = "My news";
    $script = "<script src='assets/js/ajax.js'></script><script src='assets/js/comment.js'></script>";
    $headContent = '<link rel="stylesheet" href="assets/css/postview.css"><link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">';
?>
<?php ob_start(); ?>
<?php echo "<h1>".htmlspecialchars($post['title'])."</h1>" ?>

    <div class="news">
        <?php
            echo "<article>
                <div id='post' class='post".$post['id']."'>
                    <img class='smallImg' src='./resources/img/".$post['img_key'].".".$post['img_ext']."'></img>
                    <div class='article-content'>
                        ".$post['content']."
                    </div>
                    <p class='article-signature'>Publié par: ".$post['author'].", le [".$post['date']."] 
                        <button id='btnPost' class='btn btn-primary' value='".$post['id']."'>Commenter</button>
                    </p>
                    <br>
                </div>
            </article>";
        ?>
            
    </div>
    <?php 
        echo "<h6>Commentaires de l'article:</h6><br>";
        if($comments){
            for($i = 0; $i < sizeof($comments);$i++){
                
                if(!$comments[$i]->getComment_parent()){
                echo "<div class='commentGroup'>
                    <div class='comment node-depth-".$comments[$i]->getDepth()."' id='post".$comments[$i]->getId()."'>
                        <p class='comment-content'>
                            ".$comments[$i]->getComment()."
                        </p>
                        <p class='comment-signature'>Rédigé par: <strong>".$comments[$i]->getAuthor()."</strong>, le [".$comments[$i]->getComment_date()."] 
                            <button onclick='displayForm(".$comments[$i]->getId().",".$comments[$i]->getDepth().")' class='comment-answer btn btn-primary' data-comment-id=".$comments[$i]->getId().">Répondre</button>
                            <button onclick='reportComment(".$comments[$i]->getId().",".$post['id'].")' class='btn-report btn btn-warning'><i class='far fa-bell'></i> Signaler</button>
                        </p>
                        <br>
                    </div>
                    <ul>";
                }
                
                for($j = 0; $j < sizeof($comments);$j++){
                    if(($comments[$j]->getComment_parent() == $comments[$i]->getId()) && ($comments[$j]->getDepth() == 1)){
                        echo "<li class='subCommentContainer'>
                        <div class='subComment node-depth-".$comments[$j]->getDepth()."' id='post".$comments[$j]->getId()."'
                            <p>
                                En réponse à <strong>".$comments[$i]->getAuthor()."</strong>
                            </p>
                            <p class='comment-content'>
                                ".$comments[$j]->getComment()."
                            </p>
                            <p class='comment-signature'>
                                Rédigé par: <strong>".$comments[$j]->getAuthor()."</strong>, le [".$comments[$j]->getComment_date()."] 
                                <button onclick='displayForm(".$comments[$j]->getId().",".$comments[$j]->getDepth().")' class='comment-answer btn btn-primary' data-comment-id=".$comments[$j]->getId().">Répondre</button>
                                <button onclick='reportComment(".$comments[$j]->getId().",".$post['id'].")'  class='btn-report btn btn-warning'><i class='far fa-bell'></i> Signaler</button>
                            </p>
                            <br>
                        </div>
                        <ul>"; 
                        for($k = 0; $k < sizeof($comments);$k++){
                            if(($comments[$k]->getComment_parent() == $comments[$j]->getId()) && ($comments[$k]->getDepth() == 2)){
                                echo "<li>
                                <div class='subSubComment node-depth-".$comments[$k]->getDepth()."' id='post".$comments[$k]->getId()."'>
                                    <p>
                                        En réponse à <strong>".$comments[$j]->getAuthor()."</strong>
                                    </p>
                                    <p class='comment-content'>
                                        ".$comments[$k]->getComment()."
                                    </p>
                                    <p class='comment-signature'>
                                        Rédigé par: <strong>".$comments[$k]->getAuthor()."</strong>, le [".$comments[$k]->getComment_date()."] 
                                        <button onclick='displayForm(".$comments[$k]->getId().",".$comments[$k]->getDepth().")' class='comment-answer btn btn-primary' data-comment-id=".$comments[$k]->getId().">Répondre</button>
                                        <button onclick='reportComment(".$comments[$k]->getId().",".$post['id'].")'  class='btn-report btn btn-warning'><i class='far fa-bell'></i> Signaler</button>
                                    </p>
                                    <br>
                                </div>
                                </li>";
                                for($x = 0; $x < sizeof($comments);$x++){
                                    if(($comments[$x]->getComment_parent() == $comments[$k]->getId()) && ($comments[$x]->getDepth() == 2)){
                                        echo "<li>
                                        <div class='subSubComment node-depth-".$comments[$x]->getDepth()."' id='post".$comments[$x]->getId()."'>
                                            <p>
                                                En réponse à <strong>".$comments[$k]->getAuthor()."</strong>
                                            </p>
                                            <p class='comment-content'>
                                                ".$comments[$x]->getComment()."
                                            </p>
                                            <p class='comment-signature'>
                                                Rédigé par: <strong>".$comments[$x]->getAuthor()."</strong>, le [".$comments[$x]->getComment_date()."] 
                                                <button onclick='displayForm(".$comments[$x]->getId().",".$comments[$x]->getDepth().")' class='comment-answer btn btn-primary' data-comment-id=".$comments[$x]->getId().">Répondre</button>
                                                <button onclick='reportComment(".$comments[$x]->getId().",".$post['id'].")'  class='btn-report btn btn-warning'><i class='far fa-bell'></i> Signaler</button>
                                            </p>
                                            <br>
                                        </div>
                                        </li>";
                                    }
                                }
                            }
                            
                        }
                        echo "</ul>"; 
                        echo "</li>";
                    }
                }
                if(!$comments[$i]->getComment_parent()){
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