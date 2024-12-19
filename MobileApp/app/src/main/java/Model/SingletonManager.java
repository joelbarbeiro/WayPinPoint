package Model;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EMAIL;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.REGISTER;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.TOKEN;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.USER_DATA;

import android.content.Context;
import android.content.SharedPreferences;
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

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import Listeners.CartListener;
import Listeners.LoginListener;
import Listeners.UserListener;
import pt.ipleiria.estg.dei.waypinpoint.R;
import pt.ipleiria.estg.dei.waypinpoint.utils.CartJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.StatusJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.UserJsonParser;

public class SingletonManager {

    private static SingletonManager instance = null;
    private UserDbHelper userDbHelper = null;

    private ArrayList<User> users;

    private UserListener userListener;
    private LoginListener loginListener;
    //Region Cart Instances
    private CartListener cartListener;
    private CartDbHelper cartDbHelper = null;
    private ArrayList<Cart> carts;
    private Cart cart;

    private static RequestQueue volleyQueue = null;

    public SingletonManager(Context context) {
        users = new ArrayList<>();
        userDbHelper = new UserDbHelper(context);
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
                    params.put("photo", user.getPhoto() == null ? User.DEFAULT_IMG : user.getPhoto());
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
                    params.put("photo", user.getPhoto() == null ? User.DEFAULT_IMG : user.getPhoto());
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
    //REGION # MÉTODOS CART - API #

    public ArrayList<Cart> getCartsDB() {
        carts = cartDbHelper.getAllCartDb();
        return new ArrayList<>(carts);
    }

    public Cart getCart(int id) {
        carts = getCartsDB();
        for (Cart cart : carts) {
            if (cart.getId() == id) {
                return cart;
            }
        }
        return null;
    }

    public void getCartAPI(final Context context, final CartListener listener, final Cart cart) {
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
            listener.onErrorAdd(context.getString(R.string.error_no_internet));

        } else {
            JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, apiHost + "carts/" + cart.getId(), null, new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray response) {
                    System.out.println("------> Cart Data: " + response);
                    Cart cart = CartJsonParser.parserJsonCart(response.toString());
                    cartDbHelper.addCartDb(cart);
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

    public void getCartByUserId(final Context context, int userId, final CartListener listener) {
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
            listener.onErrorAdd(context.getString(R.string.error_no_internet));
        } else {
            JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, urlApi + "carts/buyers/" + userId, null, new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray response) {
                    System.out.println("------> Cart Data: " + response);
                    ArrayList<Cart> carts = CartJsonParser.parserJsonCarts(response);
                    for (Cart cart : carts) {
                        cartDbHelper.addCartDb(cart);
                    }
                }
            },
                    new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            System.out.println("Error fetching cart user ID");
                            Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                        }
                    });
            volleyQueue.add(request);
        }
    }

    public void addCartApi(String apiHost, final Cart cart, final Context context, final CartListener CartListener) {
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            StringRequest request = new StringRequest(Request.Method.POST, apiHost + "cart/add",
                    new Response.Listener<String>() {
                        @Override
                        public void onResponse(String response) {
                            Cart newCart = CartJsonParser.parserJsonCart(response);
                            cartDbHelper.addCartDb(newCart);
                            if (CartListener != null) {
                                CartListener.validateOperation("Cart added successfully!");
                            }
                        }
                    },
                    new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            System.out.println("---> " + error.getMessage());
                            Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                        }
                    }) {
                @Override
                protected Map<String, String> getParams() {
                    Map<String, String> params = new HashMap<>();
                    params.put("id", String.valueOf(cart.getId()));
                    params.put("user_id", String.valueOf(cart.getUser_id()));
                    params.put("product_id", String.valueOf(cart.getProduct_id()));
                    params.put("quantity", String.valueOf(cart.getQuantity()));
                    params.put("status", String.valueOf(cart.getStatus()));
                    params.put("calendar_id", String.valueOf(cart.getCalendar_id()));
                    params.put("time", cart.getTime());
                    params.put("date", cart.getDate());
                    params.put("price", String.valueOf(cart.getPrice()));
                    return params;
                }
            };
            volleyQueue.add(request);
        }
    }

    public void editCart(final Cart cart, final Context context) {
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            StringRequest request = new StringRequest(Request.Method.PUT, urlApi + "users/" + cart.getId(), new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    cartDbHelper.editCartDb(cart);
                    Toast.makeText(context, R.string.cart_edited, Toast.LENGTH_SHORT).show();
                    if (cartListener != null) {
                        cartListener.onValidateOperation(MenuMainActivity.EDIT);
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
    }

    public void removeCartAPI(final Context context, final Cart cart) {
        if (!StatusJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
        } else {
            StringRequest request = new StringRequest(Request.Method.DELETE, urlApi + "carts/" + cart.getId(), new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    Toast.makeText(context, R.string.cart_deleted, Toast.LENGTH_SHORT).show();
                    cartDbHelper.removeCartDb(CartJsonParser.parserJsonCart(response));
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
                    Map<String, String> params = new HashMap<>();
                    params.put("status", "1");
                    return params;
                }
            };
            volleyQueue.add(request);
        }
    }

    public void setCartsListener(CartListener cartsListener) {
        this.cartListener = cartsListener;
    }

    public void setCartListener(CartListener cartListener) {
        this.cartListener = cartListener;
    }

    //ENDREGION


}