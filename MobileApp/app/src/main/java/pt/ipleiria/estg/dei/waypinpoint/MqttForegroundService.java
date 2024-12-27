package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.CHANNEL_ID;

import android.app.Notification;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.Service;
import android.content.Intent;
import android.os.Build;
import android.os.IBinder;

import androidx.annotation.Nullable;
import androidx.core.app.NotificationCompat;

import Model.MqttHelper;

public class MqttForegroundService extends Service implements MqttHelper.MessageListener {

    private MqttHelper mqttHelper;

    @Override
    public void onCreate(){
        super.onCreate();
        createNotificationChannel();

        Notification notification = new NotificationCompat.Builder(this, CHANNEL_ID)
                .setContentTitle("MQTT Service")
                .setContentText("Listening for MQTT messages...")
                .setSmallIcon(android.R.drawable.ic_dialog_info)
                .build();

        startForeground(1, notification);

        mqttHelper = new MqttHelper(this, this);

        mqttHelper.connect();
    }

    @Override
    public int onStartCommand (Intent intent, int flags, int startId){
        return START_STICKY;
    }

    @Override
    public void onDestroy(){
        super.onDestroy();
        if (mqttHelper != null){
            mqttHelper.disconnect();
        }
    }
    @Nullable
    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }

    private void createNotificationChannel(){
        if(Build.VERSION.SDK_INT >= Build.VERSION_CODES.O){
            NotificationChannel channel = new NotificationChannel(
                    CHANNEL_ID,
                    "MQTT Service Channel",
                    NotificationManager.IMPORTANCE_DEFAULT
            );
            NotificationManager manager = getSystemService(NotificationManager.class);
            if (manager != null){
                manager.createNotificationChannel(channel);
            }
        }
    }

    private void showNotification(String title, String message) {
        Notification notification = new NotificationCompat.Builder(this, CHANNEL_ID)
                .setContentTitle(title)
                .setContentText(message)
                .setSmallIcon(android.R.drawable.ic_dialog_info)
                .build();

        NotificationManager manager = (NotificationManager) getSystemService(NotificationManager.class);
        if (manager != null) {
            manager.notify((int) System.currentTimeMillis(), notification);
        }
    }
    @Override
    public void onMessageReceived(String topic, String message) {
        showNotification("New MQTT Message", "Topic: " + topic + ", Message: " + message);
    }
}
