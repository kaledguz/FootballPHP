<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link href="/dist/output.css" rel="stylesheet">
</head>

<header>
    <?php
    session_start();
    if (isset($_SESSION['sessionUti'])) {
        include('../Modele/Utilisateur.php');
        $utilisateurSession = unserialize($_SESSION['sessionUti']);
    }
    include('../Vue/v_menu.php');
    ?>
</header>

<body>

    <p class="h-[56px]"></p>

    <?php

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        switch ($page) {
            case 'accueil':
                include('c_accueil.php');
                break;
            case 'article':
                include('c_article.php');
                break;
            case 'classement':
                if (isset($_GET['ligue']) && empty($_GET['ligue'])) {
                    include('c_accueil.php');
                    break;
                } else {
                    if (($_GET['ligue'] == 1 || $_GET['ligue'] == 2)) {
                        include('c_classement.php');
                        break;
                    } else {
                        include('c_accueil.php');
                        break;
                    }
                }
            case 'resultats':
                if (isset($_GET['ligue']) && empty($_GET['ligue'])) {
                    include('c_erreur.php');
                    break;
                } else {
                    if (($_GET['ligue'] == 1 || $_GET['ligue'] == 2)) {
                        include('c_resultats.php');
                        break;
                    } else {
                        include('c_erreur.php');
                        break;
                    }
                }
            case 'calendrier':
                if (isset($_GET['ligue']) && empty($_GET['ligue'])) {
                    include('c_erreur.php');
                    break;
                } else {
                    if (($_GET['ligue'] == 1 || $_GET['ligue'] == 2)) {
                        include('c_calendrier.php');
                        break;
                    } else {
                        include('c_erreur.php');
                        break;
                    }
                }
            case 'page3':
                include('c_page3.php');
                break;
            case 'inscription':
                include('c_inscription.php');
                break;
            case 'connexion':
                include('c_connexion.php');
                break;
            case 'deconnexion':
                include('c_deconnexion.php');
                break;
            case 'profil':
                include('c_profil.php');
                break;
            case 'commentaire':
                if (isset($_GET['idArticle']) && empty($_GET['idArticle'])) {
                    include('c_accueil.php');
                    break;
                } else {
                    include('c_commentaire.php');
                    break;
                }
            case 'info_club':
                if (isset($_GET['idClub']) && empty($_GET['idClub'])) {
                    include('c_accueil.php');
                    break;
                } else {
                    include('c_info_club.php');
                    break;
                }
            case 'administration':
                include('c_administration.php');
                break;
            case 'add_article':
                include('c_add_article.php');
                break;

            default:
                include('c_erreur.php');
                break;
        }
    } else {
        include('c_accueil.php');
    }
    ?>

</body>

</html>