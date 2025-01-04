package Model;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.MQTT_CREATE_ACTIVITY;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.MQTT_UPDATE_ACTIVITY;

import android.content.Context;
import android.util.Log;

import org.eclipse.paho.client.mqttv3.IMqttDeliveryToken;
import org.eclipse.paho.client.mqttv3.MqttCallback;
import org.eclipse.paho.client.mqttv3.MqttClient;
import org.eclipse.paho.client.mqttv3.MqttException;
import org.eclipse.paho.client.mqttv3.MqttMessage;

import Listeners.MosquittoListener;
import pt.ipleiria.estg.dei.waypinpoint.utils.Utilities;

public class MQTTManager {
    private static final String TAG = "->> MQTTManager";
    private String BROKER_URL;
    private static final String CLIENT_ID = MqttClient.generateClientId();
    private MqttClient mqttClient;
    private static MQTTManager instance;
    private MosquittoListener messageListener;

    public MQTTManager(Context context) {
        BROKER_URL = Utilities.getBrokerUri(context);
        try {
            mqttClient = new MqttClient(BROKER_URL, CLIENT_ID, null);

            mqttClient.setCallback(new MqttCallback() {
                @Override
                public void messageArrived(String topic, MqttMessage message) throws Exception {
                    Log.d(TAG, "Message arrived on topic: " + topic);
                    Log.d(TAG, "Message: " + new String(message.getPayload()));

                    if (messageListener != null) {
                        messageListener.onMessageReceived(topic, String.valueOf(message));
                    }
                }

                @Override
                public void deliveryComplete(IMqttDeliveryToken token) {

                }

                @Override
                public void connectionLost(Throwable cause) {
                    Log.e(TAG, "Connection lost", cause);
                    while (!mqttClient.isConnected()) {
                        Log.d(TAG, "Attempting to reconnect...");
                        connect();
                        try {
                            Thread.sleep(5000);
                        } catch (Exception e) {
                            Thread.currentThread().interrupt();
                        }
                    }
                }

            });

        } catch (MqttException e) {
            throw new RuntimeException(e);
        }
    }

    public static synchronized MQTTManager getInstance(Context context) {
        if (instance == null) {
            instance = new MQTTManager(context);
        }
        return instance;
    }

    public void connect() {
        try {
                mqttClient.connect();
                Log.d(TAG, "Connected to server");
                subscribe(MQTT_CREATE_ACTIVITY);
                subscribe(MQTT_UPDATE_ACTIVITY);
        } catch (MqttException e) {
            Log.e(TAG, "Error connecting to broker", e);
        }
    }

    public void disconnect() {
        try {
            if (mqttClient.isConnected()) {
                mqttClient.disconnect();
            }
        } catch (MqttException e) {
            Log.e(TAG, "Error disconnecting from broker", e);
        }
    }

    public void subscribe(String topic) {
        try {
            if (mqttClient.isConnected()) {
                mqttClient.subscribe(topic);
                Log.d(TAG, " Subscribed " + topic);
            } else {
                Log.e(TAG, "Cannot subscribe, client not connected");
            }
        } catch (MqttException e) {
            Log.e(TAG, "Error subscribing to topic", e);
        }
    }

    public void setMosquittoListener(MqttNotificationManager mqttNotificationManager) {
        this.messageListener = mqttNotificationManager;
    }
}