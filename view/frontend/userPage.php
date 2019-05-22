<?php 
    $title = "Gest. Utilisateurs";
    $script = "<script src='./assets/js/admin.js'></script>
            <script src='assets/js/ajax.js'></script>
            <script src='assets/js/userPage.js'></script>";
    $headContent = "<link href='./assets/css/adminpanel.css' rel='stylesheet' type='text/css'>
    <link href='./assets/css/userPage.css' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.8.2/css/all.css' integrity='sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay' crossorigin='anonymous'>";
?>
<?php ob_start(); ?>
<h1 class="center"> Gestion des utilisateurs </h1>


    
    <div id="admin-options">
        <a href="index.php?action=create"><button><i class="far fa-newspaper"></i> Créer nouvel article</button></a>
        <a href="index.php?action=editarticle"><button><i class="far fa-edit"></i> Modifier un article</button></a>        
        <a href="index.php?action=moderation"><button><i class="far fa-comment-dots"></i> Gestion des commentaires</button></a>
        <a href="index.php?action=users"><button><i class="fas fa-user-graduate"></i> Gestion des utilisateurs</button></a>
    </div>

    <div method='POST' enctype='text/plain' role='form' action="index.php?action=users&newuser=true" class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Nouvel Utilisateur</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <i class="fas fa-user prefix grey-text"></i>
                        <input name='name' type="text" id="orangeForm-name" class="form-control validate" required>
                        <label data-error="wrong" data-success="right" for="orangeForm-name">Nom utilisateur</label>
                    </div>

                    <div class="md-form mb-5">
                        <i class="fas fa-envelope prefix grey-text"></i>
                        <input name='email' type="email" id="orangeForm-email" class="form-control validate" required>
                        <label data-error="wrong" data-success="right" for="orangeForm-email">Email utilisateur</label>
                    </div>

                    <div class="md-form mb-4">
                        <i class="fas fa-lock prefix grey-text"></i>
                        <input name='raw_pwd' type="password" id="orangeForm-pass" class="form-control validate" required>
                        <label data-error="wrong" data-success="right" for="orangeForm-pass">MDP utilisateur</label>
                    </div>

                    <div class="md-form mb-4">
                        <i class="fas fa-user-graduate"></i>
                        <label data-error="wrong" data-success="right" for="orangeForm-rank">Rang utilisateur: </label>
                        <select id='orangeForm-rank' name='rank'>
                            <option value='1'>Modérateur</option>
                            <option value='2'>Autheur</option>
                            <option value='3'>Admin</option>
                        </select>                    
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button id='btn-submit' class="btn btn-deep-orange"><i class="fas fa-user-plus"></i> Ajouter l'utilisateur</button>
                </div>
            </div>
        </div>
    </div>

<div class="text-center">
  <button type="button" class="btn btn-info">
    <a id='user-add-btn' href="" class="btn btn-default btn-rounded" data-toggle="modal" data-target="#modalRegisterForm"><i class="fas fa-user-plus"></i> Ajouter un utilisateur</a>
  </button>
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
                        
                            <button onclick='rankUser(true,".$users[$i]['id'].")' data-toggle='tooltip' title='Promouvoir utilisateur'><i class='fas fa-plus'></i></button> 
                            <button onclick='rankUser(false, ".$users[$i]['id'].")' data-toggle='tooltip' title='Retrograder utilisateur'><i class='fas fa-minus'></i></button>
                            <button onclick='manageUser('del', ".$users[$i]['id'].")' data-toggle='tooltip' title='Supprimer utilisateur'><i class='fas fa-user-minus'></i></button>
                            <button onclick='mailUser()' data-toggle='tooltip' title='MailTo utilisateur'><i class='fas fa-envelope-open-text'></i></button>
                        </td>
                    </tr>";
                }
            
            ?>
        </tbody>
    </table>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>