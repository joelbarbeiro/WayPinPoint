package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ACTIVITY_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ADD;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.CALENDAR_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getCategoryById;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
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
    private TextView tvName;
    private TextView tvDescription;
    private TextView tvMaxPax;
    private TextView tvPricePerPax;
    private TextView tvCategory;
    private Button btnReviews;
    private Spinner spinnerDateTime;
    private ImageView imageActivity;
    private FloatingActionButton fabCrudActivity;
    private Button btBuyActivity;
    private int calendarId;
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
        tvName = findViewById(R.id.tvActivityDetailsName);
        tvDescription = findViewById(R.id.tvActivityDetailsDescription);
        tvMaxPax = findViewById(R.id.tvActivityDetailsMaxPax);
        tvPricePerPax = findViewById(R.id.tvActivityDetailsPricePerPax);
        tvCategory = findViewById(R.id.tvActivityDetailsCategory);
        spinnerDateTime = findViewById(R.id.spinnerActivityDateTime);

        btnReviews = findViewById(R.id.btnReview);


        btBuyActivity = findViewById(R.id.btBuyActivity);
        loadActivity();
    }

    private void loadActivity() {
        setTitle(getString(R.string.details_activity_title) + activity.getName());

        tvName.setText(activity.getName());
        tvDescription.setText(activity.getDescription());
        tvMaxPax.setText("" + activity.getMaxpax());
        tvPricePerPax.setText("" + activity.getPriceperpax() + " €");
        tvCategory.setText(getCategoryById(activity.getId(), categories));

        //region # DateTime spinner #
        // ######################################################################
        ArrayAdapter<Calendar> calendarAdapter = new ArrayAdapter<Calendar>(
                this,
                android.R.layout.simple_spinner_item,
                calendars
        ) {
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
                calendarId = selectedCalendar.getId();
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

        btBuyActivity.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(ActivityDetailsActivity.this, CartActivity.class);
                intent.putExtra(ACTIVITY_ID, activity.getId());
                intent.putExtra(CALENDAR_ID, calendarId);
                startActivityForResult(intent, ADD);
            }
        });
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
                .commit();
    }

    public void onClickPhotos(View view) {
        // Hide background views
        findViewById(R.id.scrollView).setVisibility(View.GONE);
        findViewById(R.id.button_container).setVisibility(View.GONE);
        // Show fragment container
        findViewById(R.id.fragment_container).setVisibility(View.VISIBLE);
        // Add the fragment
        FragmentManager fragmentManager = getSupportFragmentManager();
        Fragment fragment = new ActivityPhotosFragment();
        Bundle args = new Bundle();
        args.putInt(ID_ACTIVITY, getIntent().getIntExtra(ID_ACTIVITY, 2));
        fragment.setArguments(args);
        fragmentManager.beginTransaction()
                .replace(R.id.fragment_container, fragment)
                .commit();
    }
}