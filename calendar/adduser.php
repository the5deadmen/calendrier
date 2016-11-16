<?php
if(!empty($_POST['pseudo']))
{
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=ecalendar;charset=utf8', 'root', 'root');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
	
// Je mets aussi certaines sécurités ici…
$passe = mysql_real_escape_string(htmlspecialchars($_POST['passe']));
$passe2 = mysql_real_escape_string(htmlspecialchars($_POST['passe2']));
if($passe == $passe2)
{
$pseudo = mysql_real_escape_string(htmlspecialchars($_POST['pseudo']));
$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
// Je vais crypter le mot de passe.
$passe = sha1($passe);

mysql_query("INSERT INTO validation VALUES('', '$pseudo', '$passe', '$email')");
}
 
else
{
echo 'Les deux mots de passe que vous avez rentrés ne correspondent pas…';
}
}
?><!DOCTYPE html>
<html lang="fr">
	 <head>
        <title>eCalendar - S'identifier</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
         <!-- Bootstrap style -->
            <link href="../calendar/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <!-- Main style -->
            <link rel="StyleSheet" href="../calendar/css/main_box.css" type="text/css" />
		 
    </head>
	<body>
		 <div class="container">
          	<div class="box">
<section id="enter">
<br>
	<img class="center-block" src="../calendar/img/logo.png" width="190px" height="190px" alt="logo">
<form method="POST">
<label>Pseudo: <input type="text" name="pseudo"/></label><br/>
<label>Mot de passe: <input type="password" name="passe"/></label><br/>
<label>Confirmation du mot de passe: <input type="password" name="passe2"/></label><br/>
<label>Adresse e-mail: <input type="text" name="email"/></label><br/>
<input type="submit" value="M'inscrire"/>
</form>
</section></div>
		</div>
	</body>
</html>
