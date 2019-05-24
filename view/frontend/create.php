<?php 
    $title = "Page d'administration";
    $script = "";
    $headContent = "<link href='./assets/css/adminpanel.css' rel='stylesheet' type='text/css'>";
?>
<?php ob_start(); ?>
<h1 class="center"> Panneau d'administration </h1>
    
<div id="admin-options">
        <a href="index.php?action=create"><button><i class="far fa-newspaper"></i> Créer nouvel article</button></a>
        <a href="index.php?action=editarticle"><button><i class="far fa-edit"></i> Modifier un article</button></a>        
        <a href="index.php?action=moderation"><button><i class="far fa-comment-dots"></i> Gestion des commentaires</button></a>
        <a href="index.php?action=users"><button><i class="fas fa-user-graduate"></i> Gestion des utilisateurs</button></a>
        <a href="index.php?action=logout"><button><i class="fas fa-sign-out-alt"></i> Se déconnecter (<?php echo $_SESSION['login']; ?>)</button></a>
    </div>
    
    <div id='addPost'>

        <form id="new-article-form" action="index.php?action=create&addPost=true" method="POST" enctype="multipart/form-data">
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
            <textarea form="new-article-form" name='content'></textarea>
            <input type="submit" value="Poster l'article">
        </form>
            
            
        </p>
    </div>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>