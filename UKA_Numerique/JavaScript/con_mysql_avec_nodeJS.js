
const mysql = require('mysql');

// on recupere les donn�es du tabeau dans les lignes

const connection = mysql.createConnection({
     host: 'localhost',
     user: 'root',
     password: '',
     database: 'bdd_uka'
    });


    connection.connect((err) =>
    {
      if(err) throw err 
      console.log("La connexion a reussie") 
    })

    // Ici on récupere le matricule de l'étudiant dans la base de donnée
    var mat_etudiant="00045 E/12/KAN";

    // Requête de sélection des articles dans la table
    const sql = "SELECT * FROM Etudiant where Matricule=?";// where Matricule= ?
    
// Exécuter la requête
connection.query(sql,[mat_etudiant], (error, results) => {
    if (error) {
      console.error('Erreur lors de l\'exécution de la requête :', error);
      return;
    }
    
    // Parcours des données avec une boucle
    for (const row of results) {
      const Nom = row.Nom;
      const Postnom= row.Postnom;
      const Prenom = row.Prenom;
      
      // Stocker les données dans des variables ou faire autre chose avec elles
      console.log(`Nom : ${Nom}, Postnom : ${Postnom}, Sexe : ${Prenom}`);
    }
  });


    

  
 connection.end();