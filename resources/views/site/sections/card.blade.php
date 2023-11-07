


@php
    $message = urlencode("السلام عليكم\nاذا ممكن ترسل تفاصيل هذا الإعلان في مستر عقار
                                وشكرا\n" ). request()->getSchemeAndHttpHost() . '/'.app()->getLocale().'/advertising/';
@endphp

<card lang="{{app()->getLocale()}}" :purpose_lang="{rent: '{{ __('rent') }}' ,sell: '{{ __('sell') }}' ,exchange: '{{ __('exchange') }}' ,required_for_rent: '{{ __('required_for_rent') }}' , }" :card="card" v-for="card in cards" inline-template>
    <a :href="href" style="text-decoration: none;" class="text-body">

    <div class="col-xs-12 rounded-sm p-0 mb-3" :class="card.advertising_type === 'premium' ? 'primary-card rounded-sm bg-primary' : 'b-gray'">
        <div class="border p-2 overflow-hidden card-height">
            <div class="row mb-2">
                <div class="p-relative col-sm-2 w-sm1/5 p{{$side}}-image p-0 " :class="{'image-box':card.advertising_type === 'premium', 'd-none' : card.purpose === 'required_for_rent'}">
                    <div :style="`background-image: url('` + (card.main_image ? card.main_image : '{{route('image.noimage', '')}}') + `')`" class="card-image">
                    </div>
                </div>
                <div class="col-sm-10 w-sm4/5 p-0 w-100">
                    <div class="d-flex">
                        <div class="w-100">
                            <h2 class="text-md mb-2 sm:width-110"  :class="card.advertising_type === 'premium' ? 'text-white' : ''" v-text=" card.purpose !== 'service' ? `${purpose_lang[card.purpose]} ${card.venue.title_{{app()->getLocale()}} } {{__('in')}} ${card.area.name_{{app()->getLocale()}} }` : `${card.title_{{app()->getLocale()}} }`"></h2>
                            <div class="infos flex-container justify-content-between w-100">
                                <div>
                                    <span class="primary-color fw-600 d-inline-block m{{$side}}-2"  :class="card.advertising_type === 'premium' ? 'text-white' : ''" v-if="card.price">@{{card.price | commaSeparate }} {{__('kd_title')}}</span>
                                </div>
                                {{-- <div>
                                    <span :onclick="`event.preventDefault();
                                     location = 'https://api.whatsapp.com/send?phone=${tel}&text={{$message}}${card.hash_number}/details'`"
                                          class="d-flex align-items-center"  :class="card.advertising_type === 'premium' ? 'text-white' : ''"><strong v-text="card.phone_number"></strong>&nbsp;<img src="/images/main/whatsapp.webp" alt="whatsapp square icon" width="20px"></span>
                                </div> --}}
                            </div>
                            <div class="infos flex-container justify-content-between w-100" >
                                <div>
                                    <span class="flex flex-container m{{$side}}-4" :class="card.advertising_type === 'premium' ? 'text-white' : 'text-muted'">
                                        <i class="material-icons text-sm  m{{$side}}-1 mb-1" >calendar_month</i>
                                        <span class="text-xs">@{{ card.created_at }}</span>
                                    </span>
{{--                                    <span class="flex flex-container"  :class="card.advertising_type === 'premium' ? 'text-white' : 'text-muted'">--}}
{{--                                        <i class="material-icons-outlined text-sm  m{{$side}}-1" >visibility</i>--}}
{{--                                        <span class="text-xs">@{{card.view_count}}</span>--}}
{{--                                    </span>--}}
                                </div>
                            </div>
                            <div :dir="isArabic(card.description) ? 'rtl' : 'ltr'" :class="card.advertising_type === 'premium' ? 'fw-600 text-light' : ''" class="d-none d-sm-block d-md-block d-lg-more-block mb-2 text-sm card-description"  :class="card.advertising_type === 'premium' ? 'text-white' : ''" v-snip:js="1">
                                @{{card.description  | truncate(80, '...')}}
                            </div>
                            <span class="mdc-button mdc-button--outlined small-button d-none sm-show-button text-decoration-none text-sm"  :class="{'mdc-button--outlined-white': card.advertising_type === 'premium' , 'd-none' : card.purpose !== 'service'}" :style="  card.purpose === 'service' ? 'display:none !important' :'' " style="padding: 10px 15px;background: white ">
                                <span class="mdc-button__ripple"></span>
                                <span class="mdc-button__label fw-600">@{{purpose_lang[card.purpose]}}</span>
                            </span>
                        </div>
                        {{-- <div class="cdropdown show">
                            <i class="material-icons-outlined text-sm text-muted" :onclick="`event.preventDefault();location = '/{{ app()->getLocale() }}/card-action/`+card.id+`'`">more_vert</i>
                            <div class="cdropdown-menu" >
                                <div class="cdropdown-item" href="#">
                                    <span class="flex flex-container  m{{$side}}-2" :onclick="`event.preventDefault();  location = '/{{ app()->getLocale() }}/confirm-report/ad/`+card.id+`'`">
                                        <i class="material-icons-outlined text-sm text-muted m{{$side}}-1 mb-1"
                                            style="font-size: 22px">sms_failed</i>
                                        <span class="text-xs">{{__('report')}}</span>
                                    </span>
                                </div>
                                <div class="cdropdown-item" href="#">
                                    <span class="flex flex-container  m{{$side}}-2" :onclick="`event.preventDefault();  location = '/{{ app()->getLocale() }}/block/ad/`+card.id+`'`">
                                        <i class="material-icons-outlined text-sm text-muted m{{$side}}-1 mb-1"
                                            style="font-size: 22px">block</i>
                                        <span class="text-xs">{{__('block')}}</span>
                                    </span>
                                </div>
                            </div>
                        </div>--}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div :dir="isArabic(card.description) ? 'rtl' : 'ltr'" class="col-xs-12 p-0 d-sm-none d-md-none d-lg-more-none text-sm card-description"  :class="card.advertising_type === 'premium' ? 'text-white' : ''" v-snip:css="3">
                    @{{card.description  | truncate(80, '...')}}
                </div>
            </div>
        </div>
    </div>
    </a>
</card>
