package pt.ipleiria.estg.dei.waypinpoint.Adapters;

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

import Model.Cart;
import pt.ipleiria.estg.dei.waypinpoint.R;

public class CartAdapter extends BaseAdapter {
    private Context context;
    private LayoutInflater layoutInflater;
    private ArrayList<Cart> arrayList;

    public CartAdapter(Context context, ArrayList<Cart> arrayList) {
        this.context = context;
        this.arrayList = arrayList;
    }

    @Override
    public int getCount() {
        return arrayList.size();
    }

    @Override
    public Object getItem(int position) {
        return arrayList.get(position);
    }

    @Override
    public long getItemId(int position) {
        return arrayList.get(position).getId();
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

        viewHolderList.update(arrayList.get(position));

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
            tvProductName.setText(cart.getProduct_id());
            tvQuantity.setText(String.valueOf(cart.getQuantity()));

//            Glide.with(context)
//                    //.load(cart.getActivityImg())
//                    .placeholder(R.drawable.img_default_activity)
//                    .diskCacheStrategy(DiskCacheStrategy.ALL)
//                    .into(imageViewCartItem);
        }
    }
}
