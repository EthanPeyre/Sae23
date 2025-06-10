<?php
// config.php

// Affiche les erreurs pour le débogage (à commenter en production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Démarrage de la session, essentiel pour les pages de login/admin
session_start();

// Inclusion de vos paramètres de connexion à la base de données
require_once 'db.php'; 

// Fonction de sécurité pour l'affichage des données
function h($str) {
    if ($str === null) return '';
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>