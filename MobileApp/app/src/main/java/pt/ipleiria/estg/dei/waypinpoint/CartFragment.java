package pt.ipleiria.estg.dei.waypinpoint;

import android.content.Intent;
import android.os.Bundle;

import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import Listeners.CartListener;
import Model.Cart;
import Model.SingletonManager;
import pt.ipleiria.estg.dei.waypinpoint.Adapters.CartAdapter;

public class CartFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, CartListener {
    private SwipeRefreshLayout swipeRefreshLayout;
    private int userId;
    private CartListener cartListener;
    private ListView lvCart;
    private String mParam2;
    private FloatingActionButton fabCheckout;

    public CartFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        setHasOptionsMenu(true);
        View view =  inflater.inflate(R.layout.fragment_cart, container, false);
        lvCart = view.findViewById(R.id.lvCart);
        SingletonManager.getInstance(getContext()).setCartsListener(this);
        SingletonManager.getInstance(getContext()).getCartByUserId(getContext(),userId, cartListener);
        lvCart.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
//                Toast.makeText(getContext(), bookList.get(position).getTitle() , Toast.LENGTH_SHORT).show();
                //Fazer code para ir para os details do livro
                Intent intent = new Intent(getContext(), CartDetailsActivity.class);
                intent.putExtra(CartDetailsActivity.ID_CART, (int) id);
                startActivityForResult(intent, MenuMainActivity.EDIT);
            }
        });

        fabCheckout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(getContext(), CartDetailsActivity.class);
                startActivityForResult(intent, MenuMainActivity.CREATE);
            }
        });

        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh_layout);
        swipeRefreshLayout.setOnRefreshListener(this);

        return view;
    }

    @Override
    public void onValidateOperation(int op) {

    }

    @Override
    public void onErrorAdd(String errorMessage) {

    }

    @Override
    public void onSuccess() {

    }

    @Override
    public void validateOperation(String s) {

    }

    @Override
    public void onRefresh() {
        SingletonManager.getInstance(getContext()).getCartByUserId(getContext(), userId, cartListener);
        swipeRefreshLayout.setRefreshing(false);
    }

    public void onRefreshBookList(ArrayList<Cart> cartArrayList) {
        if(cartArrayList != null)
        {
            lvCart.setAdapter(new CartAdapter(getContext(), cartArrayList));
        }
    }
}