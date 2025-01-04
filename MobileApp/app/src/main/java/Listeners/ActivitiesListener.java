package Listeners;

import java.util.ArrayList;

import Model.Activity;
import Model.Calendar;
import Model.CalendarTime;
import Model.Category;

public interface ActivitiesListener {
    void onRefreshActivitiesList(ArrayList<Activity> listActivities);

    void onRefreshCalendarList(ArrayList<Calendar> listCalendar);

    void onRefreshTimeList(ArrayList<CalendarTime> listCalendarTime);

    void onRefreshCategoryList(ArrayList<Category> listCategory);

    default void onRefreshAllData(ArrayList<Activity> listActivities, ArrayList<Calendar> listCalendar, ArrayList<CalendarTime> listCalendarTime, ArrayList<Category> listCategory) {
        onRefreshActivitiesList(listActivities);
        onRefreshCalendarList(listCalendar);
        onRefreshTimeList(listCalendarTime);
        onRefreshCategoryList(listCategory);
    }
}
