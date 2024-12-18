package Model;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import androidx.annotation.Nullable;

import java.util.ArrayList;

public class ActivityDbHelper extends SQLiteOpenHelper {
    private static final String DB_NAME = "waypinpointmobile";
    private static final int DB_VERSION = 1;
    private static final String TABLE_NAME = "activities";
    private static final String ID = "id";
    private static final String NAME = "name";
    private static final String DESCRIPTION = "description";
    private static final String PHOTO = "photo";
    private static final String MAXPAX = "maxpax";
    private static final String PRICEPERPAX = "priceperpax";
    private static final String ADDRESS = "address";
    private static final String SUPPLIER = "supplier";
    private static final String STATUS = "status";
    private static final String CATGORY = "catgory";
    private final SQLiteDatabase db;

    public ActivityDbHelper(@Nullable Context context, @Nullable String name, @Nullable SQLiteDatabase.CursorFactory factory, int version, SQLiteDatabase db) {
        super(context, name, factory, version);
        this.db = db;
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        String createActivitiesTable = "CREATE TABLE " + TABLE_NAME +
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
                CATGORY + " TEXT NOT NULL" +
                ")";
        db.execSQL(createActivitiesTable);
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME);
        this.onCreate(db);
    }
    public void addActivityDB(Activity a){
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
        val.put(CATGORY, a.getCatgory());

        this.db.insert(TABLE_NAME, null, val);
    }

    public boolean editActivityDB(Activity a){
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
        val.put(CATGORY, a.getCatgory());

        return this.db.update(TABLE_NAME, val, ID + "= ?", new String[]{""+a.getId()}) > 0;
    }

    public boolean delActivityDB(int id){
        return (this.db.delete(TABLE_NAME, ID + " = ?", new String[]{"" + id}) == 1);
    }

    public ArrayList<Activity> getActivitiesDB(){
        ArrayList<Activity> activities = new ArrayList<>();
        Cursor cursor = this.db.query(TABLE_NAME, new String[]{ID, NAME, DESCRIPTION, PHOTO, MAXPAX, PRICEPERPAX, ADDRESS, SUPPLIER, STATUS, CATGORY},
            null, null, null, null, null);
        if (cursor.moveToFirst()){
            do {
                Activity auxActivity = new Activity(cursor.getInt(0), cursor.getString(1), cursor.getString(2),
                        cursor.getString(3), cursor.getInt(4), cursor.getFloat(5), cursor.getString(6),
                        cursor.getInt(7), cursor.getInt(8), cursor.getString(9));
                activities.add(auxActivity);
            }while(cursor.moveToNext());
        }
        return activities;
    }
    public void delAllActivitiesDB(){
        this.db.delete(TABLE_NAME, null, null);
    }
}
