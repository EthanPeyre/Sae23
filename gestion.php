<?php
require_once 'config.php';

// Security: Verify that the user is indeed a manager
if (!isset($_SESSION['login_type']) || $_SESSION['login_type'] !== 'gestionnaire') {
    header('Location: login.php');
    exit();
}

$building_id = $_SESSION['building_id'];
$stmt_salles = mysqli_prepare($conn, "SELECT nom FROM Salle WHERE id_batiment = ? ORDER BY nom");

if (!$stmt_salles) {
    die("Error preparing the query to retrieve rooms: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt_salles, 'i', $building_id);
mysqli_stmt_execute($stmt_salles);
$result_salles = mysqli_stmt_get_result($stmt_salles);

// Variables for results
$mesures = [];
$stats = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salle_nom = $_POST['salle_nom']; 
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $sql_mesures = "SELECT m.date, m.heure, m.valeur, m.id_capteur FROM Mesure m JOIN Capteur c ON m.id_capteur = c.nom WHERE c.id_salle = ? AND m.date BETWEEN ? AND ? ORDER BY m.date DESC, m.heure DESC";
    $stmt_m = mysqli_prepare($conn, $sql_mesures);
    mysqli_stmt_bind_param($stmt_m, 'sss', $salle_nom, $date_debut, $date_fin);
    mysqli_stmt_execute($stmt_m);
    $result_mesures = mysqli_stmt_get_result($stmt_m);
    while($row = mysqli_fetch_assoc($result_mesures)) {
        $mesures[] = $row;
    }

    $sql_stats = "SELECT MIN(valeur) as v_min, MAX(valeur) as v_max, AVG(valeur) as v_avg FROM Mesure m JOIN Capteur c ON m.id_capteur = c.nom WHERE c.id_salle = ? AND m.date BETWEEN ? AND ?";
    $stmt_s = mysqli_prepare($conn, $sql_stats);
    mysqli_stmt_bind_param($stmt_s, 'sss', $salle_nom, $date_debut, $date_fin);
    mysqli_stmt_execute($stmt_s);
    $stats = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_s));
}

include 'header.php';
?>

<h1>Manager Space</h1>
<p>Consult data for your building.</p>

<div class="panel-wrapper">
    <h3 class="panel-title">Consult your building's data</h3>
    <form method="POST" action="gestion.php" style="padding: 1.5rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <label style="color:black;">Room :</label>
                <select name="salle_nom" required style="width: 100%; color:black;">
                    <?php while($salle = mysqli_fetch_assoc($result_salles)): ?>
                    <option value="<?php echo h($salle['nom']); ?>"><?php echo h($salle['nom']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label style="color:black;">Start Date :</label>
                <input type="date" name="date_debut" required style="width: 100%; color:black;">
            </div>
            <div>
                <label style="color:black;">End Date :</label>
                <input type="date" name="date_fin" required style="width: 100%; color:black;">
            </div>
            <div style="align-self: end;">
                <button type="submit">Search</button>
            </div>
        </div>
    </form>
</div>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <div style="margin-top: 2rem;">
        <?php if (!empty($mesures)): ?>
            <div class="panel-wrapper" style="margin-bottom: 2rem;">
                <h3 class="panel-title">Statistics for the period</h3>
                <p style="padding: 1.5rem;">
                    Minimum Value: <strong><?php echo h(round($stats['v_min'], 2)); ?></strong> | 
                    Maximum Value: <strong><?php echo h(round($stats['v_max'], 2)); ?></strong> | 
                    Average: <strong><?php echo h(round($stats['v_avg'], 2)); ?></strong>
                </p>
            </div>
            <div class="panel-wrapper">
                <h3 class="panel-title">Detailed readings</h3>
                <div style="padding: 1rem; overflow-x: auto;">
                    <table>
                        <thead><tr><th>Sensor</th><th>Date</th><th>Time</th><th>Value</th></tr></thead>
                        <tbody>
                            <?php foreach ($mesures as $m): ?>
                            <tr>
                                <td><?php echo h($m['id_capteur']); ?></td>
                                <td><?php echo h($m['date']); ?></td>
                                <td><?php echo h($m['heure']); ?></td>
                                <td><?php echo h($m['valeur']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="panel-wrapper"><p style="padding: 1.5rem;">No measurements found for this selection.</p></div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>