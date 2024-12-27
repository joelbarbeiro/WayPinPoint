package adaptors;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.RatingBar;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;

import java.util.ArrayList;

import Model.Activity;
import Model.Review;
import pt.ipleiria.estg.dei.waypinpoint.R;
import pt.ipleiria.estg.dei.waypinpoint.utils.Utilities;

public class ReviewListAdapter extends BaseAdapter {
    private Context context;
    private LayoutInflater inflater;
    private ArrayList<Review> reviews;

    public ReviewListAdapter(Context context, ArrayList<Review> reviews) {
        this.context = context;
        this.reviews = reviews;
    }

    @Override
    public int getCount() {
        return reviews.size();
    }

    @Override
    public Object getItem(int position) {
        return reviews.get(position);
    }

    @Override
    public long getItemId(int position) {
        return reviews.get(position).getId();
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        if (inflater == null) {
            inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if (convertView == null) {
            convertView = inflater.inflate(R.layout.item_list_review, null);
        }

        ViewHolderReview viewHolderReview = (ViewHolderReview) convertView.getTag();
        if (viewHolderReview == null) {
            viewHolderReview = new ViewHolderReview(convertView);
            convertView.setTag(viewHolderReview);
        }
        viewHolderReview.update(reviews.get(position));

        return convertView;
    }

    private class ViewHolderReview {
        private TextView tvMessage, tvDate;
        private RatingBar ratingBar;


        public ViewHolderReview(View view) {
            ratingBar = view.findViewById(R.id.ratingBarScore);
            tvMessage = view.findViewById(R.id.tvMessageContent);
            tvDate = view.findViewById(R.id.tvDateContent);
        }

        public void update(Review review) {
            int date = review.getCreatedAt();
            String dateToinsert;
            ratingBar.setRating(Float.parseFloat("" + review.getScore()));
            tvMessage.setText(review.getMessage());
            dateToinsert = Utilities.setDateFromTimestamp(date);
            tvDate.setText(dateToinsert);
        }
    }
}
