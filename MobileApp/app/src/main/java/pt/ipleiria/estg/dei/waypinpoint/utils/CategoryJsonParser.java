package pt.ipleiria.estg.dei.waypinpoint.utils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import Model.Category;

public class CategoryJsonParser {
    public static ArrayList<Category> parserJsonCategory(JSONArray response) {
        ArrayList<Category> categories = new ArrayList<>();
        try {
            for (int c = 0; c < response.length(); c++) {
                JSONObject category = (JSONObject) response.get(c);
                int id = category.getInt("id");
                String description  = category.getString("description");

                Category auxCategory = new Category(id, description);
                categories.add(auxCategory);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return categories;
    }
}
