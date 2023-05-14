<?php
include("../Vue/v_page3.php");

/* 
// Store the string into variable
$password = 'Password';


$hash_default_salt = password_hash(
    $password,
    PASSWORD_DEFAULT
);

if (password_verify('Password123', $hash_default_salt)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
} 
*/

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
    $cheminImg = "";
}
$insertionVide = "";

// Vérifie que les valeurs ne sont pas vide
if (!(isset($_REQUEST['equipeFavorite'])) || !(empty($_REQUEST['equipeFavorite']))) {
    $insertionVide = "equipeFavorite";
} else if (!(isset($_REQUEST['nomInscription'])) || (empty($_REQUEST['nomInscription']))) {
    $insertionVide = "nomInscription";
} else if (!(isset($_REQUEST['prenomInscription'])) || (empty($_REQUEST['prenomInscription']))) {
    $insertionVide = "prenomInscription";
} else if (!(isset($_REQUEST['sexeInscription'])) || (empty($_REQUEST['sexeInscription']))) {
    $insertionVide = "sexeInscription";
} else if (!(isset($_REQUEST['mailInscription'])) || (empty($_REQUEST['mailInscription']))) {
    $insertionVide = "mailInscription";
} else if (!(isset($_REQUEST['passwordInscription'])) || (empty($_REQUEST['passwordInscription']))) {
    $insertionVide = "passwordInscription";
} else if (isset($_FILES["imgUti"]["type"]) && empty($_FILES["imgUti"]["type"])) {
    $insertionVide = "imgUti";
} else if (($_REQUEST['radioNews'] == 'oui') && (!(isset($_REQUEST['newsLetterClub'])))) {
    $insertionVide = "newsLetterClub";
}

if ($insertionVide == "") {
    /* Vérification du mot de passe */
    if (isset($_REQUEST['passwordInscription']) && !(empty($_REQUEST['passwordInscription']))) { 
        if (preg_match('#^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$#', $_REQUEST['passwordInscription'])) {
            $password_utilisateur = password_hash($_REQUEST['passwordInscription'], PASSWORD_DEFAULT);
            echo $password_utilisateur;
        } else {
            $insertion = false;
            echo 'mot de passe pas bon';
        }
    } 
}





/* Vérification de la news letter */
if (isset($_POST['newsLetterClub'])) {
    $listClubAbonnement = array();
    foreach ($_POST['newsLetterClub'] as $valeur) {
        array_push($listClubAbonnement, $valeur);
    }
} else {
    echo 'pas de club';
}

$date_inscription_utilisateur = date('d-m-Y');
