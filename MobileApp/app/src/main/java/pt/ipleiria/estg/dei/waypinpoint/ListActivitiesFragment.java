package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.REGISTER;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SearchView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.snackbar.Snackbar;

import java.util.ArrayList;

import Listeners.ActivitiesListener;
import Model.Activity;
import Model.SingletonManager;
import adaptors.ActivitiesListAdaptor;


public class ListActivitiesFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, ActivitiesListener {

    private ListView lvActivities;
    private ArrayList<Activity> activities;
    private SwipeRefreshLayout swipeRefreshLayout;
    private SearchView searchView;
    private FloatingActionButton fabCrudActivity;

    public ListActivitiesFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        setHasOptionsMenu(true);
        View view = inflater.inflate(R.layout.fragment_list_activities, container, false);
        lvActivities = view.findViewById(R.id.lvActivities);

        lvActivities.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                Intent intent = new Intent(getContext(), ActivityDetailsActivity.class);
                intent.putExtra(ActivityDetailsActivity.ID_ACTIVITY, (int) l);
                startActivityForResult(intent, EDIT);
            }
        });

        fabCrudActivity = view.findViewById(R.id.fabCrudActivity);
        fabCrudActivity.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getContext(), ActivityDetailsActivity.class);
                startActivityForResult(intent, REGISTER);
            }
        });
        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh_layout);
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
                ArrayList<Activity> tempLivros = new ArrayList<>();

                for (Activity a : SingletonManager.getInstance(getContext()).getActivities()) {
                    if (a.getName().toLowerCase().contains(s.toLowerCase())) {
                        tempLivros.add(a);
                    }
                }

                lvActivities.setAdapter(new ActivitiesListAdaptor(getContext(), tempLivros));

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
    public void onRefresh() {
        SingletonManager.getInstance(getContext()).getActivities(getContext());
        swipeRefreshLayout.setRefreshing(false);
    }

    @Override
    public void onRefreshActivitiesList(ArrayList<Activity> listActivities) {
        if (listActivities != null) {
            lvActivities.setAdapter(new ActivitiesListAdaptor(getContext(), listActivities));
        }
    }
}