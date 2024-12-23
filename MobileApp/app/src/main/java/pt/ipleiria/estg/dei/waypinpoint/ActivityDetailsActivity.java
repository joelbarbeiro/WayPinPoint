package pt.ipleiria.estg.dei.waypinpoint;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.google.android.material.floatingactionbutton.FloatingActionButton;

import Model.Activity;
import Model.SingletonManager;

public class ActivityDetailsActivity extends AppCompatActivity {

    public static final String ID_ACTIVITY = "ID_ACTIVITY";
    private Activity activity;
    private EditText etName;
    private EditText etDescription;
    private EditText etMaxPax;
    private EditText etPricePerPax;
    private Button btnReviews;
    private Spinner spinnerDateTime;
    private ImageView imageActivity;
    private FloatingActionButton fabCrudActivity;
    private FragmentManager fragmentManager;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_details);
        int id = getIntent().getIntExtra(ID_ACTIVITY, 2);
        fragmentManager = getSupportFragmentManager();

        activity = SingletonManager.getInstance(getApplicationContext()).getActivity(id);

        etName = findViewById(R.id.etActivityName);
        etDescription = findViewById(R.id.etActivityDescription);
        etMaxPax = findViewById(R.id.etActivityMaxPax);
        etPricePerPax = findViewById(R.id.etActivityPricePerPax);
        spinnerDateTime = findViewById(R.id.spinnerActivityDateTime);

        btnReviews = findViewById(R.id.btnReview);


        if(activity != null){

        }
    }
    private void loadActivity(){
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


    public void onClickReviews(View view) {
        // Hide background views
        findViewById(R.id.scrollView).setVisibility(View.GONE);
        findViewById(R.id.button_container).setVisibility(View.GONE);

        // Show fragment container
        findViewById(R.id.fragment_container).setVisibility(View.VISIBLE);

        // Add the fragment
        Fragment fragment = new ListReviewsFragment();
        Bundle args = new Bundle();
        args.putInt(ID_ACTIVITY, getIntent().getIntExtra(ID_ACTIVITY, 2));
        fragment.setArguments(args);
        fragmentManager.beginTransaction()
                .replace(R.id.fragment_container, fragment)
                .addToBackStack(null) // Add to back stack for navigation
                .commit();
    }
}