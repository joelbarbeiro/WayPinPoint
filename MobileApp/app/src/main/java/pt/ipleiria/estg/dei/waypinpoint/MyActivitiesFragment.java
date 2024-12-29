package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ACTIVITY_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.REGISTER;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.filterActivitiesBySupplier;


import android.content.Context;
import android.content.Intent;
import android.os.Bundle;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SearchView;

import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.snackbar.Snackbar;

import java.util.ArrayList;
import java.util.List;

import Listeners.ActivitiesListener;
import Model.Activity;
import Model.Calendar;
import Model.CalendarTime;
import Model.Category;
import Model.SingletonManager;
import adaptors.ActivitiesListAdaptor;
import adaptors.MyActivitiesAdapter;


public class MyActivitiesFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, ActivitiesListener {

    private ListView lvMyActivities;
    private ArrayList<Activity> activities;
    private ArrayList<Calendar> calendars;
    private ArrayList<CalendarTime> times;
    private ArrayList<Category> categories;
    private SwipeRefreshLayout swipeRefreshLayout;
    private SearchView searchView;
    private FloatingActionButton fabCrudActivity;

    public MyActivitiesFragment() {

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        setHasOptionsMenu(true);
        View view = inflater.inflate(R.layout.fragment_my_activities, container, false);
        lvMyActivities = view.findViewById(R.id.lvMyActivities);

        lvMyActivities.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long l) {
                Intent intent = new Intent(getContext(), ActivityCreateActivity.class);
                intent.putExtra(ACTIVITY_ID, (int) l);
                startActivityForResult(intent, EDIT);
            }
        });

        fabCrudActivity = view.findViewById(R.id.fabCrudActivity);
        fabCrudActivity.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getContext(), ActivityCreateActivity.class);
                startActivityForResult(intent, REGISTER);
            }
        });
        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh_layout_Crud_Activities);
        swipeRefreshLayout.setOnRefreshListener(this);

        SingletonManager.getInstance(getContext()).setActivitiesListener(this);
        SingletonManager.getInstance(getContext()).getActivities(getContext());

        return view;
    }
    @Override
    public void onCreateOptionsMenu(@NonNull Menu menu, @NonNull MenuInflater inflater) {
        inflater.inflate(R.menu.menu_search, menu);

        MenuItem itemSearch = menu.findItem(R.id.app_bar_search);
        searchView = (SearchView) itemSearch.getActionView();


        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String s) {
                return false;
            }

            @Override
            public boolean onQueryTextChange(String s) {
                ArrayList<Activity> tempActivity = new ArrayList<>();

                for (Activity a : SingletonManager.getInstance(getContext()).getActivities()) {
                    if (a.getName().toLowerCase().contains(s.toLowerCase())) {
                        tempActivity.add(a);
                    }
                }

                lvMyActivities.setAdapter(new ActivitiesListAdaptor(getContext(), tempActivity, calendars, times, categories));

                return true;
            }
        });

        super.onCreateOptionsMenu(menu, inflater);
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        if (resultCode == LoginActivity.RESULT_OK) {
            if (requestCode == REGISTER || requestCode == EDIT) {
                SingletonManager.getInstance(getContext()).getActivities(getContext());

                switch (requestCode) {
                    case REGISTER:
                        Snackbar.make(getView(), "Activity successfully created!", Snackbar.LENGTH_SHORT).show();
                        break;
                    case EDIT:
                        if (data.getIntExtra(OP_CODE, 0) == DELETE) {
                            Snackbar.make(getView(), "Activity removed!", Snackbar.LENGTH_SHORT).show();
                        } else {
                            Snackbar.make(getView(), "Activity edit successful!", Snackbar.LENGTH_SHORT).show();
                        }
                        break;
                }
            }
        }


        super.onActivityResult(requestCode, resultCode, data);
    }
    @Override
    public void onRefreshActivitiesList(ArrayList<Activity> listActivities) {

    }
    @Override
    public void onRefreshCalendarList(ArrayList<Model.Calendar> listCalendar) {

    }
    @Override
    public void onRefreshTimeList(ArrayList<CalendarTime> listCalendarTime) {

    }
    @Override
    public void onRefreshCategoryList(ArrayList<Category> listCategory) {

    }
    @Override
    public void onRefreshAllData(ArrayList<Activity> listActivities, ArrayList<Calendar> listCalendar, ArrayList<CalendarTime> listCalendarTime, ArrayList<Category> listCategories){
        if (listActivities != null && listCalendar != null && listCalendarTime != null && listCategories != null) {
            ArrayList<Activity> supplierActivity = filterActivitiesBySupplier(getContext(), listActivities);
            lvMyActivities.setAdapter(new MyActivitiesAdapter(getContext(), supplierActivity, listCalendar, listCalendarTime, listCategories));
        }
        else{
            System.out.println("---> something is empty listActivitiesFragment");
        }
    }
    @Override
    public void onRefresh() {
        SingletonManager.getInstance(getContext()).getActivities(getContext());
        swipeRefreshLayout.setRefreshing(false);
    }
}