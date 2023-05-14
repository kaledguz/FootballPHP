<?php
include('../Modele/GestionBDD.php');

$ligue = $_GET['ligue'];

$BDD = new GestionBDD('Ligue_1');
$cnx = $BDD->connect();

$res = $cnx->query("select * from classement_saison(2023, ". $ligue . ")");

include("../Vue/v_classement.php");

$cnx = null;
