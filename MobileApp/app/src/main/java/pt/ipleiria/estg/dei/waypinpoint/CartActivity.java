package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID_CART;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import java.util.ArrayList;

import Listeners.CartListener;
import Model.Cart;
import Model.SingletonManager;
import Model.WaypinpointDbHelper;

public class CartActivity extends AppCompatActivity implements CartListener {
    private FragmentManager fragmentManager;
    private int quantity;
    private int activityId;
    private String apiHost = null;
    private Cart cart;
    private EditText etQuantity;
    public static final String APIHOST = "APIHOST";
    private Button buttonAddCart;
    private CartListener listener;
    private double total;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cart);

        Intent intent = getIntent();
        int calendarId = intent.getIntExtra("CALENDAR_ID", 0);
        int activityId = intent.getIntExtra("ACTIVITY_ID", 0);
        int id = getIntent().getIntExtra(ID_CART, 0);
        cart = SingletonManager.getInstance(getApplicationContext()).getCart(id);
        etQuantity = findViewById(R.id.textviewQuantity);
        buttonAddCart = findViewById(R.id.buttonAddCart);
        buttonAddCart.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String quantityStr = etQuantity.getText().toString().trim();
                if (isQuantityValid(quantityStr)) {
                    int quantity = Integer.parseInt(quantityStr);
                    Cart newCart = new Cart(
                            0,
                            getUserId(getApplicationContext()),
                            activityId,
                            quantity,
                            0,
                            calendarId
                    );

                    SingletonManager.getInstance(getApplicationContext()).addCartApi(newCart, getApplicationContext());
                    Fragment fragment = new CartFragment();
                    Toast.makeText(CartActivity.this, "Cart added successfully!", Toast.LENGTH_SHORT).show();
                    System.out.println("------> Initializing Transaction" + fragment);
                    findViewById(R.id.cartLayout).setVisibility(View.GONE);
                    FragmentManager fragmentManager = getSupportFragmentManager();
                    // Show fragment container
                    findViewById(R.id.fragment_container_cart).setVisibility(View.VISIBLE);
                    fragmentManager
                            .beginTransaction()
                            .replace(R.id.fragment_container_cart, fragment) // Ensure the container ID matches your layout
                            .commit();
                    finish();
                }

            }
        });
    }

    private boolean isQuantityValid(String input) {
        if (input.isEmpty()) {
            etQuantity.setError("Field cannot be empty");
            return false;
        }

        try {
            int quantity = Integer.parseInt(input);
            if (quantity <= 0) {
                etQuantity.setError("Quantity must be greater than 0");
                return false;
            }
        } catch (NumberFormatException e) {
            etQuantity.setError("Invalid quantity");
            return false;
        }
        return true;

    }


    @Override
    public void onSuccess(ArrayList<Cart> carts) {

    }

    @Override
    public void onError(String s) {

    }

    @Override
    public void onRefreshCartList(ArrayList<Cart> cartList) {

    }
}

