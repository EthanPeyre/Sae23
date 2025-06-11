#!/bin/bash

echo "=== Configuration du système MQTT pour XAMPP ==="

echo "Installation des dépendances MQTT..."
sudo apt update
sudo apt install -y mosquitto-clients jq

XAMPP_HTDOCS="/opt/lampp/htdocs"

if [ ! -d "$XAMPP_HTDOCS" ]; then
    echo "XAMPP non trouvé dans /opt/lampp/htdocs"
    echo "Veuillez vérifier l'installation de XAMPP"
    exit 1
fi

echo "Copie des fichiers web dans XAMPP..."
sudo cp mqtt.html "$XAMPP_HTDOCS/"
sudo cp api.php "$XAMPP_HTDOCS/"
sudo chown -R daemon:daemon "$XAMPP_HTDOCS/mqtt.html" "$XAMPP_HTDOCS/api.php"

echo "Configuration du script MQTT..."
sudo cp mqtt_collector.sh /usr/local/bin/
sudo chmod +x /usr/local/bin/mqtt_collector.sh

echo "Création du fichier de log..."
sudo touch /var/log/mqtt_collector.log
sudo chown $USER:$USER /var/log/mqtt_collector.log

echo "Démarrage de XAMPP..."
sudo /opt/lampp/lampp start

echo "Création de la base de données..."
/opt/lampp/bin/mysql -u root < database_setup.sql

echo "Configuration du service systemd..."
sudo tee /etc/systemd/system/mqtt-collector.service > /dev/null <<EOF
[Unit]
Description=MQTT Data Collector for XAMPP
After=network.target

[Service]
Type=simple
User=$USER
ExecStart=/usr/local/bin/mqtt_collector.sh
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
EOF

sudo systemctl daemon-reload
sudo systemctl enable mqtt-collector.service
sudo systemctl start mqtt-collector.service

echo "Installation terminée !"
echo "Démarrez XAMPP si ce n'est pas fait: sudo /opt/lampp/lampp start"
echo "Accédez à votre interface: http://localhost/mqtt.html"
echo "PhpMyAdmin: http://localhost/phpmyadmin"
echo "Vérifiez le service: sudo systemctl status mqtt-collector.service"
echo "Logs: tail -f /var/log/mqtt_collector.log"