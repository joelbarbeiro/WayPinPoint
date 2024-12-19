package pt.ipleiria.estg.dei.waypinpoint;

import android.os.Bundle;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import Model.Activity;
import Model.SingletonManager;

public class ActivityDetailsActivity extends AppCompatActivity {

    private Activity activity;
    private EditText etName;
    private EditText etDescription;
    private EditText etMaxPax;
    private EditText etPricePerPax;
    private Spinner spinnerDate;
    private Spinner spinnerHour;
    private ImageView imageActivity;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_details);

        activity = SingletonManager.getInstance(getApplicationContext().getActivity(id));

        etName = findViewById(R.id.etActivityName);
        etDescription = findViewById(R.id.etActivityDescription);
        etMaxPax = findViewById(R.id.etActivityMaxPax);
        etPricePerPax = findViewById(R.id.etActivityPricePerPax);
        spinnerDate = findViewById(R.id.spinnerActivityDate);
        spinnerHour = findViewById(R.id.spinnerActivityHour);

        if(activity != null){

        }
    }
}