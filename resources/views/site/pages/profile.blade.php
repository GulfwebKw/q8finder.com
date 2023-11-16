@extends('site.layout.panel')
@section('title' , __('edit_profile_title'))
@section('panel-content')
@if((session('status')) == 'success')
<div class="alert alert-success">
    <strong>{{ __('success_title') }}!</strong> {{ __('profile_success') }}
</div>
@elseif((session('status')) == 'unsuccess')
<div class="alert alert-danger">
    <strong>{{ __('un_success_title') }}!</strong> {{ __('contact_unsuccess') }}
</div>
@endif

<form method="post" class="contact-form" action="{{ route('User.editUser',app()->getLocale()) }}"
    enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-6 col-md-6 col-xs-12">
                <label>{{__('your_name')}}</label>
                <input type="text" dir="rtl" class="input text-right @error('name') is-invalid @enderror"
                       value="{{ old('name' , auth()->user()->name)}}" name="name"
                        placeholder="{{__('your_name')}}">
            @error('name')
            <div class="invalid-feedback warn-color">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-6 col-md-6 col-xs-12">
            <label>{{__('phone_number_title')}}</label>
            <input type="text" dir="ltr" disabled class="input text-left"
                   value="{{  auth()->user()->mobile}}">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6 col-md-6 col-xs-12">
            <label>{{__('email_address_title')}}</label>
            <input type="email" dir="ltr" class="input text-left @error('email') is-invalid @enderror"
                   value="{{ old('email' , auth()->user()->email)}}" name="email"
                    placeholder="{{__('email_address_title')}}">
            @error('email')
            <div class="invalid-feedback warn-color">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>
    </div>

    @if( auth()->user()->isCompany )
    <hr class="mt-3">
    <h3 class="my-2">{{__('company_settings')}}</h3>

    <div class="row mt-3">
        <div class="col-lg-6 col-md-6 col-xs-12">
            <label>{{__('company_name_title')}}</label>
            <input type="text" dir="rtl" class="input text-right @error('company_name') is-invalid @enderror"
                   value="{{ old('company_name' , auth()->user()->company_name)}}" name="company_name"
                    placeholder="{{__('company_name_title')}}">
            @error('company_name')
            <div class="invalid-feedback warn-color">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-6 col-md-6 col-xs-12">
            <label>{{__('company_phone_title')}}</label>
            <input type="text" dir="ltr" class="input text-left @error('company_phone') is-invalid @enderror"
                   value="{{ old('company_phone' , auth()->user()->company_phone)}}" name="company_phone"
                   placeholder="{{__('company_phone_title')}}">
            @error('company_phone')
            <div class="invalid-feedback warn-color">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-lg-6 col-md-6 col-xs-12">
            <label>{{__('company_email')}}</label>
            <input type="email" dir="ltr" class="input text-left @error('social_email') is-invalid @enderror"
                   value="{{ old('social_email' , auth()->user()->social_email)}}" name="social_email"
                   placeholder="{{__('company_email')}}">
            @error('social_email')
            <div class="invalid-feedback warn-color">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12">
            <label>{{__('instagram_address_title')}}</label>
            <input type="text" dir="ltr" class="input text-left @error('instagram') is-invalid @enderror"
                   value="{{ old('instagram' , auth()->user()->instagram)}}" name="instagram"
                   placeholder="{{__('instagram_address_title')}}">
            @error('instagram')
            <div class="invalid-feedback warn-color">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-lg-6 col-md-6 col-xs-12">
            <label>{{__('twitter_address_title')}}</label>
            <input type="text" dir="ltr" class="input text-left @error('twitter') is-invalid @enderror"
                   value="{{ old('twitter' , auth()->user()->twitter)}}" name="twitter"
                   placeholder="{{__('twitter_address_title')}}">
            @error('twitter')
            <div class="invalid-feedback warn-color">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>
    </div>


    <hr class="mt-3">
    @endif
    <div class="row mt-3">
{{--        <div class="col-lg-6 col-md-6 col-xs-12">--}}
{{--             <div class="mdc-text-field--outlined w-100">--}}
{{--                 <label class="text-muted w-100">{{__('get_verified_profile_title')}}</label>--}}
{{--                 <input type="file" name="licence" id="licence"--}}
{{--                        class="mdc-text-field__input @error('licence') is-invalid @enderror"--}}
{{--                        placeholder="{{__('get_verified_profile_title')}}">--}}
{{--             </div>--}}
{{--            @error('licence')--}}
{{--            <span class="invalid-feedback warn-color">--}}
{{--                <strong>{{ $message }}</strong>--}}
{{--            </span>--}}
{{--            @enderror--}}
{{--        </div>--}}
        <div class="col-lg-6 col-md-6 col-xs-12">
            <label>{{__('profile_image_title')}}</label>
            <input type="file" class="input @error('avatar') is-invalid @enderror"
                   value="{{ old('avatar' , auth()->user()->avatar)}}" name="avatar"
                   placeholder="{{__('profile_image_title')}}">
            @error('avatar')
            <div class="invalid-feedback warn-color">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>

    </div>

    <button type="submit" class="btn btn_lg"
        style="margin-top:30px !important;">{{__('save_title')}} &amp; {{__('upload_title')}}</button>
</form>

{{-- <button type="submit" class="mdc-button mdc-button--raised mdc-ripple-upgraded bg-danger"
style="margin-top:30px;">{{__('delete')}} {{__('account')}}</button> --}}
{{--<a href="user/delete" style="margin-top:30px;" class="mdc-button mdc-button--raised mdc-ripple-upgraded bg-danger">--}}
{{--    {{__('delete')}} {{__('account')}}--}}
{{--</a>--}}
@endsection
