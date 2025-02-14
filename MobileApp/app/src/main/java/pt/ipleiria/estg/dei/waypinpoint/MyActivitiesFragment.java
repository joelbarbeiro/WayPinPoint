package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ACTIVITY_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ADD;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.filterActivitiesBySupplier;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SearchView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.snackbar.Snackbar;

import java.util.ArrayList;

import Adapters.ActivitiesListAdapter;
import Adapters.MyActivitiesAdapter;
import Listeners.ActivitiesListener;
import Model.Activity;
import Model.Calendar;
import Model.CalendarTime;
import Model.Category;
import Model.SingletonManager;


public class MyActivitiesFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, ActivitiesListener {

    private ListView lvMyActivities;
    private ArrayList<Activity> activities;
    private ArrayList<Calendar> calendars;
    private ArrayList<CalendarTime> times;
    private ArrayList<Category> categories;
    private SwipeRefreshLayout swipeRefreshLayout;
    private View emptyView;
    private SearchView searchView;
    private FloatingActionButton fabCrudActivity;
    private TextView tvEmptyMessage;


    public MyActivitiesFragment() {

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        setHasOptionsMenu(true);
        View view = inflater.inflate(R.layout.fragment_my_activities, container, false);
        lvMyActivities = view.findViewById(R.id.lvMyActivities);
        emptyView = view.findViewById(R.id.emptyViewLayoutMyActivities);

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
                startActivityForResult(intent, ADD);
            }
        });
        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh_layout_Crud_Activities);
        swipeRefreshLayout.setOnRefreshListener(this);

        lvMyActivities.setOnScrollListener(new AbsListView.OnScrollListener() {
            @Override
            public void onScrollStateChanged(AbsListView view, int scrollState) {

            }

            @Override
            public void onScroll(AbsListView view, int firstVisibleItem, int visibleItemCount, int totalItemCount) {
                boolean enableRefresh = firstVisibleItem == 0 &&
                        view.getChildAt(0) != null &&
                        view.getChildAt(0).getTop() == 0;
                swipeRefreshLayout.setEnabled(enableRefresh);
            }
        });

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

                for (Activity a : filterActivitiesBySupplier(getContext(), SingletonManager.getInstance(getContext()).getActivities())) {
                    if (a.getName().toLowerCase().contains(s.toLowerCase())) {
                        tempActivity.add(a);
                    }
                }

                lvMyActivities.setAdapter(new ActivitiesListAdapter(getContext(), tempActivity, calendars, times, categories));

                return true;
            }
        });

        super.onCreateOptionsMenu(menu, inflater);
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        if (resultCode == LoginActivity.RESULT_OK) {
            if (requestCode == ADD || requestCode == EDIT) {
                SingletonManager.getInstance(getContext()).getActivities(getContext());

                switch (requestCode) {
                    case ADD:
                        Snackbar.make(getView(), R.string.activity_success_message, Snackbar.LENGTH_SHORT).show();
                        break;
                    case EDIT:
                        if (data.getIntExtra(OP_CODE, 0) == DELETE) {
                            Snackbar.make(getView(), R.string.activity_remove_success, Snackbar.LENGTH_SHORT).show();
                        } else {
                            Snackbar.make(getView(), R.string.activity_edit_success_message, Snackbar.LENGTH_SHORT).show();
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
    public void onRefreshAllData(ArrayList<Activity> listActivities, ArrayList<Calendar> listCalendar, ArrayList<CalendarTime> listCalendarTime, ArrayList<Category> listCategories) {
        if (listActivities != null && listCalendar != null && listCalendarTime != null && listCategories != null) {
            ArrayList<Activity> supplierActivity = filterActivitiesBySupplier(getContext(), listActivities);
            if (!supplierActivity.isEmpty()) {
                lvMyActivities.setVisibility(View.VISIBLE);
                emptyView.setVisibility(View.GONE);
                lvMyActivities.setAdapter(new MyActivitiesAdapter(getContext(), supplierActivity, listCalendar, listCalendarTime, listCategories));
            } else {
                lvMyActivities.setVisibility(View.GONE);
                emptyView.setVisibility(View.VISIBLE);
                tvEmptyMessage = emptyView.findViewById(R.id.tvEmptyMessage);
                tvEmptyMessage.setText(R.string.empty_my_activities_message);
            }
        }
    }

    @Override
    public void onRefresh() {
        SingletonManager.getInstance(getContext()).getActivities(getContext());
        swipeRefreshLayout.setRefreshing(false);
    }
}