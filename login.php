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
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-6">
          <div class="card">
            <div class="card-header text-center">
              <img src="./images/hd_logo_dive_2_bleu.svg" alt="navbar brand" class="navbar-brand">
            </div>
            <div class="card-body">
              <form action="mdp.php" method="POST">
                <h1>Connexion</h1>
                <?php
                if(isset($_GET['erreur']))
                {
                  $err = $_GET['erreur'];
                  if($err==1 || $err==2){echo "<p style='color:red'>Utilisateur ou mot de passe incorrect</p>";}
                  //if($err==2){echo "<p style='color:red'>Mot de passe incorrect</p>";}
                }
                ?>
                <div class="form-group">
                  <label>Nom d'utilisateur</label>
                  <input type="text" class="form-control" placeholder="nom d'utilisateur" name="username" required>
                </div>
                <div class="form-group">
                  <label>Mot de passe</label>
                  <input type="password" class="form-control" placeholder="mot de passe" name="password" required>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Sign in</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
