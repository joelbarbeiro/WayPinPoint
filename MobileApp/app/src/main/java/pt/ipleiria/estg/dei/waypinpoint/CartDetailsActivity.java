package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID_CART;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import Listeners.CartListener;
import Model.Cart;
import Model.SingletonManager;
import pt.ipleiria.estg.dei.waypinpoint.utils.CartJsonParser;

public class CartDetailsActivity extends AppCompatActivity implements CartListener {
    private Cart cart;
    private EditText etActivityName, etQuantity, etPrice, etDate;
    private ImageView iv_activityImg;
    public static final String DEFAULT_IMG = null;
    private FloatingActionButton fabCheckout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.cart_details_activity);

        int id = getIntent().getIntExtra(ID_CART, 0);

        cart = SingletonManager.getInstance(getApplicationContext()).getCart(id);
        fabCheckout = findViewById(R.id.fabCheckout);
        etActivityName = findViewById(R.id.tvActivityDetailsName);
        etQuantity = findViewById(R.id.etQuantity);
        etPrice = findViewById(R.id.etPrice);
        etDate = findViewById(R.id.etDate);
        iv_activityImg = findViewById(R.id.iv_activityImg);
    }

    private void loadCart() {
        etActivityName.setText(cart.getProduct_id());
        etQuantity.setText(cart.getQuantity());
        Glide.with(getApplicationContext());
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        if (cart != null) {
            getMenuInflater().inflate(R.menu.cart_remove, menu);
            return super.onCreateOptionsMenu(menu);
        }
        return false;
    }

    @Override
    public boolean onOptionsItemSelected(@NonNull MenuItem item) {
        if (item.getItemId() == R.id.itemRemove) {
            if(!CartJsonParser.isConnectionInternet(getApplicationContext()))
            {
                Toast.makeText(this, "Não tens net bro", Toast.LENGTH_SHORT).show();
            } else {
                dialogRemove();
            }
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    private void dialogRemove() {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(R.string.delete_cart_item);
        builder.setMessage(R.string.remove_cart_item);
        builder.setPositiveButton(R.string.dialog_yes, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        SingletonManager.getInstance(getApplicationContext()).removeCartAPI(getApplicationContext(), cart);
                    }
                })
                .setNegativeButton(R.string.dialog_no, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        dialog.cancel();
                    }
                })
                .setIcon(R.drawable.ic_cross)
                .show();
    }

    //Maybe needs an Override
    public void onRefreshDetails(int op) {
        Intent intent = new Intent();
        intent.putExtra(OP_CODE, EDIT);
        setResult(RESULT_OK, intent);
        finish();
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
