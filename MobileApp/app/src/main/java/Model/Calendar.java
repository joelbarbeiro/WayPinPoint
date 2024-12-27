package Model;

public class Calendar {
    private int id, activity_id, time_id, date_id;
    private String date;

    public Calendar(int id, int activity_id, int date_id, String date, int time_id) {
        this.id = id;
        this.activity_id = activity_id;
        this.date_id = date_id;
        this.date = date;
        this.time_id = time_id;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getActivity_id() {
        return activity_id;
    }

    public void setActivity_id(int activity_id) {
        this.activity_id = activity_id;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public int getTime_id() {
        return time_id;
    }

    public void setTime_id(int time_id) {
        this.time_id = time_id;
    }

    public int getDate_id() {
        return date_id;
    }

    public void setDate_id(int date_id) {
        this.date_id = date_id;
    }
}
