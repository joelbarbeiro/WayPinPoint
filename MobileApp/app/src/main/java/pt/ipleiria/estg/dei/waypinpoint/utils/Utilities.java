package pt.ipleiria.estg.dei.waypinpoint.utils;

import static android.content.Context.MODE_PRIVATE;
import static android.os.Looper.getMainLooper;

import android.Manifest;
import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.database.Cursor;
import android.net.Uri;
import android.os.Build;
import android.os.Handler;
import android.provider.MediaStore;
import android.widget.TextView;

import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

import Model.Category;
import pt.ipleiria.estg.dei.waypinpoint.R;

public class Utilities {
    public static final int ADD = 100;
    public static final int EDIT = 200;
    public static final int DELETE = 300;
    public static final int PICK_IMAGE = 400;
    public static final int REQUEST_CODE = 500;
    public static final int CHECKOUT = 600;
    public static final String OP_CODE = "DETAIL_OPERATION";
    public static final String EMAIL = "EMAIL";
    public static final String ID = "ID";
    public static final String APIHOST = "APIHOST";
    public static final String API_HOSTNAME = "API_HOSTNAME";
    public static final String USER_DATA = "USER_DATA";
    public static final String TOKEN = "TOKEN";
    public static final String NO_TOKEN = "NO TOKEN";

    public static final String SNACKBAR_MESSAGE = "SNACKBAR_MESSAGE";
    public static final String ID_CART = "ID_CART";
    public static final String ID_INVOICE = "ID_INVOICE";
    public static final int DB_VERSION = 4;
    public static final String CALENDAR_ID = "CALENDAR_ID";

    public static final String PROFILE_PIC = "PROFILE_PIC";
    public static final String BACKEND_PORT = ":8080";
    public static final String ID_REVIEW = "ID_REVIEW";
    public static final String USER_ID = "USER_ID";
    public static final String ACTIVITY_ID = "ACTIVITY_ID";
    public static final String TAG_QRCODEACTIVITY = "QRCodeScannerActivity";

    public static final String IMG_URI = "IMG_URI";
    public static final String PHOTOS_URI = "PHOTOS_URI";
    public static final String IMG_URI_USER = "IMG_URI_USER";
    public static final String BROKER_URL = "BROKER_URL";

    //region #ENDPOINTS TO SEND IMAGE
    public static final String ENDPOINT_ACTIVITY = "activity/photo";
    public static final String ENDPOINT_USER = "user/photo";
    public static final String MQTT_CREATE_ACTIVITY = "activity/created";
    public static final String MQTT_UPDATE_ACTIVITY = "activity/updated";
    public static final String MQTT_REVIEW_CREATE = "review/created";
    public static final String MQTT_REVIEW_UPDATE = "review/updated";

    //endregion

    public static String getApiHost(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(API_HOSTNAME, MODE_PRIVATE);
        System.out.println("--> Get Host " + sharedPreferences.getString(API_HOSTNAME, null));
        return sharedPreferences.getString(API_HOSTNAME, null);
    }

    public static int getUserId(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(USER_DATA, MODE_PRIVATE);
        return sharedPreferences.getInt(ID, 0);
    }

    public static void checkAndRequestMediaPermissions(Context context, Activity activity) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) { // Android 13+
            if (ContextCompat.checkSelfPermission(context, android.Manifest.permission.READ_MEDIA_IMAGES)
                    != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(activity, new String[]{android.Manifest.permission.READ_MEDIA_IMAGES}, REQUEST_CODE);
            }
        } else { // Android 6+
            if (ContextCompat.checkSelfPermission(context, Manifest.permission.READ_EXTERNAL_STORAGE)
                    != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(activity, new String[]{Manifest.permission.READ_EXTERNAL_STORAGE}, REQUEST_CODE);
            }
        }
    }

    public static void checkAndRequestCameraPermission(Context context, Activity activity) {
        if (ContextCompat.checkSelfPermission(context, android.Manifest.permission.CAMERA)
                != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(activity, new String[]{android.Manifest.permission.CAMERA}, REQUEST_CODE);
        }
    }

    public static void checkAndRequestNotificationPermissions(Context context, Activity activity) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            if (ContextCompat.checkSelfPermission(context, android.Manifest.permission.POST_NOTIFICATIONS) != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(activity, new String[]{Manifest.permission.POST_NOTIFICATIONS}, REQUEST_CODE);
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

    public static String getPhotosUri(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(PHOTOS_URI, MODE_PRIVATE);
        System.out.println("--> Get PHOTOS URI " + sharedPreferences.getString(PHOTOS_URI, null));
        return sharedPreferences.getString(PHOTOS_URI, null);
    }

    public static void setImgUri(String uri, Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(IMG_URI, MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        String imgPath = "http://" + uri + "/img/activity/";
        editor.putString(IMG_URI, imgPath);
        editor.apply();
    }

    public static void setPhotoUri(String uri, Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(PHOTOS_URI, MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        String imgPath = "http://" + uri + "/img/activity/photos";
        editor.putString(PHOTOS_URI, imgPath);
        editor.apply();
    }

    public static void setImgUriUser(String uri, Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(IMG_URI_USER, MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        String imgUserPath = "http://" + uri + "/img/user/";
        editor.putString(IMG_URI_USER, imgUserPath);
        editor.apply();
    }

    public static String getBrokerUri(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(BROKER_URL, MODE_PRIVATE);
        System.out.println("--> Get BROKER URL " + sharedPreferences.getString(BROKER_URL, null));
        return sharedPreferences.getString(BROKER_URL, null);
    }

    public static void setBrokerUrl(String uri, Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(BROKER_URL, MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        String tmpUrl = "tcp://" + uri + ":1883";
        editor.putString(BROKER_URL, tmpUrl);
        editor.apply();
    }

    public static double getPriceById(int activityId, ArrayList<Model.Activity> activities) {
        if (activities == null) {
            return 0;
        }
        for (Model.Activity activity : activities) {
            if (activity.getId() == activityId) {
                return activity.getPriceperpax();
            }
        }
        return 0;
    }

    public static String getCalendarDateById(int calendar_id, ArrayList<Model.Calendar> calendars) {
        if (calendars == null) {
            return null;
        }
        for (Model.Calendar calendar : calendars) {
            if (calendar.getId() == calendar_id) {
                return calendar.getDate();
            }
        }
        return null;
    }

    public static String getActivityNameById(int activityId, ArrayList<Model.Activity> activities) {
        if (activities == null) {
            return null;
        }
        for (Model.Activity activity : activities) {
            if (activity.getId() == activityId) {
                return activity.getName();
            }
        }
        return null;
    }

    public static String getCategoryById(int categoryId, ArrayList<Category> categories) {
        if (categories == null) {
            return null;
        }
        for (Category category : categories) {
            if (category.getId() == categoryId) {
                return category.getDescription();
            }
        }
        return null;
    }

    public static String getImgFromActivities(int activityId, ArrayList<Model.Activity> activities) {
        if (activities == null) {
            return null;
        }
        for (Model.Activity activity : activities) {
            if (activity.getId() == activityId) {
                return activity.getSupplier() + "/" + activity.getPhoto();
            }
        }
        return null;
    }

    public static String setDateFromTimestamp(int timestamp) {
        // Convert the timestamp (seconds since Unix Epoch) into a Date object
        Date date = new Date((long) timestamp * 1000); // Multiply by 1000 to convert to milliseconds

        // Create a SimpleDateFormat to format the date
        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss"); // You can change the format as needed

        // Format the date
        String formattedDate = dateFormat.format(date);

        return formattedDate;
    }

    public static ArrayList<Model.Activity> filterActivitiesBySupplier(Context context, ArrayList<Model.Activity> listActivities) {
        ArrayList<Model.Activity> filteredActivities = new ArrayList<>();
        for (Model.Activity a : listActivities) {
            if (Utilities.getUserId(context) == a.getSupplier()) {
                filteredActivities.add(a);
                System.out.println(">>> " + a);
            }
        }
        return filteredActivities;
    }

    public static boolean isQuantityValid(String input, Context context, TextView etQuantity) {
        if (input.isEmpty()) {
            etQuantity.setError(context.getString(R.string.error_quantity_empty));
            return false;
        }
        try {
            int quantity = Integer.parseInt(input);
            if (quantity <= 0) {
                etQuantity.setError(context.getString(R.string.error_quantity_zero));
                return false;
            }
        } catch (NumberFormatException e) {
            etQuantity.setError(context.getString(R.string.error_invalid_quantity));
            return false;
        }
        return true;
    }
}