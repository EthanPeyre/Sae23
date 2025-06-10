# SAE23 – Portail IoT de l’IUT de Blagnac

[![PHP](https://img.shields.io/badge/PHP-7.4+-8892BF.svg)](https://www.php.net/)  
[![Node-RED](https://img.shields.io/badge/Node--RED-v2.1.5-brightgreen.svg)](https://nodered.org/)  
[![Grafana](https://img.shields.io/badge/Grafana-v10.1-blue.svg)](https://grafana.com/)  
[![Docker](https://img.shields.io/badge/Docker-v24.0-blue.svg)](https://www.docker.com/)  
[![MySQL](https://img.shields.io/badge/MySQL-v8.0-orange.svg)](https://www.mysql.com/)

## Description

Ce projet a pour but de **récupérer**, **stocker**, et **visualiser** les mesures environnementales (température, humidité, CO₂, pression, luminosité…) issues des capteurs de l’IUT de Blagnac :

1. **Node‑RED** capte les messages MQTT publiés par les capteurs.  
2. Les mesures sont insérées automatiquement dans une base **MySQL**.  
3. **Grafana** permet de créer des tableaux de bord temps‑réel et historiques.  
4. Un **site web PHP** (procédural) propose :  
   - une interface **administrateur** (CRUD bâtiments / salles / capteurs),  
   - un espace **gestionnaire** (consultation + stats sur son bâtiment),  
   - une page **publique** de consultation des dernières mesures,  
   - une page **gestion de projet** (GANTT, outils, synthèses).

## Prérequis

- **Docker** (pour Node‑RED & Grafana)  
- **XAMPP** (Apache + PHP + MySQL) ou tout autre LAMP/WAMP  
- **PHP 7.4+**, **MySQL 8.0+**, **HTML5/CSS3**, **JavaScript**  
- Un terminal pour lancer les containers Docker  

## Installation et démarrage

1. **Cloner ce dépôt**  

   git clone https://github.com/EthanPeyre/Sae23
   cd Sae23

2. **Démarrer les containers Docker**

   docker start noderedRT
   docker start grafanaRT

> Assurez‑vous que les containers `noderedRT` et `grafanaRT` existent et sont correctement configurés.

3. **Lancer XAMPP**

   * Sur Windows : ouvrez le **XAMPP Control Panel** et démarrez **Apache** et **MySQL**.
   * Sur Linux (LAMPP) :

     sudo /opt/lampp/lampp start

4. **Importer la base de données**

   * Ouvrez **phpMyAdmin** ([http://localhost/phpmyadmin](http://localhost/phpmyadmin))
   * Créez la base `sae23` et exécutez le script `sql/schema.sql`.

5. **Configurer les adresses IP**
   Dans `db.php`, `donnees.php` et `graphiques.php`, mettez à jour :

   * l’**IP** de votre VM XAMPP (`127.0.0.1` ou `192.168.xxx.xxx`)
   * le **port** si nécessaire (MySQL `3306`, Node‑RED Dashboard `1880`, Grafana `3000`)

6. **Importer le dashboard Grafana**

   * Dans Grafana → **Dashboards** → **Import** → téléversez `IUT-...-Grafana.json`
   * Sélectionnez la **DataSource MySQL** correspondante (`sae23`).

## Arborescence du projet (Tree)

sae23/
│
├── sql/
│   └── schema.sql            # Script de création des tables MySQL
│
├── flows/                    # Flows Node‑RED pour MQTT → MySQL et jauges
│   ├── salle_sql.json
│   └── salle_jauge.json
│
├── grafana/
│   └── IUT-1749116382877-Grafana.json    # Export JSON du dashboard Grafana
│
└── website/                 ← code du site PHP procédural
    │
    ├── admin.php            ← CRUD bâtiments / salles / capteurs (admin)
    ├── config.php           ← connexion mysqli + session + fonction h()
    ├── db.php               ← alias de la connexion à MySQL
    ├── donnees.php          ← dernières mesures + iframe Node‑RED
    ├── footer.php           ← pied de page commun HTML
    ├── gestion.php          ← consultation + stats par gestionnaire
    ├── gestion_projet.php   ← GANTT, outils, synthèses, conclusion
    ├── graphiques.php       ← iframe Grafana
    ├── header.php           ← en‑tête HTML + menu + inclusion config
    ├── index.php            ← page d’accueil (objectif, bâtiments, salles, mentions légales)
    ├── login.php            ← formulaire de connexion administrateur
    ├── logout.php           ← destruction de session / redirection
    ├── menu.php             ← menu de navigation inclus partout
    └── style.css            ← styles globaux (CSS)
│
└── README.md

## Fonctionnalités clés

* **Node‑RED**

  * Souscription aux topics `AM107/by-room/E###/data`
  * Injection automatique dans MySQL
  * Dashboard de jauges temps‑réel

* **MySQL**

  * Tables normalisées : `Administrateur`, `Bâtiment`, `Salle`, `Capteur`, `Mesure`
  * Stockage durable des relevés

* **Grafana**

  * Séries temporelles (Température, Humidité, CO₂…)
  * Dashboard interactif, rafraîchissement en continu

* **Site PHP (procédural)**

  * **Admin** : gestion complète du référentiel spatialisé
  * **Gestion** : consultation détaillée + min/max/moyenne
  * **Consultation** : vue publique, dernière mesure de chaque capteur
  * **Projet** : planning, outils, rétrospective, bilan

## Bonnes pratiques

* **Requêtes préparées** (`mysqli_prepare`) pour éviter l’injection SQL
* **Sessions PHP** pour authentifier et autoriser
* **Échappement HTML** (`htmlspecialchars`) pour sécuriser les sorties
* **Séparation** des responsabilités :

  * Node‑RED = ingestion
  * MySQL = stockage
  * Grafana = visualisation
  * PHP = interface web

## Auteurs & Contributeurs

* **Gabriel Roques** – Intégration Grafana & Développement PHP & UI & XAMPP
* **Lucas Cousin** – Schéma de la table MySQL, schéma de conception du site web
* **Mouad Meliani** – Développement Node‑RED
* **Ethan Peyre** – Documentation & gestion de projet

## Licence

Ce projet est sous licence MIT.
Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

> **Remarque :**
>
> * Pense bien à mettre à jour les URLs/ports Docker et XAMPP
> * Sauvegarde régulièrement ta base (`mysqldump SAE23 > backup.sql`)
> * Pense à exporter les flows Node‑RED et le dashboard Grafana pour versionner

Bonne continuation !!!
Toute l'équipe.