<?php
require_once 'config.php';

// Sécurité : Vérifier que l'utilisateur est bien un gestionnaire
if (!isset($_SESSION['login_type']) || $_SESSION['login_type'] !== 'gestionnaire') {
    header('Location: login.php');
    exit();
}

$building_id = $_SESSION['building_id'];

// ==============================================================================
// MODIFICATION 1 : On sélectionne la colonne 'nom' car 'id_salle' n'existe pas.
// ==============================================================================
$stmt_salles = mysqli_prepare($conn, "SELECT nom FROM Salle WHERE id_batiment = ? ORDER BY nom");

if (!$stmt_salles) {
    die("Erreur de préparation de la requête pour récupérer les salles : " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt_salles, 'i', $building_id);
mysqli_stmt_execute($stmt_salles);
$result_salles = mysqli_stmt_get_result($stmt_salles);

// Variables pour les résultats
$mesures = [];
$stats = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // La variable contient maintenant un nom de salle (string), pas un ID numérique
    $salle_nom = $_POST['salle_nom']; 
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Dans la table Capteur, votre colonne 'id_salle' contient bien le nom de la salle, donc cette logique est bonne.
    $sql_mesures = "SELECT m.date, m.heure, m.valeur, m.id_capteur FROM Mesure m JOIN Capteur c ON m.id_capteur = c.nom WHERE c.id_salle = ? AND m.date BETWEEN ? AND ? ORDER BY m.date DESC, m.heure DESC";
    $stmt_m = mysqli_prepare($conn, $sql_mesures);
    // MODIFICATION 3 : Le premier paramètre est maintenant un 's' (string) au lieu de 'i' (integer).
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

<h1>Espace Gestionnaire</h1>
<p>Consultation des données du bâtiment.</p>

<div class="panel-wrapper">
    <h3 class="panel-title">Consulter les données de votre bâtiment</h3>
    <form method="POST" action="gestion.php" style="padding: 1.5rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <label style="color:black;">Salle :</label>
                <select name="salle_nom" required style="width: 100%; color:black;">
                    <?php while($salle = mysqli_fetch_assoc($result_salles)): ?>
                    <option value="<?php echo h($salle['nom']); ?>"><?php echo h($salle['nom']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label style="color:black;">Date de début :</label>
                <input type="date" name="date_debut" required style="width: 100%; color:black;">
            </div>
            <div>
                <label style="color:black;">Date de fin :</label>
                <input type="date" name="date_fin" required style="width: 100%; color:black;">
            </div>
            <div style="align-self: end;">
                <button type="submit">Rechercher</button>
            </div>
        </div>
    </form>
</div>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <div style="margin-top: 2rem;">
        <?php if (!empty($mesures)): ?>
            <div class="panel-wrapper" style="margin-bottom: 2rem;">
                <h3 class="panel-title">Statistiques pour la période</h3>
                <p style="padding: 1.5rem;">
                    Valeur Minimale: <strong><?php echo h(round($stats['v_min'], 2)); ?></strong> | 
                    Valeur Maximale: <strong><?php echo h(round($stats['v_max'], 2)); ?></strong> | 
                    Moyenne: <strong><?php echo h(round($stats['v_avg'], 2)); ?></strong>
                </p>
            </div>
            <div class="panel-wrapper">
                <h3 class="panel-title">Relevés détaillés</h3>
                <div style="padding: 1rem; overflow-x: auto;">
                    <table>
                        <thead><tr><th>Capteur</th><th>Date</th><th>Heure</th><th>Valeur</th></tr></thead>
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
            <div class="panel-wrapper"><p style="padding: 1.5rem;">Aucune mesure trouvée pour cette sélection.</p></div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>