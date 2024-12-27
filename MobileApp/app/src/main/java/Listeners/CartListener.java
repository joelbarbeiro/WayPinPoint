package Listeners;

import java.util.ArrayList;

import Model.Cart;

public interface CartListener {
    void onSuccess(ArrayList<Cart> carts);

    void onError(String s);

    void onRefreshCartList(ArrayList<Cart> cartList);

    void onValidateOperation(int op);
}
