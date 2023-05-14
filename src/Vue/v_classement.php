<h1 class="title is-1 is-spaced text-center mt-[20px]">Classement des équipes de Ligue <?php echo $ligue ?></h1>

<div>
    <table>
        <thead>
            <tr>
                <th style="color:white">Position</th>
                <th style="color:white">Club</th>
                <th style="color:white">Points</th>
                <th style="color:white">Joués</th>
                <th style="color:white">Gagnés</th>
                <th style="color:white">Nuls</th>
                <th style="color:white">Perdus</th>
                <th style="color:white">Buts Pour</th>
                <th style="color:white">Buts Contre</th>
                <th style="color:white">Différence</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $diff = 0;
            while ($ligne = $res->fetch()) {
                $diff = ($ligne["nb_buts_marques"] - $ligne["nb_buts_encaisse"]);
                echo "<tr>";
                echo "<td>" . $i . "</td>";
                echo "<td style='text-align:left'>" . "<a href='index.php?page=info_club&idClub=" . $ligne["id_club"] . "'><img src='" . $ligne["logo"] . "' class='center' />" . $ligne["nom_club"] . "</a></td>";
                echo "<td>" . $ligne["nb_points"] . "</td>";
                echo "<td>" . $ligne["nb_match_joue"] . "</td>";
                echo "<td>" . $ligne["nb_match_gagne"] . "</td>";
                echo "<td>" . $ligne["nb_match_nul"] . "</td>";
                echo "<td>" . $ligne["nb_match_perdu"] . "</td>";
                echo "<td>" . $ligne["nb_buts_marques"] . "</td>";
                echo "<td>" . $ligne["nb_buts_encaisse"] . "</td>";
                if ($diff > 0) {
                    echo "<td>+" . $diff . "</td>";
                } else {
                    echo "<td>" . $diff . "</td>";
                }
                
                echo "</tr>";
                $i++;
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
        width: 30px;
    }
</style>