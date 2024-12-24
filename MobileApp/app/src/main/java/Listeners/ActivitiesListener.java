package Listeners;

import java.util.ArrayList;

import Model.Activity;
import Model.Calendar;
import Model.CalendarTime;

public interface ActivitiesListener {
    void onRefreshActivitiesList(ArrayList<Activity> listActivities);
    void onRefreshCalendarList(ArrayList<Calendar> listCalendar);
    void onRefreshTimeList(ArrayList<CalendarTime> listCalendarTime);
    default void onRefreshAllData(ArrayList<Activity> listActivities, ArrayList<Calendar> listCalendar, ArrayList<CalendarTime> listCalendarTime) {
        onRefreshActivitiesList(listActivities);
        onRefreshCalendarList(listCalendar);
        onRefreshTimeList(listCalendarTime);
    }
}
