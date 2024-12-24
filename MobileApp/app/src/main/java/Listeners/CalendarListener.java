package Listeners;

import java.util.ArrayList;

import Model.Calendar;

public interface CalendarListener {
    void onRefreshCalendarsList(ArrayList<Calendar> listCalendars);

}
