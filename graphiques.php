<?php 
include('header.php'); 
$grafanaDashboard = isset($_GET['grafana']) ? $_GET['grafana'] : 'e47e2990-c4b9-430f-801a-1d0101f934f4';
?>

<h1>Tableaux de bord</h1>

<h2>Visualisations Grafana</h2>
<div class="container grafana-container">
    <div class="panel-wrapper grafana-panel-wrapper">
      <div class="status-indicator"></div>
      <h3 class="panel-title"><i class="fas fa-thermometer-half"></i> Température E003</h3>
      <iframe src="http://192.168.1.108:3000/d-solo/<?php echo $grafanaDashboard; ?>/iut?orgId=1&from=now-6h&to=now&theme=light&panelId=1" frameborder="0"></iframe>
    </div>

    <div class="panel-wrapper grafana-panel-wrapper">
      <div class="status-indicator"></div>
      <h3 class="panel-title"><i class="fas fa-tint"></i> Humidité E003</h3>
      <iframe src="http://192.168.1.108:3000/d-solo/<?php echo $grafanaDashboard; ?>/iut?orgId=1&from=now-6h&to=now&theme=light&panelId=2" frameborder="0"></iframe>
    </div>

    <div class="panel-wrapper grafana-panel-wrapper">
      <div class="status-indicator"></div>
      <h3 class="panel-title"><i class="fas fa-smog"></i> CO2 E003</h3>
      <iframe src="http://192.168.1.108:3000/d-solo/<?php echo $grafanaDashboard; ?>/iut?orgId=1&from=now-6h&to=now&theme=light&panelId=3" frameborder="0"></iframe>
    </div>

    <div class="panel-wrapper grafana-panel-wrapper">
      <div class="status-indicator"></div>
      <h3 class="panel-title"><i class="fas fa-thermometer-half"></i> Température E100</h3>
      <iframe src="http://192.168.1.108:3000/d-solo/<?php echo $grafanaDashboard; ?>/iut?orgId=1&from=now-6h&to=now&theme=light&panelId=4" frameborder="0"></iframe>
    </div>

    <div class="panel-wrapper grafana-panel-wrapper">
      <div class="status-indicator"></div>
      <h3 class="panel-title"><i class="fas fa-lightbulb"></i> Illumination E100</h3>
      <iframe src="http://192.168.1.108:3000/d-solo/<?php echo $grafanaDashboard; ?>/iut?orgId=1&from=now-6h&to=now&theme=light&panelId=5" frameborder="0"></iframe>
    </div>

    <div class="panel-wrapper grafana-panel-wrapper">
      <div class="status-indicator"></div>
      <h3 class="panel-title"><i class="fas fa-thermometer-half"></i> Température E007</h3>
      <iframe src="http://192.168.1.108:3000/d-solo/<?php echo $grafanaDashboard; ?>/iut?orgId=1&from=now-6h&to=now&theme=light&panelId=6" frameborder="0"></iframe>
    </div>

    <div class="panel-wrapper grafana-panel-wrapper">
      <div class="status-indicator"></div>
      <h3 class="panel-title"><i class="fas fa-tachometer-alt"></i> Pression E007</h3>
      <iframe src="http://192.168.1.108:3000/d-solo/<?php echo $grafanaDashboard; ?>/iut?orgId=1&from=now-6h&to=now&theme=light&panelId=7" frameborder="0"></iframe>
    </div>
</div>

<div class="nodered-container">
    <h2>Panneau de contrôle Node-RED</h2>
    <div class="panel-wrapper">
      <iframe src="http://192.168.1.108:1880/ui/#!/1?socketid=9UQaERSMgEqoc7PUAAAX"></iframe>
    </div>
</div>

<?php include('footer.php'); ?>