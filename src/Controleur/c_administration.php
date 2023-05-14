<?php
if (isset($_SESSION['sessionUti'])) {
            
    $admin = $utilisateurSession->getAdmin_utilisateur();

    if ($admin) {
        include('../Vue/v_administration.php');
    } else {
        header("Location: index.php?page=erreur");
    }
} else {
    header("Location: index.php?page=erreur");
}
