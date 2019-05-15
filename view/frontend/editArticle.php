<?php 
    $title = "Page d'administration";
    $script = "<script src='assets/js/ajax.js'></script><script src='assets/js/tinyEdit.js'></script>";
    $headContent = "<link href='./assets/css/adminpanel.css' rel='stylesheet' type='text/css'>";
?>
<?php ob_start(); ?>
<h1 class="center"> Panneau d'administration </h1>
    
    <div id="admin-options">
        <a href="edit.php"><button>Modifier un article</button></a>
        <a href="create.php"><button>Créer nouvel article</button></a>
        <a href="users.php"><button>Gestion des utilisateurs</button></a>
    </div>
    
    <div id='addPost'>
    <?php
        echo "<form id='new-article-form' action='index.php?action=editarticle&article=".$_GET['article']."&edit=true' method='POST' enctype='multipart/form-data'>";
    ?>
            <label for="article-title">Titre de l'article: </label>
            <input id="article-title" type="text" name='title' required>
            <label>Image(Max 10Mo): </label>
            <input type="file" name="image">
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
            <select name="author">
            <?php 
                for($i = 0; $i < sizeof($usersList); $i++){
                    echo "<option value='".$usersList[$i]['name']."'>".$usersList[$i]['name']."</option>";
                }
            ?>
            <option value="test">test</option>
            </select>
            <textarea id="editor" form="new-article-form" name='content' value="test"></textarea>
            <input type="submit" value="Modifier l'article">
        </form>
            
            
        </p>
    </div>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>