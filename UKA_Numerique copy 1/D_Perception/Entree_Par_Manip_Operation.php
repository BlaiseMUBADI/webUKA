

<section class="home-section" style="height: 100%;">
      <?php
        require_once 'Profil_Guichet.php';
      ?>
  <div class="home-content me-3 ms-3">
    <!---------------------------------------------------------------------------------------------------------------->
    <!------- BLOC POUR LA SELECTION DES LA FILIERE, DE LA PROMOTION, DE L'ANNEE ACADEMIQUE ET DE LA DATE------------->
    <!---------------------------------------------------------------------------------------------------------------->
  
    <div class="rounded m-0 p-3 mt-3 text-center"
      style="color:white;background-color: #273746;">
    
        <div class="row mt-1 mb-2 p-2" style="border-bottom: 1px solid white;">

          Selectionner la Filière :
            <div class="col-3 ms-2 me-2 fs-7 fw-bolder text-end font-weight-bold p-0 ">         
              <select id="filiere_transact" class="form-select form-select-sm m-0 p-0 text-center
                fw-bold float-end"
                        aria-label="Small select example" 
                        style="background-color:#273746;color:white;">
                        
                <option selected>Filière </option>
                <?php 
                  $req="select * from filiere order by LENGTH(Libelle_Filiere) ASC;";
                  $data= $con-> query($req);
                  while ($ligne=$data->fetch())
                  {
                ?>
                    <option style=" width:100%;"value=<?php echo $ligne['IdFiliere'];?>><?php echo $ligne['Libelle_Filiere']?></option>
                    
                <?php 
                  }
                ?>
             </select>
            </div>

            Selectionner la promotion :
            <div class="col-3 ms-2  fs-7 fw-bolder text-end font-weight-bold p-0 ">         
              <select id="promo_tansact" class="form-select form-select-sm m-0 p-0 text-center
                fw-bold float-end"
                        aria-label="Small select example" 
                        style="background-color:#273746;color:white;"
                        id="promo_transaction">
                        
                <option selected>Promotions </option>
             </select>
            </div>

        </div>
        

        
        <div class="row mt-1 mb-2 p-2 " style="border-bottom: 1px solid white;">
            

           Année Académique : 
            <div class="col-2 ms-2 me-3 fs-7 fw-bolder text-end font-weight-bold p-0 "> 
              <select id="Id_an_acad_1" class="form-select form-select-sm m-0 p-0 text-center
                  fw-bold float-end"
                          aria-label="Small select example" 
                          style="background-color:#273746;color:white;">
                          
                  <option selected>Année acad </option>
                  <?php 
                    //Requette de sélection Année Académique
                    $req="select * from annee_academique order by Annee_debut desc";
                    $data= $con-> query($req);
                    while ($ligne=$data->fetch())
                    {
                    ?>

                    <option value="<?php echo $ligne['idAnnee_Acad']?>"><?php echo $ligne['Annee_debut'];?>-<?php echo $ligne['Annee_fin'];?></option>

                    <?php 
                      }
                    ?>
             </select>              
            </div>

            Date de transaction :

            <div class="col-3 ms-2 me-3 fs-7 fw-bolder text-end font-weight-bold p-0 "> 
              <input type="date" class="form-control p-0 m-0 text-center" id="date_paiement_1"
                  style="background-color:#273746;color:white;">
              
            </div>

        </div>

    </div>
    <!----------------------------------FIN DU BLOC DE LA SELECTION DE LA FILIERE ET AUTRES -------------------------->


    <!---------------------------------------------------------------------------------------------------------------->
    <!-------------------------- BLOC POUR LA ZONE DE LA RECHERCHE DES ETUDIANT ---------------------------------------->
    <!---------------------------------------------------------------------------------------------------------------->
  
    <div class="sales-boxes m-0 p-0 mt-3">
      <div class="recent-sales box" style=" width:100%; margin:0px; ">

          <div class="input-group p-2 border rounded"
            style="color:white;">
            
            <span class="input-group-text p-0 border-0 font-weight-bold" 
                style="background-color:#273746;color:white;">Recherche étudiant ... </span>

            <input id="txt_recherch_etudiant_1" type="text" 
            class="form-control p-0 ps-2 fw-bolder text-s border-0" 
                                aria-label="Saisir en franc congolais"
                                style="background-color:#273746;color:white; font-weight:bold;">
            
          </div>
      </div>
    </div>


    <!----------------------------------FIN DU BLOC ZONE DE LA RECHERCHE ------------------------------ -------------------------->


    


    <form action="">
      <input type="hidden"  id="mat_etudiant_transact" value=""> 
    </form>

    <!--------------------------------- CE BLOC CONCERNE L'AFFICHAGE DES ETUDIANT ET EFFECTUER LES OPERATIOS ------------------------------ -------------------------->

    <div class="sales-boxes m-0 p-3 mt-3 border" 
        style="background-color:rgb(39,55,70); height:400px;">

        <!---------------------------------------------------------------------------------------------------------------->
        <!-------------------------- BLOC POUR LE TABLEAU D'AFFICHAGE DES ETUDIANTS ---------------------------------------->
        <!---------------------------------------------------------------------------------------------------------------->
      
        <div class="container table-responsive small p-0 m-0" 
          style="width:48%; float:left; height:100%; overflow-y:auto; overflow-x:auto;" >

          <table  class="tab1" id="table_etudiant1" style="width:100%; height:100%;">              
            <thead class="sticky-sm-top m-0 fw-bold" style="background-color:midnightblue; color:white;">
              <tr>
                <th style="border: none; padding: 8px;">N°</th>
                <th style="border: none; padding: 8px;">Matricule</th>
                <th style="border: none; padding: 8px;">Nom</th>
                <th style="border: none; padding: 8px;">Postnom</th>
                <th style="border: none; padding: 8px;">Prenom</th>
                <th style="border: none; padding: 8px;">Sexe</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
        <!----------------------------------FIN DU BLOC ZONE DE LA RECHERCHE ------------------------------ -------------------------->

        <!---------------------------------------------------------------------------------------------------------------->
        <!-------------------------- Bloc deuxieme tableau afichage operations ------------------------------------------->
        <!---------------------------------------------------------------------------------------------------------------->
      
        <div class="container table-responsive small p-0 m-0" 
          style="width:51%; float:right; height:100%; overflow-y:auto; overflow-x:auto;" >

          <table  class="tab1" id="table_transact" style="width:100%; height:100%;">              
            <thead class="sticky-sm-top m-0 fw-bold" style="background-color:midnightblue; color:white;">
              <tr>
                <th style="border: none; padding: 8px;">N°</th>
                <th style="border: none; padding: 8px;">Date Transact</th>
                <th style="border: none; padding: 8px;">Motifs Transact</th>
                <th style="border: none; padding: 8px;">Montant</th>
                <th style="border: none; padding: 8px;">Lieu Paie</th>
                <th style="border: none; padding: 8px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
        <!----------------------------------FIN DU BLOC ZONE DE TABLEAU TRANSACT------------------------------ -------------------------->
      

    
    </div>
    <!--------------------------------- FIN BLOC DE TABLEAU------------------------------ -------------------------->

  </div>
</section>
    






<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    Une boite pour afficher un message de confirmation d'enregistrement ou d'échec ------>

<dialog id="boite_alert_Transactions" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Message (U.KA. @ CIUKA )</h5>
      <button type="button" class="close ms-3" onclick="Fermer_Boite_Transactions()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    
    <div class="modal-body">
      <h5 class="modal-title  text-center" id="text_alert_transaction">Connexion Réussier</h5>
    </div>
  </div>
</dialog>



<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    Une boite pour afficher une confirmation d'action ( suppression ou modification ) ------>

<dialog id="boite_confirmaion_Transactions" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Confirmation (U.KA. @ CIUKA )</h5>
      <!--button type="button" class="close ms-3" onclick="Confirmation_SM_UE_NON()">
        <span aria-hidden="true">&times;</span>
      </button-->
    </div>
    
    <div class="modal-body">
      <h7 class="modal-title  text-center" id="text_confirm_afficher_transaction">Connexion Réussier</h7>
      
    </div>


    <div class="modal-footer p-0 m-0">

      <div class="container">

        <div class="row  ">

          <div class="col text-center">
            <button type="button" id="btn_action_oui" class="btn btn-primary"
            style="width:100%;">OUI </button>
          </div>

          <div class="col text-center">
            <button type="button" id="btn_action_non" class="btn btn-primary"
            style="width:100%;">NON</button>
          </div>

        </div>
      </div>        
    </div>


  </div>
</dialog>
       