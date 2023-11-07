@extends('site.layout.master')

@isset($company)
@section('title', $company->company_name . ' | ' . $company->company_phone)
@endisset
@isset($required_for_rent)
@section('title', __('required_for_rent_page_title'))
@endisset

@section('content')

<div  x-data="{
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
    }" x-init="fetchAreas();fetchTypes();">
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
                    <small> عقارات للايجارفي اكويت (2121 إعلان)</small>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
{{--                @include('site.sections.card')--}}
            </div>
        </div>
    </section>
</div>

@endsection
