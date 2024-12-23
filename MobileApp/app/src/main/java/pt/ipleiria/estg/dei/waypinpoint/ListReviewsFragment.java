package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.ActivityDetailsActivity.ID_ACTIVITY;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SearchView;

import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import Listeners.ReviewsListener;
import Model.Review;
import Model.SingletonManager;
import adaptors.ReviewListAdapter;

public class ListReviewsFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, ReviewsListener {

    private ListView lvReviews;
    private ArrayList<Review> reviews;
    private SwipeRefreshLayout swipeRefreshLayout;
    private SearchView searchView;
    private FloatingActionButton fabReview;

    public ListReviewsFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_list_reviews, container, false);
        lvReviews = view.findViewById(R.id.lvReviews);
        int activityId = getArguments().getInt(ID_ACTIVITY);

        lvReviews.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
//                Intent intent = new Intent(getContext(), ReviewDetailsActivity.class);
//                intent.putExtra(ID_REVIEW, id);
            }
        });

        fabReview = view.findViewById(R.id.fabCrudReview);
        fabReview.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
//                Intent intent = new Intent(getContext(), ActivityDetailsActivity.class);
//                startActivityForResult(intent, REGISTER);
            }
        });

        swipeRefreshLayout = view.findViewById(R.id.srl_reviews);
        swipeRefreshLayout.setOnRefreshListener(this);

        SingletonManager.getInstance(getContext()).setReviewsListener(this);
        SingletonManager.getInstance(getContext()).getReviewsApi(getContext(), activityId);

        return view;
    }

    @Override
    public void onRefresh() {
        int activityId = getArguments().getInt(ID_ACTIVITY);
        SingletonManager.getInstance(getContext()).getReviewsApi(getContext(), activityId);
    }

    @Override
    public void onRefreshReviewsList(ArrayList<Review> listReviews) {
        if (listReviews != null) {
            lvReviews.setAdapter(new ReviewListAdapter(getContext(), listReviews));
        }
    }
}