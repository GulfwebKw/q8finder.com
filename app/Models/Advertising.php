<?php

namespace App\Models;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class Advertising extends Model implements Feedable
{
    use SoftDeletes;
    static $isForService = false;

    const TYPES = [
        'RESIDENTIAL' => 'residential',
    ];
    const purpose = [
        'Rent' => 'rent',
        'Sell' => 'sell',
        'Exchange' => 'exchange',
        'commercial' => 'commercial',
        'Required_for_rent' => 'required_for_rent',
        'Service' => 'service',
    ];
    const RESIDENTIALS = [
        'Apartment' => 'apartment',
        'Villa' => 'villa',
        'Rest_house' => 'rest_house',
    ];
    protected $guarded = ['id'];
    protected $appends = ['total', 'thumb_main_image','isOldAd'];
    protected $hidden = [
        "rss_image",
        "number_of_rooms",
        "number_of_bathrooms",
        "number_of_master_rooms",
        "number_of_miad_rooms",
        "number_of_floor",
        "gym",
        "pool",
        "furnished",
        "number_of_parking",
        "number_of_balcony",
        "floor_plan",
        "surface",
        "security",
        "sort",
        "reported",
    ];


    protected static function booted()
    {
        static::addGlobalScope('notService', function (Builder $builder) {
            if ( self::$isForService )
                $builder->where('advertisings.purpose', "service");
            else
                $builder->where('advertisings.purpose', '!=', "service");
        });
    }

    /**
     * Get the user's first name.
     *
     * @param string $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }


    public function getExpireAtAttribute($value)
    {
        if (isset($value) && $value != null && $value != "null" && $value < date('Y-m-d'))
            return $value;
        // return Carbon::parse($value)->diffForHumans();

        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class)->withTrashed();
    }

    public function city()
    {
        return $this->belongsTo(City::class)->withTrashed();
    }

    public function venue()
    {
        return $this->belongsTo(VenueType::class , 'venue_type')->withTrashed();
    }


    public function service()
    {
        return $this->belongsTo(ServiceCategory::class , 'service_category_id')->withTrashed();
    }



    public static function getValidAdvertising($status = 1): Builder
    {
        $ad = Advertising::with(["user", "area", "city", "venue"]);

        if ( ! config('app.JUST_SHOW_ONE_PREMIUM_AD' , false ) or Advertising::$isForService )
            $ad = $ad->orderBy("advertising_type", "desc");

        $ad = $ad->orderBy('created_at', 'desc');
        if ($status == 1) {
            $ad = $ad->where('status', 'accepted');
        }
        return $ad;

    }


    /**
     * @return array|\Spatie\Feed\FeedItem
     */
    public function toFeedItem()
    {
        $lang = request()->get('lang');
        if (empty($lang)) {
            $lang = 'en';
        }

        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->{'title_' . $lang},
            'summary' => $this->{'description_' . $lang} ?? '',
            'updated' => $this->updated_at,
            'link' => route('site.ad.detail' , [$lang , $this->hash_number]),
            'mobile' => $this->phone_number,
            'author' => optional($this->user)->name ?? '',
        ]);
    }

    public static function getFeedItems()
    {
        return Advertising::where('advertising_type', 'premium')->where('status', 'accepted')->where('expire_at', '>=', date('Y-m-d'))->orderBy('created_at', 'desc')->get();
    }

    public function advertisingView()
    {
        return $this->hasMany(AdvertisingView::class);
    }

    public function getTotalAttribute()
    {
        return parent::getAttribute('view_count');
//        return sizeof(self::advertisingView()->get());
    }

//    public function getViewCountAttribute($value)
//    {
//        return $this->advertisingView()->count();
//    }

    public function getThumbMainImageAttribute($value)
    {
        return str_replace('uploads/images', 'thumb/200xx/resources/uploads/images' ,$this->main_image);
    }

    public function getIsOldAdAttribute($value)
    {
        return  Carbon::parse($this->original['created_at'])->lt(now()->subMonth());
    }

    public static  function makeHashNumber()
    {
        do {
            $hash = uniqid();
            $data = Advertising::where('hash_number',$hash)->first();
        }
        while ($data);

        return $hash;

    }

}
