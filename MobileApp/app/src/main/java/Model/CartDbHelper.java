package Model;

import android.database.sqlite.SQLiteDatabase;

public class CartDbHelper {
    private static final String DB_NAME = "waypinpointmobile";
    private static final int DB_VERSION = 1;
    private final SQLiteDatabase db;

    public CartDbHelper(SQLiteDatabase db) {
        this.db = db;
    }
}
