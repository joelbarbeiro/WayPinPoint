package Model;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import java.util.ArrayList;

public class CartDbHelper extends SQLiteOpenHelper {

    private static final String DB_NAME = "waypinpointmobile";
    private static final int DB_VERSION = 3;

    private final SQLiteDatabase db;

    private static final String TABLE_NAME = "cart";
    private static final String ID = "id";
    private static final String USER_ID = "user_id";
    private static final String PRODUCT_ID = "product_id";
    private static final String QUANTITY = "quantity";
    private static final String STATUS = "status";
    private static final String CALENDAR_ID = "calendar_id";
    private static final String TIME = "time";
    private static final String DATE = "date";
    private static final String PRICE = "price";

    public CartDbHelper(Context context) {
        super(context, DB_NAME, null, DB_VERSION);
        this.db = getWritableDatabase();
    }

    public void onCreate(SQLiteDatabase db) {
            String createCartTable = "CREATE TABLE " + TABLE_NAME +
                "(" + ID + " INTEGER PRIMARY KEY, " +
                USER_ID + " INTEGER NOT NULL, " +
                PRODUCT_ID + " INTEGER NOT NULL, " +
                QUANTITY + " INTEGER NOT NULL, " +
                STATUS + " INTEGER NOT NULL, " +
                CALENDAR_ID + " INTEGER NOT NULL," +
                TIME + " TEXT," +
                DATE + " TEXT," +
                PRICE + " REAL" +
                ");";
        db.execSQL(createCartTable);
    }

    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME);
        this.onCreate(db);
    }

    public void addCartDb(Cart cart) {
        ContentValues values = new ContentValues();
        values.put(ID, cart.getId());
        values.put(USER_ID, cart.getUser_id());
        values.put(PRODUCT_ID, cart.getProduct_id());
        values.put(QUANTITY, cart.getQuantity());
        values.put(STATUS, cart.getStatus());
        values.put(CALENDAR_ID, cart.getCalendar_id());
        values.put(TIME, cart.getTime());
        values.put(DATE, cart.getDate());
        values.put(PRICE, cart.getPrice());
        this.db.insert(TABLE_NAME, null, values);
    }

    public boolean editCartDb(Cart cart) {
        ContentValues values = new ContentValues();
        values.put(QUANTITY, cart.getQuantity());
        values.put(CALENDAR_ID, cart.getCalendar_id());
        values.put(TIME, cart.getTime());
        values.put(DATE, cart.getDate());

        return this.db.update(TABLE_NAME, values, ID + "= ?", new String[]{"" + cart.getId()}) > 0;
    }

    public boolean removeCartDb(Cart id) {
        return this.db.delete(TABLE_NAME, ID + "= ?", new String[]{"" + id}) == 1;
    }

    public ArrayList<Cart> getAllCartsDb() {
        ArrayList<Cart> carts = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME, new String[]{ID, USER_ID, PRODUCT_ID, QUANTITY, STATUS, CALENDAR_ID, TIME, DATE, PRICE},
                null, null, null, null, null);

        if (cursor.moveToFirst()) {
            do {
                Cart auxCart = new Cart(cursor.getInt(0),
                        cursor.getInt(1),
                        cursor.getInt(2),
                        cursor.getInt(3),
                        0,
                        cursor.getInt(5),
                        cursor.getString(6),
                        cursor.getString(7),
                        cursor.getFloat(8)
                );
                carts.add(auxCart);
            } while (cursor.moveToNext());
        }
        cursor.close();
        return carts;
    }

    public void removeAllCartDb() {
        this.db.delete(TABLE_NAME, null, null);
    }

    public ArrayList<Cart> getCartByUserId(int userId) {
        ArrayList<Cart> carts = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME, new String[]{ID, USER_ID, PRODUCT_ID, QUANTITY, STATUS, CALENDAR_ID, TIME, DATE, PRICE},
                USER_ID + "= ?",
                new String[]{"" + userId}, null, null, null
        );
        if (cursor.moveToFirst()) {
            do {
                Cart auxCart = new Cart(
                        cursor.getInt(0),
                        cursor.getInt(1),
                        cursor.getInt(2),
                        cursor.getInt(3),
                        cursor.getInt(4),
                        cursor.getInt(5),
                        cursor.getString(6),
                        cursor.getString(7),
                        cursor.getFloat(8)
                );
                carts.add(auxCart);
            } while (cursor.moveToNext());
        }
        cursor.close();
        return carts;
    }
}
