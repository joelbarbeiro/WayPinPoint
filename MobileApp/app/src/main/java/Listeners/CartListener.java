package Listeners;

public interface CartListener {
    void onValidateOperation(int op);
    void onErrorAdd(String errorMessage);
    void onSuccess();
    void validateOperation(String s);
}
