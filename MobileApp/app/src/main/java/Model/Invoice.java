package Model;

public class Invoice {
    private int id;
    private String participant,address,day,hour,activityName,activityDescription;
    private double price;
    private int nif;

    public Invoice(int id, String participant, String address, String day, String hour, String activityName, String activityDescription, double price, int nif) {
        this.id = id;
        this.participant = participant;
        this.address = address;
        this.day = day;
        this.hour = hour;
        this.activityName = activityName;
        this.activityDescription = activityDescription;
        this.price = price;
        this.nif = nif;
    }

    public String getParticipant() {
        return participant;
    }

    public void setParticipant(String participant) {
        this.participant = participant;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getDay() {
        return day;
    }

    public void setDay(String day) {
        this.day = day;
    }

    public String getHour() {
        return hour;
    }

    public void setHour(String hour) {
        this.hour = hour;
    }

    public String getActivityName() {
        return activityName;
    }

    public void setActivityName(String activityName) {
        this.activityName = activityName;
    }

    public String getActivityDescription() {
        return activityDescription;
    }

    public void setActivityDescription(String activityDescription) {
        this.activityDescription = activityDescription;
    }

    public double getPrice() {
        return price;
    }

    public void setPrice(double price) {
        this.price = price;
    }

    public int getNif() {
        return nif;
    }

    public void setNif(int nif) {
        this.nif = nif;
    }
}
