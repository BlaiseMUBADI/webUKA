<!-- Sale & Revenue Start -->
            <center><div class="container-fluid pt-4 px-4" id="block1_menu_comptabilte" style="display: block;">
                <div class="row g-4">
                    
                    <div class="col-sm-6 col-xl-12">
                        <button style=" border-radius:10px 0px 0px 0px; " id="bouton1" onclick="accueil()">
                            <i class="fa fa-home" style="color:green;"></i>
                            <div>&nbsp Accueil &nbsp</div>
                        </button>
                        <button style="" id="bouton1" onclick="affichage_budget1()">
                            <i class="fa fa-wallet" style="color:#add8e6;"></i>
                            <div>&nbsp Budget &nbsp</div>
                        </button>
                        <button style="" id="bouton1" onclick="affichage_resultat()">
                            <i class="fa fa-chart-line" style="color: red;"></i>
                            <div>&nbsp Résultats &nbsp</div>
                        </button>
                        <button style="" id="bouton1">
                            <i class="fa fa-file-invoice-dollar" style="color:blue;"></i>
                            <div>&nbsp Balance &nbsp</div>
                        </button>
                        <button style="" id="bouton1">
                            <i class="fa fa-credit-card" style="color:red;"></i>
                            <div>&nbsp grand livre &nbsp</div>
                        </button>
                        <button style="" id="bouton1">
                            <i class="fa fa fa-search" style="color:green;"></i>
                            <div>&nbsp  recherche &nbsp</div>
                        </button>
                        <button style="" id="bouton1" onclick="affichage_compte()">
                            <i class="fa fa-piggy-bank" style="color:gold;" ></i>
                            <div>&nbsp Comptes &nbsp</div>
                        </button>
                       
                        <button style="" id="bouton1">
                            <i class="fa fa-shopping-cart" style="color:#f08080;"></i>
                            <div>&nbsp Rapprochement &nbsp</div>
                        </button>
                        <button style=" border-radius:0px 10px 0px 0px; " id="bouton1">
                            <i class="fa fa-cogs" style="color:green;"></i>
                            <div> Parametre </div>
                        </button>
                    </div>
                    
                </div>
            </div>
            </center>
            <!-- Sale & Revenue End -->
      
                <?php
                    include("D_Comptable_ERP/budget.php");
                    include("D_Comptable_ERP/compte.php");
                    include("D_Comptable_ERP/resultat.php");
              
                
                    
                    
                ?>
