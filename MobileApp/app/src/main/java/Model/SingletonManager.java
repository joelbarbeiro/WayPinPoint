package Model;

import android.content.Context;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.Volley;

public class SingletonManager {

    private static SingletonManager instance = null;

    private UserDbHelper userDbHelper = null;

    private static RequestQueue volleyQueue = null;

    public SingletonManager(Context context) {
        userDbHelper = new UserDbHelper(context);
    }

    public static synchronized SingletonManager getInstance(Context context) {
        if (instance == null) {
            instance = new SingletonManager(context);
            volleyQueue = Volley.newRequestQueue(context);
        }
        return instance;
    }
}
