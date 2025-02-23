package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ADD;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;

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

import com.google.android.material.snackbar.Snackbar;

import java.util.ArrayList;

import Adapters.ActivitiesListAdapter;
import Listeners.ActivitiesListener;
import Model.Activity;
import Model.Calendar;
import Model.CalendarTime;
import Model.Category;
import Model.MQTTManager;
import Model.SingletonManager;


public class ListActivitiesFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, ActivitiesListener {

    private ListView lvActivities;
    private ArrayList<Activity> activities;
    private ArrayList<Calendar> calendars;
    private ArrayList<CalendarTime> times;
    private ArrayList<Category> categories;
    private SwipeRefreshLayout swipeRefreshLayout;
    private View emptyView;
    private TextView tvEmptyMessage;
    private SearchView searchView;
    private MQTTManager mqttManager;

    public ListActivitiesFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        setHasOptionsMenu(true);
        View view = inflater.inflate(R.layout.fragment_list_activities, container, false);
        lvActivities = view.findViewById(R.id.lvActivities);
        emptyView = view.findViewById(R.id.emptyViewLayoutActivities);

        lvActivities.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                Intent intent = new Intent(getContext(), ActivityDetailsActivity.class);
                intent.putExtra(ActivityDetailsActivity.ID_ACTIVITY, (int) l);
                startActivityForResult(intent, EDIT);
            }
        });

        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh_layout_Activities);
        swipeRefreshLayout.setOnRefreshListener(this);

        lvActivities.setOnScrollListener(new AbsListView.OnScrollListener() {
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
        inflater.inflate(R.menu.menu_cart, menu);

        MenuItem itemCart = menu.findItem(R.id.navCart);
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

                lvActivities.setAdapter(new ActivitiesListAdapter(getContext(), tempActivity, calendars, times, categories));

                return true;
            }
        });

        itemCart.setOnMenuItemClickListener(new MenuItem.OnMenuItemClickListener() {
            @Override
            public boolean onMenuItemClick(MenuItem menuItem) {
                Fragment fragment = new CartFragment();
                getActivity().getSupportFragmentManager()
                        .beginTransaction()
                        .addToBackStack(null)
                        .replace(R.id.contentFragment, fragment)
                        .commit();
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
    public void onRefreshCalendarList(ArrayList<Calendar> listCalendar) {

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
            lvActivities.setVisibility(View.VISIBLE);
            emptyView.setVisibility(View.GONE);
            lvActivities.setAdapter(new ActivitiesListAdapter(getContext(), listActivities, listCalendar, listCalendarTime, listCategories));
        }
        if (listActivities.isEmpty()) {
            lvActivities.setVisibility(View.GONE);
            emptyView.setVisibility(View.VISIBLE);
            tvEmptyMessage = emptyView.findViewById(R.id.tvEmptyMessage);
            tvEmptyMessage.setText(R.string.no_activities_message);
        }
    }

    @Override
    public void onRefresh() {
        SingletonManager.getInstance(getContext()).getActivities(getContext());
        swipeRefreshLayout.setRefreshing(false);
    }
}