<?php $title = "BLOG - Authentification"; ?>
<?php $headContent = "<link rel='stylesheet' type='text/css' href='../css/Login-Form-Dark.css'>"; ?>

<?php ob_start(); ?>
<h1>Merci de vous identifier pour accéder au panneau d'administration</h1>
<div style="text-align: center;">
    <div class="login-dark">
        <form action="#" method="post">
            <h2>Admin only</h2>
            <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
            <div class="form-group"><input class="form-control" type="username" name="username" placeholder="ID" required></div>
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Log In</button></div>
        </form>
    </div>

</div>
        
    <?php

        if(!empty($_POST['password']) && !empty($_POST['username'])){
            $id = checkInput($_POST['username']);
            $pwd = checkInput($_POST['password']);
            

            if($pwd == "banane" && $id == 'admin'){
                $_SESSION['passwordIsOkay'] = TRUE;
                echo "<script type='text/javascript'>document.location.replace('adminpanel.php');</script>";
                exit();
            }
            else{
                echo "<script>alert('Et non!');</script>";
            }

        }


        function checkInput($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
?>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__.'/../template.php'); ?>