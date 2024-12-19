package pt.ipleiria.estg.dei.waypinpoint;

import android.os.Bundle;

import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;

import java.util.ArrayList;

import Listeners.ActivitiesListener;
import Model.Activity;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link ListActivitiesFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class ListActivitiesFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, ActivitiesListener {

    private ListView lvActivities;
    private ArrayList<Activity> activities;
    private SwipeRefreshLayout swipeRefreshLayout;

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
                Intent intent = new Intent(getContext(), )
                        //TODO: ActivityDetails <----- !!!
            }
        });
        return inflater.inflate(R.layout.fragment_list_activities, container, false);
    }

    @Override
    public void onRefresh() {

    }

    @Override
    public void onRefreshActivitiesList(ArrayList<Activity> listActivities) {

    }
}