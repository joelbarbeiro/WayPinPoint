package Listeners;

import java.util.ArrayList;

import Model.Cart;

public interface CartListener {
    void onValidateOperation(int op);
    void onErrorAdd(String errorMessage);
    void onSuccess(ArrayList<Cart> carts);
    void validateOperation(String s);

    void onError(String s);
}
