package Adapters;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getActivityNameById;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getCalendarDateById;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getPriceById;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;

import java.util.ArrayList;

import Model.Activity;
import Model.Calendar;
import Model.Cart;
import pt.ipleiria.estg.dei.waypinpoint.R;
import pt.ipleiria.estg.dei.waypinpoint.utils.Utilities;

public class CartAdapter extends BaseAdapter {
    private Context context;
    private LayoutInflater layoutInflater;
    private ArrayList<Cart> cartList;
    private ArrayList<Activity> activities;
    private ArrayList<Calendar> calendars;

    public CartAdapter(Context context, ArrayList<Cart> cartList, ArrayList<Activity> activities, ArrayList<Calendar> calendars) {
        this.context = context;
        this.cartList = cartList;
        this.activities = activities;
        this.calendars = calendars;
    }

    @Override
    public int getCount() {
        return cartList.size();
    }

    @Override
    public Object getItem(int position) {
        return cartList.get(position);
    }

    @Override
    public long getItemId(int position) {
        return cartList.get(position).getId();
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        if (layoutInflater == null) {
            layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if (convertView == null) {
            convertView = layoutInflater.inflate(R.layout.item_list_cart, null);
        }

        ViewHolderList viewHolderList = (ViewHolderList) convertView.getTag();
        if (viewHolderList == null) {
            viewHolderList = new ViewHolderList(convertView);
            convertView.setTag(viewHolderList);
        }

        viewHolderList.update(cartList.get(position));

        return convertView;
    }

    private class ViewHolderList {
        private TextView tvProductName, tvQuantity, tvPrice, tvDate;
        private ImageView imageViewCartItem;

        public ViewHolderList(View view) {
            tvProductName = view.findViewById(R.id.tvActivityName);
            tvQuantity = view.findViewById(R.id.tvQuantity);
            tvPrice = view.findViewById(R.id.tvPrice);
            tvDate = view.findViewById(R.id.tvDate);
            imageViewCartItem = view.findViewById(R.id.imageViewCartItem);
        }

        private void update(Cart cart) {
            System.out.println("->> ADAPT!!!OOOOO!!!!R " + cart);
            tvProductName.setText(String.valueOf(getActivityNameById(cart.getProduct_id(), activities)));
            tvQuantity.setText(String.valueOf(cart.getQuantity()));
            tvPrice.setText(String.valueOf(cart.getQuantity() * getPriceById(cart.getProduct_id(), activities)));
            tvDate.setText(String.valueOf(getCalendarDateById(cart.getCalendar_id(), calendars)));
            String imgPath = Utilities.getImgUri(context) + Utilities.getImgFromActivities(cart.getProduct_id(), activities);
            Glide.with(context)
                    .load(imgPath)
                    .placeholder(R.drawable.img_default_activity)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .into(imageViewCartItem);
        }
    }
}
