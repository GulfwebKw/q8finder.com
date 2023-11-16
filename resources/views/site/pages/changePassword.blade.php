@extends('site.layout.panel')
@section('title' , __('change_password_title'))
@section('panel-content')
    @if((session('status')) == 'success')
        <div class="alert alert-success">
            <strong>{{ __('success_title') }}!</strong> {{ __('profile_success') }}
        </div>
    @elseif((session('status')) == 'unsuccess')
        <div class="alert alert-danger">
            <strong>{{ __('un_success_title') }}!</strong> {{ __('contact_unsuccess') }}
        </div>
    @elseif((session('status')) == 'dontmatch')
        <div class="alert alert-danger">
            <strong>{{ __('un_success_title') }}!</strong> {{ __('Passwords_not_match') }}
        </div>
    @endif

    <form method="post" class="contact-form" action="{{ route('User.changePassword', app()->getLocale()) }}">
        @csrf
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <label>{{__('Password')}}</label>
                <input type="password" dir="ltr" class="input text-left @error('current') is-invalid @enderror"
                       name="current"
                       placeholder="{{__('Password')}}">
                @error('current')
                <div class="invalid-feedback warn-color">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-6 col-md-6">
                <label>{{__('new_Password')}}</label>
                <input type="password" dir="ltr" class="input text-left @error('password') is-invalid @enderror"
                       name="password"
                       placeholder="{{__('new_Password')}}">
                @error('password')
                <div class="invalid-feedback warn-color">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-6">
                <label>{{__('Confirm Password')}}</label>
                <input type="password" dir="ltr" class="input text-left @error('password_confirmation') is-invalid @enderror"
                       name="password_confirmation"
                       placeholder="{{__('Confirm Password')}}">
                @error('password_confirmation')
                <div class="invalid-feedback warn-color">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </div>

        </div>

        <button type="submit" class="btn btn_lg" style="margin-top:30px !important;">{{__('save_title')}} &amp; {{__('upload_title')}}</button>


    </form>
@endsection
{{--@section('pagination')--}}
{{--    dsadad--}}
{{--@endsection--}}
