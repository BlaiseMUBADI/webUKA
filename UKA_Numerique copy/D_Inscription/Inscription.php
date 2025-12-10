<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
    </style>
</head>
<body>
<?php 
		include("Connexion.php");
		include("Menu.php"); ?>

    <div class="container mt-5">
	
        <div class="text-center"> 
        
       <h4>Formulaire de demande d'inscription</h4>
       <h6>Année académique 2024-2025</h6>


    </div>
        <form action="Inscription.php" method="post" enctype="multipart/form-data">
            <div class="form-step active">
                
                <div class="form-group">
                    <label for="name">Année d'obtention du duplôme d'Etat</label>
                    <select id="Annobt" name="Annobt" class="form-control">
                        <option value="2023-2024">2023-2024</option>
                        <option value="2022-2023">2022-2023</option>
                        <option value="2021-2022">2021-2022</option>
                        <option value="2020-2021">2020-2021</option>
                        <option value="2019-2020">2019-2020</option>
                        <option value="2018-2019">2018-2019</option>
                        <option value="2017-2018">2017-2018</option>
                        <option value="2016-2017">2016-2017</option>
                        
                       
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Province dans laquelle vous avez obtenu le diplôme</label>
                    <select id="Provobt" name="Provobt" class="form-control">
                        <option value="Bas-Uele">Bas-Uele</option>
                        <option value="Equateur">Equateur</option>
                        <option value="Haut-Katanga">Haut-Katanga</option>
                        <option value="Haut-Lomami">Haut-Lomami</option>
                        <option value="Haut-Uele">Haut-Uele</option>
                        <option value="Ituri">Ituri</option>
                        <option value="Kasaï">Kasaï</option>
                        <option value="Kasaï Central">Kasaï Central</option>
                        <option value="Kasaï Oriental">Kasaï Oriental</option>
                        <option value="Kinshasa">Kinshasa</option>
                        <option value="Kwango">Kwango</option>
                        <option value="Kwilu">Kwilu</option>
                        <option value="Lomami">Lomami</option>
                        <option value="Lualaba">Lualaba</option>
                        <option value="Maidombe">Maidombe</option>
                        <option value="Maniema">Maniema</option>
                        <option value="Mongala">Mongala</option>
                        <option value="Nord-Kivu">Nord-Kivu</option>
                        <option value="Nord-Ubangi">Nord-Ubangi</option>
                        <option value="Sankuru">Sankuru</option>
                        <option value="Sud-Kivu">Sud-Kivu</option>
                        <option value="Sud-Ubangi">Sud-Ubangi</option>
                        <option value="Tanganyika">Tanganyika</option>
                        <option value="Tshopo">Tshopo</option>
                        <option value="Tshuapa">Tshuapa</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="email">Province Educationnelle</label>
                    <input type="text" class="form-control" id="ecole" name="Proveduc">
                </div>
                <div class="form-group">
                    <label for="email">Ecole d'obtention du diplôme</label>
                    <input type="text" class="form-control" id="ecole" required name="Ecole">
                </div>
                <div class="form-group">
                    <label for="name">Section</label>
                    <select id="sexe" name="Section" class="form-control">
                        <option value="Technique">Technique</option>
                        <option value="Scientifique">Scientifique</option>
                        <option value="Littéraire">Littéraire</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Option</label>
                    <input type="text" class="form-control" id="option" required name="Options">
                </div>
                <div class="form-group">
                    <label for="email">Pourcentage obtenu</label>
                    <input type="text" class="form-control" maxlength="2"placeholder="%"  style="width: 60px;" id="option" required name="Pourcentage">
                </div>

                <div class="form-group">
                    <label for="name">Votre Nom</label>
                    <input type="text" class="form-control" id="name" required name="Nom">
                </div>
                <div class="form-group">
                    <label for="name">Votre Post-nom</label>
                    <input type="text" class="form-control" id="name"  name="Postnom"
                </div>
                <div class="form-group">
                    <label for="name">Votre Prénom</label>
                    <input type="text" class="form-control" id="name" name="Prenom">
                </div>
                <div class="form-group">
                    <label for="name">Sexe</label>
                    <select id="sexe" name="Sexe" class="form-control">
                        <option value="masculin">Masculin</option>
                        <option value="féminin">Féminin</option>
                    </select>
                </div>


                <button type="button" class="btn btn-primary next-step">Suivant</button>
            </div>
            <div class="form-step">
                <div class="form-group">
                    <label for="name">Etat civil</label>
                    <select id="sexe" name="Etatciv" class="form-control">
                        <option value="Célibataire">Célibataire</option>
                        <option value="Marié(e)">Marié(e)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Lieunaiss">Lieu de naissance</label>
                    <input type="text" class="form-control" id="lieu" name="Lieunaiss" required>
                </div>
                <div class="form-group">
                <label for="Date">Date de naissance</label>
                <input type="date" class="form-control" id="datenais" name="Datenaiss" required>
                </div>
                <div class="form-group">
                    <label for="">Nationalite</label>
                    <input type="text" class="form-control" id="nationalite" name="Nationalite" required>
                </div>
                <div class="form-group">
                    <label for="name">Province d'origine</label>
                    <select id="Provorg" name="Provorg" class="form-control">
                        <option value="Bas-Uele">Bas-Uele</option>
                        <option value="Equateur">Equateur</option>
                        <option value="Haut-Katanga">Haut-Katanga</option>
                        <option value="Haut-Lomami">Haut-Lomami</option>
                        <option value="Haut-Uele">Haut-Uele</option>
                        <option value="Ituri">Ituri</option>
                        <option value="Kasaï">Kasaï</option>
                        <option value="Kasaï Central">Kasaï Central</option>
                        <option value="Kasaï Oriental">Kasaï Oriental</option>
                        <option value="Kinshasa">Kinshasa</option>
                        <option value="Kwango">Kwango</option>
                        <option value="Kwilu">Kwilu</option>
                        <option value="Lomami">Lomami</option>
                        <option value="Lualaba">Lualaba</option>
                        <option value="Maidombe">Maidombe</option>
                        <option value="Maniema">Maniema</option>
                        <option value="Mongala">Mongala</option>
                        <option value="Nord-Kivu">Nord-Kivu</option>
                        <option value="Nord-Ubangi">Nord-Ubangi</option>
                        <option value="Sankuru">Sankuru</option>
                        <option value="Sud-Kivu">Sud-Kivu</option>
                        <option value="Sud-Ubangi">Sud-Ubangi</option>
                        <option value="Tanganyika">Tanganyika</option>
                        <option value="Tshopo">Tshopo</option>
                        <option value="Tshuapa">Tshuapa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Territoir">Votre Territoire/District</label>
                    <input type="text" class="form-control" id="territoir" name="Territoir" required>
                </div>
                <div class="form-group">
                    <label for="address">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="Adresse"required>
                </div>
                <button type="button" class="btn btn-secondary prev-step">Précédent</button>
                <button type="submit" class="btn btn-success">Soumettre</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var currentStep = 0;
            var steps = $(".form-step");

            $(".next-step").click(function() {
                $(steps[currentStep]).removeClass("active");
                currentStep++;
                $(steps[currentStep]).addClass("active");
            });

            $(".prev-step").click(function() {
                $(steps[currentStep]).removeClass("active");
                currentStep--;
                $(steps[currentStep]).addClass("active");
            });

            $("#registrationForm").submit(function(event) {
                event.preventDefault();
                alert("Formulaire soumis !");
            });
        });
    </script>
</body>
</html>
