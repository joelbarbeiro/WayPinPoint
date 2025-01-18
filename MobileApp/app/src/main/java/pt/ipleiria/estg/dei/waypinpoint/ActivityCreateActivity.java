package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ACTIVITY_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.PICK_IMAGE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getImgUri;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.app.DatePickerDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Calendar;

import Listeners.ActivityListener;
import Model.Activity;
import Model.CalendarTime;
import Model.Category;
import Model.DateTimeParser;
import Model.SingletonManager;
import Model.WaypinpointDbHelper;
import pt.ipleiria.estg.dei.waypinpoint.utils.StatusJsonParser;

public class ActivityCreateActivity extends AppCompatActivity implements ActivityListener {
    private int categoryId;
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
    private ArrayList<Model.Calendar> calendars;
    private ArrayList<CalendarTime> calendarTimes;
    private ArrayList<DateTimeParser> dateHour = new ArrayList<>();
    private WaypinpointDbHelper waypinpointDbHelper;
    private Uri imageUri;

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

        imgActivity.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(Intent.ACTION_PICK, MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
                startActivityForResult(intent, PICK_IMAGE);
            }
        });

        fabCreateActivity = findViewById(R.id.fabCreateActivity);

        if (activity != null) {
            imageUri = Uri.parse(activity.getPhoto());
            loadActivity();
            loadExistingDateTime(activity.getId());
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
                collectDateHourData();
                if (etName.getText().toString().isEmpty() ||
                        etDescription.getText().toString().isEmpty() ||
                        etMaxPax.getText().toString().isEmpty() ||
                        etPricePerPax.getText().toString().isEmpty() ||
                        etAddress.getText().toString().isEmpty()||
                        imageUri == null || imageUri.toString().isEmpty() ||
                        dateHour.isEmpty()) {

                    Toast.makeText(getApplicationContext(), R.string.activity_empty_fields, Toast.LENGTH_SHORT).show();
                    return;
                }
                if (activity != null) {
                    activity.setName(etName.getText().toString() );
                    activity.setDescription(etDescription.getText().toString());
                    activity.setPhoto(imageUri.toString());
                    activity.setMaxpax(Integer.parseInt(etMaxPax.getText().toString()));
                    activity.setPriceperpax(Double.parseDouble(etPricePerPax.getText().toString()));
                    activity.setAddress(etAddress.getText().toString());
                    activity.setSupplier(getUserId(getApplicationContext()));
                    activity.setStatus(1);
                    activity.setCategory(categoryId);

                    try {
                        SingletonManager.getInstance(getApplicationContext()).editActivityAPI(activity, dateHour, getApplicationContext());
                    } catch (IOException e) {
                        throw new RuntimeException(e);
                    }

                } else {
                    activity = new Activity(
                            0,
                            etName.getText().toString(),
                            etDescription.getText().toString(),
                            imageUri.toString(),
                            Integer.parseInt(etMaxPax.getText().toString()),
                            Double.parseDouble(etPricePerPax.getText().toString()),
                            etAddress.getText().toString(),
                            getUserId(getApplicationContext()),
                            1,
                            categoryId
                    );

                    try {
                        SingletonManager.getInstance(getApplicationContext()).postActivityAPI(activity, dateHour, getApplicationContext());
                    } catch (IOException e) {
                        if(activity.getId() == 0){
                            activity = null;
                            Toast.makeText(getApplicationContext(), R.string.activity_saving_error, Toast.LENGTH_SHORT).show();
                        }
                        throw new RuntimeException(e);
                    }
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
        etAddress.setText(activity.getAddress());

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
                    String selectedDate = selectedYear + "-" + (selectedMonth + 1) + "-" + selectedDay;
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

        dateHourRow.addView(tvDate);
        dateHourRow.addView(spHours);

        lvDateHour.addView(dateHourRow);
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
                //System.out.println("->> Category id " + categoryId);
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });
    }

    public void loadExistingDateTime(int activity_id) {
        dateTimeForActivity(activity_id);
        for (DateTimeParser i : dateHour) {
            LinearLayout dateHourRow = new LinearLayout(this);
            dateHourRow.setOrientation(LinearLayout.HORIZONTAL);

            TextView tvDate = new TextView(this);
            tvDate.setText(i.getParserDate());
            tvDate.setPadding(8, 8, 8, 8);

            Spinner spHours = new Spinner(this);
            ArrayAdapter<CalendarTime> hourAdapter = new ArrayAdapter<CalendarTime>(
                    this,
                    android.R.layout.simple_spinner_item,
                    calendarTimes) {
                @Override
                public View getDropDownView(int position, View convertView, ViewGroup parent) {
                    View view = super.getDropDownView(position, convertView, parent);

                    CalendarTime calendarTime = getItem(position);
                    if (calendarTime != null) {
                        String displayText = calendarTime.getHour();
                        TextView textView = view.findViewById(android.R.id.text1);
                        textView.setText(displayText);
                    }

                    return view;
                }

                @Override
                public View getView(int position, View convertView, ViewGroup parent) {
                    View view = super.getView(position, convertView, parent);

                    CalendarTime calendarTime = getItem(position);
                    if (calendarTime != null) {
                        TextView textView = view.findViewById(android.R.id.text1);
                        String displayText = calendarTime.getHour();
                        textView.setText(displayText);
                    }

                    return view;
                }
            };

            spHours.setAdapter(hourAdapter);
            int preselectedIndex = getIndexForTimeId(i.getParserTime());
            if (preselectedIndex != -1) {
                spHours.setSelection(preselectedIndex);
            }

            dateHourRow.addView(tvDate);
            dateHourRow.addView(spHours);

            lvDateHour.addView(dateHourRow);
        }
    }

    private int getIndexForTimeId(int timeId) {
        for (int i = 0; i < calendarTimes.size(); i++) {
            if (calendarTimes.get(i).getId() == timeId) {
                return i;
            }
        }
        return -1;
    }

    public void dateTimeForActivity(int id) {
        calendars = waypinpointDbHelper.getCalendarByActivityId(id);
        for (Model.Calendar c : calendars) {
            int tmpHour = waypinpointDbHelper.getCalendarTimeById(c.getTime_id()).getId();
            DateTimeParser tmpTimeParser = new DateTimeParser(c.getDate(), tmpHour);
            dateHour.add(tmpTimeParser);
        }
    }

    @Override
    public void onRefreshActivityDetails(int op) {
        Intent intent = new Intent();
        intent.putExtra(OP_CODE, EDIT);
        setResult(RESULT_OK, intent);
        finish();
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == PICK_IMAGE && resultCode == android.app.Activity.RESULT_OK && data != null) {
            imageUri = data.getData();

            if (imageUri != null) {
                imgActivity.setImageURI(imageUri);
            }
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        if (activity != null) {
            getMenuInflater().inflate(R.menu.menu_delete, menu);
            return super.onCreateOptionsMenu(menu);
        }
        return false;
    }

    public boolean onOptionsItemSelected(@NonNull MenuItem item) {
        if (item.getItemId() == R.id.itemRemover) {
            if (!StatusJsonParser.isConnectionInternet(getApplicationContext())) {
                Toast.makeText(this, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
            } else {
                dialogRemover();
            }
        }
        return super.onOptionsItemSelected(item);
    }

    private void dialogRemover() {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(R.string.delete_activity_title)
                .setMessage(R.string.dialog_remove_activity_message)
                .setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {
                        SingletonManager.getInstance(getApplicationContext()).delActivityAPI(activity, getApplicationContext());
                    }
                }).setNegativeButton(android.R.string.no, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {

                    }
                }).setIcon(android.R.drawable.ic_delete)
                .show();
    }
    private void collectDateHourData() {
        dateHour.clear();
        DateTimeParser tmpDateTime;
        for (int i = 0; i < lvDateHour.getChildCount(); i++) {
            LinearLayout row = (LinearLayout) lvDateHour.getChildAt(i);
            TextView dateTextView = (TextView) row.getChildAt(0);
            Spinner hourSpinner = (Spinner) row.getChildAt(1);
            CalendarTime selectedTime = (CalendarTime) hourSpinner.getSelectedItem();

            tmpDateTime = new DateTimeParser(dateTextView.getText().toString(), selectedTime.getId());

            dateHour.add(tmpDateTime);
        }
    }
}