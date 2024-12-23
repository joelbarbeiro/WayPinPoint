package Listeners;

import java.util.ArrayList;

import Model.Review;

public interface ReviewsListener {
    void onRefreshReviewsList(ArrayList<Review> listReviews);
}
