package pt.ipleiria.estg.dei.waypinpoint.utils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import Model.Calendar;


public class CalendarJsonParser {
    public static ArrayList<Calendar> parserJsonCalendar(JSONArray response) {
        ArrayList<Calendar> calendars = new ArrayList<>();
        try {
            for (int c = 0; c < response.length(); c++) {
                JSONObject calendar = (JSONObject) response.get(c);
                int id = calendar.getInt("id");
                int activity_id = calendar.getInt("activity_id");
                int date_id = calendar.getInt("date_id");
                String date = calendar.getString("date");
                int time_id = calendar.getInt("time_id");

                Calendar auxCalendar = new Calendar(id, activity_id, date_id, date, time_id);
                calendars.add(auxCalendar);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return calendars;
    }
}
