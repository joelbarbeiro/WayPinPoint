package Model;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DB_VERSION;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import androidx.annotation.Nullable;

import java.util.ArrayList;

public class WaypinpointDbHelper extends SQLiteOpenHelper {

    private static final String DB_NAME = "waypinpointmobile";
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
    private static final String ROLE = "role";
    //endregion

    //region = CART DECLARATIONS #
    private static final String TABLE_NAME_CART = "cart";
    public static final String USER = "user";
    private static final String PRODUCT_ID = "product_id";
    private static final String QUANTITY = "quantity";
    private static final String STATUS_ = "status";
    private static final String CALENDAR_ID = "calendar_id";
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

    //region = CATEGORY DECLARATIONS #
    private static final String TABLE_NAME_CATEGORY = "categories";
    //endregion

    //region = CALENDAR #
    private static final String TABLE_NAME_CALENDAR = "calendars";
    private static final String ID_CALENDAR = "id";
    private static final String ID_ACTIVITY = "id_activity";
    private static final String ID_DATE = "id_date";
    private static final String ID_TIME = "id_time";
    private static final String HOUR = "hour";
    private static final String DATE = "date";
    //endregion

    //region = CALENDAR TIME DECLARATIONS #
    private static final String TABLE_NAME_TIME = "calendartimes";
    //endregion

    //region = REVIEW DECLARATIONS #
    private static final String TABLE_NAME_REVIEWS = "reviews";
    private static final String USER_ID = "user_id";
    private static final String ACTIVITY_ID = "activity_id";
    private static final String SCORE = "score";
    private static final String MESSAGE = "message";
    private static final String CREATED_AT = "created_at";
    private static final String CREATOR = "creator";
    //endregion

    //region = INVOICE DECLARATIONS #
    private static final String TABLE_NAME_INVOICES = "invoices";
    private static final String PARTICIPANT = "participant";
    private static final String DAY = "day";
    private static final String ACTIVITYNAME = "activity_name";
    private static final String ACTIVITYDESCRIPTION = "activity_description";
    private static final String PRICE = "price";
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
                TOKEN + " TEXT," +
                ROLE + " TEXT" +
                ");";
        try {
            db.execSQL(createUserTable);
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("->>> Error creating table: " + e.getMessage());
        }

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
        try {
            db.execSQL(createActivitiesTable);
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("->>> Error creating table: " + e.getMessage());
        }

        String createCategoryTable = "CREATE TABLE " + TABLE_NAME_CATEGORY +
                "(" +
                ID + " INTEGER, " +
                DESCRIPTION + " TEXT NOT NULL" +
                ")";
        try {
            db.execSQL(createCategoryTable);
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("->>> Error creating table: " + e.getMessage());
        }

        String createCalendarTable = "CREATE TABLE " + TABLE_NAME_CALENDAR +
                "(" +
                ID + " INTEGER, " +
                ID_ACTIVITY + " INTEGER NOT NULL, " +
                ID_DATE + " INTEGER NOT NULL, " +
                DATE + " TEXT NOT NULL, " +
                ID_TIME + " INTEGER NOT NULL" +
                ")";
        try {
            db.execSQL(createCalendarTable);
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("->>> Error creating table: " + e.getMessage());
        }

        String createTimeTable = "CREATE TABLE " + TABLE_NAME_TIME +
                "(" +
                ID + " INTEGER, " +
                HOUR + " TEXT NOT NULL" +
                ")";
        try {
            db.execSQL(createTimeTable);
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("->>> Error creating table: " + e.getMessage());
        }

        String createReviewsTable = "CREATE TABLE " + TABLE_NAME_REVIEWS +
                "(" +
                ID + " INTEGER, " +
                USER_ID + " INTEGER NOT NULL, " +
                ACTIVITY_ID + " INTEGER NOT NULL, " +
                SCORE + " INTEGER NOT NULL, " +
                MESSAGE + " TEXT NOT NULL, " +
                CREATED_AT + " INT NOT NULL, " +
                CREATOR + " TEXT" +
                ")";
        try {
            db.execSQL(createReviewsTable);
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("->>> Error creating table: " + e.getMessage());
        }

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
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("->>> Error creating table: " + e.getMessage());
        }

        String createInvoiceTable = "CREATE TABLE " + TABLE_NAME_INVOICES +
                "(" + ID + " INTEGER, " +
                PARTICIPANT + " TEXT NOT NULL, " +
                ADDRESS + " TEXT NOT NULL, " +
                DAY + " TEXT NOT NULL, " +
                HOUR + " TEXT NOT NULL, " +
                ACTIVITYNAME + " TEXT NOT NULL, " +
                ACTIVITYDESCRIPTION + " TEXT NOT NULL, " +
                PRICE + " INTEGER NOT NULL, " +
                NIF + " INTEGER NOT NULL" +
                ");";
        try {
            db.execSQL(createInvoiceTable);
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("->>> Error creating table: " + e.getMessage());
        }
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_USERS);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_ACTIVITIES);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_CATEGORY);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_CALENDAR);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_TIME);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_REVIEWS);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_CART);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME_INVOICES);
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
        values.put(ROLE, user.getRole());

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
        values.put(ROLE, user.getRole());

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

        Cursor cursor = this.db.query(TABLE_NAME_USERS, new String[]{ID, USERNAME, EMAIL, PASSWORD, ADDRESS, PHONE, NIF, PHOTO, ROLE},
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
                        "",
                        cursor.getString(8)
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
                        cursor.getInt(7), cursor.getInt(8), cursor.getInt(9));
                activities.add(auxActivity);
            } while (cursor.moveToNext());
        }
        return activities;
    }

    public void delAllActivitiesDB() {
        this.db.delete(TABLE_NAME_ACTIVITIES, null, null);
    }

    public Activity getActivityById(int activity_id) {

        Activity activity = null;
        String selection = ID + " = ?";
        String[] selectionArgs = {String.valueOf(activity_id)};

        Cursor cursor = this.db.query(TABLE_NAME_ACTIVITIES,
                new String[]{ID, NAME, DESCRIPTION, PHOTO, MAXPAX, PRICEPERPAX, ADDRESS, SUPPLIER, STATUS, CATEGORY_ID},
                selection,
                selectionArgs,
                null, null, null);
        if (cursor.moveToFirst()) {
            activity = new Activity(
                    cursor.getInt(0),
                    cursor.getString(1),
                    cursor.getString(2),
                    cursor.getString(3),
                    cursor.getInt(4),
                    cursor.getFloat(5),
                    cursor.getString(6),
                    cursor.getInt(7),
                    cursor.getInt(8),
                    cursor.getInt(9));
        } else {
            System.out.println(" if move first >>>>- vazio");
        }
        return activity;
    }

    //endregion

    //region CALENDAR DB METHODS#
    public void addCalendarDB(Calendar c) {
        ContentValues val = new ContentValues();
        val.put(ID, c.getId());
        val.put(ID_ACTIVITY, c.getActivity_id());
        val.put(ID_DATE, c.getDate_id());
        val.put(DATE, c.getDate());
        val.put(ID_TIME, c.getTime_id());

        this.db.insert(TABLE_NAME_CALENDAR, null, val);
    }

    public boolean editCalendarDB(Calendar c) {
        ContentValues val = new ContentValues();
        val.put(ID, c.getId());
        val.put(ID_ACTIVITY, c.getActivity_id());
        val.put(ID_DATE, c.getDate_id());
        val.put(DATE, c.getDate());
        val.put(ID_TIME, c.getTime_id());

        return this.db.update(TABLE_NAME_CALENDAR, val, ID + "= ?", new String[]{"" + c.getId()}) > 0;
    }

    public boolean delCalendarDB(int id) {
        return (this.db.delete(TABLE_NAME_CALENDAR, ID + " = ?", new String[]{"" + id}) == 1);
    }

    public ArrayList<Calendar> getCalendarDB() {
        ArrayList<Calendar> calendars = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME_CALENDAR, new String[]{ID, ID_ACTIVITY, ID_DATE, DATE, ID_TIME},
                null, null, null, null, null);
        if (cursor.moveToFirst()) {
            do {
                Calendar auxCalendar = new Calendar(cursor.getInt(0), cursor.getInt(1), cursor.getInt(2),
                        cursor.getString(3), cursor.getInt(4));
                calendars.add(auxCalendar);
            } while (cursor.moveToNext());
        }
        return calendars;
    }

    public Calendar getCalendarById(int calendar_id) {

        Calendar calendar = null;
        String selection = ID + " = ?";
        String[] selectionArgs = {String.valueOf(calendar_id)};

        Cursor cursor = this.db.query(TABLE_NAME_CALENDAR,
                new String[]{ID, ID_ACTIVITY, ID_DATE, DATE, ID_TIME},
                selection,
                selectionArgs,
                null, null, null);
        if (cursor.moveToFirst()) {
            calendar = new Calendar(
                    cursor.getInt(0),
                    cursor.getInt(1),
                    cursor.getInt(2),
                    cursor.getString(3),
                    cursor.getInt(4)
            );
        } else {
            System.out.println("Error getting calendar");
        }
        return calendar;
    }

    public ArrayList<Calendar> getCalendarByActivityId(int activity_id) {
        ArrayList<Calendar> calendars = new ArrayList<>();

        String selection = ID_ACTIVITY + " = ?";
        String[] selectionArgs = {String.valueOf(activity_id)};

        Cursor cursor = this.db.query(TABLE_NAME_CALENDAR,
                new String[]{ID, ID_ACTIVITY, ID_DATE, DATE, ID_TIME},
                selection,
                selectionArgs,
                null, null, null);
        if (cursor.moveToFirst()) {
            do {
                Calendar auxCalendar = new Calendar(cursor.getInt(0), cursor.getInt(1), cursor.getInt(2),
                        cursor.getString(3), cursor.getInt(4));
                calendars.add(auxCalendar);
                System.out.println("Cal >>>>- " + auxCalendar);
            } while (cursor.moveToNext());
        } else {
            System.out.println("Error getting ccalendars");
        }
        return calendars;
    }

    public void delAllCalendarDB() {
        this.db.delete(TABLE_NAME_CALENDAR, null, null);
    }

    //endregion

    //region CALENDAR TIME DB METHODS#
    public void addCalendarTimeDB(CalendarTime c) {
        ContentValues val = new ContentValues();
        val.put(ID, c.getId());
        val.put(HOUR, c.getHour());

        this.db.insert(TABLE_NAME_TIME, null, val);
    }

    public ArrayList<CalendarTime> getCalendarTimeDB() {
        ArrayList<CalendarTime> time = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME_TIME, new String[]{ID, HOUR},
                null, null, null, null, null);
        if (cursor.moveToFirst()) {
            do {
                CalendarTime auxTime = new CalendarTime(cursor.getInt(0), cursor.getString(1));
                time.add(auxTime);
            } while (cursor.moveToNext());
        }
        return time;
    }

    public CalendarTime getCalendarTimeById(int id) {
        CalendarTime time = null;

        String selection = ID + " = ?";
        String[] selectionArgs = {String.valueOf(id)};

        Cursor cursor = this.db.query(TABLE_NAME_TIME,
                new String[]{ID, HOUR},
                selection,
                selectionArgs,
                null, null, null);
        if (cursor.moveToFirst()) {
            do {
                time = new CalendarTime(cursor.getInt(0), cursor.getString(1));
            } while (cursor.moveToNext());
        }
        return time;
    }

    public void delAllCalendarTimeDB() {
        this.db.delete(TABLE_NAME_TIME, null, null);
    }

    //endregion

    //region CATEGORY DB METHODS#
    public void addCategoryDB(Category c) {
        ContentValues val = new ContentValues();
        val.put(ID, c.getId());
        val.put(DESCRIPTION, c.getDescription());

        this.db.insert(TABLE_NAME_CATEGORY, null, val);
    }

    public boolean editCategoryDB(Category c) {
        ContentValues val = new ContentValues();
        val.put(ID, c.getId());
        val.put(DESCRIPTION, c.getDescription());

        return this.db.update(TABLE_NAME_CATEGORY, val, ID + "= ?", new String[]{"" + c.getId()}) > 0;
    }

    public boolean delCategoryDB(int id) {
        return (this.db.delete(TABLE_NAME_CATEGORY, ID + " = ?", new String[]{"" + id}) == 1);
    }

    public ArrayList<Category> getCategoryDB() {
        ArrayList<Category> category = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME_CATEGORY, new String[]{ID, DESCRIPTION},
                null, null, null, null, null);
        if (cursor.moveToFirst()) {
            do {
                Category auxCategory = new Category(cursor.getInt(0), cursor.getString(1));
                category.add(auxCategory);
            } while (cursor.moveToNext());
        }
        return category;
    }

    public void delAllCategoriesDB() {
        this.db.delete(TABLE_NAME_CATEGORY, null, null);
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
        val.put(CREATOR, r.getCreator());

        this.db.insert(TABLE_NAME_REVIEWS, null, val);
    }

    public ArrayList<Review> getReviewsDb() {
        ArrayList<Review> reviews = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME_REVIEWS, new String[]{ID, USER_ID, ACTIVITY_ID, SCORE, MESSAGE, CREATED_AT, CREATOR},
                null, null, null, null, null);
        if (cursor.moveToFirst()) {
            do {
                Review auxReview = new Review(
                        cursor.getInt(0),
                        cursor.getInt(1),
                        cursor.getInt(2),
                        cursor.getInt(3),
                        cursor.getString(4),
                        cursor.getInt(5),
                        cursor.getString(6));
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
        values.put(CREATOR, review.getCreator());

        return this.db.update(TABLE_NAME_REVIEWS, values, ID + "= ?", new String[]{"" + review.getId()}) > 0;
    }

    public boolean removeReviewDb(int id) {
        return this.db.delete(TABLE_NAME_REVIEWS, ID + "= ?", new String[]{"" + id}) == 1;
    }

    public void delAllReviewsDb() {
        this.db.delete(TABLE_NAME_REVIEWS, null, null);
    }

    //endregion

    //region CART DB METHODS #
    public void addCartDb(Cart cart) {
        ContentValues values = new ContentValues();
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

    public boolean removeCartDb(int id) {
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

    public ArrayList<Cart> getCartByUserIdDB(int userId) {
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


    public Cart getCartById(int id) {

        Cart cart = null;
        String selection = ID + " = ?";
        String[] selectionArgs = {String.valueOf(id)};

        Cursor cursor = this.db.query(TABLE_NAME_CART,
                new String[]{ID, USER_ID, PRODUCT_ID, QUANTITY, STATUS, CALENDAR_ID},
                selection,
                selectionArgs,
                null, null, null);
        if (cursor.moveToFirst()) {
            cart = new Cart(
                    cursor.getInt(0),
                    cursor.getInt(1),
                    cursor.getInt(2),
                    cursor.getInt(3),
                    cursor.getInt(4),
                    cursor.getInt(5));
        } else {
            System.out.println(" if move first >>>>- vazio");
        }
        return cart;
    }
    //endregion

    //region INVOICE DB METHODS #
    public void addInvoiceDB(Invoice i) {
        ContentValues val = new ContentValues();
        val.put(ID, i.getId());
        val.put(PARTICIPANT, i.getParticipant());
        val.put(ADDRESS, i.getAddress());
        val.put(DAY, i.getDay());
        val.put(HOUR, i.getHour());
        val.put(ACTIVITYNAME, i.getActivityName());
        val.put(ACTIVITYDESCRIPTION, i.getActivityDescription());
        val.put(PRICE, i.getPrice());
        val.put(NIF, i.getNif());

        this.db.insert(TABLE_NAME_INVOICES, null, val);
    }

    public boolean editInvoiceDb(Invoice invoice) {
        ContentValues values = new ContentValues();
        values.put(PARTICIPANT, invoice.getParticipant());
        values.put(ADDRESS, invoice.getAddress());
        values.put(DAY, invoice.getDay());
        values.put(HOUR, invoice.getHour());
        values.put(ACTIVITYNAME, invoice.getActivityName());
        values.put(ACTIVITYDESCRIPTION, invoice.getActivityDescription());
        values.put(PRICE, invoice.getPrice());
        values.put(NIF, invoice.getNif());

        return this.db.update(TABLE_NAME_INVOICES, values, ID + "= ?", new String[]{"" + invoice.getId()}) > 0;
    }

    public boolean removeInvoiceDb(int id) {
        return this.db.delete(TABLE_NAME_INVOICES, ID + "= ?", new String[]{"" + id}) == 1;
    }

    public void delAllInvoicesDb() {
        this.db.delete(TABLE_NAME_INVOICES, null, null);
    }

    public ArrayList<Invoice> getInvoicesDB() {
        ArrayList<Invoice> invoices = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME_INVOICES, new String[]{ID, PARTICIPANT, ADDRESS, DAY, HOUR, ACTIVITYNAME, ACTIVITYDESCRIPTION, PRICE, NIF},
                null, null, null, null, null);
        if (cursor.moveToFirst()) {
            do {
                Invoice auxInvoice = new Invoice(
                        cursor.getInt(0),
                        cursor.getString(1),
                        cursor.getString(2),
                        cursor.getString(3),
                        cursor.getString(4),
                        cursor.getString(5),
                        cursor.getString(6),
                        cursor.getInt(7),
                        cursor.getInt(8));
                invoices.add(auxInvoice);
            } while (cursor.moveToNext());
        }
        return invoices;
    }

    //endregion
}
