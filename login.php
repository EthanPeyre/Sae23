<?php
require_once 'config.php';

// Redirection si déjà connecté
if (isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = isset($_POST['login']) ? $_POST['login'] : '';
    $pass = isset($_POST['pass']) ? $_POST['pass'] : '';

    // 1. Vérifier si c'est un administrateur
    $stmt_admin = mysqli_prepare($conn, "SELECT login, mot_de_passe FROM Administrateur WHERE login = ?");
    if ($stmt_admin) {
        mysqli_stmt_bind_param($stmt_admin, 's', $login);
        mysqli_stmt_execute($stmt_admin);
        $result_admin = mysqli_stmt_get_result($stmt_admin);
        if ($admin = mysqli_fetch_assoc($result_admin)) {
            if (md5($pass) === $admin['mot_de_passe']) {
                $_SESSION['login'] = $admin['login'];
                $_SESSION['login_type'] = 'admin';
                header('Location: admin.php');
                exit();
            }
        }
    }

    // 2. Si ce n'est pas un admin, vérifier si c'est un gestionnaire
    $stmt_gest = mysqli_prepare($conn, "SELECT id, login, mot_de_passe, id_batiment FROM gestionnaires WHERE login = ?");
    if ($stmt_gest) {
        mysqli_stmt_bind_param($stmt_gest, 's', $login);
        mysqli_stmt_execute($stmt_gest);
        $result_gest = mysqli_stmt_get_result($stmt_gest);
        if ($gestionnaire = mysqli_fetch_assoc($result_gest)) {
            
            // ====================================================================
            // MODIFICATION CLÉ : On utilise md5() au lieu de password_verify()
            // ====================================================================
            if (md5($pass) === $gestionnaire['mot_de_passe']) {
                $_SESSION['login'] = $gestionnaire['login'];
                $_SESSION['login_type'] = 'gestionnaire';
                $_SESSION['gestionnaire_id'] = $gestionnaire['id'];
                $_SESSION['building_id'] = $gestionnaire['id_batiment'];
                header('Location: gestion.php');
                exit();
            }
        }
    } else {
        die("Erreur critique : Impossible de préparer la requête pour les gestionnaires. La table 'gestionnaires' existe-t-elle bien ?");
    }
    
    // Si aucune correspondance n'a été trouvée
    $error_message = 'Identifiants incorrects.';
}

include 'header.php';
?>

<h1>Connexion</h1>
<div style="max-width: 500px; margin: 2rem auto;">
    <div class="panel-wrapper">
        <div class="panel-title">Veuillez vous identifier</div>
        <form method="POST" action="login.php" style="padding: 2rem;">
            <?php if ($error_message): ?>
                <p style="color: #e74c3c; margin-bottom: 1rem;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <div style="margin-bottom: 1rem;">
                <label for="login" style="display: block; margin-bottom: 0.5rem; color: #ecf0f1;">Identifiant :</label>
                <input type="text" name="login" id="login" required style="width: 100%; padding: 0.5rem; color: black;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label for="pass" style="display: block; margin-bottom: 0.5rem; color: #ecf0f1;">Mot de passe :</label>
                <input type="password" name="pass" id="pass" required style="width: 100%; padding: 0.5rem; color: black;">
            </div>
            <button type="submit" style="width: 100%; padding: 0.75rem; border: none; background-color: #00f2fe; color: #2d3748; font-weight: bold; cursor: pointer; border-radius: 5px;">
                Se connecter
            </button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>