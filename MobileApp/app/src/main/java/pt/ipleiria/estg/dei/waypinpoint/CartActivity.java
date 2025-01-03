package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ACTIVITY_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.CALENDAR_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID_CART;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.isQuantityValid;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.FragmentManager;

import Listeners.CartListener;
import Model.Cart;
import Model.SingletonManager;

public class CartActivity extends AppCompatActivity implements CartListener {
    private FragmentManager fragmentManager;
    private int quantity;
    private int activityId;
    private String apiHost = null;
    private Cart cart;
    private EditText etQuantity;
    private Button buttonAddCart;
    private CartListener listener;
    private double total;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cart);

        Intent intent = getIntent();
        int calendarId = intent.getIntExtra(CALENDAR_ID, 0);
        int activityId = intent.getIntExtra(ACTIVITY_ID, 0);
        int id = getIntent().getIntExtra(ID_CART, 0);
        cart = SingletonManager.getInstance(getApplicationContext()).getCart(id);
        etQuantity = findViewById(R.id.textviewQuantity);
        buttonAddCart = findViewById(R.id.buttonAddCart);
        buttonAddCart.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String quantityStr = etQuantity.getText().toString().trim();
                if (isQuantityValid(quantityStr, getApplicationContext())) {
                    int quantity = Integer.parseInt(quantityStr);
                    Cart newCart = new Cart(
                            0,
                            getUserId(getApplicationContext()),
                            activityId,
                            quantity,
                            0,
                            calendarId
                    );
                    SingletonManager.getInstance(getApplicationContext()).setCartListener(CartActivity.this);
                    SingletonManager.getInstance(getApplicationContext()).addCartApi(newCart, getApplicationContext());
                }
            }
        });
    }

    @Override
    public void onValidateOperation(int op) {
        Intent intent = new Intent();
        intent.putExtra(OP_CODE, op);
        setResult(RESULT_OK, intent);
        finish();
    }
}

