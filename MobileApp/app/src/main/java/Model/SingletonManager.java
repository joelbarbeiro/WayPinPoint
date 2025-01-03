package Model;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.CHECKOUT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EMAIL;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.REGISTER;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.TOKEN;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.USER_DATA;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getApiHost;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.content.Context;
import android.content.SharedPreferences;
import android.net.Uri;
import android.util.Base64;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.NetworkError;
import com.android.volley.NetworkResponse;
import com.android.volley.ParseError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.ServerError;
import com.android.volley.TimeoutError;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import Listeners.ActivitiesListener;
import Listeners.ActivityListener;
import Listeners.CartListener;
import Listeners.CartsListener;
import Listeners.LoginListener;
import Listeners.PhotosListener;
import Listeners.ReviewListener;
import Listeners.ReviewsListener;
import Listeners.UserListener;
import pt.ipleiria.estg.dei.waypinpoint.R;
import pt.ipleiria.estg.dei.waypinpoint.utils.ActivityJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.CalendarJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.CartJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.CategoryJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.ImageSender;
import pt.ipleiria.estg.dei.waypinpoint.utils.ReviewJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.StatusJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.TimeJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.UserJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.Utilities;

public class SingletonManager {

    private static SingletonManager instance = null;
    private WaypinpointDbHelper waypinpointDbHelper = null;

    //region # Users instances #
    private ArrayList<User> users;
    private UserListener userListener;
    private LoginListener loginListener;
    //endregion

    //region # Photos instances #
    private PhotosListener photosListener;
    //endregion

    //region # Reviews instances #
    private ReviewsListener reviewsListener;
    private ReviewListener reviewListener;
    private ArrayList<Review> reviews;
    //endregion

    //Region Cart Instances
    private CartListener cartListener;
    private CartsListener cartsListener;

    private ArrayList<Cart> carts;
    private Cart cart;
    //endregion
    //region # Activities instances #

    private ActivitiesListener activitiesListener;
    private ActivityListener activityListener;

    private ArrayList<Activity> activities;
    private ArrayList<Calendar> calendars;
    private ArrayList<CalendarTime> calendarTimes;
    private ArrayList<Category> categories;

    //endregion

    private static RequestQueue volleyQueue = null;

    public SingletonManager(Context context) {
        waypinpointDbHelper = new WaypinpointDbHelper(context);

        users = new ArrayList<>();
        carts = new ArrayList<>();
        activities = new ArrayList<>();
        reviews = new ArrayList<>();
        calendars = new ArrayList<>();
        calendarTimes = new ArrayList<>();
        categories = new ArrayList<>();
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
        users = waypinpointDbHelper.getAllUsersDb();
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
        waypinpointDbHelper.editPhotoDb(photo, id);
    }

    public void addUserDb(User user) {
        waypinpointDbHelper.addUserDb(user);
    }

    public void editUserDb(User user) {
        waypinpointDbHelper.editUserDb(user);
    }

    public void removeUserDb(int userId) {
        User b = getUser(userId);
        if (b != null) {
            waypinpointDbHelper.removeUserDb(b.getId());
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
                    params.put("photo", "null");
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
    //endregion

    //region = API PHOTOS METHODS #

    public void setPhotosListener(PhotosListener photosListener) {
        this.photosListener = photosListener;
    }

    public void getAllPhotos(final Context context, final int activityId) {
        String apiHost = getApiHost(context);
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, apiHost + "activities/pictures/" + activityId, null, new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray response) {
                    try {
                        ArrayList<String> photos = new ArrayList<>();
                        for (int i = 0; i < response.length(); i++) {
                            String photoUrl = response.getString(i); // Extract URLs from the response
                            photos.add(photoUrl);
                        }
                        System.out.println("---> PHOTOS: " + photos);
                        // Notify listener with the updated photos list
                        if (photosListener != null) {
                            photosListener.onRefreshPhotosList(photos);
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                        Toast.makeText(context, "Error parsing JSON response", Toast.LENGTH_SHORT).show();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("---> GET PHOTOS ERROR --> " + error);
                }
            });
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
    //REGION # MÉTODOS CART - API #

    public ArrayList<Cart> getCartsDB(ArrayList<Cart> carts) {
        carts = waypinpointDbHelper.getAllCartsDb();
        return new ArrayList<>(carts);
    }

    public void addCartsDB(ArrayList<Cart> carts) {
        waypinpointDbHelper.removeAllCartDb();
        for (Cart c : carts) {
            waypinpointDbHelper.addCartDb(c);
        }
    }

    public ArrayList<Cart> getCarts() {
        carts = waypinpointDbHelper.getAllCartsDb();
        return new ArrayList<>(carts);
    }

    public void removeCartDb(int cartId) {
        Cart cart = getCart(cartId);
        if (cart != null) {
            waypinpointDbHelper.removeCartDb(cart.getId());
        }
    }

    public Cart getCart(int id) {
        carts = getCarts();
        for (Cart cart : carts) {
            if (cart.getId() == id) {
                return cart;
            }
        }
        return null;
    }

    public void setCartsListener(CartsListener cartsListener) {
        this.cartsListener = cartsListener;
    }

    public void setCartListener(CartListener cartListener) {
        this.cartListener = cartListener;
    }

    public void getCartAPI(final Context context, final CartListener listener, final Cart cart) {
        String apiHost = getApiHost(context);
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
            if (cartsListener != null) {
                cartsListener.onRefreshCartList(waypinpointDbHelper.getCartByUserId(getUserId(context)));
            }
        } else {
            JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, apiHost + "carts/" + cart.getId(), null, new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray response) {
                    System.out.println("------> Cart Data: " + response.toString());
                    Cart cart = CartJsonParser.parserJsonCart(response.toString());
                    waypinpointDbHelper.addCartDb(cart);
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("Error fetching cart");
                    Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                }
            });

            volleyQueue.add(request);
        }
    }

    public void getCartByUserId(final Context context) {
        User user = getUser(getUserId(context));
        String apiHost = getApiHost(context);
        int userId = getUserId(context);
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
            if (cartsListener != null) {
                cartsListener.onRefreshCartList(waypinpointDbHelper.getCartByUserId(userId));
            }
        } else {
            JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, apiHost + "carts/buyers/" + userId, null, new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray response) {
                    System.out.println("--> CARTGETAPI: " + response);
                    carts = CartJsonParser.parserJsonCarts(response);
                    addCartsDB(carts);
                    if (cartsListener != null) {
                        cartsListener.onRefreshCartList(carts);
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("--> GET --> " + error);

                }
            }) {
                @Override
                public Map<String, String> getHeaders() throws AuthFailureError {
                    Map<String, String> headers = new HashMap<>();
                    // Add Basic Authentication Header
                    String username = user.getUsername(); // Replace with your username
                    String password = user.getPassword(); // Replace with your password
                    String credentials = username + ":" + password;
                    String auth = "Basic " + Base64.encodeToString(credentials.getBytes(), Base64.NO_WRAP);
                    headers.put("Authorization", auth);
                    return headers;
                }
            };

            volleyQueue.add(request);
        }
    }

    public void addCartApi(final Cart cart, final Context context) {
        User user = getUser(getUserId(context));
        String apiHost = getApiHost(context);
        StringRequest request = new StringRequest(Request.Method.POST, apiHost + "carts/addcart", new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jsonResponse = new JSONObject(response);
                    System.out.println("------> Cart Data: " + response);
                    waypinpointDbHelper.addCartDb(CartJsonParser.parserJsonCart(response));
                    if (jsonResponse.getBoolean("success")) {
                        if (cartListener != null) {
                            cartListener.onValidateOperation(REGISTER);
                        }
                    } else {
                        Toast.makeText(context, "Error: " + jsonResponse.getString("errors"), Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if (error.networkResponse != null) {
                    int statusCode = error.networkResponse.statusCode;
                    String responseBody = new String(error.networkResponse.data);
                    Toast.makeText(context, "Server Error: " + statusCode + "\n" + responseBody, Toast.LENGTH_LONG).show();
                    Log.e("ServerError", "Code: " + statusCode + " Response: " + responseBody);
                } else {
                    Toast.makeText(context, "No response from server.", Toast.LENGTH_SHORT).show();
                    Log.e("ServerError", "No response from server.");
                }
                Toast.makeText(context, "Error: " + error.getMessage(), Toast.LENGTH_SHORT).show();
            }
        }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("user_id", String.valueOf(getUserId(context)));
                params.put("product_id", String.valueOf(cart.getProduct_id()));
                params.put("quantity", String.valueOf(cart.getQuantity()));
                params.put("status", "0");
                params.put("calendar_id", String.valueOf(cart.getCalendar_id()));

                return params;
            }

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String> headers = new HashMap<>();
                // Add Basic Authentication Header
                String username = user.getUsername(); // Replace with your username
                String password = user.getPassword(); // Replace with your password
                String credentials = username + ":" + password;
                String auth = "Basic " + Base64.encodeToString(credentials.getBytes(), Base64.NO_WRAP);
                headers.put("Authorization", auth);
                return headers;
            }
        };
        volleyQueue.add(request);
    }

    public void editCart(final Cart cart, final Context context) {
        String apiHost = getApiHost(context);
        StringRequest request = new StringRequest(Request.Method.PUT, apiHost + "carts/updatecart/" + cart.getId(),
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        waypinpointDbHelper.editCartDb(cart);
                        Toast.makeText(context, "Cart updated successfully", Toast.LENGTH_SHORT).show();
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(context, "Error: " + error.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("quantity", String.valueOf(cart.getQuantity()));
                if (cart.getCalendar_id() != 0) {
                    params.put("calendar_id", String.valueOf(cart.getCalendar_id()));
                }
                return params;
            }
        };
        volleyQueue.add(request);
    }

    public void removeCartAPI(final Context context, final Cart cart) {
        String apiHost = getApiHost(context);
        StringRequest request = new StringRequest(Request.Method.DELETE, apiHost + "carts/delete/" + cart.getId(),
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        waypinpointDbHelper.removeCartDb(cart.getId());
                        Toast.makeText(context, "Cart item deleted", Toast.LENGTH_SHORT).show();

                        if (cartListener != null) {
                            cartListener.onValidateOperation(DELETE);
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(context, "Error: " + error.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });
        volleyQueue.add(request);
    }

    public void checkoutCart(final Context context, final Cart cart) {
        String apiHost = getApiHost(context);
        StringRequest request = new StringRequest(Request.Method.POST, apiHost + "carts/checkout/" + cart.getId(), new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Toast.makeText(context, R.string.cart_checkout_success, Toast.LENGTH_SHORT).show();
                if (cartListener != null) {
                    cartListener.onValidateOperation(CHECKOUT);
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(context, R.string.cart_checkout_error, Toast.LENGTH_SHORT).show();
                String responseBody;
                try {
                    responseBody = new String(error.networkResponse.data, "UTF-8");
                    Log.e("Checkout ", "Error Response Body: " + responseBody);
                    Log.e("Checkout", "Error Message: " + error.getMessage());
                } catch (Exception e) {
                    e.printStackTrace();
                }

            }
        });
        volleyQueue.add(request);
    }

    //ENDREGION

    //region # Activity API #
    public void setActivitiesListener(ActivitiesListener activitiesListener) {
        this.activitiesListener = activitiesListener;
    }

    public void setActivityListener(ActivityListener activityListener) {
        this.activityListener = activityListener;
    }

    public ArrayList<Activity> getActivities() {
        activities = waypinpointDbHelper.getActivitiesDB();
        return new ArrayList<>(activities);
    }

    public Activity getActivity(int id) {
        for (Activity a : activities) {
            if (a.getId() == id) {
                return a;
            }
        }
        return null;
    }

    public void addActivitiesDB(ArrayList<Activity> activities) {
        waypinpointDbHelper.delAllActivitiesDB();
        for (Activity a : activities) {
            System.out.println("DB Add --> " + a);
            waypinpointDbHelper.addActivityDB(a);
        }
    }

    public void getActivities(final Context context) {
        String apiHost = getApiHost(context);
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();

            if (activitiesListener != null) {
                activitiesListener.onRefreshAllData(waypinpointDbHelper.getActivitiesDB(), waypinpointDbHelper.getCalendarDB(), waypinpointDbHelper.getCalendarTimeDB(), waypinpointDbHelper.getCategoryDB());
            }
        } else {
            final int totalRequests = 4;
            final int[] completedRequests = {0};

            Runnable onRequestCompleted = () -> {
                completedRequests[0]++;
                if (completedRequests[0] == totalRequests && activitiesListener != null) {
                    activitiesListener.onRefreshAllData(activities, calendars, calendarTimes, categories);
                }
            };

            getCalendarTimes(context, onRequestCompleted);
            getCalendar(context, onRequestCompleted);
            getCategory(context, onRequestCompleted);

            JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, apiHost + "activities/all", null, new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray response) {
                    activities = ActivityJsonParser.parserJsonActivities(response);
                    addActivitiesDB(activities);
                    onRequestCompleted.run();


                    if (activitiesListener != null) {
                        activitiesListener.onRefreshAllData(activities, calendars, calendarTimes, categories);
                    }
                }

            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("--> GET --> " + error);
                    onRequestCompleted.run();
                }
            });
            volleyQueue.add(request);
        }
    }

    public void postActivityAPI(final Activity activity, final ArrayList<DateTimeParser> dateTimeParser, final Context context) throws IOException {
        String apiHost = getApiHost(context);
        System.out.println("Running create");

        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            JSONObject params = new JSONObject();
            try {
                params.put("name", activity.getName());
                params.put("description", activity.getDescription());
                params.put("maxpax", activity.getMaxpax());
                params.put("priceperpax", activity.getPriceperpax());
                params.put("address", activity.getAddress());
                params.put("category_id", activity.getCategory());
                params.put("user_id", getUserId(context));
                params.put("status", 1);

                JSONArray dateArray = new JSONArray();
                JSONArray hourArray = new JSONArray();

                int i = 0;
                for (DateTimeParser dateTime : dateTimeParser) {
                    params.put("date[" + i + "]", dateTime.getParserDate());
                    params.put("hour[" + i + "]", dateTime.getParserTime());
                    i++;
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
            Uri imgUri = Uri.parse(activity.getPhoto());

            Map<String, File> files = new HashMap<>();
            File file = new File(String.valueOf(ImageSender.resizeImageToTempFile(context, imgUri, 640)));
            files.put("photoFile", file);

            VolleyMultipartRequest multipartRequest = new VolleyMultipartRequest(
                    Request.Method.POST,
                    apiHost + "activities/createactivity",
                    params,
                    files,
                    new Response.Listener<String>() {
                        @Override
                        public void onResponse(String response) {
                            System.out.println("->> " + params);

                            System.out.println("->> Server Response: " + response);
                            waypinpointDbHelper.addActivityDB(ActivityJsonParser.parserJsonActivity(response));

                            if (activitiesListener != null) {
                                activityListener.onRefreshActivityDetails(REGISTER);
                            }
                        }
                    },
                    new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            System.out.println("->> onErrorResponse: " + error.getMessage());
                            error.printStackTrace();
                        }
                    }
            );

            Volley.newRequestQueue(context).add(multipartRequest);
        }
    }

    public void editActivityAPI(final Activity activity, final ArrayList<DateTimeParser> dateTimeParser, final Context context) throws IOException {
        String apiHost = getApiHost(context);
        System.out.println("Running update");
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            JSONObject params = new JSONObject();
            try {
                params.put("name", activity.getName());
                params.put("description", activity.getDescription());
                params.put("maxpax", activity.getMaxpax());
                params.put("priceperpax", activity.getPriceperpax());
                params.put("address", activity.getAddress());
                params.put("category_id", activity.getCategory());
                params.put("user_id", getUserId(context));
                params.put("status", 1);

                JSONArray dateArray = new JSONArray();
                JSONArray hourArray = new JSONArray();

                int i = 0;
                for (DateTimeParser dateTime : dateTimeParser) {
                    params.put("date[" + i + "]", dateTime.getParserDate());
                    params.put("hour[" + i + "]", dateTime.getParserTime());
                    i++;
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
            Map<String, File> files = new HashMap<>();

            Uri imgUri = Uri.parse(activity.getPhoto());
            if (imgUri.getScheme() != null) {
                File file = new File(String.valueOf(ImageSender.resizeImageToTempFile(context, imgUri, 640)));
                files.put("photoFile", file);
            } else {
                files.put("photoFile", null);
            }
            VolleyMultipartRequest multipartRequest = new VolleyMultipartRequest(
                    Request.Method.POST,
                    apiHost + "activities/updateactivity/" + activity.getId(),
                    params,
                    files,
                    new Response.Listener<String>() {
                        @Override
                        public void onResponse(String response) {
                            System.out.println("->> " + params);

                            System.out.println("->> Server Response: " + response);
                            waypinpointDbHelper.editActivityDB(ActivityJsonParser.parserJsonActivity(response));

                            if (activitiesListener != null) {
                                activityListener.onRefreshActivityDetails(EDIT);
                            }
                        }
                    },
                    new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            System.out.println("->> onErrorResponse: " + error.getMessage());
                            error.printStackTrace();
                        }
                    }
            );

            Volley.newRequestQueue(context).add(multipartRequest);
        }
    }

    public void delActivityAPI(final Activity activity, final Context context) {
        String apiHost = getApiHost(context);

        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            StringRequest request = new StringRequest(Request.Method.DELETE, apiHost + "activities/" + activity.getId(), new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {

                    removeActivityBD(activity.getId());

                    if (activityListener != null) {
                        activityListener.onRefreshActivityDetails(DELETE);
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

    public void removeActivityBD(int id) {
        Activity a = getActivity(id);
        waypinpointDbHelper.delActivityDB(a.getId());
    }

    public void addCalendarsDB(ArrayList<Calendar> calendar) {
        waypinpointDbHelper.delAllCalendarDB();
        for (Calendar c : calendar) {
            System.out.println("DB Add --> " + c);
            waypinpointDbHelper.addCalendarDB(c);
        }
    }

    public void getCalendar(final Context context, final Runnable onComplete) {
        String apiHost = Utilities.getApiHost(context);
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();

            if (activitiesListener != null) {
                activitiesListener.onRefreshCalendarList(waypinpointDbHelper.getCalendarDB());
            }
        } else {

            JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, apiHost + "activities/calendar", null, new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray response) {
                    calendars = CalendarJsonParser.parserJsonCalendar(response);
                    addCalendarsDB(calendars);
                    onComplete.run();

                    if (activitiesListener != null) {
                        activitiesListener.onRefreshCalendarList(calendars);
                    }
                }

            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("--> Calendar GET --> " + error);
                    onComplete.run();
                }
            });
            volleyQueue.add(request);
        }
    }

    public void addTimesDB(ArrayList<CalendarTime> calendarTime) {
        waypinpointDbHelper.delAllCalendarTimeDB();
        for (CalendarTime c : calendarTime) {
//            System.out.println("DB Add time --> " + c);
            waypinpointDbHelper.addCalendarTimeDB(c);
        }
    }

    private void getCalendarTimes(final Context context, final Runnable onComplete) {
        String apiHost = Utilities.getApiHost(context);
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();

            if (activitiesListener != null) {
                activitiesListener.onRefreshTimeList(waypinpointDbHelper.getCalendarTimeDB());
            }
        } else {
            JsonArrayRequest timesRequest = new JsonArrayRequest(Request.Method.GET, apiHost + "activities/time", null, new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray timesResponse) {
                    calendarTimes = TimeJsonParser.parserJsonTime(timesResponse);
                    addTimesDB(calendarTimes);
                    onComplete.run();

                    if (activitiesListener != null) {
                        activitiesListener.onRefreshTimeList(calendarTimes);
                    }
                }

            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("--> Time GET --> " + error);
                    onComplete.run();
                }
            });
            volleyQueue.add(timesRequest);
        }
    }

    public void addCategoriesDB(ArrayList<Category> categories) {
        waypinpointDbHelper.delAllCategoriesDB();
        for (Category c : categories) {
            System.out.println("DB Add category --> " + c);
            waypinpointDbHelper.addCategoryDB(c);
        }
    }

    private void getCategory(final Context context, final Runnable onComplete) {
        String apiHost = Utilities.getApiHost(context);
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();

            if (activitiesListener != null) {
                activitiesListener.onRefreshCategoryList(waypinpointDbHelper.getCategoryDB());
            }
        } else {
            JsonArrayRequest categoryRequest = new JsonArrayRequest(Request.Method.GET, apiHost + "activities/category", null, new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray categoryResponse) {
                    categories = CategoryJsonParser.parserJsonCategory(categoryResponse);
                    addCategoriesDB(categories);
                    onComplete.run();

                    if (activitiesListener != null) {
                        activitiesListener.onRefreshCategoryList(categories);
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("--> Category GET --> " + error);
                    onComplete.run();
                }
            });

            volleyQueue.add(categoryRequest);
        }
    }
    //endregion

    //region # Review API #
    public void setReviewsListener(ReviewsListener reviewsListener) {
        this.reviewsListener = reviewsListener;
    }

    public void setReviewListener(ReviewListener reviewListener) {
        this.reviewListener = reviewListener;
    }

    public Review getReview(int id) {
        for (Review review : reviews) {
            if (review.getId() == id) {
                return review;
            }
        }
        return null;
    }

    public void addReviewsDb(ArrayList<Review> reviews) {
        waypinpointDbHelper.delAllReviewsDb();
        for (Review r : reviews) {
            System.out.println("DB Add review--> " + r);
            waypinpointDbHelper.addReviewDB(r);
        }
    }

    public void addReviewDb(Review review) {
        waypinpointDbHelper.addReviewDB(review);
    }

    public void editReviewDb(Review review) {
        waypinpointDbHelper.editReviewDb(review);
    }

    public void removeReviewDb(int reviewId) {
        Review r = getReview(reviewId);
        if (r != null) {
            waypinpointDbHelper.removeReviewDb(r.getId());
        }
    }

    public void getReviewsApi(final Context context, int id) {
        String apiHost = getApiHost(context);
        User user = getUser(getUserId(context));
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
            if (reviewsListener != null) {
                reviewsListener.onRefreshReviewsList(waypinpointDbHelper.getReviewsDb());
            }
        } else {
            JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, apiHost + "reviews/activity/" + id, null, new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray response) {
                    System.out.println("--> GETAPI: " + response);
                    reviews = ReviewJsonParser.parserJsonReviews(response);
                    addReviewsDb(reviews);
                    if (reviewsListener != null) {
                        reviewsListener.onRefreshReviewsList(reviews);
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("--> GET --> " + error);

                }
            }) {
                @Override
                public Map<String, String> getHeaders() throws AuthFailureError {
                    Map<String, String> headers = new HashMap<>();
                    // Add Basic Authentication Header
                    String username = user.getUsername(); // Replace with your username
                    String password = user.getPassword(); // Replace with your password
                    String credentials = username + ":" + password;
                    String auth = "Basic " + Base64.encodeToString(credentials.getBytes(), Base64.NO_WRAP);
                    headers.put("Authorization", auth);
                    return headers;
                }
            };

            volleyQueue.add(request);
        }
    }

    public void addReviewApi(final Review review, Context context) {
        String apiHost = Utilities.getApiHost(context);
        int userID = review.getUserId();
        int activityId = review.getActivityId();
        int score = review.getScore();
        String message = review.getMessage();

        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            StringRequest request = new StringRequest(Request.Method.POST, apiHost + "reviews",
                    new Response.Listener<String>() {
                        @Override
                        public void onResponse(String response) {

                            addReviewDb(ReviewJsonParser.parserJsonReview(response));
                            if (reviewListener != null) {
                                reviewListener.onValidateReviewOperation(REGISTER);
                            }
                        }
                    }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    String responseBody;
                    try {
                        responseBody = new String(error.networkResponse.data, "UTF-8");
                        Log.e("ReviewDetailsActivity", "Error Response Body: " + responseBody);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }) {
                @Override
                protected Map<String, String> getParams() {
                    Map<String, String> params = new HashMap<>();
                    params.put("user_id", String.valueOf(userID));
                    params.put("activity_id", String.valueOf(activityId));
                    params.put("score", String.valueOf(score));
                    params.put("message", message);
                    return params;
                }
            };
            volleyQueue.add(request);
        }
    }

    public void editReviewApi(final Review review, final Context context) {
        String apiHost = Utilities.getApiHost(context);
        int userID = review.getUserId();
        int activityId = review.getActivityId();
        int score = review.getScore();
        String message = review.getMessage();

        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            StringRequest request = new StringRequest(Request.Method.PUT, apiHost + "reviews/edit", new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    editReviewDb(ReviewJsonParser.parserJsonReview(response));
                    if (reviewListener != null) {
                        reviewListener.onValidateReviewOperation(EDIT);
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
                    params.put("id", String.valueOf(review.getId()));
                    params.put("user_id", String.valueOf(userID));
                    params.put("activity_id", String.valueOf(activityId));
                    params.put("score", String.valueOf(score));
                    params.put("message", message);
                    return params;
                }
            };
            System.out.println("---> REQUEST EDIT REVIEW" + request);

            volleyQueue.add(request);
        }
    }

    public void removeReviewApi(final Review review, final Context context) {
        String apiHost = Utilities.getApiHost(context);


        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            StringRequest request = new StringRequest(Request.Method.DELETE, apiHost + "reviews/" + review.getId(), new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    removeReviewDb(review.getId());

                    if (reviewListener != null) {
                        reviewListener.onValidateReviewOperation(DELETE);
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
    //endregion
}
