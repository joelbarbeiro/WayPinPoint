package adaptors;

import android.content.Context;
import android.icu.util.ULocale;
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
import pt.ipleiria.estg.dei.waypinpoint.R;

public class ActivitiesListAdaptor extends BaseAdapter {

    private Context context;
    private LayoutInflater inflater;
    private ArrayList<Activity> activities;

    public ActivitiesListAdaptor(Context context, ArrayList<Activity> activities){
        this.context = context;
        this.activities = activities;
    }

    @Override
    public int getCount() {
        return activities.size();
    }

    @Override
    public Object getItem(int i) {
        return activities.get(i);
    }

    @Override
    public long getItemId(int i) {
        return activities.get(i).getId();
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if(inflater == null){
            inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if (view == null) {
            view = inflater.inflate(R.layout.item_list_activity, null);
        }

        // TODO: call view holder
        return view;
    }

    private class ViewHolderActivity{
        private TextView tvName, tvCatgory, tvPrice, tvAddress;
        private ImageView activityImg;

        public ViewHolderActivity(View view){
            tvName = view.findViewById(R.id.tvNameContent);
            tvCatgory = view.findViewById(R.id.tvCategoryContent);
            tvPrice = view.findViewById(R.id.tvPriceContent);
            tvAddress = view.findViewById(R.id.tvPriceContent);
            activityImg = view.findViewById(R.id.imageViewCover);
        }

        public void update(Activity activity){
            tvName.setText(activity.getName());
            tvCatgory.setText(activity.getCatgory());
            tvPrice.setText(""+activity.getPriceperpax());
            tvAddress.setText(activity.getAddress());
            Glide.with(context)
                    .load(activity.getPhoto())
                    .placeholder(R.drawable.img_default_activity)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .into(activityImg);
        }
    }
}
