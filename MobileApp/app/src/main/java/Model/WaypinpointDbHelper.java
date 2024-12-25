package Model;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import androidx.annotation.Nullable;

import java.util.ArrayList;

public class WaypinpointDbHelper extends SQLiteOpenHelper {

    private static final String DB_NAME = "waypinpointmobile";
    private static final int DB_VERSION = 4;

    private final SQLiteDatabase db;

    //region = USER DECLARATIONS #
    private static final String TABLE_NAME_USERS = "users";
    private static final String USERNAME = "username";
    private static final String EMAIL = "email";
    private static final String PASSWORD = "password";
    private static final String ADDRESS = "address";
    private static final String NIF = "nif";
    private static final String SUPPLIER = "supplier";
    private static final String TOKEN = "token";
    private static final String PHONE = "phone";
    private static final String PHOTO = "photo";
    private static final String ID = "id";
    //endregion
    //region = CART DECLARATIONS #
    private static final String TABLE_NAME_CART = "cart";

    private static final String PRODUCT_ID = "product_id";
    private static final String QUANTITY = "quantity";
    private static final String STATUS_ = "status";
    private static final String CALENDAR_ID = "calendar_id";
    private static final String TIME = "time";
    private static final String DATE = "date";
    private static final String PRICE = "price";
    //endregion
    //region = ACTIVITIES DECLARATIONS #
    private static final String TABLE_NAME_ACTIVITIES = "activities";
    private static final String NAME = "name";
    private static final String DESCRIPTION = "description";
    private static final String MAXPAX = "maxpax";
    private static final String PRICEPERPAX = "priceperpax";
    private static final String STATUS = "status";
    private static final String CATEGORY_ID = "category_id";
    //endregion

    //region = REVIEW DECLARATIONS #
    private static final String TABLE_NAME_REVIEWS = "reviews";
    private static final String USER_ID = "user_id";
    private static final String ACTIVITY_ID = "activity_id";
    private static final String SCORE = "score";
    private static final String MESSAGE = "message";
    private static final String CREATED_AT = "created_at";
    //endregion

    public WaypinpointDbHelper(@Nullable Context context) {
        super(context, DB_NAME, null, DB_VERSION);
        this.db = getWritableDatabase();
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        String createUserTable = "CREATE TABLE " + TABLE_NAME_USERS +
                "(" + ID + " INTEGER, " +
                USERNAME + " TEXT NOT NULL, " +
                EMAIL + " TEXT NOT NULL, " +
                PASSWORD + " TEXT NOT NULL, " +
                ADDRESS + " TEXT NOT NULL, " +
                NIF + " INTEGER NOT NULL, " +
                PHONE + " TEXT NOT NULL," +
                PHOTO + " TEXT NOT NULL," +
                SUPPLIER + " INTEGER," +
                TOKEN + " TEXT" +
                ");";

        db.execSQL(createUserTable);

        String createActivitiesTable = "CREATE TABLE " + TABLE_NAME_ACTIVITIES +
                "(" +
                ID + " INTEGER, " +
                NAME + " TEXT NOT NULL, " +
                DESCRIPTION + " TEXT NOT NULL, " +
                PHOTO + " TEXT NOT NULL, " +
                MAXPAX + " INTEGER NOT NULL, " +
                PRICEPERPAX + " FLOAT NOT NULL, " +
                ADDRESS + " TEXT NOT NULL, " +
                SUPPLIER + " INTEGER NOT NULL, " +
                STATUS + " INTEGER NOT NULL, " +
                CATEGORY_ID + " TEXT NOT NULL" +
                ")";
        db.execSQL(createActivitiesTable);

        String createReviewsTable = "CREATE TABLE " + TABLE_NAME_REVIEWS +
                "(" +
                ID + " INTEGER, " +
                USER_ID + " INTEGER NOT NULL, " +
                ACTIVITY_ID + " INTEGER NOT NULL, " +
                SCORE + " INTEGER NOT NULL, " +
                MESSAGE + " TEXT NOT NULL, " +
                CREATED_AT + " INT NOT NULL" +
                ")";
        db.execSQL(createReviewsTable);

        String createCartTable = "CREATE TABLE " + TABLE_NAME_CART +
                "(" + ID + " INTEGER, " +
                USER_ID + " INTEGER NOT NULL, " +
                PRODUCT_ID + " INTEGER NOT NULL, " +
                QUANTITY + " INTEGER NOT NULL, " +
                STATUS + " INTEGER NOT NULL, " +
                CALENDAR_ID + " INTEGER NOT NULL" +
                ");";
        try {
            db.execSQL(createCartTable);
            System.out.println("Table " + TABLE_NAME_CART + " created successfully.");
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Error creating table: " + e.getMessage());
        }
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_USERS);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_ACTIVITIES);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_REVIEWS);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_CART);
        this.onCreate(db);
    }

    //region USERS DB METHODS#

    public void addUserDb(User user) {
        ContentValues values = new ContentValues();
        values.put(ID, user.getId());
        values.put(USERNAME, user.getUsername());
        values.put(EMAIL, user.getEmail());
        values.put(PASSWORD, user.getPassword());
        values.put(ADDRESS, user.getAddress());
        values.put(NIF, user.getNif());
        values.put(PHONE, user.getPhone());
        values.put(PHOTO, user.getPhoto());

        this.db.insert(TABLE_NAME_USERS, null, values);
    }

    public boolean editUserDb(User user) {
        ContentValues values = new ContentValues();
        values.put(USERNAME, user.getUsername());
        values.put(EMAIL, user.getEmail());
        values.put(PASSWORD, user.getPassword());
        values.put(ADDRESS, user.getAddress());
        values.put(NIF, user.getNif());
        values.put(PHONE, user.getPhone());
        values.put(PHOTO, user.getPhoto());

        return this.db.update(TABLE_NAME_USERS, values, ID + "= ?", new String[]{"" + user.getId()}) > 0;
    }

    public boolean editPhotoDb(String photo, int id) {
        ContentValues values = new ContentValues();
        values.put(PHOTO, photo);
        return this.db.update(TABLE_NAME_USERS, values, ID + "= ?", new String[]{"" + id}) > 0;
    }

    public boolean removeUserDb(int id) {
        return this.db.delete(TABLE_NAME_USERS, ID + "= ?", new String[]{"" + id}) == 1;
    }

    public ArrayList<User> getAllUsersDb() {
        ArrayList<User> users = new ArrayList<>();

        Cursor cursor = this.db.query(TABLE_NAME_USERS, new String[]{ID, USERNAME, EMAIL, PASSWORD, ADDRESS, PHONE, NIF, PHOTO},
                null, null, null, null, null);

        if (cursor.moveToFirst()) {
            do {
                User auxUser = new User(cursor.getInt(0),
                        cursor.getString(1),
                        cursor.getString(2),
                        cursor.getString(3),
                        cursor.getString(4),
                        cursor.getInt(5),
                        cursor.getInt(6),
                        cursor.getString(7),
                        0,
                        ""
                );
                users.add(auxUser);
            } while (cursor.moveToNext());
        }
        return users;
    }

    public void removeAllUsersDb() {
        this.db.delete(TABLE_NAME_USERS, null, null);
    }
    //endregion

    //region ACTIVITIES DB METHODS#
    public void addActivityDB(Activity a) {
        ContentValues val = new ContentValues();
        val.put(ID, a.getId());
        val.put(NAME, a.getName());
        val.put(DESCRIPTION, a.getDescription());
        val.put(PHOTO, a.getPhoto());
        val.put(MAXPAX, a.getMaxpax());
        val.put(PRICEPERPAX, a.getPriceperpax());
        val.put(ADDRESS, a.getAddress());
        val.put(SUPPLIER, a.getSupplier());
        val.put(STATUS, a.getStatus());
        val.put(CATEGORY_ID, a.getCategory());

        this.db.insert(TABLE_NAME_ACTIVITIES, null, val);
    }

    public boolean editActivityDB(Activity a) {
        ContentValues val = new ContentValues();
        val.put(ID, a.getId());
        val.put(NAME, a.getName());
        val.put(DESCRIPTION, a.getDescription());
        val.put(PHOTO, a.getPhoto());
        val.put(MAXPAX, a.getMaxpax());
        val.put(PRICEPERPAX, a.getPriceperpax());
        val.put(ADDRESS, a.getAddress());
        val.put(SUPPLIER, a.getSupplier());
        val.put(STATUS, a.getStatus());
        val.put(CATEGORY_ID, a.getCategory());

        return this.db.update(TABLE_NAME_ACTIVITIES, val, ID + "= ?", new String[]{"" + a.getId()}) > 0;
    }

    public boolean delActivityDB(int id) {
        return (this.db.delete(TABLE_NAME_ACTIVITIES, ID + " = ?", new String[]{"" + id}) == 1);
    }

    public ArrayList<Activity> getActivitiesDB() {
        ArrayList<Activity> activities = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME_ACTIVITIES, new String[]{ID, NAME, DESCRIPTION, PHOTO, MAXPAX, PRICEPERPAX, ADDRESS, SUPPLIER, STATUS, CATEGORY_ID},
                null, null, null, null, null);
        if (cursor.moveToFirst()) {
            do {
                Activity auxActivity = new Activity(cursor.getInt(0), cursor.getString(1), cursor.getString(2),
                        cursor.getString(3), cursor.getInt(4), cursor.getFloat(5), cursor.getString(6),
                        cursor.getInt(7), cursor.getInt(8), cursor.getString(9));
                activities.add(auxActivity);
            } while (cursor.moveToNext());
        }
        return activities;
    }

    public void delAllActivitiesDB() {
        this.db.delete(TABLE_NAME_ACTIVITIES, null, null);
    }

    //endregion


    //region REVIEWS DB METHODS#
    public void addReviewDB(Review r) {
        ContentValues val = new ContentValues();
        val.put(ID, r.getId());
        val.put(USER_ID, r.getUserId());
        val.put(ACTIVITY_ID, r.getActivityId());
        val.put(SCORE, r.getScore());
        val.put(MESSAGE, r.getMessage());
        val.put(CREATED_AT, r.getCreatedAt());

        this.db.insert(TABLE_NAME_REVIEWS, null, val);
    }

    public ArrayList<Review> getReviewsDb() {
        ArrayList<Review> reviews = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME_REVIEWS, new String[]{ID, USER_ID, ACTIVITY_ID, SCORE, MESSAGE, CREATED_AT},
                null, null, null, null, null);
        if (cursor.moveToFirst()) {
            do {
                Review auxReview = new Review(
                        cursor.getInt(0),
                        cursor.getInt(1),
                        cursor.getInt(2),
                        cursor.getInt(3),
                        cursor.getString(4),
                        cursor.getInt(5));
                reviews.add(auxReview);
            } while (cursor.moveToNext());
        }
        return reviews;
    }

    public boolean editReviewDb(Review review) {
        ContentValues values = new ContentValues();
        values.put(USER_ID, review.getUserId());
        values.put(ACTIVITY_ID, review.getActivityId());
        values.put(SCORE, review.getScore());
        values.put(MESSAGE, review.getMessage());
        values.put(CREATED_AT, review.getCreatedAt());

        return this.db.update(TABLE_NAME_REVIEWS, values, ID + "= ?", new String[]{"" + review.getId()}) > 0;
    }

    public boolean removeReviewDb(int id) {
        return this.db.delete(TABLE_NAME_REVIEWS, ID + "= ?", new String[]{"" + id}) == 1;
    }
    //endregion
    //REGION CART DB METHODS #
    public void addCartDb(Cart cart) {
        ContentValues values = new ContentValues();
        System.out.println("------> ID: " + ID + " " + cart.getId());
        values.put(ID, cart.getId());
        values.put(USER_ID, cart.getUser_id());
        values.put(PRODUCT_ID, cart.getProduct_id());
        values.put(QUANTITY, cart.getQuantity());
        values.put(STATUS, cart.getStatus());
        values.put(CALENDAR_ID, cart.getCalendar_id());
        this.db.insert(TABLE_NAME_CART, null, values);
    }

    public boolean editCartDb(Cart cart) {
        ContentValues values = new ContentValues();
        values.put(QUANTITY, cart.getQuantity());
        values.put(CALENDAR_ID, cart.getCalendar_id());

        return this.db.update(TABLE_NAME_CART, values, ID + "= ?", new String[]{"" + cart.getId()}) > 0;
    }

    public boolean removeCartDb(Cart id) {
        return this.db.delete(TABLE_NAME_CART, ID + "= ?", new String[]{"" + id}) == 1;
    }

    public ArrayList<Cart> getAllCartsDb() {
        ArrayList<Cart> carts = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME_CART, new String[]{ID, USER_ID, PRODUCT_ID, QUANTITY, STATUS, CALENDAR_ID},
                null, null, null, null, null);

        if (cursor.moveToFirst()) {
            do {
                Cart auxCart = new Cart(cursor.getInt(0),
                        cursor.getInt(1),
                        cursor.getInt(2),
                        cursor.getInt(3),
                        cursor.getInt(4),
                        cursor.getInt(5)
                );
                carts.add(auxCart);
            } while (cursor.moveToNext());
        }
        return carts;
    }

    public void removeAllCartDb() {
        this.db.delete(TABLE_NAME_CART, null, null);
    }

    public ArrayList<Cart> getCartByUserId(int userId) {
        ArrayList<Cart> carts = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME_CART, new String[]{ID, USER_ID, PRODUCT_ID, QUANTITY, STATUS, CALENDAR_ID},
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
                        cursor.getInt(5)
                );
                carts.add(auxCart);
            } while (cursor.moveToNext());
        }
        cursor.close();
        return carts;
    }
    //ENDREGION

}
