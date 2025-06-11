#!/bin/bash

MQTT_HOST="mqtt.iut-blagnac.fr"
MQTT_PORT="1883"
TOPIC="AM107/by-room/+/data"
DB_HOST="localhost"
DB_USER="iot_user"
DB_PASS="iot_password"
DB_NAME="iot_sensors"

LOG_FILE="/var/log/mqtt_collector.log"

log_message() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') - $1" >> "$LOG_FILE"
}

mosquitto_sub -h "$MQTT_HOST" -p "$MQTT_PORT" -t "$TOPIC" | while read -r message; do
    if [[ -n "$message" ]]; then
        log_message "Message reçu: $message"
        
        sensor_data=$(echo "$message" | jq -r '.[0]')
        device_info=$(echo "$message" | jq -r '.[1]')
        
        temperature=$(echo "$sensor_data" | jq -r '.temperature // "NULL"')
        humidity=$(echo "$sensor_data" | jq -r '.humidity // "NULL"')
        activity=$(echo "$sensor_data" | jq -r '.activity // "NULL"')
        co2=$(echo "$sensor_data" | jq -r '.co2 // "NULL"')
        tvoc=$(echo "$sensor_data" | jq -r '.tvoc // "NULL"')
        illumination=$(echo "$sensor_data" | jq -r '.illumination // "NULL"')
        infrared=$(echo "$sensor_data" | jq -r '.infrared // "NULL"')
        infrared_and_visible=$(echo "$sensor_data" | jq -r '.infrared_and_visible // "NULL"')
        pressure=$(echo "$sensor_data" | jq -r '.pressure // "NULL"')
        latitude=$(echo "$sensor_data" | jq -r '.Latitude // "NULL"')
        longitude=$(echo "$sensor_data" | jq -r '.Langitude // "NULL"')
        
        device_name=$(echo "$device_info" | jq -r '.deviceName // "NULL"')
        dev_eui=$(echo "$device_info" | jq -r '.devEUI // "NULL"')
        room=$(echo "$device_info" | jq -r '.room // "NULL"')
        floor=$(echo "$device_info" | jq -r '.floor // "NULL"')
        building=$(echo "$device_info" | jq -r '.Building // "NULL"')
        
        mysql -h "$DB_HOST" -u "$DB_USER" "$DB_NAME" << EOF
INSERT INTO sensor_data (
    device_name, dev_eui, room, floor, building,
    temperature, humidity, activity, co2, tvoc,
    illumination, infrared, infrared_and_visible,
    pressure, latitude, longitude, timestamp
) VALUES (
    '$device_name', '$dev_eui', '$room', $floor, '$building',
    $temperature, $humidity, $activity, $co2, $tvoc,
    $illumination, $infrared, $infrared_and_visible,
    $pressure, $latitude, $longitude, NOW()
);
EOF
        
        if [[ $? -eq 0 ]]; then
            log_message "Données insérées avec succès pour $device_name"
        else
            log_message "Erreur lors de l'insertion des données pour $device_name"
        fi
    fi
done