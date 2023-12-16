<template x-for="ad in advertise">
    <div class="col-101 col-lg-3 mx-auto advertise">
        <div class=" advertise-bg-white mb-3">

            {{--        <!-- created time in desktop -->--}}
            {{--        <p class="text-center advertise-header-time"  x-show="ad.purpose != 'required_for_rent1'" x-text="ad.created_at"></p>--}}

            <div class="featured text-white" x-show="ad.advertising_type === 'premium'">{{ __('premium_short') }}</div>
            <div
                @click="share( (ad.purpose !== 'service' ? purpose_lang[ad.purpose] + ' '+ ad.venue.title_{{app()->getLocale()}}+ ' {{__('in')}} '+ ad.area.name_{{app()->getLocale()}} : ad.title_{{app()->getLocale()}}) ,ad.description , ad.share_link.{{ app()->getLocale()  }} );"
                class="share desk_hide"><i class="fa fa-share"></i></div>
            <a :href="ad.share_link.{{ app()->getLocale()  }}">
                <img x-show="ad.purpose != 'required_for_rent1'"
                    :src="ad.main_image ? ad.main_image : '{{route('image.noimage', '')}}'"
                    alt="ad" class="ad_img"
                    onerror="this.onerror=null;this.src='{{route('image.noimage', '')}}';">
                <div class="card_details">
                    <p class="first-time desk_hide">
                        <img src="{{ asset('assets/img/time.png') }}" alt="" style="width: 20px;margin-right: 3px;">
                        <span x-text="ad.created_at"></span>
                    </p>
                    <p class="mob_hide">
                        <strong
                            x-text="ad.purpose !== 'service' ? purpose_lang[ad.purpose] + ' '+ ad.venue.title_{{app()->getLocale()}}+ ' {{__('in')}} '+ ad.area.name_{{app()->getLocale()}} : ad.title_{{app()->getLocale()}} ">
                        </strong>
                    </p>
                    <p class="desk_hide">
                        🏡
                        <span
                            x-text="ad.purpose !== 'service' ? purpose_lang[ad.purpose] + ' '+ ad.venue.title_{{app()->getLocale()}}+ ' {{__('in')}} '+ ad.area.name_{{app()->getLocale()}} : ad.title_{{app()->getLocale()}} "></span>
                    </p>


                    <p class="f-13 advertise-description" :dir="isArabic(ad.description) ? 'rtl' : 'ltr'"
                       x-text="truncate(ad.description, 40)"></p>


                    <div class="mob_hide" style="height: 25px;">
                    </div>
                    <div class="mob_hide" style="bottom: 20px;position: absolute;width: calc(100% - 50px);">
                        <p class="text-left">
                                                   <strong class="second-time" style="width:48%;float:left;font-size:12px !important;bottom:0;">
                                                       <span
                                                          x-text="ad.created_at"></span> <img src="{{ asset('assets/img/time.png') }}" alt=""
                                                                                            style="width: 15px; height: auto;"
                                                                                             class="mr-10">
                                                 </strong>

                            <strong class="text-right text-blue" dir="ltr" style="width:50%;float:right;display: block; padding-right: 1rem!important; font-size: 14px;color: var(--blue) !important;bottom:0;">
                                💰 {{ __('kd_title') }} <span
                                    x-text="ad.price"></span>
                            </strong>
                        </p>
                        <div class="clearfix"></div>
                    </div>
                    <div class="desk_hide">
                        <p class="text-left">
                            <strong dir="ltr" style="display: block;">
                                <span class="mr-10">💰</span>{{ __('kd_title') }} <span
                                    x-text="ad.price"></span>
                            </strong>
                            <strong class="text-blue" dir="ltr" style="display: block;">
                                <img src="{{ asset('assets/img/phone.png') }}"
                                   alt="" style="width: 20px; height: auto;"
                                   class="mr-10">
                                <span x-text="ad.phone_number"></span>
                            </strong>
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</template>
