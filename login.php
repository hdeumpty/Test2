<?php
  session_start();
  $thisPage=array('login','HDC - Login');
  if(isset($_GET['deconnexion']))
  {
    $deconnexion=$_GET['deconnexion'];
    if($deconnexion==true){session_unset();;}
  }
 ?>
<html>
  <?php require_once __DIR__ . '/head.php'; ?>
  <body>
    <div id="container">
      <!-- zone de connexion -->
      <form action="mdp.php" method="POST">
        <h1>Connexion</h1>
          <label><b>Nom d'utilisateur</b></label>
          <input type="text" placeholder="Entrer le nom d'utilisateur" name="username" required>
            <label><b>Mot de passe</b></label>
          <input type="password" placeholder="Entrer le mot de passe" name="password" required>
          <input type="submit" id='submit' value='LOGIN' >
          <?php
            if(isset($_GET['erreur']))
            {
              $err = $_GET['erreur'];
              if($err==1 || $err==2){echo "<p style='color:red'>Utilisateur ou mot de passe incorrect</p>";}
              //if($err==2){echo "<p style='color:red'>Mot de passe incorrect</p>";}
            }
          ?>
      </form>
    </div>
  </body>
</html>
