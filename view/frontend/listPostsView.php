<?php 
    $title = "My news";
    $script = '';
    $headContent = '';
?>
<?php ob_start(); ?>
<h1>Mes dernières nouvelles</h1>
    <div class="news">
        
            <?php 
                if(isset($_GET['page'])){
                    $page = $_GET['page'];
                }
                else{
    
                    $page = 1;
                }
                for($i = 0+($sizePage*($page-1)); $i < $sizePage*$page; $i++){
                     
                    echo "<article class='smallArticle'><a class='article-link' href='index.php?action=post&id=".$posts[$i]->getId()."'>";
                    echo "<h3>". htmlspecialchars($posts[$i]->getTitle());
                    echo "</h3>";
                    
                    if($posts[$i]->getImg_key()){
                        echo "<img class='smallImg' src='./resources/img/".$posts[$i]->getImg_key()."/".$posts[$i]->getImg_key().".".$posts[$i]->getImg_ext()."'></img>";
                    }
                    echo "<div class='post".$i."'>";
                    echo    "<div class='article-content'>".$posts[$i]->getContent()."</div>";
                    echo    "<p class='article-signature'>Rédigé par: ".$posts[$i]->getAuthor().", le [".$posts[$i]->getDate()."] <em><a href='index.php?action=post&id=".$posts[$i]->getId()."'>[".$posts[$i]->getNb_comments()."] Commentaires</a></em></p><br>";
                    echo "</div></a></article>";
                }

            ?>
            
        </p>
    </div>
    <nav id="nav-Pagination">
        <ul class="pagination">
            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
            <?php
            for($i = 1; $i <= $nbPage ;$i++){
                echo "<li id='page-link-".$i."' class='page-item'><a class='page-link' href='index.php?page=".$i."'>".$i."</a></li>";
            }
            ?>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
    </nav> 
    <form id="newsletterForm" action="index.php?newsletter=true" method="POST">
        <label for="email">S'inscrire à la Newsletter: </label>
        <input type="mail" name="email" placeholder="Votre email">
        <input type="submit" value="S'inscrire">
    </form>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>