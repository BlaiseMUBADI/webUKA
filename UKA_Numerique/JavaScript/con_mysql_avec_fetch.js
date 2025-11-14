

  
  var table= document.getElementById("table"),rIndex;
  for(var i =1; i< table.rows.length;i++)
  {
      table.rows[i].onclick= function()
      {
          rIndex= this.rowIndex;
          var mat_etudiant=this.cells[0].innerHTML;
          /*console.log(rIndex);
          console.log("Blaise") ;*/
          document.getElementById("txtMatricule").value= this.cells[0].innerHTML;
          
          const url='/perception_UKA/JavaScript/Recup_situation_paie_etudiant.php?matricule='+mat_etudiant;
          
          fetch(url) 
          .then(response => response.json())
          .then(data => {
            data.forEach(infos => {
              console.log("Nom = "+infos.Nom);
            });
          })
          .catch(error => console.error('Erreur lors de la récupération des articles :', error));


          /*
          xhr.open('GET',url,true);
          xhr.onload=function()
          {
            if(xhr.status===200)
            {
              // On recupere les infos de JSON
              const somm=JSON.parse(xhr.responseText);
              somm.forEach(element => {
              
              const Nom=element.Nom;
              //.getElementById("blaise").value=element.Nom.innerHTML;
              document.getElementById("blaise").value=Nom;
              /*
              const ele =document.getElementById("txtPostnom");
              ele.innerText="Blaise";//element.Nom;
              console.log(" Voci l'étudiant selecionner "+element.Nom) ;
              
              //.getElementById("txtPostnom").value= row.Postnom.innerHTML;
              
              //document.getElementById("txtPrenom").value= this.Prenom.innerHTML;
              });
            }
          }
          xhr.send();*/

}
}
           