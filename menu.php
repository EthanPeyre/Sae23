<nav>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="donnees.php">Live Data</a></li>
    <li><a href="graphiques.php">Graphs</a></li>

    <?php // The "Administration" menu is only visible to admins
    if (isset($_SESSION['login_type']) && $_SESSION['login_type'] === 'admin'): ?>
        <li><a href="admin.php">Administration</a></li>
    <?php endif; ?>

    <?php // The "Management" menu is only visible to managers
    if (isset($_SESSION['login_type']) && $_SESSION['login_type'] === 'gestionnaire'): ?>
        <li><a href="gestion.php">Management</a></li>
    <?php endif; ?>

    <li><a href="gestion_projet.php">Project</a></li>
  </ul>
  
  <ul style="margin-left: auto;">
    <?php if (isset($_SESSION['login'])): ?>
        <li style="padding: 1rem;"><strong><?php echo h($_SESSION['login']); ?></strong></li>
        <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
        <li><a href="login.php">Login</a></li>
    <?php endif; ?>
  </ul>
</nav>