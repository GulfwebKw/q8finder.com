@extends('layouts.app')

@section('disableHeaderNavbar' , 'yes')
@section('content')
    <section class="mt-30">
        <div class="container">
            <div class="row">
                <!-- Gallery -->
                <div class="col-lg-3 mob_hide">&nbsp;</div>

                <div class="col-12 col-lg-6">
                    <div class="seach_container">
                        <h3 class="text-center">{{ __('Verify Your Email Address') }}</h3>
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
