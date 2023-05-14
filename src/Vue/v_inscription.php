<div class="w-2/4 min-w-[600px] mx-auto my-[50px] p-[20px] rounded
shadow-[0px_0px_12px_4px_rgba(0,0,0,0.3)]">
  <h1 class="title is-1 is-spaced text-center">Inscription</h1>

  <form method="POST" action="index.php?page=inscription" name="formInscription" id="idformInscription" class="inscription" enctype="multipart/form-data">

    <!-- Nom -->
    <div class="field">
      <label class="label">Nom</label>
      <div class="control">
        <input class="input" type="text" placeholder="Entrez votre nom" name="nomInscription" required>
      </div>
    </div>

    <!-- Prénom -->
    <div class="field">
      <label class="label">Prénom</label>
      <input class="input" type="text" placeholder="Entrez votre prénom" name="prenomInscription" required>
    </div>



    <!-- Mail -->
    <div class="field">
      <label class="label">Mail</label>
      <input class="input" type="email" placeholder="Entrez votre mail" name="mailInscription" required>
    </div>

    <!-- Mot de passe -->
    <div class="field">
      <label class="label">Mot de passe</label>
      <div class="field-body">
        <div class="field">
          <div class="control">
            <input class="input" type="password" placeholder="Entrez votre mot de passe" name="passwordInscription" onKeyPress="checkPwd(this.value)" required>
          </div>
          <p id="idIsDangerPwd" class="help is-danger"></p>
        </div>
      </div>
    </div>



    <!-- Sexe -->
    <div class="field">
      <label class="label">Sexe</label>
      <div class="control">
        <label class="radio">
          <input type="radio" name="sexeInscription" value="homme" checked>
          Homme
        </label>
        <label class="radio">
          <input type="radio" name="sexeInscription" value="femme">
          Femme
        </label>
      </div>
    </div>

    <!-- Photo -->
    <div class="field">
      <label class="label">Photo de profil :</label>
      <input type="file" name="imgUti" size="50">
    </div>


    <!-- Equipe favorite -->
    <div class="field">
      <label class="label">Équipe favorite</label>
      <div class="control">
        <div class="select">
          <select name="equipeFavorite">
            <option value="">--Choisir l'équipe--</option>
            <?php
            $i = 0;
            while ($i < count($tableResultEquipe)) {
              echo "<option value=\"" . $tableResultEquipe[$i]->getId_club() . "\">" . $tableResultEquipe[$i]->getNom_club() . "</option>";
              $i++;
            }
            ?>
          </select>
        </div>
      </div>
    </div>

    <div>
      <p>Voulez vous recevoir les news des équipes ?</p>
      <div>
        <input type="radio" id="idRadioNewsOui" name="radioNews" value="oui" onclick="affichageDiv('idFormNewsEquipe', 'oui')" required>
        <label for="idRadioNewsOui">Oui</label>
      </div>
      <div>
        <input type="radio" id="idRadioNewsNon" name="radioNews" value="non" onclick="affichageDiv('idFormNewsEquipe', 'non')" checked>
        <label for="idRadioNewsNon">Non</label>
      </div>
    </div>

    <div class="field" id="idFormNewsEquipe" style="display: none;">
      <div class="control">
        <?php
        $i = 0;
        echo "<div class='field is-grouped is-grouped-multiline'>";
        while ($i < count($tableResultEquipe)) {
          echo "<p class='control'>";
          echo "<label class='checkbox'>";
          echo "<input type='checkbox' name='newsLetterClub[]' value='" . $tableResultEquipe[$i]->getId_club() . "'> " . $tableResultEquipe[$i]->getNom_club();
          echo "</label>";
          echo "</p>";
          $i++;
        }
        echo "</div>";

        ?>
      </div>
    </div>


    <div class="field is-grouped">
      <div class="control">
        <input type="submit" name="valider" value="Valider" />
      </div>
      <div class="control">
        <input type="reset" name="annuler" value="Annuler" />
      </div>
    </div>
  </form>
</div>

<script>
  function affichageDiv(div, affichage) {
    div = document.getElementById(div);
    if (affichage === "oui") {
      div.style.display = "block";
    } else {
      div.style.display = "none";
    }
  }

  function checkPwd(pwd) {
    var strCheckPwd = '';
    var character = '';
    var i = 0;
    if (pwd.length < 7) {
      strCheckPwd = 'Le mot de passe doit contenir plus de 8 caractères '
      //document.getElementById('idIsDangerPwd').textContent = 'Le mot de passe doit contenir plus de 8 caractères';

    } else {

    }
    document.getElementById('idIsDangerPwd').textContent = strCheckPwd;
  }
</script>