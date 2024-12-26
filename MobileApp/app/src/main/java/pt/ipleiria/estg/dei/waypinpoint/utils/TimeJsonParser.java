package pt.ipleiria.estg.dei.waypinpoint.utils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import Model.CalendarTime;

public class TimeJsonParser {
    public static ArrayList<CalendarTime> parserJsonTime(JSONArray response) {
        ArrayList<CalendarTime> times = new ArrayList<>();
        try {
            for (int c = 0; c < response.length(); c++) {
                JSONObject calendar = (JSONObject) response.get(c);
                int id = calendar.getInt("id");
                String hour = calendar.getString("hour");

                CalendarTime auxCalendarTime = new CalendarTime(id, hour);
                times.add(auxCalendarTime);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return times;
    }
}
