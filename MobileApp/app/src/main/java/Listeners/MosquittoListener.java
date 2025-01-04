package Listeners;

public interface MosquittoListener {
    void onMessageReceived(String topic, String message);
}
