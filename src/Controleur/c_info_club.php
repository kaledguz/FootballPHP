<?php
include('../Modele/GestionBDD.php');

$id_club = $_GET['idClub'];

$BDD = new GestionBDD('Ligue_1');
$cnx = $BDD->connect();

$res = $cnx->query("select * from get_info_club(" . $id_club . ")");
$ligne = $res->fetch();

if ($ligne) {
    include('../Vue/v_info_club.php');
} else {
    header("Location: index.php?page=erreur");
}

$cnx = null;
