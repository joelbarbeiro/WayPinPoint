package pt.ipleiria.estg.dei.waypinpoint;

import android.os.Bundle;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import Model.Activity;
import Model.Calendar;
import Model.CalendarTime;
import Model.Category;
import Model.SingletonManager;
import Model.WaypinpointDbHelper;
import pt.ipleiria.estg.dei.waypinpoint.utils.Utilities;

public class ActivityDetailsActivity extends AppCompatActivity {

    public static final String ID_ACTIVITY = "ID_ACTIVITY";
    private ArrayList<Category> categories = new ArrayList<>();
    private ArrayList<Calendar> calendars = new ArrayList<>();
    private ArrayList<CalendarTime> calendarTimesList = new ArrayList<>();
    private Activity activity;
    private EditText etName;
    private EditText etDescription;
    private EditText etMaxPax;
    private EditText etPricePerPax;
    private Button btnReviews;
    private Spinner spinnerCategories;
    private Spinner spinnerDateTime;
    private ImageView imageActivity;
    private FloatingActionButton fabCrudActivity;
    private FragmentManager fragmentManager;
    private WaypinpointDbHelper waypinpointDbHelper;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_details);
        int id = getIntent().getIntExtra(ID_ACTIVITY, 0);

        waypinpointDbHelper = new WaypinpointDbHelper(getApplicationContext());
        activity = SingletonManager.getInstance(getApplicationContext()).getActivity(id);
        categories = waypinpointDbHelper.getCategoryDB();
        calendars = waypinpointDbHelper.getCalendarByActivityId(id);
        calendarTimesList = waypinpointDbHelper.getCalendarTimeDB();

        imageActivity = findViewById(R.id.imgActiviy);
        etName = findViewById(R.id.etActivityName);
        etDescription = findViewById(R.id.etActivityDescription);
        etMaxPax = findViewById(R.id.etActivityMaxPax);
        etPricePerPax = findViewById(R.id.etActivityPricePerPax);
        spinnerCategories = findViewById(R.id.spinnerActivityDetailsCategory);
        spinnerDateTime = findViewById(R.id.spinnerActivityDateTime);

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

        //region # Category spinner #
        // ######################################################################
        ArrayAdapter<Category> categoryAdapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_spinner_item,
                categories
        );
        categoryAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerCategories.setAdapter(categoryAdapter);
        int positionToSelect = -1;

        for (int i = 0; i < categories.size(); i++) {
            if (categories.get(i).getId() == activity.getCategory()) {
                positionToSelect = i;
                break;
            }
        }
        if (positionToSelect != -1) {
            spinnerCategories.setSelection(positionToSelect);
        }

        spinnerCategories.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                Category selectedCategory = (Category) parent.getItemAtPosition(position);
                int categoryId = selectedCategory.getId();
                String categoryDescription = selectedCategory.getDescription();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });
        //endregion

        //region # DateTime spinner #
        // ######################################################################
        ArrayAdapter<Calendar> calendarAdapter = new ArrayAdapter<Calendar>(
                this,
                android.R.layout.simple_spinner_item,
                calendars
        ){
            @Override
            public View getDropDownView(int position, View convertView, ViewGroup parent) {
                View view = super.getDropDownView(position, convertView, parent);

                Calendar calendar = getItem(position);

                TextView textView = view.findViewById(android.R.id.text1);

                CalendarTime calendarTimes = getCalendarTimeById(calendar.getTime_id());

                String displayText = calendar.getDate() + " - " + calendarTimes.getHour();
                textView.setText(displayText);

                return view;
            }
            @Override
            public View getView(int position, View convertView, ViewGroup parent) {
                View view = super.getView(position, convertView, parent);

                Calendar calendar = getItem(position);
                TextView textView = view.findViewById(android.R.id.text1);
                CalendarTime calendarTimes = getCalendarTimeById(calendar.getTime_id());

                String displayText = calendar.getDate() + " - " + calendarTimes.getHour();
                textView.setText(displayText);

                return view;
            }

            private CalendarTime getCalendarTimeById(int time_id) {
                for (CalendarTime calendarTime : calendarTimesList) {
                    if (calendarTime.getId() == time_id) {
                        return calendarTime;
                    }
                }
                return null;
            }
        };

        calendarAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerDateTime.setAdapter(calendarAdapter);


        spinnerDateTime.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                Calendar selectedCalendar = (Calendar) parent.getItemAtPosition(position);
                int calendarId = selectedCalendar.getId();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });
        //endregion

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
        FragmentManager fragmentManager = getSupportFragmentManager();

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