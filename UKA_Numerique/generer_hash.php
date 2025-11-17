<?php
/**
 * Script pour générer des hash de mots de passe
 * Utilise password_hash() avec BCRYPT (même méthode que password_verify)
 */

// Le mot de passe à hasher
$motdepasse = " "; // Un espace

// Générer le hash avec password_hash (BCRYPT par défaut)
//$hash_bcrypt = password_hash($motdepasse, PASSWORD_DEFAULT);
$hash_bcrypt=password_hash($motdepasse,PASSWORD_BCRYPT);

// Générer aussi le SHA1 pour comparaison
$hash_sha1 = sha1($motdepasse);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de Hash</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 10px;
        }
        .result {
            background: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .label {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .hash {
            font-family: 'Courier New', monospace;
            background: #1e293b;
            color: #10b981;
            padding: 10px;
            border-radius: 5px;
            word-break: break-all;
            margin-top: 5px;
        }
        .info {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        code {
            background: #e5e7eb;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Générateur de Hash de Mot de Passe</h1>
        
        <div class="info">
            <strong>⚠️ Information:</strong> Ce script génère le hash d'un espace (" ") avec <code>password_hash()</code>
        </div>

        <div class="result">
            <div class="label">📝 Mot de passe original:</div>
            <div class="hash">[<?php echo $motdepasse; ?>] (un espace)</div>
        </div>

        <div class="result">
            <div class="label">🔑 Hash BCRYPT (password_hash):</div>
            <div class="hash"><?php echo $hash_bcrypt; ?></div>
        </div>

        <div class="result">
            <div class="label">🔒 Hash SHA1 (ancienne méthode):</div>
            <div class="hash"><?php echo $hash_sha1; ?></div>
        </div>

        <div class="success">
            <strong>✅ Vérification:</strong><br>
            <?php
            // Tester password_verify
            if (password_verify($motdepasse, $hash_bcrypt)) {
                echo "✓ password_verify() fonctionne avec ce hash<br>";
            } else {
                echo "✗ password_verify() NE fonctionne PAS<br>";
            }
            
            // Tester SHA1
            if (sha1($motdepasse) === $hash_sha1) {
                echo "✓ SHA1 correspond";
            }
            ?>
        </div>

        <div class="info">
            <strong>📋 Pour mettre à jour la base de données:</strong><br><br>
            <code style="display: block; white-space: pre-wrap; background: #1e293b; color: #10b981; padding: 15px; border-radius: 5px; margin-top: 10px;">UPDATE compte_agent 
SET Mot_passe = '<?php echo $hash_bcrypt; ?>' 
WHERE Login = 'votre_login';</code>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">

        <h2>🛠️ Générer un nouveau hash</h2>
        <form method="POST" action="">
            <p>
                <label for="nouveau_mdp" style="display: block; margin-bottom: 5px; font-weight: bold;">Nouveau mot de passe:</label>
                <input type="text" 
                       id="nouveau_mdp" 
                       name="nouveau_mdp" 
                       style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 5px; font-size: 16px;"
                       placeholder="Entrez le mot de passe à hasher">
            </p>
            <p>
                <button type="submit" 
                        name="generer" 
                        style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; font-weight: bold;">
                    Générer le Hash
                </button>
            </p>
        </form>

        <?php
        if (isset($_POST['generer']) && !empty($_POST['nouveau_mdp'])) {
            $nouveau_mdp = $_POST['nouveau_mdp'];
            $nouveau_hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
            $nouveau_sha1 = sha1($nouveau_mdp);
            
            echo '<div class="result">';
            echo '<div class="label">📝 Mot de passe:</div>';
            echo '<div class="hash">[' . htmlspecialchars($nouveau_mdp) . ']</div>';
            echo '</div>';
            
            echo '<div class="result">';
            echo '<div class="label">🔑 Hash BCRYPT:</div>';
            echo '<div class="hash">' . $nouveau_hash . '</div>';
            echo '</div>';
            
            echo '<div class="result">';
            echo '<div class="label">🔒 Hash SHA1:</div>';
            echo '<div class="hash">' . $nouveau_sha1 . '</div>';
            echo '</div>';
            
            echo '<div class="info">';
            echo '<strong>📋 Requête SQL:</strong><br><br>';
            echo '<code style="display: block; white-space: pre-wrap; background: #1e293b; color: #10b981; padding: 15px; border-radius: 5px; margin-top: 10px;">';
            echo "UPDATE compte_agent \nSET Mot_passe = '" . $nouveau_hash . "' \nWHERE Login = 'votre_login';";
            echo '</code>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
