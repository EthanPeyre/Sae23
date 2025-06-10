<?php
require_once 'config.php';

// --- SÉCURITÉ ---
if (!isset($_SESSION['login_type']) || $_SESSION['login_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// --- TRAITEMENT DES FORMULAIRES (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // -- ACTION : AJOUTER UN BÂTIMENT --
    if ($action === 'add_building' && !empty($_POST['nom'])) {
        $stmt = mysqli_prepare($conn, "INSERT INTO Batiment (nom) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $_POST['nom']);
        mysqli_stmt_execute($stmt);
    }

    // -- ACTION : SUPPRIMER UN BÂTIMENT --
    // MODIFICATION : Utilise 'id' au lieu de 'id_bat'
    elseif ($action === 'delete_building' && !empty($_POST['id'])) {
        $stmt = mysqli_prepare($conn, "DELETE FROM Batiment WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $_POST['id']);
        mysqli_stmt_execute($stmt);
    }
    
    // -- ACTION : AJOUTER UNE SALLE --
    elseif ($action === 'add_room' && !empty($_POST['nom']) && !empty($_POST['id_batiment'])) {
        $stmt = mysqli_prepare($conn, "INSERT INTO Salle (nom, id_batiment, type, capacite) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sisi", $_POST['nom'], $_POST['id_batiment'], $_POST['type'], $_POST['capacite']);
        mysqli_stmt_execute($stmt);
    }

    // -- ACTION : SUPPRIMER UNE SALLE --
    elseif ($action === 'delete_room' && !empty($_POST['nom'])) {
        $stmt = mysqli_prepare($conn, "DELETE FROM Salle WHERE nom = ?");
        mysqli_stmt_bind_param($stmt, "s", $_POST['nom']);
        mysqli_stmt_execute($stmt);
    }

    // -- ACTION : AJOUTER UN CAPTEUR --
    elseif ($action === 'add_sensor' && !empty($_POST['nom']) && !empty($_POST['id_salle'])) {
        $stmt = mysqli_prepare($conn, "INSERT INTO Capteur (nom, type, unite, id_salle) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $_POST['nom'], $_POST['type'], $_POST['unite'], $_POST['id_salle']);
        mysqli_stmt_execute($stmt);
    }

    // -- ACTION : SUPPRIMER UN CAPTEUR --
    elseif ($action === 'delete_sensor' && !empty($_POST['nom'])) {
        $stmt = mysqli_prepare($conn, "DELETE FROM Capteur WHERE nom = ?");
        mysqli_stmt_bind_param($stmt, "s", $_POST['nom']);
        mysqli_stmt_execute($stmt);
    }

    header('Location: admin.php');
    exit();
}

// --- RÉCUPÉRATION DES DONNÉES POUR L'AFFICHAGE ---
$buildings_result = mysqli_query($conn, "SELECT * FROM Batiment ORDER BY nom");

// MODIFICATION : La jointure se fait sur b.id au lieu de b.id_bat
$rooms_query = "SELECT s.nom, s.type, s.capacite, b.nom as batiment_nom FROM Salle s JOIN Batiment b ON s.id_batiment = b.id ORDER BY b.nom, s.nom";
$rooms_result = mysqli_query($conn, $rooms_query);

$sensors_result = mysqli_query($conn, "SELECT c.nom, c.type, c.unite, c.id_salle FROM Capteur c ORDER BY c.id_salle, c.nom");

include 'header.php';
?>

<h1>Panneau d'Administration</h1>
<p>Gérez ici les infrastructures et les capteurs du site.</p>

<div class="container" style="grid-template-columns: 1fr; gap: 2rem;">

    <div class="panel-wrapper">
        <h3 class="panel-title">Gérer les Bâtiments</h3>
        <div style="padding: 1.5rem;">
            <h4>Liste des bâtiments existants</h4>
            <div class="table-wrapper" style="margin-bottom: 1.5rem;">
                <table>
                    <thead><tr><th>ID</th><th>Nom</th><th style="width: 120px;">Action</th></tr></thead>
                    <tbody>
                        <?php if($buildings_result) { mysqli_data_seek($buildings_result, 0); while ($row = mysqli_fetch_assoc($buildings_result)): ?>
                        <tr>
                            <td><?php echo h($row['id']); ?></td>
                            <td><?php echo h($row['nom']); ?></td>
                            <td>
                                <form method="POST" action="admin.php" onsubmit="return confirm('Attention ! Supprimer un bâtiment peut entraîner des problèmes si des salles y sont encore associées. Êtes-vous sûr ?');">
                                    <input type="hidden" name="action" value="delete_building">
                                    <input type="hidden" name="id" value="<?php echo h($row['id']); ?>">
                                    <button type="submit" class="delete-btn">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; } ?>
                    </tbody>
                </table>
            </div>
            <hr style="margin: 2rem 0;">
            <h4>Ajouter un nouveau bâtiment</h4>
            <form method="POST" action="admin.php">
                <input type="hidden" name="action" value="add_building">
                <label style="color:black;">Nom du bâtiment :</label>
                <input type="text" name="nom" required style="color:black;">
                <button type="submit" style="margin-top: 1rem;">Ajouter Bâtiment</button>
            </form>
        </div>
    </div>

    <div class="panel-wrapper">
        <h3 class="panel-title">Gérer les Salles</h3>
        <div style="padding: 1.5rem;">
            <h4>Liste des salles existantes</h4>
            <div class="table-wrapper" style="margin-bottom: 1.5rem;">
                <table>
                    <thead><tr><th>Nom (ID)</th><th>Type</th><th>Capacité</th><th>Bâtiment</th><th style="width: 120px;">Action</th></tr></thead>
                    <tbody>
                        <?php if($rooms_result) { while ($row = mysqli_fetch_assoc($rooms_result)): ?>
                        <tr>
                            <td><?php echo h($row['nom']); ?></td>
                            <td><?php echo h($row['type']); ?></td>
                            <td><?php echo h($row['capacite']); ?></td>
                            <td><?php echo h($row['batiment_nom']); ?></td>
                            <td>
                                <form method="POST" action="admin.php" onsubmit="return confirm('Voulez-vous vraiment supprimer cette salle ?');">
                                    <input type="hidden" name="action" value="delete_room">
                                    <input type="hidden" name="nom" value="<?php echo h($row['nom']); ?>">
                                    <button type="submit" class="delete-btn">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; } ?>
                    </tbody>
                </table>
            </div>
             <hr style="margin: 2rem 0;">
            <h4>Ajouter une nouvelle salle</h4>
            <form method="POST" action="admin.php">
                <input type="hidden" name="action" value="add_room">
                <label style="color:black;">Nom de la salle (ex: E003) :</label>
                <input type="text" name="nom" required style="color:black;">
                <label style="color:black; margin-top: 1rem;">Bâtiment :</label>
                <select name="id_batiment" required style="color:black;">
                    <?php if($buildings_result) { mysqli_data_seek($buildings_result, 0); while($bat = mysqli_fetch_assoc($buildings_result)): ?>
                    <option value="<?php echo h($bat['id']); ?>"><?php echo h($bat['nom']); ?></option>
                    <?php endwhile; } ?>
                </select>
                <label style="color:black; margin-top: 1rem;">Type (ex: Salle de cours) :</label>
                <input type="text" name="type" style="color:black;">
                <label style="color:black; margin-top: 1rem;">Capacité :</label>
                <input type="number" name="capacite" style="color:black;">
                <button type="submit" style="margin-top: 1rem;">Ajouter Salle</button>
            </form>
        </div>
    </div>

    <div class="panel-wrapper">
        <h3 class="panel-title">Gérer les Capteurs</h3>
        <div style="padding: 1.5rem;">
            <h4>Liste des capteurs existants</h4>
            <div class="table-wrapper" style="margin-bottom: 1.5rem;">
                <table>
                    <thead><tr><th>Nom (ID)</th><th>Type</th><th>Unité</th><th>Salle</th><th style="width: 120px;">Action</th></tr></thead>
                    <tbody>
                        <?php if($sensors_result) { while ($row = mysqli_fetch_assoc($sensors_result)): ?>
                        <tr>
                            <td><?php echo h($row['nom']); ?></td>
                            <td><?php echo h($row['type']); ?></td>
                            <td><?php echo h($row['unite']); ?></td>
                            <td><?php echo h($row['id_salle']); ?></td>
                            <td>
                                <form method="POST" action="admin.php" onsubmit="return confirm('Voulez-vous vraiment supprimer ce capteur ?');">
                                    <input type="hidden" name="action" value="delete_sensor">
                                    <input type="hidden" name="nom" value="<?php echo h($row['nom']); ?>">
                                    <button type="submit" class="delete-btn">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; } ?>
                    </tbody>
                </table>
            </div>
             <hr style="margin: 2rem 0;">
            <h4>Ajouter un nouveau capteur</h4>
            <form method="POST" action="admin.php">
                <input type="hidden" name="action" value="add_sensor">
                <label style="color:black;">Nom du capteur (ex: Temp_E003) :</label>
                <input type="text" name="nom" required style="color:black;">
                <label style="color:black; margin-top: 1rem;">Type (ex: Température) :</label>
                <input type="text" name="type" required style="color:black;">
                 <label style="color:black; margin-top: 1rem;">Unité (ex: °C) :</label>
                <input type="text" name="unite" required style="color:black;">
                <label style="color:black; margin-top: 1rem;">Salle :</label>
                <select name="id_salle" required style="color:black;">
                    <?php if($rooms_result) { mysqli_data_seek($rooms_result, 0); while($room = mysqli_fetch_assoc($rooms_result)): ?>
                    <option value="<?php echo h($room['nom']); ?>"><?php echo h($room['nom']); ?></option>
                    <?php endwhile; } ?>
                </select>
                <button type="submit" style="margin-top: 1rem;">Ajouter Capteur</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>