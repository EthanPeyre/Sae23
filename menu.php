<nav>
  <ul>
    <li><a href="index.php">Accueil</a></li>
    <li><a href="donnees.php">Données en direct</a></li>
    <li><a href="graphiques.php">Graphiques</a></li>

    <?php // Le menu "Administration" n'est visible que pour les admins
    if (isset($_SESSION['login_type']) && $_SESSION['login_type'] === 'admin'): ?>
        <li><a href="admin.php">Administration</a></li>
    <?php endif; ?>

    <?php // Le menu "Gestion" n'est visible que pour les gestionnaires
    if (isset($_SESSION['login_type']) && $_SESSION['login_type'] === 'gestionnaire'): ?>
        <li><a href="gestion.php">Gestion</a></li>
    <?php endif; ?>

    <li><a href="gestion_projet.php">Projet</a></li>
  </ul>
  
  <ul style="margin-left: auto;">
    <?php if (isset($_SESSION['login'])): ?>
        <li style="padding: 1rem;"><strong><?php echo h($_SESSION['login']); ?></strong></li>
        <li><a href="logout.php">Déconnexion</a></li>
    <?php else: ?>
        <li><a href="login.php">Connexion</a></li>
    <?php endif; ?>
  </ul>
</nav>