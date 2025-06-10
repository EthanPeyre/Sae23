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

    <div class="section">
            <h2>Outils Collaboratifs</h2>
            <p>Nous avons utilisé plusieurs outils pour faciliter la collaboration et le suivi des tâches.</p>
            <div class="grid-container">
                <div>
                    <h3>GitHub</h3>
                    <p>Pour le code et le suivi des `issues` techniques.</p>
                    <a href="https://github.com/EthanPeyre/Sae23" target="_blank">
                        <img src="assets/github.png" alt="Capture de GitHub" style="width:100%;">
                    </a>
                </div>
                <div>
                    <h3>Trello</h3>
                    <p>Pour la gestion des tâches et la répartition du travail (qui fait quoi).</p>
                    <img src="assets/trello.png" alt="Capture de Trello" style="width:100%;">
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Synthèse Personnelle</h2>
            
            <h3>Gabriel Roques</h3>
            <p><strong>Activités réalisées :</strong> Intégration Grafana & Développement PHP & UI & XAMPP</p>
            <p><strong>Problèmes rencontrés :</strong> Le PHP ne fonctionnait pas tout le temps, Grafana empêché la visualisation des données depuis l'extérieur, l'UI de Nodered était souvent en désordre et enfin MySQL avait certains problèmes de connexion majeur avec le compte noder.</p>
            <p><strong>Solutions apportées :</strong> Recherche de tutoriel en ligne et prise de conseil en PHP et Javascript au prêt d'un ami développeur, recherche dans les fichiers de configuration de Grafana de différentes options pour ouvrir l'accès à la visualisation des données pour tous. Mise en place d'une meilleure gestion de l'affichage sur nodered et enfin optimisation de la base de donnée MySQL afin de régler le soucis.</p>
            <hr>

            <h3>Lucas Cousin</h3>
            <p><strong>Activités réalisées :</strong> Schéma de la table MySQL, schéma de conception du site web</p>
            <p><strong>Problèmes rencontrés :</strong> trouver une table cohérante avec nos idées et également faciliter la mise en place du site web malgré quelques informations floues</p>
            <p><strong>Solutions apportées :</strong> Aide trouvé en TP accompagné.</p>
            <hr>

            <h3>Mouad Meliani</h3>
            <p><strong>Activités réalisées :</strong> Développement Node‑RED</p>
            <p><strong>Problèmes rencontrés :</strong> La mise en place de node-red fut assez problématique avec les changements de salle constant.</p>
            <p><strong>Solutions apportées :</strong> Faciliter le changement des adresses IP en notant tous les emplacements d'IP que l'on doit changer dans le Read Me sur Github.</p>
            <hr>

            <h3>Ethan Peyre</h3>
            <p><strong>Activités réalisées :</strong> Documentation & gestion de projet</p>
            <p><strong>Problèmes rencontrés :</strong> Difficulté à mettre en place un agenda cohérant avec nos idées afin de ne pas dépasser la limite de temps.</p>
            <p><strong>Solutions apportées :</strong> Créer un Trello où tout le monde peut participer librement.</p>
        </div>

    <div class="panel-wrapper">
        <h3 class="panel-title">Conclusion</h3>
        <div style="padding: 1.5rem;">
            <p>Ce projet nous a permis de mettre en œuvre un cycle de développement complet pour une application IoT. Le cahier des charges a été respecté, avec une interface web fonctionnelle permettant à la fois la visualisation temps réel et historique, ainsi qu'une gestion administrative et par bâtiment. Les choix techniques se sont avérés pertinents pour répondre aux objectifs fixés.</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>