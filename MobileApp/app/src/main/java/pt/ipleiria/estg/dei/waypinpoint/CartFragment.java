package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.ActivityDetailsActivity.ID_ACTIVITY;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID_CART;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.REGISTER;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.USER_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;

import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.Toast;

import com.google.android.material.snackbar.Snackbar;

import java.util.ArrayList;

import Listeners.CartListener;
import Model.Cart;
import Model.SingletonManager;
import pt.ipleiria.estg.dei.waypinpoint.Adapters.CartAdapter;

public class CartFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, CartListener {
    private SwipeRefreshLayout swipeRefreshLayout;
    private ArrayList<Cart> cartList;
    private ListView lvCart;
    private CartAdapter CartAdapter;

    public CartFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_cart, container, false);
        lvCart = view.findViewById(R.id.lvCart);
        int userId = getUserId(getContext());

//        CartAdapter = new CartAdapter(getContext(), cartList);
//        lvCart.setAdapter(CartAdapter);
        lvCart.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Intent intent = new Intent(getContext(), CartDetailsActivity.class);
                intent.putExtra(ID_CART, (int) id);
                intent.putExtra(USER_ID, userId);
                startActivity(intent);
            }
        });
        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh_layout);
        swipeRefreshLayout.setOnRefreshListener(this);
        SingletonManager.getInstance(getContext()).setCartListener(this);
        SingletonManager.getInstance(getContext()).getCartByUserId(getContext());
        return view;
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        if (resultCode == Activity.RESULT_OK) {
            if (requestCode == REGISTER || requestCode == EDIT) {
                SingletonManager.getInstance(getContext()).getCartByUserId(getContext());
                switch (requestCode) {
                    case REGISTER:
                        Snackbar.make(getView(), "Book Added Successfully", Snackbar.LENGTH_SHORT).show();
                        break;
                    case EDIT:
                        if (data.getIntExtra(OP_CODE, 0) == DELETE) {
                            Snackbar.make(getView(), "Book Removed Successfully", Snackbar.LENGTH_SHORT).show();
                        } else {
                            Snackbar.make(getView(), "Book Edited Successfully", Snackbar.LENGTH_SHORT).show();
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        super.onActivityResult(requestCode, resultCode, data);
    }




    @Override
    public void onSuccess(ArrayList<Cart> carts) {

    }

    @Override
    public void onError(String s) {

    }

    @Override
    public void onRefresh() {
        SingletonManager.getInstance(getContext()).getCartByUserId(getContext());
        swipeRefreshLayout.setRefreshing(false);
    }

    public void onRefreshCartList(ArrayList<Cart> cartArrayList) {
        if (cartArrayList != null) {
            lvCart.setAdapter(new CartAdapter(getContext(), cartArrayList));
        }
    }
}