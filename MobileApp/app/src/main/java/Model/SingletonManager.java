package Model;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DEFAULT_IMG;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EMAIL;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.REGISTER;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.TOKEN;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.USER_DATA;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getApiHost;

import android.content.Context;
import android.content.SharedPreferences;
import android.net.http.UrlRequest;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import Listeners.ActivitiesListener;
import Listeners.ActivityListener;
import Listeners.LoginListener;
import Listeners.UserListener;
import pt.ipleiria.estg.dei.waypinpoint.R;
import pt.ipleiria.estg.dei.waypinpoint.utils.ActivityJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.StatusJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.UserJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.Utilities;

public class SingletonManager {

    private static SingletonManager instance = null;
    private UserDbHelper userDbHelper = null;

    private ArrayList<User> users;

    private UserListener userListener;
    private LoginListener loginListener;

    //region # Activities instances #
    private ActivityDbHelper activityDbHelper = null;

    private ActivitiesListener activitiesListener;
    private ArrayList<Activity> activities;

    private ActivityListener activityListener;


    //endregion

    private static RequestQueue volleyQueue = null;

    public SingletonManager(Context context) {
        users = new ArrayList<>();
        userDbHelper = new UserDbHelper(context);

        activities = new ArrayList<>();
        activityDbHelper = new ActivityDbHelper(context);
    }

    public static synchronized SingletonManager getInstance(Context context) {
        if (instance == null) {
            instance = new SingletonManager(context);
            volleyQueue = Volley.newRequestQueue(context);
        }
        return instance;
    }

    //REGISTER LISTENERS
    public void setUserListener(UserListener userListener) {
        this.userListener = userListener;
    }

    public void setLoginListener(LoginListener loginListener) {
        this.loginListener = loginListener;
    }

    public ArrayList<User> getUsersBD() {
        users = userDbHelper.getAllUsersDb();
        return new ArrayList<>(users);
    }

    public User getUser(int id) {
        users = getUsersBD();
        for (User user : users) {
            if (user.getId() == id) {
                return user;
            }
        }
        return null;
    }

    public void editPhotoDb(String photo, int id) {
        userDbHelper.editPhotoDb(photo, id);
    }

    public void addUserDb(User user) {
        userDbHelper.addUserDb(user);
    }

    public void editUserDb(User user) {
        userDbHelper.editUserDb(user);
    }

    public void removeUserDb(int userId) {
        User b = getUser(userId);
        if (b != null) {
            userDbHelper.removeUserDb(b.getId());
        }
    }

    //region = API USER METHODS #

    public void addUserApi(String apiHost, final User user, final Context context) {
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            StringRequest request = new StringRequest(Request.Method.POST, apiHost + "user/register", new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    addUserDb(UserJsonParser.parserJsonUser(response));
                    if (userListener != null) {
                        userListener.onValidateOperation(REGISTER);
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("---> " + error.getMessage());
                    Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }) {
                @Override
                protected Map<String, String> getParams() {
                    Map<String, String> params = new HashMap<String, String>();
                    params.put("id", "" + user.getId());
                    params.put("username", user.getUsername());
                    params.put("email", user.getEmail());
                    params.put("password", user.getPassword());
                    params.put("address", user.getAddress());
                    params.put("phone", "" + user.getPhone());
                    params.put("nif", "" + user.getNif());
                    params.put("photo", user.getPhoto() == null ? DEFAULT_IMG : user.getPhoto());
                    return params;
                }
            };
            volleyQueue.add(request);
        }
    }

    public void editUserApi(String apiHost, final User user, final Context context) {
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            StringRequest request = new StringRequest(Request.Method.PUT, apiHost + "users/update/" + user.getId(), new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    editUserDb(UserJsonParser.parserJsonUser(response));
                    if (userListener != null) {
                        userListener.onValidateOperation(EDIT);
                    }
                    System.out.println("---> EDIT USER RESPONSE: " + response);
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("---> " + error.getMessage());
                    Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }) {
                @Override
                protected Map<String, String> getParams() {
                    Map<String, String> params = new HashMap<String, String>();
                    params.put("username", user.getUsername());
                    params.put("email", user.getEmail());
                    params.put("address", user.getAddress());
                    params.put("phone", "" + user.getPhone());
                    params.put("nif", "" + user.getNif());
                    params.put("photoFile", user.getPhoto() == null ? String.valueOf(R.drawable.ic_default_profile) : user.getPhoto());
                    return params;
                }
            };
            System.out.println("---> REQUEST  USER" + request);

            volleyQueue.add(request);
        }
    }

    public void removeUserApi(String apiHost, final User user, final Context context) {
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            StringRequest request = new StringRequest(Request.Method.DELETE, apiHost + "users/" + user.getUsername(), new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    removeUserDb(user.getId());

                    if (userListener != null) {
                        userListener.onValidateOperation(DELETE);
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                }
            });
            volleyQueue.add(request);
        }
    }

    public void addPhotoApi(String apiHost, final int id, final String photoProfile, final Context context) {
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            StringRequest request = new StringRequest(Request.Method.PUT, apiHost + "user/photo", new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    System.out.println("---> SUCCESS NO RESPONSE " + response);
                    editPhotoDb(UserJsonParser.parserJsonPhoto(response), id);
                    Toast.makeText(context, "Photo Uploaded Successfully", Toast.LENGTH_SHORT).show();
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("---> ERRO NO RESPONSE " + error.getMessage());
                    Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }) {

                @Override
                public Map<String, String> getParams() {
                    Map<String, String> params = new HashMap<>();
                    params.put("id", "" + id);
                    params.put("photoFile", photoProfile);
                    return params;
                }
            };
            System.out.println("---> REQUEST  " + request);
            volleyQueue.add(request);
        }
    }

    //endregion

    //region # LOGIN API #
    public void loginAPI(String apiHost, final String email, final String password, final Context context, final LoginListener listener) {
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
            listener.onErrorLogin(context.getString(R.string.error_no_internet));
        } else {
            StringRequest request = new StringRequest(Request.Method.POST, apiHost + "users/login", new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    System.out.println("---> SUCCESS Login " + response);
                    addUserDb(UserJsonParser.parserJsonUser(response));
                    SharedPreferences sharedPreferences = context.getSharedPreferences(USER_DATA, Context.MODE_PRIVATE);
                    SharedPreferences.Editor editor = sharedPreferences.edit();
                    try {
                        JSONObject jsonObject = new JSONObject(response);
                        String token = jsonObject.getString("token");
                        int id = jsonObject.getInt("id");
                        editor.putString(TOKEN, token);
                        editor.putString(EMAIL, email);
                        editor.putInt(ID, id);
                        editor.apply();
                        listener.onValidateLogin(token);
                    } catch (JSONException e) {
                        e.printStackTrace();
                        listener.onErrorLogin(context.getString(R.string.login_error_parse_response));
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("---> ERROR Login" + error.getMessage() + error);
                    SharedPreferences sharedPreferences = context.getSharedPreferences(USER_DATA, Context.MODE_PRIVATE);
                    SharedPreferences.Editor editor = sharedPreferences.edit();
                    if (error.networkResponse.statusCode == 401) {
                        Toast.makeText(context, R.string.login_invalid_login, Toast.LENGTH_SHORT).show();
                        listener.onErrorLogin(error.getMessage());
                    } else {
                        Toast.makeText(context, R.string.login_error_message, Toast.LENGTH_SHORT).show();
                        listener.onErrorLogin(error.getMessage());
                    }
                    editor.putString(TOKEN, "NO TOKEN");
                    editor.apply();
                    listener.onErrorLogin(error.getMessage());
                }
            }) {
                protected Map<String, String> getParams() {
                    Map<String, String> params = new HashMap<String, String>();
                    params.put("email", email);
                    params.put("password", password);

                    return params;

                }
            };
            volleyQueue.add(request);
        }
    }
    //endregion

    //region # Activity API #
    public void setActivitiesListener(ActivitiesListener activitiesListener) {
        this.activitiesListener = activitiesListener;
    }

    public void setActivityListener(ActivityListener activityListener) {
        this.activityListener = activityListener;
    }

    public ArrayList<Activity> getActivities(){
        activities = activityDbHelper.getActivitiesDB();
        return new ArrayList<>(activities);
    }

    public Activity getActivity(int id){
        for(Activity a: activities){
            if(a.getId() == id){
                return a;
            }
        }
        return null;
    }
    public void addActivitiesDB(ArrayList<Activity> activities){
        //activityDbHelper.delAllActivitiesDB();
        for(Activity a: activities){
            System.out.println("DB Add --> " + a);
            activityDbHelper.addActivityDB(a);
        }
    }
    public void getActivities( final Context context){
        String apiHost = Utilities.getApiHost(context);
        if(!StatusJsonParser.isConnectionInternet(context)){
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();

            if(activitiesListener != null){
                activitiesListener.onRefreshActivitiesList(activityDbHelper.getActivitiesDB());
            }
        } else {
            JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, apiHost + "activities", null, new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray response) {
                    System.out.println("--> GETAPI: " + response);
                    activities = ActivityJsonParser.parserJsonActivity(response);
                    addActivitiesDB(activities);

                    if(activitiesListener != null){
                        activitiesListener.onRefreshActivitiesList(activities);
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("--> GET --> " + error);

                }
            });
        volleyQueue.add(request);
        }
    }

    //endregion

}
