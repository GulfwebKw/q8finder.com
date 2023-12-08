@extends('site.layout.master')
@section('title' , __('login_title'))
@section('disableHeaderNavbar' , 'yes')
@section('content')
    <style>
        .activeTabItem {
            border-radius: 10000px;
            background-color: blue;
            color: white;
        }
        .tabItems {
            border-radius: 10000px;
        }
    </style>
    <section class="mt-30">
        <div class="container">
            <div class="row">
                <!-- Gallery -->
                <div class="col-lg-3 mob_hide">&nbsp;</div>
                <div class="col-12 col-lg-6">
                    <div class="seach_container">
                        <div class="bg-white d-flex flex text-body w-100 mb-3" style="border-radius: 10000px;" role="tablist">
                            <div class="p-1 text-center w-100 tabItems activeTabItem" role="presentation" onclick="$('.tab-pane').hide('fast');$('#login').show('fast');$('.tabItems').removeClass('activeTabItem');$(this).addClass('activeTabItem');">
                                {{__('login_title')}}
                            </div>
                            <div class="p-1 text-center w-100 tabItems" role="presentation"  onclick="$('.tab-pane').hide('fast');$('#register').show('fast');$('.tabItems').removeClass('activeTabItem');$(this).addClass('activeTabItem');">
                                {{__('sign_up_title')}}
                            </div>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane" style="display:block;" id="login" role="tabpanel" aria-labelledby="home-tab">
                                <h3 class="text-center">{{__('welcome_back')}}</h3>
                                <p class="text-center"><a href="{{ route('register',app()->getLocale()) }}"> {{__('new_to_site')}}
                                        {{__('sign_up')}}</a></p>
                                <hr>
                                @if(session('status'))
                                    <div class="alert alert-success">
                                        <strong>{{ __('success_title') }}!</strong> {{session('status')}}!
                                    </div>
                                @endif
                                <form method="post" action="{{ route('login',app()->getLocale()) }}">
                                    @csrf
                                <div class="mb-20">
                                    <label><i class="fa fa-phone fa-lg"></i> {{__('phone_number_title')}}</label>
                                    <input type="text" dir="ltr" class="input form-control  @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autofocus placeholder="{{__('phone_number_title')}}" onblur="this.placeholder='{{__('phone_number_title')}}'" onclick="this.placeholder=''">
                                    @error('mobile')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-20">
                                    <label><i class="fa fa-lock fa-lg"></i> {{__('password')}}</label>
                                    <input type="password" dir="ltr" name="password" required class="input form-control @error('password') is-invalid @enderror" placeholder="{{__('password')}}" onblur="this.placeholder=''" onclick="this.placeholder='{{__('password')}}'">
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-20">
                                    <input type="checkbox" style="width: auto;" class="ml-10"  name="remember"   id="remember" {{ old('remember') ? 'checked' : '' }}> <label for="remember">{{ __('remember_me') }}</label>
                                </div>

                                <div class="text-center"><button type="submit" class="btn btn_lg"><strong>{{__('login')}}</strong></button></div>

                                </form>
                                <hr>

                                <p><a href="{{ route('password.request',app()->getLocale()) }}"><i class="fa fa-key fa-lg"></i> {{__('forgot_password')}}</a></p>

                            </div>
                            <div class="tab-pane" id="register" style="display: none;" >
                                ...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
