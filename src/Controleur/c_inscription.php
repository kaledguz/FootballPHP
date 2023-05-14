<?php

if (isset($_POST['valider'])) {
    include('../Modele/Utilisateur.php');
    include('../Modele/GestionBDD.php');
    include('../Modele/GestionUtilisateur.php');
    include('../Modele/GestionAbonner.php');

    $insertion = true;

    /* Vérification de l'image */
    if (isset($_FILES["imgUti"]["type"]) && !(empty($_FILES["imgUti"]["type"]))) {
        if (($_FILES["imgUti"]["type"] == "image/jpeg")
            || ($_FILES["imgUti"]["type"] == "image/pjpeg")
            || ($_FILES["imgUti"]["type"] == "image/png")
        ) {
            if (!($_FILES["imgUti"]["error"] > 0)) {

                $_FILES["imgUti"]["name"] = genererChaineAleatoire(20) . ".jpg";

                if (file_exists("img/" . $_FILES["imgUti"]["name"])) {
                    while (file_exists("img/" . $_FILES["imgUti"]["name"])) {
                        $_FILES["imgUti"]["name"] = genererChaineAleatoire(20) . ".jpg";
                    }
                }
                move_uploaded_file($_FILES["imgUti"]["tmp_name"], "img/" . $_FILES["imgUti"]["name"]);
                $cheminImg = "img/" . $_FILES["imgUti"]["name"];
            } else {
                $insertion = false;
            }
        } else {
            echo "Chemin invalide ! <br/>";
            $insertion = false;
        }
    } else {
        echo "pas d'img";
        $cheminImg = "imgProfil.jpg";
    }

    /* Vérification de l'équipe */
    if (isset($_REQUEST['equipeFavorite']) && !(empty($_REQUEST['equipeFavorite']))) {
        $id_club_utilisateur = $_REQUEST['equipeFavorite'];
    } else {
        $insertion = false;
    }

    /* Vérification du nom */
    if (isset($_REQUEST['nomInscription']) && !(empty($_REQUEST['nomInscription']))) {
        $nom_utilisateur = $_REQUEST['nomInscription'];
    } else {
        $insertion = false;
    }

    /* Vérification du prénom */
    if (isset($_REQUEST['prenomInscription']) && !(empty($_REQUEST['prenomInscription']))) {
        $prenom_utilisateur = $_REQUEST['prenomInscription'];
    } else {
        $insertion = false;
    }

    /* Vérification du sexe */
    if (isset($_REQUEST['sexeInscription']) && !(empty($_REQUEST['sexeInscription']))) {
        $sexe_utilisateur = $_REQUEST['sexeInscription'];
    } else {
        $insertion = false;
    }

    /* Vérification du mot de passe */
    if (isset($_REQUEST['passwordInscription']) && !(empty($_REQUEST['passwordInscription']))) {
        if (preg_match('#^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$#', $_REQUEST['passwordInscription'])) {
            $password_utilisateur = password_hash($_REQUEST['passwordInscription'], PASSWORD_DEFAULT);
        } else {
            $insertion = false;
            echo 'mot de passe pas bon';
        }
    } else {
        $insertion = false;
    }

    /* Vérification du mail */
    if (isset($_REQUEST['mailInscription']) && !(empty($_REQUEST['mailInscription']))) {
        $mail_utilisateur = $_REQUEST['mailInscription'];
    } else {
        $insertion = false;
    }

    /* Vérification de la news letter */
    if (($_REQUEST['radioNews'] == 'oui') && isset($_REQUEST['newsLetterClub'])) {
        $listClubAbonnement = array();
        foreach ($_REQUEST['newsLetterClub'] as $valeur) {
            array_push($listClubAbonnement, $valeur);
        }
        $test = $_REQUEST['newsLetterClub'];
        echo count($test);
    } else {
        echo 'pas de club';
    }

    $date_inscription_utilisateur = date('d-m-Y');

    /* Insertion des données */
    if ($insertion) {
        $userInscription = new Utilisateur(
            0,
            (int) $id_club_utilisateur,
            $nom_utilisateur,
            $prenom_utilisateur,
            $sexe_utilisateur,
            $password_utilisateur,
            $date_inscription_utilisateur,
            $cheminImg,
            $mail_utilisateur,
            false
        );

        $BDD = new GestionBDD('Ligue_1');
        $cnx = $BDD->connect();

        $GU = new GestionUtilisateur($cnx);


        try {
            $userInscription = $GU->insertUser($userInscription);

            if (isset($_POST['newsLetterClub'])) {
                $GA = new GestionAbonner($cnx);
                $GA->insertAbonner($userInscription, $listClubAbonnement);
            }

            session_start();
            $_SESSION["sessionUti"] = $userInscription->getId_utilisateur();
            header("Location: index.php?page=accueil");

            $cnx = null;
        } catch (Exception $e) {
        }
    } else {
        echo 'nop';
    }
} else {
    include('../Modele/GestionBDD.php');
    include('../Modele/GestionClub.php');
    include('../Modele/Club.php');

    $BDD = new GestionBDD('Ligue_1');
    $cnx = $BDD->connect();

    $GC = new GestionClub($cnx);
    $tableResultEquipe = $GC->getListClubByName();

    include('../Vue/v_inscription.php');

    $cnx = null;
}


function genererChaineAleatoire($longueur, $listeCar = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $chaine = '';
    $max = strlen($listeCar) - 1;
    for ($i = 0; $i < $longueur; ++$i) {
        $chaine .= $listeCar[random_int(0, $max)];
    }
    return $chaine;
}
