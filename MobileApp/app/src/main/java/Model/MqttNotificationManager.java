package Model;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.MQTT_CREATE_ACTIVITY;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.MQTT_REVIEW_CREATE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.MQTT_UPDATE_ACTIVITY;

import android.content.Context;
import androidx.core.app.NotificationCompat;
import Listeners.MosquittoListener;
import pt.ipleiria.estg.dei.waypinpoint.R;
import pt.ipleiria.estg.dei.waypinpoint.utils.ActivityJsonParser;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.os.Build;

public class MqttNotificationManager implements MosquittoListener {
    private static final String CHANNEL_ID = "MQTT_NOTIFICATION_CHANNEL";
    private Context context;
    private MosquittoListener mosquittoListener;
    public MqttNotificationManager(Context context) {
        if (context == null) {
            throw new IllegalArgumentException("Context cannot be null");
        }
        this.context = context.getApplicationContext();
        createNotificationChannel();
    }

    public void onMessageReceived(String topic, String mqttMessage) {

        switch (topic){
            case MQTT_CREATE_ACTIVITY:
                Activity createActivity = ActivityJsonParser.parserJsonActivity(mqttMessage);
                String message = "Price " + createActivity.getPriceperpax() + " Max participants " + createActivity.getMaxpax() + " Location " + createActivity.getAddress();
                sendNotification("New activity " + createActivity.getName(), message);
                break;
            case MQTT_UPDATE_ACTIVITY:
                Activity updateActivity = ActivityJsonParser.parserJsonActivity(mqttMessage);
                String updateMessage = "Price " + updateActivity.getPriceperpax() + " Max participants " + updateActivity.getMaxpax() + " Location " + updateActivity.getAddress();
                sendNotification("Activity " + updateActivity.getName() + " update!", updateMessage);
                break;
            case MQTT_REVIEW_CREATE:

                break;
            default:
                break;

        }
    }
    private void sendNotification(String title, String message) {
        NotificationCompat.Builder builder = new NotificationCompat.Builder(context.getApplicationContext(), CHANNEL_ID)
                .setSmallIcon(R.drawable.waypinpointfront)
                .setContentTitle(title)
                .setContentText(message)
                .setPriority(NotificationCompat.PRIORITY_HIGH)
                .setAutoCancel(true);

        NotificationManager notificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
        if (notificationManager != null) {
            notificationManager.notify((int) System.currentTimeMillis(), builder.build());
        }
    }

    private void createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            CharSequence name = "MQTT Notifications";
            String description = "Channel for MQTT notifications";
            int importance = NotificationManager.IMPORTANCE_HIGH;
            NotificationChannel channel = new NotificationChannel(CHANNEL_ID, name, importance);
            channel.setDescription(description);

            NotificationManager notificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
            if (notificationManager != null) {
                notificationManager.createNotificationChannel(channel);
            }
        }
    }
}
