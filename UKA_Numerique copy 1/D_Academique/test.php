<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numérisation de Document</title>
    <link rel="stylesheet" href="code_css_scan.css">
    <script src="js/jspdf.umd.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Numériser un Document</h1>
        <input type="file" id="fileInput" accept="image/*">
        <button id="convertBtn">Convertir en PDF</button>
        <a id="downloadLink" style="display: none;">Télécharger le PDF</a>
    </div>
    <script src="js/js_script.js"></script>
</body>
</html>
