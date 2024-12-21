package Model;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import androidx.annotation.Nullable;

import java.util.ArrayList;

public class UserDbHelper extends SQLiteOpenHelper {

    private static final String DB_NAME = "waypinpointmobile";
    private static final int DB_VERSION = 3;

    private final SQLiteDatabase db;

    private static final String TABLE_NAME = "users";
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

    public UserDbHelper(@Nullable Context context) {
        super(context, DB_NAME, null, DB_VERSION);
        this.db = getWritableDatabase();
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        String createUserTable = "CREATE TABLE " + TABLE_NAME +
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
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME);
        this.onCreate(db);
    }

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

        this.db.insert(TABLE_NAME, null, values);
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

        return this.db.update(TABLE_NAME, values, ID + "= ?", new String[]{"" + user.getId()}) > 0;
    }

    public boolean editPhotoDb(String photo, int id) {
        ContentValues values = new ContentValues();
        values.put(PHOTO, photo);
        return this.db.update(TABLE_NAME, values, ID + "= ?", new String[]{"" + id}) > 0;
    }

    public boolean removeUserDb(int id) {
        return this.db.delete(TABLE_NAME, ID + "= ?", new String[]{"" + id}) == 1;
    }

    public ArrayList<User> getAllUsersDb() {
        ArrayList<User> users = new ArrayList<>();

        Cursor cursor = this.db.query(TABLE_NAME, new String[]{ID, USERNAME, EMAIL, PASSWORD, ADDRESS, PHONE, NIF, PHOTO},
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
        this.db.delete(TABLE_NAME, null, null);
    }
}
