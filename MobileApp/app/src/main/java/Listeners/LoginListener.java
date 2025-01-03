package Listeners;

public interface LoginListener {
    void onValidateLogin(String token);

    void onErrorLogin(String errorMessage);
}
