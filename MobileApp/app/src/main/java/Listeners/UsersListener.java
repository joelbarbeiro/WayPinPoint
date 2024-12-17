package Listeners;

import java.util.ArrayList;

import Model.User;

public interface UsersListener {
    void onRefreshUserList(ArrayList<User> usersList);
}