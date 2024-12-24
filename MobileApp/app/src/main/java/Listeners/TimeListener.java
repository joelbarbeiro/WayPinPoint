package Listeners;

import java.util.ArrayList;

import Model.CalendarTime;

public interface TimeListener {
    void onRefreshTimeList(ArrayList<CalendarTime> listTime);

}
