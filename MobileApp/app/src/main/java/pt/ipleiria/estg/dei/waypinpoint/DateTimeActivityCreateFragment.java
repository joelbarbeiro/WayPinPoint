package pt.ipleiria.estg.dei.waypinpoint;

import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;


public class DateTimeActivityCreateFragment extends Fragment {
    private ListView lv

    public DateTimeActivityCreateFragment() {
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        return inflater.inflate(R.layout.fragment_date_time_activity_create, container, false);
    }
}