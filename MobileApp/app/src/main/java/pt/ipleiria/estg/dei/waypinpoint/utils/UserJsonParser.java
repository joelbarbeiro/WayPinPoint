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
            int nifUser = User.getInt("nif");
            int phoneUser = User.getInt("phone");
            String addressUser = User.getString("address");
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
                    0
            );

        } catch (JSONException e) {
            e.printStackTrace();
        }

        return auxUser;
    }
}
