package pt.ipleiria.estg.dei.waypinpoint;

import static android.os.FileObserver.CREATE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID_CART;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.content.Intent;
import android.os.Bundle;

import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.Toast;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import Listeners.CartListener;
import Model.Cart;
import Model.SingletonManager;
import pt.ipleiria.estg.dei.waypinpoint.Adapters.CartAdapter;

public class CartFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, CartListener {
    private SwipeRefreshLayout swipeRefreshLayout;
    private ArrayList<Cart> cartList;
    private CartListener cartListener;
    private ListView lvCart;
    private String mParam2;
    private CartAdapter CartAdapter;
    //private FloatingActionButton fabCheckout;

    public CartFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        setHasOptionsMenu(true);
        View view = inflater.inflate(R.layout.fragment_cart, container, false);
        lvCart = view.findViewById(R.id.lvCart);
        cartList = new ArrayList<>();
        CartAdapter = new CartAdapter(getContext(), cartList);
        lvCart.setAdapter(CartAdapter);
        SingletonManager.getInstance(getContext()).getCartByUserId(getContext(), new CartListener() {
            @Override
            public void onSuccess(ArrayList<Cart> carts) {
                cartList.clear();
                cartList.addAll(carts);
                CartAdapter.notifyDataSetChanged();
            }

            @Override
            public void onError(String errorMessage) {
                Toast.makeText(getContext(), "Error fetching carts: " + errorMessage, Toast.LENGTH_SHORT).show();
            }
        });

        lvCart.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (cartList == null || cartList.isEmpty()) {
                    Toast.makeText(getContext(), "Cart list is empty or null!", Toast.LENGTH_SHORT).show();
                    return;
                }
                Intent intent = new Intent(getContext(), CartDetailsActivity.class);
                System.out.println("---> ID CART: " + cartList.get(position).getId());
                intent.putExtra(ID_CART, cartList.get(position).getId());
                startActivity(intent);
            }
        });


//        fabCheckout.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View v) {
//                Intent intent = new Intent(getContext(), CartDetailsActivity.class);
//                startActivityForResult(intent, CREATE);
//            }
//        });

        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh_layout);
        swipeRefreshLayout.setOnRefreshListener(this);

        return view;
    }



    @Override
    public void onSuccess(ArrayList<Cart> carts) {

    }

    @Override
    public void onError(String s) {

    }

    @Override
    public void onRefresh() {
        SingletonManager.getInstance(getContext()).getCartByUserId(getContext(), cartListener);
        swipeRefreshLayout.setRefreshing(false);
    }

    public void onRefreshCartList(ArrayList<Cart> cartArrayList) {
        if (cartArrayList != null) {
            lvCart.setAdapter(new CartAdapter(getContext(), cartArrayList));
        }
    }
}