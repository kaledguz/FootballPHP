<nav class="navbar fixed w-full bg-gray-100 shadow-[0px_0px_12px_4px_rgba(0,0,0,0.3)]" role="navigation" aria-label="main navigation">
    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
            <a href="index.php?page=accueil" class="navbar-item">
                Accueil
            </a>

            <?php
            if (isset($_SESSION['sessionUti'])) {
                echo "
            <a href='index.php?page=profil' class='navbar-item'>
                Profil
            </a>";
            }
            ?>

            <a href="index.php?page=article" class="navbar-item">
                Article
            </a>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    Ligue 1
                </a>

                <div class="navbar-dropdown">
                    <a href="index.php?page=classement&ligue=1" class="navbar-item">
                        Classement
                    </a>
                    <a href="index.php?page=resultats&ligue=1" class="navbar-item">
                        Résultats
                    </a>
                    <a href="index.php?page=calendrier&ligue=1" class="navbar-item">
                        Calendrier
                    </a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    Ligue 2
                </a>

                <div class="navbar-dropdown">
                    <a href="index.php?page=classement&ligue=2" class="navbar-item">
                        Classement
                    </a>
                    <a href="index.php?page=resultats&ligue=2" class="navbar-item">
                        Résultats
                    </a>
                    <a href="index.php?page=calendrier&ligue=2" class="navbar-item">
                        Calendrier
                    </a>
                </div>
            </div>

            <a href="index.php?page=page3" class="navbar-item">
                Page 3
            </a>
        </div>

        <?php
        if (isset($_SESSION['sessionUti'])) {
            
            $admin = $utilisateurSession->getAdmin_utilisateur();

            echo "<div class='navbar-end'>";
            if ($admin) {
                echo "
                <div class='navbar-item'>
                    <div class='buttons'>
                        <a href='index.php?page=administration' class='button'>
                            <strong>Administration</strong>
                        </a>
                    </div>
                </div>";
            }

            echo "            
                <div class='navbar-item'>
                    <div class='buttons'>
                        <a href='index.php?page=deconnexion' class='button'>
                            <strong>Deconnexion</strong>
                        </a>
                    </div>
                </div>
            </div> ";
        } else {
            echo "
        <div class='navbar-end'>
            <div class='navbar-item'>
                <div class='buttons'>
                    <a href='index.php?page=inscription' class='button'>
                        <strong>Inscription</strong>
                    </a>
                    <a href='index.php?page=connexion' class='button' id='idConnexion'>
                        <strong>Connexion</strong>
                    </a>
                </div>
            </div>
        </div> ";
        }
        ?>
    </div>
</nav>