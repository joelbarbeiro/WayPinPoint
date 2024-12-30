package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.ActivityDetailsActivity.ID_ACTIVITY;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ACTIVITY_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID_REVIEW;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.REGISTER;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.USER_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SearchView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.snackbar.Snackbar;

import java.util.ArrayList;

import Listeners.ReviewsListener;
import Model.Review;
import Model.SingletonManager;
import Adapters.ReviewListAdapter;

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
        int userId = getUserId(getContext());
        if (requireActivity() instanceof AppCompatActivity) {
            ((AppCompatActivity) requireActivity()).getSupportActionBar().setTitle("Reviews for Activity");
        }
        lvReviews.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Intent intent = new Intent(getContext(), ReviewDetailsActivity.class);
                intent.putExtra(ID_REVIEW, (int) id);
                intent.putExtra(USER_ID, userId);
                intent.putExtra(ACTIVITY_ID, activityId);
                startActivityForResult(intent, EDIT);
            }
        });

        fabReview = view.findViewById(R.id.fabCrudReview);
        fabReview.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(getContext(), ReviewDetailsActivity.class);
                intent.putExtra(USER_ID, userId);
                intent.putExtra(ACTIVITY_ID, activityId);
                startActivityForResult(intent, REGISTER);
            }
        });

        swipeRefreshLayout = view.findViewById(R.id.srl_reviews);
        swipeRefreshLayout.setOnRefreshListener(this);

        SingletonManager.getInstance(getContext()).setReviewsListener(this);
        SingletonManager.getInstance(getContext()).getReviewsApi(getContext(), activityId);

        return view;
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        int activityId = getArguments().getInt(ID_ACTIVITY);
        if (resultCode == Activity.RESULT_OK) {
            if (requestCode == REGISTER || requestCode == EDIT) {

                SingletonManager.getInstance(getContext()).getReviewsApi(getContext(), activityId);

                switch (requestCode) {
                    case REGISTER:
                        Snackbar.make(getView(), "Review Added Successfully", Snackbar.LENGTH_SHORT).show();
                        break;
                    case EDIT:
                        if (data.getIntExtra(OP_CODE, 0) == DELETE) {
                            Snackbar.make(getView(), "Review Removed Successfully", Snackbar.LENGTH_SHORT).show();
                        } else {
                            Snackbar.make(getView(), "Review Edited Successfully", Snackbar.LENGTH_SHORT).show();
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        super.onActivityResult(requestCode, resultCode, data);
    }

    @Override
    public void onRefresh() {
        int activityId = getArguments().getInt(ID_ACTIVITY);
        SingletonManager.getInstance(getContext()).getReviewsApi(getContext(), activityId);
        swipeRefreshLayout.setRefreshing(false);
    }

    @Override
    public void onRefreshReviewsList(ArrayList<Review> listReviews) {
        if (listReviews != null) {
            lvReviews.setAdapter(new ReviewListAdapter(getContext(), listReviews));
        }
    }
}