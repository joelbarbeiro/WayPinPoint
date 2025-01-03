package Listeners;

import java.util.ArrayList;

import Model.Cart;

public interface CartsListener {
    void onRefreshCartList(ArrayList<Cart> cartList);
}