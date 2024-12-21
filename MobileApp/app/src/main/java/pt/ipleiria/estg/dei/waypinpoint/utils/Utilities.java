package pt.ipleiria.estg.dei.waypinpoint.utils;

import static android.content.Context.MODE_PRIVATE;
import static android.os.Looper.getMainLooper;

import android.Manifest;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.database.Cursor;
import android.net.Uri;
import android.os.Build;
import android.os.Handler;
import android.provider.MediaStore;

import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import pt.ipleiria.estg.dei.waypinpoint.LoginActivity;
import pt.ipleiria.estg.dei.waypinpoint.MenuMainActivity;

public class Utilities {
    public static final int REGISTER = 100;
    public static final int EDIT = 200;
    public static final int DELETE = 300;
    public static final int PICK_IMAGE = 400;
    public static final int REQUEST_CODE = 500;
    public static final String OP_CODE = "DETAIL_OPERATION";
    public static final String EMAIL = "EMAIL";
    public static final String ID = "ID";
    public static final String APIHOST = "APIHOST";
    public static final String API_HOSTNAME = "API_HOSTNAME";
    public static final String USER_DATA = "USER_DATA";
    public static final String TOKEN = "TOKEN";
    public static final String SNACKBAR_MESSAGE = "SNACKBAR_MESSAGE";
    public static final String DEFAULT_IMG = "https://images.app.goo.gl/WRUpq3qmgD331B64A";
    public static final String PROFILE_PIC = "PROFILE_PIC";
    public static final String BACKEND_PORT = ":8080";

    public static final String DB_VERSION = "DB_VERSION";
    public static final String IMG_URI = "IMG_URI";
    public static final String IMG_URI_USER = "IMG_URI_USER";

    public static String getApiHost(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(API_HOSTNAME, MODE_PRIVATE);
        System.out.println("--> Get Host " + sharedPreferences.getString(API_HOSTNAME, null));
        return sharedPreferences.getString(API_HOSTNAME, null);
    }

    public static int getUserId(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(USER_DATA, MODE_PRIVATE);
        return sharedPreferences.getInt(ID, 0);
    }

    public static void toastDuration(int duration){
        new Handler(getMainLooper()).postDelayed(() -> {
        }, duration);
    }

    public static String getRealPathFromURI(Uri uri, Context context) {
        String filePath = null;
        if ("content".equalsIgnoreCase(uri.getScheme())) {
            String[] projection = {MediaStore.Images.Media.DATA};
            try (Cursor cursor = context.getContentResolver().query(uri, projection, null, null, null)) {
                if (cursor != null && cursor.moveToFirst()) {
                    int columnIndex = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
                    filePath = cursor.getString(columnIndex);
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        } else if ("file".equalsIgnoreCase(uri.getScheme())) {
            filePath = uri.getPath();
        }
        return filePath;
    }

    public static void checkAndRequestPermissions(Context context, Activity activity) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) { // Android 13+
            if (ContextCompat.checkSelfPermission(context, android.Manifest.permission.READ_MEDIA_IMAGES)
                    != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(activity,
                        new String[]{android.Manifest.permission.READ_MEDIA_IMAGES},
                        REQUEST_CODE);
            }
        } else { // Android 6+
            if (ContextCompat.checkSelfPermission(context, Manifest.permission.READ_EXTERNAL_STORAGE)
                    != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(activity,
                        new String[]{Manifest.permission.READ_EXTERNAL_STORAGE},
                        REQUEST_CODE);
            }
        }
    }

    public static String getImgUri(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(IMG_URI, MODE_PRIVATE);
        System.out.println("--> Get IMG URI " + sharedPreferences.getString(IMG_URI, null));
        return sharedPreferences.getString(IMG_URI, null);
    }

    public static String getImgUriUser(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(IMG_URI_USER, MODE_PRIVATE);
        System.out.println("--> Get IMG URI FOR USER " + sharedPreferences.getString(IMG_URI_USER, null));
        return sharedPreferences.getString(IMG_URI_USER, null);
    }

    public static void setImgUri(String uri, Context context){
        SharedPreferences sharedPreferences = context.getSharedPreferences(IMG_URI, MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        String imgPath = "http://" + uri + "/img/activity/";
        editor.putString(IMG_URI, imgPath);
        editor.apply();
    }

    public static void setImgUriUser(String uri, Context context){
        SharedPreferences sharedPreferences = context.getSharedPreferences(IMG_URI_USER, MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        String imgUserPath = "http://" + uri + "/img/user/";
        editor.putString(IMG_URI_USER, imgUserPath);
        editor.apply();
    }

}