package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.CHECKOUT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID_CART;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.USER_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.google.android.material.snackbar.Snackbar;

import java.util.ArrayList;

import Adapters.CartAdapter;
import Listeners.CartsListener;
import Model.Calendar;
import Model.Cart;
import Model.SingletonManager;
import Model.WaypinpointDbHelper;

public class CartFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, CartsListener {
    private SwipeRefreshLayout swipeRefreshLayout;
    private ArrayList<Cart> cartList;
    private ArrayList<Model.Activity> activities;
    private ArrayList<Calendar> calendars;
    private ListView lvCart;
    private View emptyView;
    private TextView tvEmptyMessage;
    private WaypinpointDbHelper waypinpointDbHelper;

    public CartFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_cart, container, false);
        setHasOptionsMenu(true);
        lvCart = view.findViewById(R.id.lvCart);
        emptyView = view.findViewById(R.id.emptyViewLayoutCarts);
        int userId = getUserId(getContext());
        waypinpointDbHelper = new WaypinpointDbHelper(getContext());
        cartList = waypinpointDbHelper.getCartByUserIdDB(userId);
        activities = waypinpointDbHelper.getActivitiesDB();
        calendars = waypinpointDbHelper.getCalendarDB();

        lvCart.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Intent intent = new Intent(getContext(), CartDetailsActivity.class);
                intent.putExtra(ID_CART, (int) id);
                intent.putExtra(USER_ID, userId);
                startActivityForResult(intent, EDIT);
            }
        });
        swipeRefreshLayout = view.findViewById(R.id.srl_Cart);
        swipeRefreshLayout.setOnRefreshListener(this);
        
        SingletonManager.getInstance(getContext()).setCartsListener(this);
        loadCartData();
        return view;
    }

    public void loadCartData() {
        SingletonManager.getInstance(getContext()).getCartByUserId(getContext());
    }


    @Override
    public void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        if (resultCode == Activity.RESULT_OK) {
            if (requestCode == EDIT) {
                SingletonManager.getInstance(getContext()).getCartByUserId(getContext());
                switch (requestCode) {
                    case EDIT:
                        if (data.getIntExtra(OP_CODE, 0) == DELETE) {
                            Snackbar.make(getView(), R.string.cart_removed_successful_message, Snackbar.LENGTH_SHORT).show();
                        } else if (data.getIntExtra(OP_CODE, 0) == CHECKOUT) {
                            Snackbar.make(getView(), R.string.checkout_success_message, Snackbar.LENGTH_SHORT).show();
                        } else {
                            Snackbar.make(getView(), R.string.cart_edit_success_message, Snackbar.LENGTH_SHORT).show();
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
    public void onRefresh() {
        loadCartData();
        swipeRefreshLayout.setRefreshing(false);
    }

    public void onRefreshCartList(ArrayList<Cart> cartArrayList) {
        if (cartArrayList != null || !cartArrayList.isEmpty()) {
            lvCart.setVisibility(View.VISIBLE);
            emptyView.setVisibility(View.GONE);
            activities = waypinpointDbHelper.getActivitiesDB();
            calendars = waypinpointDbHelper.getCalendarDB();
            lvCart.setAdapter(new CartAdapter(getContext(), cartArrayList, activities, calendars));
        }
        if (cartArrayList.isEmpty()) {
            lvCart.setVisibility(View.GONE);
            emptyView.setVisibility(View.VISIBLE);
            tvEmptyMessage = emptyView.findViewById(R.id.tvEmptyMessage);
            tvEmptyMessage.setText(R.string.empty_cart_message);
        }
    }
}