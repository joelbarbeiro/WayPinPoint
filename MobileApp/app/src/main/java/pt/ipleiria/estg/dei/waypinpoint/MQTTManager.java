package pt.ipleiria.estg.dei.waypinpoint;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;


import androidx.appcompat.app.AppCompatActivity;

import org.eclipse.paho.client.mqttv3.IMqttActionListener;
import org.eclipse.paho.client.mqttv3.IMqttDeliveryToken;
import org.eclipse.paho.client.mqttv3.IMqttToken;
import org.eclipse.paho.client.mqttv3.MqttCallback;
import org.eclipse.paho.client.mqttv3.MqttClient;
import org.eclipse.paho.client.mqttv3.MqttException;
import org.eclipse.paho.client.mqttv3.MqttMessage;

import pt.ipleiria.estg.dei.waypinpoint.utils.Utilities;


public class MQTTManager extends AppCompatActivity {
    private static final String TAG = "->> MQTTManager";
    private String BROKER_URL;
    private static final String CLIENT_ID = MqttClient.generateClientId();

    private MqttClient mqttClient;

    private static MQTTManager instance;

    private MQTTManager(Context context) {
        BROKER_URL = Utilities.getBrokerUri(context);
        try {
            mqttClient = new MqttClient(BROKER_URL, CLIENT_ID, null);

            // Set the callback to handle messages and connection states
            mqttClient.setCallback(new MqttCallback() {
                @Override
                public void messageArrived(String topic, MqttMessage message) throws Exception {
                    // This method is called when a message is received
                    Log.d(TAG, "Message arrived on topic: " + topic);
                    Log.d(TAG, "Message: " + new String(message.getPayload()));

                    String receivedMessage = new String(message.getPayload());

                    System.out.println(TAG + " " + receivedMessage);
                }

                @Override
                public void deliveryComplete(IMqttDeliveryToken token) {

                }

                @Override
                public void connectionLost(Throwable cause) {
                    Log.e(TAG, "Connection lost", cause);
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
            if (!mqttClient.isConnected()) {
                mqttClient.connect();
                System.out.println(TAG + " Connected");
            }
        } catch (MqttException e) {
            System.out.println(TAG + "Error connecting to broke " + e);
            Log.e(TAG, "Error connecting to broker", e);
        }
    }

    public void disconnect(IMqttActionListener callback) {
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
                System.out.println(TAG + " subscribed to " + topic);
            } else {
                Log.e(TAG, "Cannot subscribe, client not connected");
            }
        } catch (MqttException e) {
            Log.e(TAG, "Error subscribing to topic", e);
        }
    }
}