<template x-for="ad in advertise.data">
    <div class="col-11 col-lg-3 mx-auto">
        <p class="text-center" x-text="ad.created_at"></p>
        <div class="featured" x-show="ad.advertising_type === 'premium'">{{ __('premium_short') }}</div>
        <a href="#" class="share desk_hide"><i class="fa fa-share fa-lg"></i></a>
        <a :href="ad.share_link.{{ app()->getLocale()  }}"><img :src="ad.main_image ? ad.main_image : '{{route('image.noimage', '')}}'" alt="ad" class="ad_img">
            <div class="card_details">
                <p><img src="{{ asset('assets/img/home.png') }}" alt="" style="width: 25px; height: auto;">  <span x-text="ad.purpose !== 'service' ? purpose_lang[ad.purpose] + ' '+ ad.venue.title_{{app()->getLocale()}}+ ' {{__('in')}} '+ ad.area.name_{{app()->getLocale()}} : ad.title_{{app()->getLocale()}} "></span></p>
                <p class="f-13 text-gray" :dir="isArabic(ad.description) ? 'rtl' : 'ltr'" x-text="truncate(ad.description, 40)"></p>
                <p class="text-left">
                    <strong><img src="{{ asset('assets/img/time.png') }}" alt="" style="width: 20px; height: auto;" class="f-left mr-10"> <span x-text="ad.created_at"></span></strong><br/>
                    <strong dir="ltr"><img src="{{ asset('assets/img/money.png') }}" alt="" style="width: 25px; height: auto;" class="mr-10">{{ __('kd_title') }} <span x-text="ad.price"></span></strong><br/>
                    <strong class="text-blue" dir="ltr"><img src="{{ asset('assets/img/phone.png') }}" alt="" style="width: 20px; height: auto;" class="mr-10"> <span x-text="ad.phone_number"></span></strong></p>
            </div>
        </a>
    </div>
</template>
