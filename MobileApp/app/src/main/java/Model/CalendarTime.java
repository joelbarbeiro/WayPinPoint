package Model;

public class CalendarTime {
    int id;
    String hour;

    public CalendarTime(int time_id, String hour) {
        this.id = time_id;
        this.hour = hour;
    }

    public void setId(int time_id) {
        this.id = time_id;
    }

    public void setHour(String hour) {
        this.hour = hour;
    }

    public int getId() {
        return id;
    }

    public String getHour() {
        return hour;
    }

    @Override
    public String toString() {
        return hour;
    }
}
