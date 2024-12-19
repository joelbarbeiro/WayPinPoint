package pt.ipleiria.estg.dei.waypinpoint.utils;

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import Model.Cart;

public class CartJsonParser {
    public static ArrayList<Cart> parserJsonCarts(JSONArray response) {
        ArrayList<Cart> carts = new ArrayList<>();

        try {
            for (int i = 0; i < response.length(); i++) {
                JSONObject cart = (JSONObject) response.get(i);
                int idCart = cart.getInt("id");
                int user_id = cart.getInt("user_id");
                int product_id = cart.getInt("product_id");
                int quantity = cart.getInt("quantity");
                int status = cart.getInt("status");
                int calendar_id = cart.getInt("calendar_id");
                String date = cart.getString("date");
                String time = cart.getString("time");
                double price = cart.getDouble("price");
                String activityImg = cart.getString("activityImg");

                Cart auxCart = new Cart(
                        idCart,
                        user_id,
                        product_id,
                        quantity,
                        status,
                        calendar_id,
                        date,
                        time,
                        price,
                        activityImg
                        );
                carts.add(auxCart);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return carts;
    }

    public static Cart parserJsonCart(String response) {
        Cart auxCart = null;

        try {
            JSONObject Cart = new JSONObject(response);
            int idCart = Cart.getInt("id");
            int user_id = Cart.getInt("user_id");
            int product_id = Cart.getInt("product_id");
            int quantity = Cart.getInt("quantity");
            int status = Cart.getInt("status");
            int calendar_id = Cart.getInt("calendar_id");
            String date = Cart.getString("date");
            String time = Cart.getString("time");
            double price = Cart.getDouble("price");
            String activityImg = Cart.getString("activityImg");

            auxCart = new Cart(
                    idCart,
                    user_id,
                    product_id,
                    quantity,
                    status,
                    calendar_id,
                    date,
                    time,
                    price,
                    activityImg
            );
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return auxCart;
    }

    public static boolean isConnectionInternet(Context context){
        ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);

        NetworkInfo networkInfo = cm.getActiveNetworkInfo();

        return networkInfo != null && networkInfo.isConnected();
    }

}
