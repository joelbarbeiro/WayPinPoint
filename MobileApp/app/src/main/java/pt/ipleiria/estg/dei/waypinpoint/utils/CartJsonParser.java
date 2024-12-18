package pt.ipleiria.estg.dei.waypinpoint.utils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.jar.JarException;

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

                Cart auxCart = new Cart(
                        idCart,
                        user_id,
                        product_id,
                        quantity,
                        status,
                        calendar_id,
                        date,
                        time,
                        price
                        );
                carts.add(auxCart);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return carts;
    }

    public static Cart parsonJsonCart(String response) {
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

            auxCart = new Cart(
                    idCart,
                    user_id,
                    product_id,
                    quantity,
                    status,
                    calendar_id,
                    date,
                    time,
                    price
            );
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return auxCart;
    }

}
