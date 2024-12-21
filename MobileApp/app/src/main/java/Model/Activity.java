package Model;

public class Activity {
    private int id, maxpax, supplier, status;
    private double priceperpax;
    private String name, description, photo, address, category;

    public Activity(int id, String name, String description, String photo, int maxpax, double priceperpax, String address, int supplier, int status, String category) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.photo = photo;
        this.maxpax = maxpax;
        this.priceperpax = priceperpax;
        this.address = address;
        this.supplier = supplier;
        this.status = status;
        this.category = category;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getMaxpax() {
        return maxpax;
    }

    public void setMaxpax(int maxpax) {
        this.maxpax = maxpax;
    }

    public int getSupplier() {
        return supplier;
    }

    public void setSupplier(int supplier) {
        this.supplier = supplier;
    }

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
        this.status = status;
    }

    public double getPriceperpax() {
        return priceperpax;
    }

    public void setPriceperpax(double priceperpax) {
        this.priceperpax = priceperpax;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getPhoto() {
        return photo;
    }

    public void setPhoto(String photo) {
        this.photo = photo;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getCategory() {
        return category;
    }

    public void setCategory(String category) {
        this.category = category;
    }
}
