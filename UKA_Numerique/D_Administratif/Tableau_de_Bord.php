    
    <style>
.container-fluid {
    display: flex;
    flex-wrap: wrap;
}

.col-sm-6, .col-xl-3 {
    flex: 1 1 auto;
    min-width: 230px; /* Ajustez la largeur minimale selon votre besoin */
    max-width: 10%;
    
}

.bg-light {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    border-radius: 8px;
    background-color:rgb(214, 214, 242)!important;
    height: 100px;
}
#graphContainer {
            width: 80%;
            max-width: 600px;
            margin: auto;
        }

        #pieChart {
            width: 100%;
            height: auto;
        }

    </style>


<script type="text/javascript" src="D_Administratif/js/chart.js"></script>
    
    <section class="home-section " style="height: auto;">
        
        <?php require_once 'D_Generale/Profil_Sec_Administratif.php'; ?>

        <div class="home-content me-3 ms-3 "id=""style="height:auto;"  >
            <div class="sales-boxes m-0 p-0 " >
                <div class="recent-sales box " style="width:100%; margin:0px;">
                    
            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">             
                        <p class="mb-2">Personnel Academique</p>
                        <h2 class="mb-0 compteur" id="academicPersonnel" data-target=""></h2>
                    </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Personnel Scientifique</p>
                        <h2 class="mb-0 compteur" id="scientificPersonnel" data-target="1200">0</h2>
                    </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Personnel Administratif</p>
                        <h2 class="mb-0 compteur" id="PersonnelAdmin" data-target="800">0</h2>
                    </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">PTO</p>
                        <h2 class="mb-0 compteur" id="PersonnelPato" data-target="450">0</h2>
                    </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Agent</p>
                        <h2 class="mb-0 compteur" id="total" data-target="100"></h2>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->
     
           

            <div id="graphContainer">
        <canvas id="pieChart"></canvas>
    </div>
            
    <script>
       
    </script>

                    </div>
                </div>
            </div>
        </div>
      
    </section>
    
