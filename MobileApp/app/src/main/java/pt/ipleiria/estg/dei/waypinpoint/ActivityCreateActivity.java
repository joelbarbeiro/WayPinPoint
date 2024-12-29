package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DEFAULT_IMG;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ACTIVITY_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getImgUri;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.app.DatePickerDialog;
import android.widget.TextView;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;
import java.util.Calendar;

import Listeners.ActivityListener;
import Model.Activity;
import Model.CalendarTime;
import Model.Category;
import Model.SingletonManager;
import Model.WaypinpointDbHelper;

public class ActivityCreateActivity extends AppCompatActivity implements ActivityListener {
    private ImageView imgActivity;
    private EditText etName;
    private EditText etDescription;
    private EditText etMaxPax;
    private EditText etPricePerPax;
    private EditText etAddress;
    private Spinner spCategories;
    private Button btCreateDateHour;
    private LinearLayout lvDateHour;
    private FloatingActionButton fabCreateActivity;
    private Activity activity;
    private ArrayList<Category> categories;
    private int categoryId;
    private ArrayList<CalendarTime> calendarTimes;
    private ArrayList<String> dateHour = new ArrayList<>();
    private WaypinpointDbHelper waypinpointDbHelper;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_create);
        int id = getIntent().getIntExtra(ACTIVITY_ID, 0);
        System.out.println("--> Id activity " + id);


        waypinpointDbHelper = new WaypinpointDbHelper(getApplicationContext());
        activity = SingletonManager.getInstance(getApplicationContext()).getActivity(id);
        categories = waypinpointDbHelper.getCategoryDB();
        calendarTimes = waypinpointDbHelper.getCalendarTimeDB();

        imgActivity = findViewById(R.id.imgCreateActiviy);
        etName = findViewById(R.id.etActivityCreateName);
        etDescription = findViewById(R.id.etActivityCreateDescription);
        etMaxPax = findViewById(R.id.etActivityCreateMaxPax);
        etPricePerPax = findViewById(R.id.etActivityCreatePricePerPax);
        etAddress = findViewById(R.id.etActivityCreateAddress);
        spCategories = findViewById(R.id.spinnerActivityCreateCategory);
        lvDateHour = findViewById(R.id.lvCreateDateHourContainer);
        btCreateDateHour = findViewById(R.id.btCreateDateHour);

        btCreateDateHour.setOnClickListener(v -> showDatePickerDialog());

        fabCreateActivity = findViewById(R.id.fabCreateActivity);

        if (activity != null) {
            loadActivity();
            fabCreateActivity.setImageResource(R.drawable.ic_save);
        } else {
            Glide.with(getApplicationContext())
                    .load(R.drawable.img_default_activity)
                    .placeholder(R.drawable.img_default_activity)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .into(imgActivity);
            loadCategory();
            fabCreateActivity.setImageResource(R.drawable.ic_action_add);
        }
        fabCreateActivity.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (activity != null) {
                    activity.setName(etName.getText().toString());
                    activity.setDescription(etDescription.getText().toString());
                    activity.setPhoto(DEFAULT_IMG);
                    activity.setMaxpax(Integer.parseInt(etMaxPax.getText().toString()));
                    activity.setPriceperpax(Double.parseDouble(etPricePerPax.getText().toString()));
                    activity.setAddress(etAddress.getText().toString());
                    activity.setSupplier(getUserId(getApplicationContext()));
                    activity.setStatus(1);
                    activity.setCategory(categoryId);
                    //TODO: EDIT ACTIVITY
                    //SingletonManager.getInstance(getApplicationContext()).putActivitiesApi(livro, getApplicationContext());

                } else {
                    activity = new Activity(
                            0,
                            etName.getText().toString(),
                            etDescription.getText().toString(),
                            DEFAULT_IMG,
                            Integer.parseInt(etMaxPax.getText().toString()),
                            Double.parseDouble(etPricePerPax.getText().toString()),
                            etAddress.getText().toString(),
                            getUserId(getApplicationContext()),
                            1,
                            categoryId
                            );

                    SingletonManager.getInstance(getApplicationContext()).postActivityAPI(activity, getApplicationContext());
                }
            }
        });

        SingletonManager.getInstance(getApplicationContext()).setActivityListener(this);

    }

    private void loadActivity() {
        setTitle("Edit: " + activity.getName());

        etName.setText(activity.getName());
        etDescription.setText(activity.getDescription());
        etMaxPax.setText("" + activity.getMaxpax());
        etPricePerPax.setText("" + activity.getPriceperpax());

        loadCategory();

        String imgPath = getImgUri(getApplicationContext());
        Glide.with(getApplicationContext())
                .load(imgPath + activity.getSupplier() + "/" + activity.getPhoto())
                .placeholder(R.drawable.img_default_activity)
                .diskCacheStrategy(DiskCacheStrategy.ALL)
                .into(imgActivity);
    }
    private void showDatePickerDialog() {
        final Calendar calendar = Calendar.getInstance();
        int year = calendar.get(Calendar.YEAR);
        int month = calendar.get(Calendar.MONTH);
        int day = calendar.get(Calendar.DAY_OF_MONTH);

        DatePickerDialog datePickerDialog = new DatePickerDialog(
                this,
                (view, selectedYear, selectedMonth, selectedDay) -> {
                    String selectedDate = selectedDay + "/" + (selectedMonth + 1) + "/" + selectedYear;
                    showHourDropdown(selectedDate);
                },
                year, month, day
        );

        datePickerDialog.show();
    }

    private void showHourDropdown(String selectedDate) {
        LinearLayout dateHourRow = new LinearLayout(this);
        dateHourRow.setOrientation(LinearLayout.HORIZONTAL);

        TextView tvDate = new TextView(this);
        tvDate.setText(selectedDate);
        tvDate.setPadding(8, 8, 8, 8);

        Spinner spHours = new Spinner(this);
        ArrayAdapter<CalendarTime> hourAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, calendarTimes);
        hourAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spHours.setAdapter(hourAdapter);

        spHours.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                CalendarTime selectedTime = (CalendarTime) parent.getItemAtPosition(position);
                int hourId = selectedTime.getId();
                String hourString = selectedTime.getHour();

                String dateHourPair = selectedDate + " " + hourString;
                if (!dateHour.contains(dateHourPair)) {
                    dateHour.add(dateHourPair);
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });

        dateHourRow.addView(tvDate);
        dateHourRow.addView(spHours);

        lvDateHour.addView(dateHourRow);
    }
    public void loadDateTime(Model.Calendar calendar){

    }

    public void loadCategory() {
        ArrayAdapter<Category> categoryAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, categories);
        categoryAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spCategories.setAdapter(categoryAdapter);
        int positionToSelect = -1;

        if (activity != null) {
            for (int i = 0; i < categories.size(); i++) {
                if (categories.get(i).getId() == activity.getCategory()) {
                    positionToSelect = i;
                    break;
                }
            }
        }
        if (positionToSelect != -1) {
            spCategories.setSelection(positionToSelect);
        }
        spCategories.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                Category selectedCategory = (Category) parent.getItemAtPosition(position);
                categoryId = selectedCategory.getId();
                String categoryDescription = selectedCategory.getDescription();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });
    }

    @Override
    public void onRefreshActivityDetails(int op) {
        Intent intent = new Intent();
        intent.putExtra(OP_CODE, EDIT);
        setResult(RESULT_OK, intent);
        finish();
    }
}