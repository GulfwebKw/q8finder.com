<template x-for="ad in advertise">
    <div class="col-101 col-lg-3 mx-auto advertise">
        <p class="text-center advertise-header-time" x-text="ad.created_at"></p>
        <div class="featured" x-show="ad.advertising_type === 'premium'">{{ __('premium_short') }}</div>
        <div @click="share( (ad.purpose !== 'service' ? purpose_lang[ad.purpose] + ' '+ ad.venue.title_{{app()->getLocale()}}+ ' {{__('in')}} '+ ad.area.name_{{app()->getLocale()}} : ad.title_{{app()->getLocale()}}) ,ad.description , ad.share_link.{{ app()->getLocale()  }} );" class="share desk_hide"><i class="fa fa-share"></i></div>
        <a :href="ad.share_link.{{ app()->getLocale()  }}"><img :src="ad.main_image ? ad.main_image : '{{route('image.noimage', '')}}'" alt="ad" class="ad_img"  onerror="this.onerror=null;this.src='{{route('image.noimage', '')}}';">
            <div class="card_details">
                <p class="first-time"><img src="{{ asset('assets/img/time.png') }}" alt="" style="width: 20px;margin-right: 3px;">  <span x-text="ad.created_at"></span></p>
                <p><img src="{{ asset('assets/img/home.png') }}" alt="" style="width: 25px;">  <span x-text="ad.purpose !== 'service' ? purpose_lang[ad.purpose] + ' '+ ad.venue.title_{{app()->getLocale()}}+ ' {{__('in')}} '+ ad.area.name_{{app()->getLocale()}} : ad.title_{{app()->getLocale()}} "></span></p>
                <p class="f-13 text-gray advertise-description" :dir="isArabic(ad.description) ? 'rtl' : 'ltr'" x-text="truncate(ad.description, 40)"></p>
                <p class="text-left">
                    <strong class="second-time"><img src="{{ asset('assets/img/time.png') }}" alt="" style="width: 20px; height: auto;" class="f-left mr-10"> <span x-text="ad.created_at"></span></strong>
                    <strong dir="ltr"><img src="{{ asset('assets/img/money.png') }}" alt="" style="width: 25px; height: auto;" class="mr-10">{{ __('kd_title') }} <span x-text="ad.price"></span></strong><br/>
                    <strong class="text-blue" dir="ltr"><img src="{{ asset('assets/img/phone.png') }}" alt="" style="width: 20px; height: auto;" class="mr-10"> <span x-text="ad.phone_number"></span></strong>
                </p>
            </div>
        </a>
    </div>
</template>
