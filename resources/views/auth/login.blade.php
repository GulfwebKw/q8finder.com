@extends('site.layout.master')
@section('title' , __('sign_up_title'))
@section('disableHeaderNavbar' , 'yes')
@section('content')
    <section class="mt-30">
        <div class="container">
            <div class="row">
                <!-- Gallery -->
                <div class="col-lg-3 mob_hide">&nbsp;</div>

                <div class="col-12 col-lg-6">
                  <div class="mdc-tab-bar-wrapper centered pricing-tabs">
                        <ul  class="d-flex justify-content-center nav" role="tablist" style="column-gap: 20px;">
                            <li role="presentation" style="cursor: pointer;" class="menuTab active p-3 rounded" onclick="$('.tab-pane').hide();$('#payasyougo').show();$('.menuTab').removeClass('active');$(this).addClass('active');">{{__('sign_in')}}</li>
                            <li role="presentation" style="cursor: pointer;" class="menuTab p-3 rounded" onclick="$('.tab-pane').hide();$('#longtermsubscribe').show();$('.menuTab').removeClass('active');$(this).addClass('active');">{{__('sign_up')}}</li>
                        </ul>
                    <div class="tab-content">
                   <!--start register-->
                   <div class="tab-pane" style="display: none;" id="longtermsubscribe">
                    <div class="seach_container">
                        
                        <form method="post" id="register-form" action="{{ route('register',app()->getLocale()) }}">
                            @csrf
                            {{--
                            @if ( old('codeValidation' , false ) )
                                <input type="hidden" name="type-usage" value="individual">
                                <input type="hidden" name="name" value="{{ old("name") }}">
                                <input type="hidden" name="mobile" value="{{ old("mobile") }}">
                                <input type="hidden" name="email" value="{{ old("email") }}">
                                <input type="hidden" name="password" value="{{ old("password") }}">
                                <input type="hidden" name="password_confirmation" value="{{ old("password_confirmation") }}">
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

                                <div class="w-100 py-3 text-center" style="min-width: 35vw;">
                                    <button class="btn btn_lg" type="submit">{{__('sign_up_title')}}</button>
                                </div>
                            @else

                            --}}

                                <div class="mb-20">
                                    <label><i class="fa fa-phone fa-lg"></i> {{__('phone_number_title')}}</label>
                                    <input type="text" name="mobile" value="{{ old('mobile') }}" dir="ltr" class="input form-control @error('mobile') is-invalid @enderror" placeholder="{{__('phone_number_title')}}" onblur="this.placeholder='{{__('phone_number_title')}}'" onclick="this.placeholder=''">
                                    @error('mobile')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-20">
                                    <label><i class="fa fa-envelope fa-lg"></i> {{__('your_email')}}</label>
                                    <input type="email" name="email" value="{{ old('email') }}" dir="ltr" class="input form-control @error('email') is-invalid @enderror" placeholder="{{__('your_email')}}" onblur="this.placeholder=''" onclick="this.placeholder='{{__('your_email')}}'">
                                    @error('email')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
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

                                <div class="text-center"><button class="btn btn_lg" type="submit"><strong>{{__('sign_up_title')}}</strong></button></div>
                            {{-- @endif --}}
                        </form>
                    </div>
                    </div>
                    <!--end register-->
                    <!--start login -->
                    <div class="tab-pane" style="display:block;" id="payasyougo">
                    <div class="seach_container">
                        
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
                     </div>
                    <!--end login -->
                    </div> 
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
            var myForm = document.getElementById("register-form");
            form.submit.apply(myForm);
        }

        setTimeout(
            function() {
                $( "#resendcode" ).show();
            }, 10000);
    </script>
@endsection
