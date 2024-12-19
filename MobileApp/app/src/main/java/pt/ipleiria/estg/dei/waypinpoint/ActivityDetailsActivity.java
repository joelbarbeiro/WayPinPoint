package pt.ipleiria.estg.dei.waypinpoint;

import android.os.Bundle;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;

import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;

import Model.Activity;
import Model.SingletonManager;

public class ActivityDetailsActivity extends AppCompatActivity {

    public static final String ID_ACTIVITY = "ID_ACTIVITY";
    private Activity activity;
    private EditText etName;
    private EditText etDescription;
    private EditText etMaxPax;
    private EditText etPricePerPax;
    private Spinner spinnerDateTime;
    private ImageView imageActivity;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_details);

        int id =getIntent().getIntExtra(ID_ACTIVITY, 0);

        activity = SingletonManager.getInstance(getApplicationContext()).getActivity(id);

        etName = findViewById(R.id.etActivityName);
        etDescription = findViewById(R.id.etActivityDescription);
        etMaxPax = findViewById(R.id.etActivityMaxPax);
        etPricePerPax = findViewById(R.id.etActivityPricePerPax);
        spinnerDateTime = findViewById(R.id.spinnerActivityDateTime);

        if(activity != null){

        }
    }
    private void deployActivity(){
        setTitle("Detalhes: " + activity.getName());

        etName.setText(activity.getName());
        etDescription.setText(activity.getDescription());
        etMaxPax.setText("" + activity.getMaxpax());
        etPricePerPax.setText("" + activity.getPriceperpax());
        Glide.with(getApplicationContext())
                .load(activity.getPhoto())
                .placeholder(R.drawable.img_default_activity)
                .diskCacheStrategy(DiskCacheStrategy.ALL)
                .into(imageActivity);
    }
}