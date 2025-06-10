<?php include 'header.php'; ?>

<h1>Gestion du Projet SAE23</h1>

<div class="container" style="grid-template-columns: 1fr; gap: 2rem;">
    <div class="panel-wrapper">
        <h3 class="panel-title">Diagramme de GANTT</h3>
        <div style="padding: 1.5rem;">
            <p>Le projet a été planifié et suivi à l'aide du diagramme de GANTT suivant, qui détaille les différentes phases du développement, de la conception à la livraison finale.</p>
            <img src="assets/Gantt_SAé23.png" alt="Diagramme de GANTT du projet" style="width: 100%; margin-top: 1rem; border-radius: 5px;">
        </div>
    </div>

    <div class="panel-wrapper">
        <h3 class="panel-title">Outils Collaboratifs</h3>
        <div style="padding: 1.5rem;">
            <p>La collaboration au sein de l'équipe a été facilitée par l'utilisation d'outils standards de l'industrie comme GitHub pour le versionnage du code et Trello pour la gestion des tâches et le suivi de l'avancement.</p>
            <img src="assets/trello.png" alt="Tableau Trello" style="width: 100%; margin-top: 1rem; border-radius: 5px;">
        </div>
    </div>

    <div class="panel-wrapper">
        <h3 class="panel-title">Synthèse Personnelle</h3>
        <div style="padding: 1.5rem;">
            <h4>Nom Membre 1</h4>
            <p><strong>Activités :</strong> Conception de la base de données, développement du backend PHP pour l'authentification et le CRUD, configuration des flux Node-RED pour l'insertion SQL.</p>
            <p><strong>Difficultés :</strong> La mise en place des requêtes préparées pour sécuriser l'application a nécessité une attention particulière.</p>
            <hr style="margin: 1rem 0;">
            <h4>Nom Membre 2</h4>
            <p><strong>Activités :</strong> Intégration HTML/CSS, mise en place de la structure du site (header/footer), développement des pages de visualisation (Grafana, Node-RED UI), et création de la page de gestion de projet.</p>
            <p><strong>Difficultés :</strong> Assurer la cohérence visuelle sur toutes les pages et rendre les panneaux de graphiques responsives.</p>
        </div>
    </div>

    <div class="panel-wrapper">
        <h3 class="panel-title">Conclusion</h3>
        <div style="padding: 1.5rem;">
            <p>Ce projet nous a permis de mettre en œuvre un cycle de développement complet pour une application IoT. Le cahier des charges a été respecté, avec une interface web fonctionnelle permettant à la fois la visualisation temps réel et historique, ainsi qu'une gestion administrative et par bâtiment. Les choix techniques se sont avérés pertinents pour répondre aux objectifs fixés.</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>