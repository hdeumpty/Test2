<?php

define( 'DB_NAME', 'coc-test' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );

/*
define( 'DB_NAME', 'ztneozl831' );
define( 'DB_USER', 'ztneozl831' );
define( 'DB_PASSWORD', '3mOK9Q421rMluv5' );
define( 'DB_HOST', 'ztneozl831.mysql.db' );
*/

/* Connexion à la base de données */
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
/* Vérification de la connexion */
if ($mysqli->connect_errno) {
  printf("init.php ligne 19 - Échec de la connexion : %s\n", $mysqli->connect_error);
  exit();
}

/* Change la base de données courante */
$mysqli->select_db(DB_NAME);

/* Charge le nom du club depuis les options */
$sql="SELECT * FROM hdc_options WHERE option_key = 'nom_club'";
if (($result = $mysqli->query($sql))===false ) {
  printf("init.php ligne 29 - Requête invalide : %s\nWhole query: %s\n", $mysqli->error, $sql);
  exit();
}
else {
  $row = $result->fetch_array();
  $var = $row["option_value"];
  define( 'NOM_CLUB', $var );
}
?>
