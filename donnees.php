<?php 
include('header.php'); 
include('db.php');
?>

<h1>Live Data</h1>

<h2>Latest measurements by room</h2>
<table>
  <tr>
    <th>Room</th>
    <th>Type</th>
    <th>Value</th>
    <th>Date</th>
    <th>Time</th>
  </tr>
<?php
$sql = "
SELECT id_capteur, valeur, date, heure 
FROM Mesure 
WHERE (id_capteur, date, heure) IN (
  SELECT id_capteur, MAX(date), MAX(heure) FROM Mesure GROUP BY id_capteur
)
ORDER BY id_capteur, date DESC, heure DESC;
";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    if (preg_match('/^([A-Za-z]+)_(E[0-9]+)/', $row['id_capteur'], $matches)) {
      $type = $matches[1];
      $salle = $matches[2];
    } else {
      $type = $row['id_capteur'];
      $salle = "?";
    }
    echo "<tr>
      <td>{$salle}</td>
      <td>{$type}</td>
      <td>{$row['valeur']}</td>
      <td>{$row['date']}</td>
      <td>{$row['heure']}</td>
    </tr>";
  }
} else {
  echo "<tr><td colspan='5'>No data available</td></tr>";
}
?>
</table>

<h2>Real-time Gauges (NodeRED)</h2>
<iframe src="http://192.168.1.108:1880/ui/" width="100%" height="500" style="border:1px solid #ccc; border-radius: 8px;"></iframe>

<?php include('footer.php'); ?>