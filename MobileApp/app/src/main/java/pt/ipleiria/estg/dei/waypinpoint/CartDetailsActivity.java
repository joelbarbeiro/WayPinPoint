package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID_CART;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import Listeners.CartListener;
import Model.Activity;
import Model.Calendar;
import Model.Cart;
import Model.SingletonManager;
import Model.WaypinpointDbHelper;
import pt.ipleiria.estg.dei.waypinpoint.utils.CartJsonParser;
import pt.ipleiria.estg.dei.waypinpoint.utils.Utilities;

public class CartDetailsActivity extends AppCompatActivity implements CartListener {
    private Cart cart;
    private TextView etActivityName, etQuantity, etPrice, etDate;
    private ImageView iv_activityImg;
    private Button btnEditQuantity;
    public static final String DEFAULT_IMG = null;
    private Activity activity;
    private Calendar calendar;
    private WaypinpointDbHelper waypinpointDbHelper;
    private FloatingActionButton fabCheckout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.cart_details_activity);

        int id = getIntent().getIntExtra(ID_CART, 0);
        waypinpointDbHelper = new WaypinpointDbHelper(getApplicationContext());
        cart = waypinpointDbHelper.getCartById(id);
        activity = waypinpointDbHelper.getActivityById(cart.getProduct_id());
        calendar = waypinpointDbHelper.getCalendarById(cart.getCalendar_id());

        fabCheckout = findViewById(R.id.fabCheckout);
        etActivityName = findViewById(R.id.etActivityName);
        etQuantity = findViewById(R.id.etQuantity);
        etPrice = findViewById(R.id.etPrice);
        etDate = findViewById(R.id.etDate);
        iv_activityImg = findViewById(R.id.iv_activityImg);
        btnEditQuantity = findViewById(R.id.btnEditQuantity);
        btnEditQuantity.setOnClickListener(v -> editQuantity());
        loadCart();
    }

    private void loadCart() {
        etActivityName.setText(activity.getName());
        etQuantity.setText("" + cart.getQuantity());
        etPrice.setText("" + activity.getPriceperpax());
        etDate.setText("" + calendar.getDate());
        String imgPath = Utilities.getImgUri(getApplicationContext()) + activity.getSupplier() + "/" + activity.getPhoto();
        Glide.with(getApplicationContext())
                .load(imgPath)
                .placeholder(R.drawable.img_default_activity)
                .diskCacheStrategy(DiskCacheStrategy.ALL)
                .into(iv_activityImg);
        SingletonManager.getInstance(getApplicationContext()).setCartListener(this);

    }

    private void editQuantity() {
        String quantityStr = etQuantity.getText().toString();
        if(isQuantityValid(quantityStr)){
            int newQuantity = Integer.parseInt(quantityStr);
            //TODO UPDATE CART ( VER NA BASE DE DADOS )
            cart.setQuantity(newQuantity);
            SingletonManager.getInstance(getApplicationContext()).editCart(cart, getApplicationContext());
            Toast.makeText(this, "Quantity Updated", Toast.LENGTH_SHORT).show();
        }
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
            if (!CartJsonParser.isConnectionInternet(getApplicationContext())) {
                Toast.makeText(this, "No internet amigo", Toast.LENGTH_SHORT).show();
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
                .setIcon(R.drawable.ic_dialog_remove)
                .show();
    }

    //Maybe needs an Override
    public void onRefreshDetails(int op) {

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

    @Override
    public void onValidateOperation(int op) {
        Intent intent = new Intent();
        intent.putExtra(OP_CODE, op);
        setResult(RESULT_OK, intent);
        finish();
    }
}
