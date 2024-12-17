package Model;

public class Cart {
    private int id, user_id, product_id, quantity, status, calendar_id;

    public Cart(int id, int user_id, int product_id, int quantity, int status, int calendar_id) {
        this.id = id;
        this.user_id = user_id;
        this.product_id = product_id;
        this.quantity = quantity;
        this.status = status;
        this.calendar_id = calendar_id;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getUser_id() {
        return user_id;
    }

    public void setUser_id(int user_id) {
        this.user_id = user_id;
    }

    public int getProduct_id() {
        return product_id;
    }

    public void setProduct_id(int product_id) {
        this.product_id = product_id;
    }

    public int getQuantity() {
        return quantity;
    }

    public void setQuantity(int quantity) {
        this.quantity = quantity;
    }

    public int getCalendar_id() {
        return calendar_id;
    }

    public void setCalendar_id(int calendar_id) {
        this.calendar_id = calendar_id;
    }

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
        this.status = status;
    }
}
