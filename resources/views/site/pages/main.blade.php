@extends('site.layout.master')

@isset($company)
    @section('title', $company->company_name . ' | ' . $company->company_phone)
@endisset
@isset($required_for_rent)
    @section('title', __('required_for_rent_page_title'))
@endisset

@section('content')

    <div x-data="{
            selectedCity : null,
            selectedCityObject : null,
            selectedType : null,
            selectedTypeObject : null,
            isRequiredPage : false,
            selectedPurpose : '{{ request()->get('type') }}',
            advertise: {},
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
                fetch('{{ asset('/api/v1/search-advertising') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        area_id: this.selectedCity > 0 ? [this.selectedCity] : null,
                        service_category_id:this.selectedType,
                        isRequiredPage: this.isRequiredPage,
                        purpose: this.selectedPurpose
                    })
                })
                .then( response => response.json() )
                .then( data => this.advertise = data.data );
            }
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
                            <span> (</span><span x-text="advertise.total"></span><span> {{ __('ads_title') }})</span>
                        </small>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row">
                    @include('site.sections.card')
                </div>
            </div>
        </section>
    </div>

@endsection
