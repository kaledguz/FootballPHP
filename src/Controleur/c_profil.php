<?php
if (!(isset($_SESSION['sessionUti']))) {
    header("Location: index.php?page=connexion");
}


include('../Modele/GestionBDD.php');
include('../Modele/GestionUtilisateur.php');

$BDD = new GestionBDD('Ligue_1');
$cnx = $BDD->connect();

$GU = new GestionUtilisateur($cnx);



$name = $utilisateurSession->getNom_utilisateur();
$img = $utilisateurSession->getImg_utilisateur();

include("../Vue/v_profil.php");

$cnx = null;
