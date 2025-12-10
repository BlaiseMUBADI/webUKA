

  <section class="home-section" style="height: auto;">
        <?php
          require_once 'D_Generale/Profil_Sec_Administratif.php';
        ?>

      <div class="home-content me-3 ms-3 "id="Ajouter_evenement_au_calendrier"style="height:auto;"  >
        <div class="sales-boxes m-0 p-0 " >
          <div class="recent-sales box " style="width:100%; margin:0px;">
            
            <div class=" wrapper login-2 " id="Calendrier">
                <div class="containe">
                    <div class="col-left">
                        <div class="login-form">
                            <h2>Evenements</h2>
                            <form>
                              
                                <p>
                                  <input type="text" id="titre" placeholder="Titre " required>
                                </p>
                                <p>
                                  <input type="text" id="descrtiption" placeholder="Détails " required>
                                </p>
                                Date-début :
                                <p>
                                  <input type="Date" id="date-deb" placeholder=" " required>
                                </p>
                                Date-fin :
                                <p>
                                 <input type="Date" id="date-fi" placeholder=" " required>
                                </p>
                                
                                <p>
                                   <button type="button" id="Save_Events" title="" class="btn btn-primary mb-3" style="font-family:Perpetua; color:rgb(29, 29, 108);font-size:20px;"><i class="fas fa-plus"></i> Enregistrer</button>
                                </p>
                            
                            </form>
                        </div>
                    </div>
                       
                    <div class='col-right'>               
                        <div id='calendrier'></div>              
                    </div>
                </div>           
            </div>
        </div>
    </div>
</div>
  </section>
    
       



