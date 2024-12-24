package pt.ipleiria.estg.dei.waypinpoint.utils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import Model.Review;
import Model.User;

public class ReviewJsonParser {

    public static ArrayList<Review> parserJsonReviews(JSONArray response) {
        ArrayList<Review> reviews = new ArrayList<>();
        try {
            for (int c = 0; c < response.length(); c++) {
                JSONObject review = (JSONObject) response.get(c);
                int id = review.getInt("id");
                int userId = review.getInt("user_id");
                int activityId = review.getInt("activity_id");
                int score = review.getInt("score");
                String message = review.getString("message");

                Review auxReview = new Review(id, userId,activityId, score, message, 0);
                reviews.add(auxReview);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return reviews;
    }

    public static Review parserJsonReview (String response){
        Review auxReview = null;

        try{
            JSONObject Review = new JSONObject(response);
            int reviewId = Review.getInt("id");
            int userId = Review.getInt("user_id");
            int activityId = Review.getInt("activity_id");
            int score = Review.getInt("score");
            String message = Review.getString("message");
            int createAt = Review.getInt("created_at");

            auxReview = new Review(
                    reviewId,
                    userId,
                    activityId,
                    score,
                    message,
                    createAt
            );
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return auxReview;
    }
}
