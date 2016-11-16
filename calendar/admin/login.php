<?php
(session_id() === '') ? session_start() : '';

if (isSet($_SESSION['admin'])) {
    header('location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'settings.php';

    $inputUser = trim($_POST['user']);
    $inputPass = trim($_POST['psw']);
    
    if ($inputUser == $admin_user && $inputPass == $admin_pass) {
        $_SESSION['admin'] = $admin_user;

        header('location: index.php');
    }
    else {
        echo "<script>alert(\"Nom d'utilisateur ou mot de passe incorrect. Réessayer\")</script>";
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>eCalendar - S'identifier</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <!-- Bootstrap style -->
            <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
        <!-- Main style -->
            <link rel="StyleSheet" href="login/css/main.css" type="text/css" />
    </head>
    <body>
        <div class="container">
            <div class="box">
                <header>S'identifier</header>
                <section>
                    <form action="" method="post">
                        <input type="text" name="user" placeholder="Nom d'utilisateur" /><br />
                        <input type="password" name="psw" placeholder="Mot de passe" /><br />
                        <input type="submit" value="Entrer" />
                    </form>
                </section>
            </div>
        </div>
    </body>
</html>