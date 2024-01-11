@extends('site.layout.master')
@section('title' , __('remove_account'))
@section('content')

<main class="content-offset-to-top">
    <div class="px-3 mt-7">
        <div class="theme-container">
            <div class="mdc-card main-content-header mb-5 sec-min-h ">
                <div class="row">
                    <div class="col-12 text-center mb-3" style="color: red;">
                        <i class="fa fa-warning fa-4x"></i>
                    </div>
                    <div class="col-12">
                        {{ __('remove_account_description') }}
                    </div>
                    <div class="col-6 mt-5">

                        <a href="{{ route('Main.profile',app()->getLocale()) }}" class="btn btn-success mx-3" data-dismiss="modal">{{__('close')}}</a>

                    </div>
                    <div class="col-6 mt-5">
                        <form action="{{ route('User.deleteUser',app()->getLocale()) }}" method="POST">
                            @method('delete')
                            @csrf
                            <button type="submit" class="btn btn-danger">{{__('remove_account')}}</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
