<?php

define( 'DB_NAME', 'coc-test' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );

$chartDataset="";
$chartLabels="";

/* Connexion à la base de données */
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
/* Vérification de la connexion */
if ($mysqli->connect_errno) {
  printf("init.php ligne 19 - Échec de la connexion : %s\n", $mysqli->connect_error);
  exit();
}

/* Change la base de données courante */
$mysqli->select_db(DB_NAME);

/* Requête nombre de membres par groupe */
$sql="SELECT * FROM (SELECT member_group, COUNT(member_id) AS 'theNumber' FROM hdc_members GROUP BY member_group) S ORDER BY theNumber desc";
if (($membersXgroups = $mysqli->query($sql))===false ) {
  printf("index.php - Requête invalide: %s\nWhole query: %s\n", $mysqli->error, $sql);
  exit();
}
else {
  while ($row = $membersXgroups->fetch_array()) {
    if ($chartDataset=="") {
      $chartDataset=$row["theNumber"];
    }
    else {
      $chartDataset=$chartDataset . ', ' . $row["theNumber"];
    }
    if ($chartLabels=="") {
      $chartLabels="'" . $row["member_group"];
    }
    else {
      $chartLabels=$chartLabels . "', '" . $row["member_group"];
    }
  }
  $chartLabels=$chartLabels . "'";
  /*echo $chartDataset;*/
  echo $chartLabels;
}


 ?>
