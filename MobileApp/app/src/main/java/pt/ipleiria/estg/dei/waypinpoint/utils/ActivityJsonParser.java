package pt.ipleiria.estg.dei.waypinpoint.utils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import Model.Activity;

public class ActivityJsonParser {
    public static ArrayList<Activity> parserJsonActivities(JSONArray response) {
        ArrayList<Activity> activities = new ArrayList<>();
        try {
            for (int c = 0; c < response.length(); c++) {
                JSONObject activity = (JSONObject) response.get(c);
                int id = activity.getInt("id");
                String name = activity.getString("name");
                String description = activity.getString("description");
                String photo = activity.getString("photo");
                int maxpax = activity.getInt("maxpax");
                double priceperpax = activity.getDouble("priceperpax");
                String address = activity.getString("address");
                int supplier = activity.getInt("user_id");
                int status = activity.getInt("status");
                int category = activity.getInt("category_id");

                Activity auxActivity = new Activity(id, name, description, photo, maxpax, priceperpax, address, supplier, status, category);
                activities.add(auxActivity);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return activities;
    }

    public static Activity parserJsonActivity(String response) {
        Activity auxActivity = null;
        try {
            JSONObject activity = new JSONObject(response);
            int id = activity.getInt("id");
            String name = activity.getString("name");
            String description = activity.getString("description");
            String photo = activity.getString("photo");
            int maxpax = activity.getInt("maxpax");
            double priceperpax = activity.getDouble("priceperpax");
            String address = activity.getString("address");
            int supplier = activity.getInt("user_id");
            int status = activity.getInt("status");
            int category = activity.getInt("category_id");

            auxActivity = new Activity(id, name, description, photo, maxpax, priceperpax, address, supplier, status, category);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return auxActivity;
    }
}
