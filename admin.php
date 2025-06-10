<?php
require_once 'config.php';

// --- SECURITY ---
if (!isset($_SESSION['login_type']) || $_SESSION['login_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// --- FORM PROCESSING (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // -- ACTION: ADD A BUILDING --
    if ($action === 'add_building' && !empty($_POST['nom'])) {
        $stmt = mysqli_prepare($conn, "INSERT INTO Batiment (nom) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $_POST['nom']);
        mysqli_stmt_execute($stmt);
    }

    // -- ACTION: DELETE A BUILDING --
    // MODIFICATION: Uses 'id' instead of 'id_bat'
    elseif ($action === 'delete_building' && !empty($_POST['id'])) {
        $stmt = mysqli_prepare($conn, "DELETE FROM Batiment WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $_POST['id']);
        mysqli_stmt_execute($stmt);
    }
    
    // -- ACTION: ADD A ROOM --
    elseif ($action === 'add_room' && !empty($_POST['nom']) && !empty($_POST['id_batiment'])) {
        $stmt = mysqli_prepare($conn, "INSERT INTO Salle (nom, id_batiment, type, capacite) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sisi", $_POST['nom'], $_POST['id_batiment'], $_POST['type'], $_POST['capacite']);
        mysqli_stmt_execute($stmt);
    }

    // -- ACTION: DELETE A ROOM --
    elseif ($action === 'delete_room' && !empty($_POST['nom'])) {
        $stmt = mysqli_prepare($conn, "DELETE FROM Salle WHERE nom = ?");
        mysqli_stmt_bind_param($stmt, "s", $_POST['nom']);
        mysqli_stmt_execute($stmt);
    }

    // -- ACTION: ADD A SENSOR --
    elseif ($action === 'add_sensor' && !empty($_POST['nom']) && !empty($_POST['id_salle'])) {
        $stmt = mysqli_prepare($conn, "INSERT INTO Capteur (nom, type, unite, id_salle) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $_POST['nom'], $_POST['type'], $_POST['unite'], $_POST['id_salle']);
        mysqli_stmt_execute($stmt);
    }

    // -- ACTION: DELETE A SENSOR --
    elseif ($action === 'delete_sensor' && !empty($_POST['nom'])) {
        $stmt = mysqli_prepare($conn, "DELETE FROM Capteur WHERE nom = ?");
        mysqli_stmt_bind_param($stmt, "s", $_POST['nom']);
        mysqli_stmt_execute($stmt);
    }

    header('Location: admin.php');
    exit();
}

// --- RETRIEVING DATA FOR DISPLAY ---
$buildings_result = mysqli_query($conn, "SELECT * FROM Batiment ORDER BY nom");

// MODIFICATION: The join is on b.id instead of b.id_bat
$rooms_query = "SELECT s.nom, s.type, s.capacite, b.nom as batiment_nom FROM Salle s JOIN Batiment b ON s.id_batiment = b.id ORDER BY b.nom, s.nom";
$rooms_result = mysqli_query($conn, $rooms_query);

$sensors_result = mysqli_query($conn, "SELECT c.nom, c.type, c.unite, c.id_salle FROM Capteur c ORDER BY c.id_salle, c.nom");

include 'header.php';
?>

<h1>Administration Panel</h1>
<p>Manage site infrastructure and sensors here.</p>

<div class="container" style="grid-template-columns: 1fr; gap: 2rem;">

    <div class="panel-wrapper">
        <h3 class="panel-title">Manage Buildings</h3>
        <div style="padding: 1.5rem;">
            <h4>List of existing buildings</h4>
            <div class="table-wrapper" style="margin-bottom: 1.5rem;">
                <table>
                    <thead><tr><th>ID</th><th>Name</th><th style="width: 120px;">Action</th></tr></thead>
                    <tbody>
                        <?php if($buildings_result) { mysqli_data_seek($buildings_result, 0); while ($row = mysqli_fetch_assoc($buildings_result)): ?>
                        <tr>
                            <td><?php echo h($row['id']); ?></td>
                            <td><?php echo h($row['nom']); ?></td>
                            <td>
                                <form method="POST" action="admin.php" onsubmit="return confirm('Warning! Deleting a building may cause issues if rooms are still associated with it. Are you sure?');">
                                    <input type="hidden" name="action" value="delete_building">
                                    <input type="hidden" name="id" value="<?php echo h($row['id']); ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; } ?>
                    </tbody>
                </table>
            </div>
            <hr style="margin: 2rem 0;">
            <h4>Add a new building</h4>
            <form method="POST" action="admin.php">
                <input type="hidden" name="action" value="add_building">
                <label style="color:black;">Building Name :</label>
                <input type="text" name="nom" required style="color:black;">
                <button type="submit" style="margin-top: 1rem;">Add Building</button>
            </form>
        </div>
    </div>

    <div class="panel-wrapper">
        <h3 class="panel-title">Manage Rooms</h3>
        <div style="padding: 1.5rem;">
            <h4>List of existing rooms</h4>
            <div class="table-wrapper" style="margin-bottom: 1.5rem;">
                <table>
                    <thead><tr><th>Name (ID)</th><th>Type</th><th>Capacity</th><th>Building</th><th style="width: 120px;">Action</th></tr></thead>
                    <tbody>
                        <?php if($rooms_result) { while ($row = mysqli_fetch_assoc($rooms_result)): ?>
                        <tr>
                            <td><?php echo h($row['nom']); ?></td>
                            <td><?php echo h($row['type']); ?></td>
                            <td><?php echo h($row['capacite']); ?></td>
                            <td><?php echo h($row['batiment_nom']); ?></td>
                            <td>
                                <form method="POST" action="admin.php" onsubmit="return confirm('Do you really want to delete this room?');">
                                    <input type="hidden" name="action" value="delete_room">
                                    <input type="hidden" name="nom" value="<?php echo h($row['nom']); ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; } ?>
                    </tbody>
                </table>
            </div>
             <hr style="margin: 2rem 0;">
            <h4>Add a new room</h4>
            <form method="POST" action="admin.php">
                <input type="hidden" name="action" value="add_room">
                <label style="color:black;">Room Name (e.g. E003) :</label>
                <input type="text" name="nom" required style="color:black;">
                <label style="color:black; margin-top: 1rem;">Building :</label>
                <select name="id_batiment" required style="color:black;">
                    <?php if($buildings_result) { mysqli_data_seek($buildings_result, 0); while($bat = mysqli_fetch_assoc($buildings_result)): ?>
                    <option value="<?php echo h($bat['id']); ?>"><?php echo h($bat['nom']); ?></option>
                    <?php endwhile; } ?>
                </select>
                <label style="color:black; margin-top: 1rem;">Type (e.g. Classroom) :</label>
                <input type="text" name="type" style="color:black;">
                <label style="color:black; margin-top: 1rem;">Capacity :</label>
                <input type="number" name="capacite" style="color:black;">
                <button type="submit" style="margin-top: 1rem;">Add Room</button>
            </form>
        </div>
    </div>

    <div class="panel-wrapper">
        <h3 class="panel-title">Manage Sensors</h3>
        <div style="padding: 1.5rem;">
            <h4>List of existing sensors</h4>
            <div class="table-wrapper" style="margin-bottom: 1.5rem;">
                <table>
                    <thead><tr><th>Name (ID)</th><th>Type</th><th>Unit</th><th>Room</th><th style="width: 120px;">Action</th></tr></thead>
                    <tbody>
                        <?php if($sensors_result) { while ($row = mysqli_fetch_assoc($sensors_result)): ?>
                        <tr>
                            <td><?php echo h($row['nom']); ?></td>
                            <td><?php echo h($row['type']); ?></td>
                            <td><?php echo h($row['unite']); ?></td>
                            <td><?php echo h($row['id_salle']); ?></td>
                            <td>
                                <form method="POST" action="admin.php" onsubmit="return confirm('Do you really want to delete this sensor?');">
                                    <input type="hidden" name="action" value="delete_sensor">
                                    <input type="hidden" name="nom" value="<?php echo h($row['nom']); ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; } ?>
                    </tbody>
                </table>
            </div>
             <hr style="margin: 2rem 0;">
            <h4>Add a new sensor</h4>
            <form method="POST" action="admin.php">
                <input type="hidden" name="action" value="add_sensor">
                <label style="color:black;">Sensor Name (e.g. Temp_E003) :</label>
                <input type="text" name="nom" required style="color:black;">
                <label style="color:black; margin-top: 1rem;">Type (e.g. Temperature) :</label>
                <input type="text" name="type" required style="color:black;">
                 <label style="color:black; margin-top: 1rem;">Unit (e.g. °C) :</label>
                <input type="text" name="unite" required style="color:black;">
                <label style="color:black; margin-top: 1rem;">Room :</label>
                <select name="id_salle" required style="color:black;">
                    <?php if($rooms_result) { mysqli_data_seek($rooms_result, 0); while($room = mysqli_fetch_assoc($rooms_result)): ?>
                    <option value="<?php echo h($room['nom']); ?>"><?php echo h($room['nom']); ?></option>
                    <?php endwhile; } ?>
                </select>
                <button type="submit" style="margin-top: 1rem;">Add Sensor</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>