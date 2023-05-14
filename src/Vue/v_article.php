    <h1 class="title is-1 is-spaced text-center mt-[20px]">Article</h1>

    <?php
    $i = 0;

    echo "<div class='ligne'>";
    while ($i < count($tableResultArticle)) {

        // Affichage de l'article
        echo "<a href='index.php?page=commentaire&idArticle=" . $tableResultArticle[$i]->getId_Article() . "' 
        style='background-image: url(\"" . $tableResultArticle[$i]->getImage_article() . "\");'>";
        echo "<article>";
        echo "<h2>" . $tableResultArticle[$i]->getTitre_article() . "</h2>";
        echo "</article>";
        echo "</a>";

        $i++;
    }
    echo "</div>";
    ?>

    <style>
        .ligne {
            display: grid;
            grid-template-columns: repeat(3, minmax(300px, 1fr));
            grid-template-rows: auto;
            gap: 40px 0px;
            margin: 40px;
        }

        .ligne a {
            margin: auto;
            width: 450px;
            height: 350px;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .ligne article {
            box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.5);
            padding: 10px;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            border: 1px solid #555;
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            justify-content: space-between;
            align-items: stretch;
            align-content: stretch;
        }

        .ligne h2 {
            background-color: #fff;
            opacity: 0.9;
            font-size: 20px;
            margin-top: 0;
            text-align: center;
            border: 1px solid #000;
        }

        .ligne p {
            background-color: #fff;
            opacity: 0.9;
            font-size: 16px;
            margin-bottom: 0;
            padding-right: 10px;
            text-align: end;
            border: 1px solid #000;
        }

        @media (max-width: 1500px) {
            .ligne {
                grid-template-columns: repeat(2, minmax(300px, 1fr));
            }
        }

        @media (max-width: 1050px) {
            .ligne {
                grid-template-columns: repeat(1, minmax(200px, 1fr));
            }
        }

        @media (max-width: 550px) {
            .ligne a {
                width: 350px;
                height: 250px;
            }
        }
    </style>