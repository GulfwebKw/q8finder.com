@error('fail')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <strong>{{__('un_success_title')}}!</strong> {!! $message !!} !
                    </div>
                </div>
            </div>
        </div>
    </section>
@enderror
@if((session('controller-success')))
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success">
                        <strong>{{__('success_title')}}!</strong> {{session('controller-success')}} !
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
