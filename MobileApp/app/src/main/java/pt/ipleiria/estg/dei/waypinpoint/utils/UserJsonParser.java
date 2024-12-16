package pt.ipleiria.estg.dei.waypinpoint.utils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import Model.User;

public class UserJsonParser {

    public static ArrayList<User> parserJsonUsers(JSONArray response) {
        ArrayList<User> users = new ArrayList<>();

        try {
            for (int i = 0; i < response.length(); i++) {
                JSONObject User = (JSONObject) response.get(i);
                int idUser = User.getInt("id");
                String usernameUser = User.getString("username");
                String emailUser = User.getString("email");
                String passwordUser = User.getString("password_hash");
                String addressUser = User.getString("address");
                int phoneUser = User.getInt("phone");
                int nifUser = User.getInt("nif");
                String photoUser = User.getString("photo");
                int supplierUser = User.getInt("supplier");
                String token = User.getString("verification_token");

                User auxUser = new User(
                        idUser,
                        usernameUser,
                        emailUser,
                        passwordUser,
                        addressUser,
                        phoneUser,
                        nifUser,
                        photoUser,
                        supplierUser,
                        token
                );
                users.add(auxUser);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return users;
    }

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
}