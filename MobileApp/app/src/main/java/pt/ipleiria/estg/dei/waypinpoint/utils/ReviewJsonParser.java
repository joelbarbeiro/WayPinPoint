package pt.ipleiria.estg.dei.waypinpoint.utils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import Model.Activity;
import Model.Review;

public class ReviewJsonParser {
    public static ArrayList<Review> parserJsonReview(JSONArray response) {
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
}
