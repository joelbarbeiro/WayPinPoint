package pt.ipleiria.estg.dei.waypinpoint;

import static android.graphics.PorterDuff.Mode.ADD;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.REGISTER;

import android.content.Intent;
import android.os.Bundle;

import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import Listeners.ActivitiesListener;
import Model.Activity;
import Model.SingletonManager;
import adaptors.ActivitiesListAdaptor;


public class ListActivitiesFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, ActivitiesListener {

    private ListView lvActivities;
    private ArrayList<Activity> activities;
    private SwipeRefreshLayout swipeRefreshLayout;
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

        lvActivities.setOnItemClickListener(new AdapterView.OnItemClickListener(){
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                Intent intent = new Intent(getContext(), ActivityDetailsActivity.class);
                intent.putExtra(ActivityDetailsActivity.ID_ACTIVITY, (int) l );
                //startActivity(intent);
                startActivityForResult(intent, EDIT);
            }
        });

        fabCrudActivity = view.findViewById(R.id.fabCrudActivity);
        fabCrudActivity.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
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
    public void onRefresh() {
        SingletonManager.getInstance(getContext()).getActivities(getContext());
        swipeRefreshLayout.setRefreshing(false);
    }

    @Override
    public void onRefreshActivitiesList(ArrayList<Activity> listActivities) {
        if(listActivities != null){
            lvActivities.setAdapter(new ActivitiesListAdaptor(getContext(), listActivities));
        }
    }
}