@extends('layouts.admin', ['crumbs' => [
    'Settings' => route('serviceCategory')],
    'title' => 'Service Category'
])
@section('content')
    <div class=" col-md-12">
        <div class="card">
            <div class="card-body">
            @component('components.serviceCategory',['serviceCategories'=>$serviceCategories,'parentCategory'=>$parentCategory])@endcomponent
            </div>
        </div>
    </div>

@endsection
