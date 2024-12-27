package pt.ipleiria.estg.dei.waypinpoint.utils;

import org.json.JSONException;
import org.json.JSONObject;

import Model.User;

public class UserJsonParser {

    public static User parserJsonUser(String response) {
        User auxUser = null;

        try {
            JSONObject User = new JSONObject(response);
            int idUser = User.getInt("id");
            String usernameUser = User.getString("username");
            String emailUser = User.getString("email");
            String passwordUser = User.getString("password");
            String addressUser = User.getString("address");
            int phoneUser = User.getInt("phone");
            int nifUser = User.getInt("nif");
            String photoUser = User.getString("photo");

            auxUser = new User(
                    idUser,
                    usernameUser,
                    emailUser,
                    passwordUser,
                    addressUser,
                    phoneUser,
                    nifUser,
                    photoUser,
                    0,
                    null
            );
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return auxUser;
    }

    public static String parserJsonPhoto(String response) {
        String photo = null;
        try {
            JSONObject String = new JSONObject(response);
            photo = String.getString("photo");
            return photo;
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return photo;
    }
}