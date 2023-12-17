@extends('site.layout.master')
@section('title' , __('forgot_password'))

@section('content')

    <section class="mt-30">
        <div class="container">
            <div class="row">
                <!-- Gallery -->
                <div class="col-lg-3 mob_hide">&nbsp;</div>

                <div class="col-12 col-lg-6">
                    <div class="seach_container">
                        <h3 class="text-center">{{__('forgot_password')}}</h3>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                    <div>{{$error}}</div>
                            @endforeach
                            </div>
                        @endif
                        <form method="post"  id="forgot-form" action="{{ route('password.email',app()->getLocale()) }}">
                            @csrf
                            @if ( old('codeValidation' , false ) )
                                <input type="hidden" name="mobile" value="{{ old("mobile") }}">
                                <input type="hidden" name="codeValidation" value="{{ old("codeValidation") }}">
                                <div class="mb-20">
                                    <label><i class="fa fa-phone fa-lg"></i> {{__('passcode')}}</label>
                                    <input id="code" dir="ltr" type="number" name="code" autofocus class="input form-control @error('code') is-invalid @enderror @if($errors->any()) is-invalid @endif" placeholder="{{__('passcode')}}" onblur="this.placeholder='{{__('passcode')}}'" onclick="this.placeholder=''">
                                    @error('code')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="invalid-feedback"><strong>{{$error}}</strong></div>
                                    @endforeach
                                @endif
                                <div id="resendcode"  onclick="submitForm();" class="btn btn-outline-info w-100 mt-3" style="cursor: pointer;display: none;">
                                    {{__('resend_code')}}
                                    <input type="checkbox" name="resend" id="resend" value="1" style="display: none;">
                                </div>
                                <div class="mb-20">
                                    <label><i class="fa fa-lock fa-lg"></i> {{__('password')}}</label>
                                    <input name="password" id="password" type="password" required dir="ltr" class="input form-control @error('code') is-invalid @enderror" placeholder="{{__('password')}}" onblur="this.placeholder=''" onclick="this.placeholder='{{__('password')}}'">
                                    @error('password')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-20">
                                    <label><i class="fa fa-lock fa-lg"></i> {{__('Confirm Password')}}</label>
                                    <input name="password_confirmation" id="password_confirmation"  dir="ltr" type="password" required class="input form-control @error('password_confirmation') is-invalid @enderror" placeholder="{{__('Confirm Password')}}" onblur="this.placeholder=''" onclick="this.placeholder='{{__('Confirm Password')}}'">
                                    @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                                <div class="text-center"><button class="btn btn_lg" type="submit"><strong>{{__('Reset Password')}}</strong></button></div>
                            @else
                                <div class="mb-20">
                                    <label><i class="fa fa-phone fa-lg"></i> {{__('phone_number_title')}}</label>
                                    <input type="text" name="mobile" value="{{ old('mobile') }}" required dir="ltr" class="input form-control @error('mobile') is-invalid @enderror" placeholder="{{__('phone_number_title')}}" onblur="this.placeholder='{{__('phone_number_title')}}'" onclick="this.placeholder=''">
                                    @error('mobile')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                                <div class="text-center"><button class="btn btn_lg" type="submit"><strong>{{__('Reset Password')}}</strong></button></div>
                            @endif
                        </form>
                        <p class="mt-3"><a href="{{ route('login',app()->getLocale()) }}">{{__('sign_in')}} </a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js')

    <script>
        function submitForm(){
            $( "#resend" ).attr("checked" , true);
            var form = document.createElement("form");
            var myForm = document.getElementById("forgot-form");
            form.submit.apply(myForm);
        }

        setTimeout(
            function() {
                $( "#resendcode" ).show();
            }, 10000);
    </script>
@endsection
