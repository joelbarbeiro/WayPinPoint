package pt.ipleiria.estg.dei.waypinpoint;

import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.EditText;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.fragment.app.FragmentManager;

import com.google.android.material.navigation.NavigationView;

public class CartActivity extends AppCompatActivity {
    private FragmentManager fragmentManager;
    private int quantity;
    private int getActivityId = 1;
    private String apiHost = null;
    private EditText etQuantity;
    public static final String APIHOST = "APIHOST";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cart);
        etQuantity = findViewById(R.id.textviewQuantity);
    }

    public void onClickCartSave(View view) {
        String input = etQuantity.getText().toString().trim();
        boolean isQuantityValid;
        isQuantityValid = isQuantityValid(input);
        if (isQuantityValid) {
            int quantity = Integer.parseInt(input);
            Intent intent = new Intent(this, RegisterActivity.class);
            intent.putExtra(RegisterActivity.APIHOST, apiHost);
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

}