package Model;


import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ACTIVITY_SUBS;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.MOSQUITTO_CLIENT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getMosquittoUri;

import android.content.Context;

import androidx.annotation.Nullable;

import org.eclipse.paho.android.service.MqttAndroidClient;
import org.eclipse.paho.client.mqttv3.IMqttActionListener;
import org.eclipse.paho.client.mqttv3.IMqttDeliveryToken;
import org.eclipse.paho.client.mqttv3.IMqttToken;
import org.eclipse.paho.client.mqttv3.MqttCallbackExtended;
import org.eclipse.paho.client.mqttv3.MqttConnectOptions;
import org.eclipse.paho.client.mqttv3.MqttException;
import org.eclipse.paho.client.mqttv3.MqttMessage;

import pt.ipleiria.estg.dei.waypinpoint.utils.Utilities;

public class MqttHelper {
    private MqttAndroidClient mqttAndroidClient;
    private MessageListener messageListener;


    public MqttHelper(Context context, MessageListener listener){
        this.messageListener = listener;
        mqttAndroidClient = new MqttAndroidClient(context, getMosquittoUri(context), MOSQUITTO_CLIENT);

        mqttAndroidClient.setCallback(new MqttCallbackExtended() {
            @Override
            public void connectComplete(boolean reconnect, String serverURI) {
                System.out.println("-->> Connection to: " + getMosquittoUri(context));

                subscribeToTopic();
            }

            @Override
            public void connectionLost(Throwable cause) {
                System.out.println("-->> Connection lost: " + cause.getMessage());
            }

            @Override
            public void messageArrived(String topic, MqttMessage message) throws Exception {
                System.out.println("-->> Message received: " + new String(message.getPayload()));
                if (messageListener != null) {
                    messageListener.onMessageReceived(topic, new String(message.getPayload()));
                }
            }

            @Override
            public void deliveryComplete(IMqttDeliveryToken token) {
                System.out.println("-->> Message delivered!");
            }
        });
        connect();
    }

    public void connect(){
        try {
            MqttConnectOptions options = new MqttConnectOptions();
            options.setAutomaticReconnect(true);
            options.setCleanSession(true);

            mqttAndroidClient.connect(options, null, new IMqttActionListener() {
                @Override
                public void onSuccess(IMqttToken asyncActionToken) {
                    System.out.println("-->> Subscribed topic");
                }

                @Override
                public void onFailure(IMqttToken asyncActionToken, Throwable exception) {
                    System.out.println("-->> Connection failed: " + exception.getMessage());
                }
            });
        } catch (MqttException e) {
            System.out.println("-->> Connect" + e.getMessage());
        }
    }

    private void subscribeToTopic(){
        try {
            mqttAndroidClient.subscribe(ACTIVITY_SUBS, 1, null, new IMqttActionListener() {
                @Override
                public void onSuccess(IMqttToken asyncActionToken) {
                    System.out.println("-->> Subscribed to topic new Activities");
                }

                @Override
                public void onFailure(IMqttToken asyncActionToken, Throwable exception) {
                    System.out.println("-->> Subscription failed");
                }
            });
        } catch (Exception e){
            System.out.println("-->> Subscribe to topic" + e.getMessage());
        }
    }

    public void disconnect(){
        try {
            mqttAndroidClient.disconnect();
        } catch (MqttException e) {
            System.out.println("-->> disconnect" + e.getMessage());
        }
    }
    public interface MessageListener {
        void onMessageReceived(String topic, String message);
    }
}
