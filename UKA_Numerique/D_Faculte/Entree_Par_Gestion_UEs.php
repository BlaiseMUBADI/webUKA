

<section class="home-section" style="height: 100%;">
      <?php
        require_once 'Profil_Gestion_delibe.php';
      ?>
  <div class="home-content me-3 ms-3"   id="div_gen_UE">


    <!----------------------------------------------------------------------------------------------->
    <!------------------ CE BLOC CONCERNE L'AFFICHAGE DES UES et ECS -------------------------------->
    <!----------------------------------------------------------------------------------------------->

    <div class="sales-boxes m-0 p-3 mt-3 border" 
          style="background-color:rgb(39,55,70);">

      <div class="container p-0 m-0" 
          style="width: 48%; float: left; height:650px;">
        
        <div class="table-responsive small p-0 m-0" style="width: 100%; height:590px; overflow-y:auto; overflow-x:auto;">
          <table  class="tab1 table-hover table-striped" id="table_ues" style="width:100%; border-collapse: collapse;">              
            <thead>
              <tr style="border-bottom: 2px solid white;">
                <th style="border: none; ">N°</th>
                <th style="border: none; ">Code UE</th>
                <th style="border: none; ">Nom UE</th>
                <th style="border: none; ">Categorie</th>
                <th style="border: none; ">Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

        <div class="container p-0 m-0 mt-2">        
          <div class="d-grid gap-1 p-0 m-0">
            <button id="btn_ajout_ue" class="btn btn-primary p-1 m-0 font-weight-bold"
                type="button" onclick="Ouvrir_Form_UEs()">Ajouter une Unité d'Enseignement
            </button>
          </div> 
        </div>
      </div>
      <!-------------------------------Fin bloc affichage UE  ---------------------------------------------->
    
    
    
    
      <div class="container p-0 m-0" 
          style="width: 48%; float: right; height:650px;">
        
        <div class="table-responsive small p-0 m-0" 
            style="width: 100%; height:590px; overflow-y:auto; overflow-x:auto;">        
          <table  class="tab1 table-hover table-striped text-center" id="table_ecs" style="width:100%; border-collapse: collapse;">              
            <thead class="sticky-sm-top m-0 fw-bold" style="background-color:midnightblue; color:white;">
              <tr style="border-bottom: 2px solid white;">
                <th style="border: none; padding: 8px;">N°</th>
                <th style="border: none; padding: 8px;">Nom E.C.</th>
                <th style="border: none; padding: 8px;">CMI</th>
                <th style="border: none; padding: 8px;">TD</th>
                <th style="border: none; padding: 8px;">TP</th>
                <th style="border: none; padding: 8px;">TPE</th>
                <th style="border: none; padding: 8px;">VHT</th>
                <th style="border: none; padding: 8px;">Crédit</th>
                <th style="border: none; padding: 8px;">Action</th>
              </tr>
            </thead>
            
            <tbody>              
            </tbody>
          </table>
        </div>

        <div class="container p-0 m-0 mt-2">        
          <div class="d-grid gap-1 p-0 m-0">
            <button id="btn_ajout_ec" class="btn btn-primary p-1 m-0 font-weight-bold"
                type="button" onclick="Ouvrir_Form_EC()">Ajouter un EC
            </button>
          </div>
        </div>
      </div>
      <!-------------------------------Fin bloc affichage EC  ---------------------------------------------->  
    </div>
    <!-------------------------------Fin bloc affichage SM et UEs ---------------------------------------------->
  </div>

</section>





<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    La boite qui permet d'afficher un formulaire ------------------------------>

<dialog id="boite_Form_UE" 
  style="border: none; border-radius: 20px; padding: 0; box-shadow: 0 10px 40px rgba(0,0,0,0.3); max-width: 550px; animation: slideDown 0.3s ease-out;">
  
  <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
    <h5 style="margin: 0; color: white; font-weight: 600; display: flex; align-items: center; gap: 10px;">
      <i class="fas fa-graduation-cap"></i>
      Ajouter des UEs
    </h5>
    <button type="button" onclick="Fermer_Form_UE()" 
      style="background: rgba(255,255,255,0.2); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center; transition: all 0.3s;"
      onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='rotate(90deg)'"
      onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='rotate(0deg)'">
      <span>&times;</span>
    </button>
  </div>
  
  <div style="background: white; padding: 30px; border-radius: 0 0 20px 20px;">
    <form>
      <div class="form-group">
        <div style="margin-bottom: 20px;">
          <label for="txt_code_ue" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
            <i class="fas fa-tag" style="color: #667eea; margin-right: 8px;"></i>Code UE
          </label>
          <input id="txt_code_ue" type="text" class="form-control" 
            placeholder="Math01"
            style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 14px; transition: all 0.3s; width: 100%;"
            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
        </div>

        <div style="margin-bottom: 20px;">
          <label for="txt_libelle_ue" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
            <i class="fas fa-book" style="color: #667eea; margin-right: 8px;"></i>Intitulé UE
          </label>
          <input id="txt_libelle_ue" type="text" class="form-control" 
            placeholder="Mathématique"
            style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 14px; transition: all 0.3s; width: 100%;"
            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
        </div>

        <div style="margin-bottom: 0;">
          <label for="categorie_ue" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
            <i class="fas fa-folder-open" style="color: #667eea; margin-right: 8px;"></i>Catégorie UE
          </label>
          <select id="categorie_ue" class="form-control"
            style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 14px; transition: all 0.3s; width: 100%; cursor: pointer;"
            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
            <option value="rien" selected>Selection Catégorie</option>
            <option value="pratique">UE pratique</option>
            <option value="magistral">UE magistral</option>
          </select>
        </div>
      </div>
    </form>
    
    <button type="button" onclick="Ajout_UE()" 
      style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 14px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; margin-top: 25px; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102,126,234,0.4);"
      onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(102,126,234,0.5)'"
      onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102,126,234,0.4)'">
      <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Valider
    </button>
  </div>
</dialog>




<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    La boite qui permet d'afficher un formulaire pour la saisie de donnée ----------------------------->

<dialog id="boite_Form_EC" 
  style="border: none; border-radius: 20px; padding: 0; box-shadow: 0 10px 40px rgba(0,0,0,0.3); max-width: 600px; animation: slideDown 0.3s ease-out;">
  
  <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
    <h5 style="margin: 0; color: white; font-weight: 600; display: flex; align-items: center; gap: 10px;">
      <i class="fas fa-book-open"></i>
      Ajouter des ECs
    </h5>
    <button type="button" onclick="Fermer_Form_EC()" 
      style="background: rgba(255,255,255,0.2); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center; transition: all 0.3s;"
      onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='rotate(90deg)'"
      onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='rotate(0deg)'">
      <span>&times;</span>
    </button>
  </div>
  
  <div style="background: white; padding: 30px; border-radius: 0 0 20px 20px; max-height: 70vh; overflow-y: auto;">
    <form>
      <div class="form-group">
        <div style="margin-bottom: 20px;">
          <label for="txt_nom_ec" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
            <i class="fas fa-pencil-alt" style="color: #667eea; margin-right: 8px;"></i>Nom EC
          </label>
          <input id="txt_nom_ec" type="text" class="form-control" 
            placeholder="Math01"
            style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 14px; transition: all 0.3s; width: 100%;"
            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
        </div>

        <div style="margin-bottom: 20px;">
          <label for="txt_nb_credit" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
            <i class="fas fa-award" style="color: #667eea; margin-right: 8px;"></i>NB. Crédit
          </label>
          <input id="txt_nb_credit" type="number" class="form-control" 
            placeholder="5"
            style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 14px; transition: all 0.3s; width: 100%;"
            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
        </div>

        <div style="background: #f7fafc; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
          <h6 style="color: #667eea; font-weight: 600; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-clock"></i>
            Volume Horaire
          </h6>
          
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
              <label for="txt_cmi" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 13px;">
                CMI
              </label>
              <input id="txt_cmi" type="number" class="form-control" 
                placeholder="5"
                style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 10px; font-size: 14px; transition: all 0.3s; width: 100%;"
                onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
            </div>

            <div>
              <label for="txt_hr_td" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 13px;">
                NB. HR. TD
              </label>
              <input id="txt_hr_td" type="number" class="form-control" 
                placeholder="20"
                style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 10px; font-size: 14px; transition: all 0.3s; width: 100%;"
                onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
            </div>

            <div>
              <label for="txt_hr_tp" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 13px;">
                NB. HR. TP
              </label>
              <input id="txt_hr_tp" type="number" class="form-control" 
                placeholder="20"
                style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 10px; font-size: 14px; transition: all 0.3s; width: 100%;"
                onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
            </div>

            <div>
              <label for="txt_tpe" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 13px;">
                NB. HR. TPE
              </label>
              <input id="txt_tpe" type="number" class="form-control" 
                placeholder="20"
                style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 10px; font-size: 14px; transition: all 0.3s; width: 100%;"
                onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
            </div>
          </div>
        </div>

        <div style="margin-bottom: 0;">
          <label for="txt_vht" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
            <i class="fas fa-calculator" style="color: #667eea; margin-right: 8px;"></i>NB. HR. VHT
          </label>
          <input id="txt_vht" type="number" class="form-control" 
            placeholder="20"
            style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 14px; transition: all 0.3s; width: 100%;"
            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
        </div>
      </div>
    </form>
    
    <button type="button" onclick="Ajout_EC()" 
      style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 14px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; margin-top: 25px; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102,126,234,0.4);"
      onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(102,126,234,0.5)'"
      onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102,126,234,0.4)'">
      <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Valider
    </button>
  </div>
</dialog>





<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    Une boite pour afficher un message de confirmation d'enregistrement ou d'échec ------>

<dialog id="boite_alert_SM_UE" 
  style="border: none; border-radius: 20px; padding: 0; box-shadow: 0 10px 40px rgba(0,0,0,0.3); max-width: 500px; animation: slideDown 0.3s ease-out;">
  
  <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
    <h5 style="margin: 0; color: white; font-weight: 600; display: flex; align-items: center; gap: 10px;">
      <i class="fas fa-info-circle"></i>
      Message (U.KA. @ CIUKA)
    </h5>
    <button type="button" onclick="Fermer_Boite_Alert_SM_UE()" 
      style="background: rgba(255,255,255,0.2); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center; transition: all 0.3s;"
      onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='rotate(90deg)'"
      onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='rotate(0deg)'">
      <span>&times;</span>
    </button>
  </div>
  
  <div style="background: white; padding: 40px 30px; border-radius: 0 0 20px 20px; text-align: center;">
    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 4px 15px rgba(102,126,234,0.3);">
      <i class="fas fa-bell" style="color: white; font-size: 32px;"></i>
    </div>
    <h5 id="text_alert_boite" style="color: #2d3748; font-weight: 600; font-size: 18px; line-height: 1.6; margin: 0;">Connexion Réussie</h5>
  </div>
</dialog>



<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    Une boite pour afficher une confirmation d'action ( suppression ou modification ) ------>

<dialog id="boite_confirmaion_action_SM_UE" 
  style="border: none; border-radius: 20px; padding: 0; box-shadow: 0 10px 40px rgba(0,0,0,0.3); max-width: 500px; animation: slideDown 0.3s ease-out;">
  
  <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
    <h5 style="margin: 0; color: white; font-weight: 600; display: flex; align-items: center; gap: 10px;">
      <i class="fas fa-exclamation-triangle"></i>
      Confirmation (U.KA. @ CIUKA)
    </h5>
  </div>
  
  <div style="background: white; padding: 40px 30px; border-radius: 0 0 20px 20px; text-align: center;">
    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 4px 15px rgba(245,87,108,0.3);">
      <i class="fas fa-question" style="color: white; font-size: 32px;"></i>
    </div>
    
    <h6 id="text_confirm_afficher" style="color: #2d3748; font-weight: 600; font-size: 16px; line-height: 1.6; margin: 0 0 30px 0;">Connexion Réussie</h6>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
      <button type="button" id="btn_action_oui" 
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102,126,234,0.3);"
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(102,126,234,0.4)'"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102,126,234,0.3)'">
        <i class="fas fa-check" style="margin-right: 6px;"></i>OUI
      </button>
      
      <button type="button" id="btn_action_non" 
        style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none; padding: 12px; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(245,87,108,0.3);"
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(245,87,108,0.4)'"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(245,87,108,0.3)'">
        <i class="fas fa-times" style="margin-right: 6px;"></i>NON
      </button>
    </div>
  </div>
</dialog>



    
       