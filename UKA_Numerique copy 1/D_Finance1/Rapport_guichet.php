 <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f6f9;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px 40px;
        }
        h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
        .form-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .form-group {
            flex: 1 1 250px;
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #444;
        }
        .form-group select,
        .form-group input[type="date"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .results {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .column {
            flex: 1 1 48%;
        }
        .box {
            background: #e9f7ef;
            border-left: 5px solid #28a745;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .box.usd {
            background: #e8f0fe;
            border-left: 5px solid #1e88e5;
        }
        .box h4 {
            margin: 0;
            color: #155724;
        }
        .box.usd h4 {
            color: #0d47a1;
        }
        .total-box {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 20px;
            font-size: 18px;
            color: #856404;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Rapport des Recettes par Agent</h2>

        <div class="form-wrapper">
           

            <div class="form-group">
                <label for="annee">Année académique :</label>
                <select id="annee">
                    <?php 
                            $reponse = $con->query ('SELECT * FROM annee_academique order by Annee_debut desc' );
                                while ($ligne = $reponse->fetch()) {?>
                            <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="agent">Agent :</label>
                <select id="agent">
                    
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date :</label>
                <input type="date" id="dateperc" />
            </div>
        </div>

        
<div class="results">
    <div class="column column-cdf"></div>
    <div class="column column-usd"></div>
</div>

<div class="total-box text-center">
    Total perçu : 0 CDF + 0 $
</div>
    </div>
    <script>
    
    </script>
<script src="D_Finance/js/Rapport_Guichet.js"></script>
