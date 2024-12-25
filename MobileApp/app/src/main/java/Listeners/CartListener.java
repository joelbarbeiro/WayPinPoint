package Listeners;

import java.util.ArrayList;

import Model.Cart;

public interface CartListener {
    void onSuccess(ArrayList<Cart> carts);
    void onError(String s);
}
