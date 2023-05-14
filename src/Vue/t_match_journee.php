<div>
    <table>
        <thead>
            <tr>
                <th style="color:white" class="title is-4"> <?php echo $dateFormatee ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="containerEquipe">
                        <div class="Equipe1 text-xl">Equipe jouant à domicile</div>
                        <div class="Center text-xl">-</div>
                        <div class="Equipe2 text-xl">Equipe jouant à l'exterieur</div>
                    </div>
                </td>
            </tr>
            <?php
            foreach ($resMatch as $ligne) {
                echo "<tr>";
                echo "<td>";
                echo "<div class='containerEquipe'>";

                echo "<div class='Equipe1'>";
                echo "<a href='index.php?page=info_club&idClub=" . $ligne["id_club_domicile"] . "'>" .
                    $ligne["nom_club_domicile"] .
                    "<img src='" . $ligne["logo_club_domicile"] . "' class='center' />" .
                    "</a></div>";

                if ($ligne["isjoue"] == 3) {
                    echo "<div class='Center'>" . $ligne["score_domicile"] . " - " . $ligne["score_visiteur"] .  "</div>";
                } else {
                    echo "<div class='Center'><p> - - - </p></div>";
                }

                echo "<div class='Equipe2'>";
                echo "<a href='index.php?page=info_club&idClub=" . $ligne["id_club_visiteur"] . "'>" .
                    "<img src='" . $ligne["logo_club_visiteur"] . "' class='center'/> </a>" .
                    $ligne["nom_club_visiteur"] . "</div>";

                echo "</div>";

                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>




</div>

<style>
    table {
        border-collapse: collapse;
        width: 80%;
        margin: auto;
        border: 1px solid #000;
        margin-top: 50px;
        margin-bottom: 50px;
    }

    th,
    td,
    tr {
        text-align: center;
        padding: 8px;
        border: none;
        border-bottom: 1px solid #ddd;
        background-color: #f0f0f0;
    }

    th:nth-child(2),
    td:nth-child(2) {
        width: 30%;
    }

    th {
        background-color: #091c3e;
        font-weight: bold;
    }

    .center {
        display: inline;
        margin-left: 10px;
        margin-right: 10px;
        height: 50px;
    }

    .containerEquipe {
        display: grid;
        grid-template-columns: 1fr 70px 1fr;
        grid-template-rows: 1fr;
        gap: 0px 0px;
        grid-template-areas:
            "Equipe1 Center Equipe2";
        align-items: center;
    }

    .Equipe1 {
        grid-area: Equipe1;
        text-align: right;
    }

    .Center {
        grid-area: Center;
        text-align: center;
    }

    .Equipe2 {
        grid-area: Equipe2;
        text-align: left;
    }
</style>