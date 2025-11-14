
console.log('nous sommes dans le js update_profil_user');  

document.getElementById('profil').addEventListener('click', function() {
    document.getElementById('nouveauForm').style.display = 'block';
    document.getElementById('fondTransparent').style.display = 'block';
    
  });

  //affichage image après son chargement
 
        function Apercu_Image(event)
            {
                console.log("dans la fonction");
                var reader = new FileReader();
                reader.onload = function () {
                                var preview = document.getElementById('preview');
                                preview.src = reader.result;
                                preview.style.display = 'block';
                    }
                reader.readAsDataURL(event.target.files[0]);
            }
