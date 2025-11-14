document.getElementById('convertBtn').addEventListener('click', function() {
    const fileInput = document.getElementById('fileInput');
    const downloadLink = document.getElementById('downloadLink');

    if (fileInput.files.length === 0) {
        alert('Veuillez sélectionner une image.');
        return;
    }

    const file = fileInput.files[0];
    const reader = new FileReader();

    reader.onload = function(event) {
        const img = new Image();
        img.onload = function() {
            const { jsPDF } = window.jspdf; // Accès à jsPDF
            const pdf = new jsPDF();
            pdf.addImage(img, 'JPEG', 0, 0, 210, 297); // Taille A4 en mm
            const pdfBlob = pdf.output('blob');

            // Créer un lien de téléchargement
            const url = URL.createObjectURL(pdfBlob);
            downloadLink.href = url;
            downloadLink.download = 'document.pdf';
            downloadLink.style.display = 'block';
            downloadLink.textContent = 'Télécharger le PDF';
        };
        img.src = event.target.result;
    };

    reader.readAsDataURL(file);
});
