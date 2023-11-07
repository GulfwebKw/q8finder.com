<section>
    <div class="container">
        <div class="mt-10">
            <div class="row">
                <div class="col-5 col-lg-6 my_search mr10" >
                    <select class="input select_input">
                        <option>{{__('all')}}</option>
                        <template x-for="city in areas" >
                            <optgroup :label="city.name_{{ app()->getLocale() }}">
                                <template x-for="cityArea in city.areas">
                                    <option :value="cityArea.id" x-text="cityArea.name_{{ app()->getLocale() }}"></option>
                                </template>
                            </optgroup>
                        </template>
                    </select>
                </div>
                <div class="col-3 col-lg-4 my_search">
                    <select class="input select_input">
                        <option>{{__('all')}}</option>
                        <template x-for="typeItem in types">
                            <option :value="typeItem.id" x-text="typeItem.title_{{ app()->getLocale() }}"></option>
                        </template>
                    </select>
                </div>
                <div class="col-3 col-lg-2 my_search"><button class="btn btn_lg">{{__('search')}}</button></div>
            </div>
        </div>
    </div>
</section>

