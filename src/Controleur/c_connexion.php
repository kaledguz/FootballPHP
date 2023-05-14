<?php
if (isset($_POST['valider'])) {
    /*     echo $_REQUEST['mailConnection'];
    echo $_REQUEST['passwordConnection']; */

    include('../Modele/Utilisateur.php');
    include('../Modele/GestionBDD.php');
    include('../Modele/GestionUtilisateur.php');

    if (isset($_REQUEST['mailConnexion']) && !(empty($_REQUEST['mailConnexion'])) && isset($_REQUEST['passwordConnexion']) && !(empty($_REQUEST['passwordConnexion']))) {

        $BDD = new GestionBDD('Ligue_1');
        $cnx = $BDD->connect();

        $GU = new GestionUtilisateur($cnx);


        $mailConnexion = $_REQUEST['mailConnexion'];
        $mdpConnexion = $_REQUEST['passwordConnexion'];
      
       
        if ($GU->existUser($mailConnexion, $utilisateur)) {

            if (password_verify($mdpConnexion, $utilisateur->getPassword_utilisateur())) {
                $utilisateur = serialize($utilisateur);
                session_start();
                $_SESSION["sessionUti"] = $utilisateur;
                header("Location: index.php?page=profil");
            } else {
                header("Location: index.php?page=connexion");
                $utilisateur = null;
            }
        } else {
            header("Location: index.php?page=connexion");
        }
    } else {
        header("Location: index.php?page=connexion");
    }
    $cnx = null;
} else {
    include("../Vue/v_connexion.php");
}
