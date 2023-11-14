@extends('site.layout.master')

@isset($company)
    @section('title', $company->company_name . ' | ' . $company->company_phone)
@endisset
@isset($required_for_rent)
    @section('title', __('required_for_rent_page_title'))
@endisset

@section('head')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <style>
        .listAdvertise .advertise .card_details .first-time {
            display: none;
        }
        .listAdvertise .advertise .card_details .second-time , .listAdvertise .advertise .card_details .advertise-description  {
            display: block;
        }
        @media (max-width: 992px) {
            body {
                background-color: #111720 !important;
            }
            .listAdvertise {
                background-color: #111720 !important;
                padding-top: 15px;
            }
            .listAdvertise .advertise {
                background-color: #252d3a  !important;
                border-radius: 10px 30px 30px 10px;
                margin: 0 40px 10px 0 !important;
                padding: 0;
            }
            .listAdvertise .advertise .desk_hide {
                display: block !important;
            }
            .listAdvertise .advertise .share i {
                margin: 10px;
            }
            .listAdvertise .advertise .share {
                right: -40px;
                bottom: 23px;
                top: auto;
                background-color: black;
                border-radius: 50%;
                cursor: pointer;
            }
            .listAdvertise .advertise .card_details {
                padding-bottom: 0;
                margin-left: 10px;
                margin-right: 10px;
                margin-bottom: 10px;
            }
            .listAdvertise .advertise .featured {
                top: 15px;
                right: 15px;
                border-radius: 0 10px 0 0;
            }
            .listAdvertise .advertise .card_details .text-left{
                margin-bottom: 0;
            }
            .listAdvertise .advertise .card_details .first-time {
                display: block;
            }
            .listAdvertise .advertise .card_details .second-time ,.listAdvertise .advertise .card_details .advertise-description {
                display: none;
            }
            .listAdvertise .advertise .ad_img{
                border-radius: 10px 25px 10px 10px;
                margin: 5px;
                width: calc(100% - 10px);
            }
            .listAdvertise .advertise .advertise-header-time{
                display: none;
            }
        }
    </style>
@endsection

@section('content')

    <div x-data="{
            totalAdvertise : 0,
            page : 1,
            totalPage : 1,
            selectedCity : null,
            selectedCityObject : null,
            selectedType : null,
            selectedTypeObject : null,
            isRequiredPage : false,
            selectedPurpose : '{{ request()->get('type') }}',
            advertise: [],
            purpose_lang:{
                rent: '{{ __('rent') }}' ,
                sell: '{{ __('sell') }}' ,
                exchange: '{{ __('exchange') }}' ,
                required_for_rent: '{{ __('required_for_rent') }}'
            },
            areas: [],
            async fetchAreas(){
                fetch('{{ asset('/api/v1/cities') }}')
                .then( response => response.json() )
                .then( data => this.areas = data.data );
                },
            types: [],
            async fetchTypes(){
                fetch('{{ asset('/api/v1/get-search-property') }}')
                .then( response => response.json() )
                .then( data => this.types = data.data.type );
            },
            async search(){
                if( this.selectedPurpose == 'commercial' ){
                    this.selectedPurpose = '';
                    do {
                        await  new Promise(resolve => setTimeout(resolve, 500));
                    } while(this.types.length == 0);

                    let obj = this.types.find(o => o.title_en.toLowerCase() == 'commercial');
                    this.selectedType = obj.id;
                }
                fetch('{{ asset('/api/v1/search-advertising') }}?page='+this.page, {
                    method: 'POST',
                    headers: {
                        'X-localization': '{{ app()->getLocale() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        area_id: this.selectedCity > 0 ? [this.selectedCity] : null,
                        venue_type:this.selectedType,
                        isRequiredPage: this.isRequiredPage,
                        purpose: this.selectedPurpose
                    })
                })
                .then( response => response.json() )
                .then( data => {
                    this.advertise = this.advertise.concat(data.data.data);
                    this.totalAdvertise = data.data.total;
                    this.totalPage = data.data.last_page;
                 });
            },
            isArabic(text) {
                let pattern = /[\u0600-\u06FF\u0750-\u077F]/;
                let result = pattern.test(text);
                return result;
            },
            truncate(input , char) {
                if (input.length > char) {
                    return input.substring(0, char) + '...';
                }
                return input;
            },
            share(title ,text , url ){
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        text: text,
                        url: url,
                    })
                        .then(() => console.log('Successful share'))
                        .catch((error) => console.log('Error sharing', error));
                } else {
                    console.log('Share not supported on this browser, do it the old way.');
                    window.open('https://web.whatsapp.com/send?text='+encodeURIComponent(url)+'&title='+encodeURIComponent(title));
                }
            },
        }" x-init="fetchAreas();fetchTypes();search();">
        @isset($company)
            {{--    @include('site.sections.company-info')--}}
            {{--        <h2>{{__('company_ads')}}</h2>--}}
        @endisset

        @if(! isset($company))
            @include('site.sections.search')
        @endif

        <section class="mt-10">
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <h3>أحدت الإعلانات</h3>
                    </div>
                    <div class="col-6 text-left">
                        <small>
                            <span x-text="selectedTypeObject ? selectedTypeObject.title_{{ app()->getLocale() }} : '' "></span>
                            <span x-text="selectedPurpose == 'rent' ? '{{ __('rent') }}' : ( selectedPurpose == 'sell' ? '{{ __('sell') }}' : ( selectedPurpose == 'exchange' ? '{{ __('exchange') }}' : '' ) )" ></span>
                            <span>{{ __('search_in') }}</span>
                            <span x-text="selectedCityObject ? selectedCityObject.name_{{ app()->getLocale() }} : '{{ __('search_kuwait') }}' "></span>
                            <span> (</span><span x-text="totalAdvertise"></span><span> {{ __('ads_title') }})</span>
                        </small>
                    </div>
                </div>
            </div>
        </section>

        <section class="listAdvertise">
            <div class="container">
                <div class="row">
                    @include('site.sections.card')
                </div>
            </div>
        </section>
        <div x-intersect:enter="if (page < totalPage) { page++;search();}"></div>
    </div>

@endsection
