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
import Model.WaypinpointDbHelper;
import pt.ipleiria.estg.dei.waypinpoint.utils.Utilities;

public class ActivityDetailsActivity extends AppCompatActivity {

    public static final String ID_ACTIVITY = "ID_ACTIVITY";
    private Activity activity;
    private EditText etName;
    private EditText etDescription;
    private EditText etMaxPax;
    private EditText etPricePerPax;
    private EditText etCategory;
    private Button btnReviews;
    private Spinner spinnerDateTime;
    private ImageView imageActivity;
    private FloatingActionButton fabCrudActivity;
    private FragmentManager fragmentManager;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_details);
        int id = getIntent().getIntExtra(ID_ACTIVITY, 0);

        activity = SingletonManager.getInstance(getApplicationContext()).getActivity(id);

        imageActivity = findViewById(R.id.imgActiviy);
        etName = findViewById(R.id.etActivityName);
        etDescription = findViewById(R.id.etActivityDescription);
        etMaxPax = findViewById(R.id.etActivityMaxPax);
        etPricePerPax = findViewById(R.id.etActivityPricePerPax);
        etCategory = findViewById(R.id.etActivityCategory);
        //spinnerDateTime = findViewById(R.id.spinnerActivityDateTime);

        btnReviews = findViewById(R.id.btnReview);


        if(activity != null){
            loadActivity();
            // TODO: fabGuardar.setImageResource(R.drawable.ic_action_guardar);
        }  else {
            //setTitle(getString(R.string.txt_adicionar_livro));
            //fabGuardar.setImageResource(R.drawable.ic_action_adicionar);
        }

    }
    private void loadActivity(){
        setTitle("Detalhes: " + activity.getName());

        etName.setText(activity.getName());
        etDescription.setText(activity.getDescription());
        etMaxPax.setText("" + activity.getMaxpax());
        etPricePerPax.setText("" + activity.getPriceperpax());

        String imgPath = Utilities.getImgUri(getApplicationContext()) + activity.getSupplier() + "/" + activity.getPhoto();
                Glide.with(getApplicationContext())
                .load(imgPath)
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