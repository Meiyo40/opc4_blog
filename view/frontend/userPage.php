<?php 
    $title = "Gest. Utilisateurs";
    $script = "<script src='./assets/js/admin.js'></script>
            <script src='assets/js/ajax.js'></script>
            <script src='assets/js/comment.js'></script>
            <script src='assets/js/userPage.js'></script>";
    $headContent = "<link href='./assets/css/adminpanel.css' rel='stylesheet' type='text/css'>
    <link href='./assets/css/usertable.css' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.8.2/css/all.css' integrity='sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay' crossorigin='anonymous'>";
?>
<?php ob_start(); ?>
<h1 class="center"> Gestion des commentaires </h1>


    
    <div id="admin-options">
        <a href="index.php?action=create"><button><i class="far fa-newspaper"></i> Créer nouvel article</button></a>
        <a href="index.php?action=editarticle"><button><i class="far fa-edit"></i> Modifier un article</button></a>        
        <a href="index.php?action=moderation"><button><i class="far fa-comment-dots"></i> Gestion des commentaires</button></a>
        <a href="index.php?action=users"><button><i class="fas fa-user-graduate"></i> Gestion des utilisateurs</button></a>
        <a href="index.php?action=create"><button><i class="fas fa-user-plus"></i> Ajouter un utilisateur</button></a>
    </div>

    <table id='userstable'>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Rang</th>
                <th>Dernière connexion</th>
                <th>Commentaires</th>                
                <th>Articles</th>
                <th>Mail</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                for($i = 0; $i < sizeof($users); $i++){
                    echo "<tr>
                        <td>".$users[$i]['name']."</td>
                        <td>".$users[$i]['rank']."</td>
                        <td>".$users[$i]['last_connexion']."</td>
                        <td>".$users[$i]['comments']."</td>
                        <td>".$users[$i]['articles']."</td>
                        <td>".$users[$i]['mail']."</td>
                        <td>
                            <button data-toggle='tooltip' title='Promouvoir utilisateur'><i class='fas fa-plus'></i></button> 
                            <button data-toggle='tooltip' title='Retrograder utilisateur'><i class='fas fa-minus'></i></button>
                            <button data-toggle='tooltip' title='Supprimer utilisateur'><i class='fas fa-user-minus'></i></button>
                            <button data-toggle='tooltip' title='MailTo utilisateur'><i class='fas fa-envelope-open-text'></i></button>
                        </td>
                    </tr>";
                }
            
            ?>
        </tbody>
    </table>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>