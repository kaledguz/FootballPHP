<head>
    <link href="/css/menu.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>



            <?php
            if (isset($_SESSION['sessionUti'])) {
                echo "
            <a href='index.php?page=profil' class='navbar-item'>
                Profil
            </a>";
            }
            ?>

<nav style="background-color: #e3f2fd;">
    <ul>
        <li><a href="index.php?page=accueil" class="navbar-item">Accueil</a></li>
        <li><a href="index.php?page=article" class="navbar-item"><img src="/img/article.png"> <br> Article</a></li>
        <li class="deroulant"><a href=""><img src="/img/ligue1.png"style="width: 40px;" ></a>
            <ul class="sous">
                <li><a href="index.php?page=classement&ligue=1" class="navbar-item"><img src="/img/classement.png" style="width: 30px;"> Classement</a></li>
                <li><a href="index.php?page=resultats&ligue=1" class="navbar-item"><img src="/img/resultat.png" style="width: 30px;">Résultat</a></li>
                <li><a href="index.php?page=calendrier&ligue=1" class="navbar-item"><img src="/img/calendrier.png" style="width: 30px;">Calendrier</a></li>
            </ul>
        </li>
        <li class="deroulant"><a href=""><img src="/img/ligue2.png" style="width: 40px;" ></a>
            <ul class="sous">
                <li><a href="index.php?page=classement&ligue=2" class="navbar-item">Classement</a></li>
                <li><a href="index.php?page=resultats&ligue=2" class="navbar-item">Résultat</a></li>
                <li><a href="index.php?page=calendrier&ligue=2" class="navbar-item">Calendrier</a></li>
            </ul>
        </li>
        <li class="deroulant"><a href="">compte</a>
            <ul class="sous">
                <?php
        if (isset($_SESSION['sessionUti'])) {
            
            $admin = $utilisateurSession->getAdmin_utilisateur();

            echo "<div class='navbar-end'>";
            if ($admin) {
                echo "
                
                
                    <li>
                        <a href='index.php?page=administration' class='button'>
                            <strong>Administration</strong>
                        </a>
                    </li>
                ";
            }

            echo "            
                    <li>
                        <a href='index.php?page=deconnexion' class='button'>
                            <strong>Deconnexion</strong>
                        </a>
                    </li>
                 ";
        } else {
            echo "
                    <li>
                        <a href='index.php?page=connexion' class='btn btn-primary' id='idConnexion'>
                        <strong>Connexion</strong>
                        </a>
                    </li>
                    <li>
                        <p> Pas de compte ? </p>
                        <a href='index.php?page=inscription'>
                        <strong>S'inscrire</strong>
                        </a>
                    </li>
                 ";
        }
        ?>
            </ul>
        </li>
    </ul>
</nav>