package pt.ipleiria.estg.dei.waypinpoint.utils;

import static android.content.Context.MODE_PRIVATE;

import android.content.Context;
import android.content.SharedPreferences;

import pt.ipleiria.estg.dei.waypinpoint.MenuMainActivity;

public class Utilities {
    public static final int REGISTER = 100;
    public static final int EDIT = 200;
    public static final int DELETE = 300;
    public static final int PICK_IMAGE = 400;
    public static final String OP_CODE = "DETAIL_OPERATION";
    public static final String EMAIL = "EMAIL";
    public static final String ID = "ID";
    public static final String APIHOST = "APIHOST";
    public static final String API_HOSTNAME = "API_HOSTNAME";
    public static final String USER_DATA = "USER_DATA";
    public static final String TOKEN = "TOKEN";
    public static final String SNACKBAR_MESSAGE = "SNACKBAR_MESSAGE";

    public static String getApiHost(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(API_HOSTNAME, MODE_PRIVATE);
        System.out.println("--> Set Host " + sharedPreferences.getString(API_HOSTNAME, null));
        return sharedPreferences.getString(API_HOSTNAME, null);
    }

    public static int getUserId(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(USER_DATA, MODE_PRIVATE);
        return sharedPreferences.getInt(ID, 0);
    }
}