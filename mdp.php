<?php
// procédure de vérification du login
session_start();
if(isset($_POST['username']) && isset($_POST['password']))
{
	// connexion à la base de données
	$db_username = 'root';
	$db_password = '';
	$db_name     = 'coc-test';
	$db_host     = 'localhost';
	$db = mysqli_connect($db_host, $db_username, $db_password,$db_name)
		or die('could not connect to database');

	// on applique les deux fonctions mysqli_real_escape_string et htmlspecialchars
	// pour éliminer toute attaque de type injection SQL et XSS
	$username = mysqli_real_escape_string($db,htmlspecialchars($_POST['username']));
	$password = mysqli_real_escape_string($db,htmlspecialchars($_POST['password']));

	if($username !== "" && $password !== "")
	{
		$sqlLogin="SELECT count(1) FROM hdc_users WHERE user_id = '" . $_POST['username'] . "'";
		$sqlPswd="SELECT * FROM hdc_users WHERE user_id ='" . $_POST['username'] . "'" ;
		$exec_requeteLogin = mysqli_query($db,$sqlLogin);
    $exec_requetePswd  = mysqli_query($db,$sqlPswd);
    $reponseLogin      = mysqli_fetch_array($exec_requeteLogin);
    $reponsePswd       = mysqli_fetch_array($exec_requetePswd);

    // Test des résultats
    if($reponseLogin[0]!=1) // le login entré n'est pas trouvé
    {
      header('Location: login.php?erreur=1'); // utilisateur incorrect
    }
    else
    {
      // Vérification du mot de passe
      if (password_verify($_POST['password'], $reponsePswd['user_pswd'])) {
        // on démarre la session
		    session_start ();
		    $_SESSION['username'] = $_POST['username'];
		    $_SESSION['password'] = $_POST['password'];
				$_SESSION['user_role'] = $reponsePswd['user_role'];
        header('Location: index.php');
      } else {
        header('Location: login.php?erreur=2'); // mot de passe incorrect
      }
    }
  }
  else
  {
   header('Location: login.php');
  }
}
 mysqli_close($db); // fermer la connexion
?>
