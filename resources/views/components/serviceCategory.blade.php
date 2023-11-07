
<div class="card-header">
    <div class="row">
        <div class="col-sm-2">
            <a  href="#addModal" data-toggle="modal" data-target="#addModal"   class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> {{__('Add')}}</a>
        </div>
        <div class="col-sm-10">
            @if ( $parentCategory )
                <a href="/admin/service-category?parent_id={{$parentCategory->parent_id}}" class="btn btn-info" ><i class="fa fa-fw fa-backward"></i> Go back to {{ $parentCategory->title_en }}</a>
            @endif
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-sm-12">

            <div class="form-group row">
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="title_en" name="title_en" placeholder="Type Title En..." required value="{{ request()->title_en }}">
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="title_ar" name="title_ar" placeholder="Type Title Ar..." required value="{{ request()->title_ar }}">
                </div>
                <div class="col-sm-6">
                    <button type="button" class="btn btn-success" onclick="search($('#title_en').val(),$('#title_ar').val());"><i class="fa fa-fw fa-search"></i> {{__('Search Service Category')}}</button>
                    <button type="button" class="btn btn-light" onclick="refreshPage();"><i class="fa fa-fw fa-close"></i> {{__('Cancel')}}</button>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="card-body">
    <table class="table table-striped table-hover">
        <thead class="thead-light" >
        <tr>
            <th>{{__('TitleEn')}}</th>
            <th>{{__('TitleAr')}}</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="areas-list">
        @foreach($serviceCategories as $item)
            <tr>
                <td id="area-{{$item->id}}-name_en">{{$item->title_en}}</td>
                <td id="area-{{$item->id}}-name_ar">{{$item->title_ar}}</td>
                <td>
                    <a href="/admin/service-category?parent_id={{$item->id}}"><i class="fa fa-fw fa-eye text-info" title="Sub Categories" style="cursor: pointer;"></i></a>
                    <i class="fa fa-fw fa-pencil text-success" data-toggle="modal" data-target="#editModal-area-{{$item->id}}" title="Edit" style="cursor: pointer;"></i>
                    {{--<button class="btn btn-xs btn-outline-success-2x" type="button" data-toggle="modal" data-target="#editModal-area-{{$area->id}}"><i class="fa fa-fw fa-pencil"></i> Edit</button>--}}
                    &nbsp;
                    <i class="fa fa-fw fa-remove text-danger" onclick="deleteVenueType('{{$item->id}}')" title="Delete" style="cursor: pointer;"></i>
                    {{--<button class="btn btn-xs btn-outline-danger-2x" type="button" onclick="deleteArea('{{$area->id}}')"><i class="fa fa-fw fa-remove"></i> Delete</button>--}}
                    <form id="destroy-form-area-{{$item->id}}" method="post" action="{{route('serviceCategory.destroy', $item->id)}}" style="display:none">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                    </form>
                </td>

                <!-- Edit Modal -->
                <div class="modal" id="editModal-area-{{$item->id}}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{__('Edit Service Category')}}</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form method="post" id="form-{{$item->id}}" enctype="multipart/form-data" onsubmit="editArea('{{$item->id}}');return false;">
                                    @csrf
                                    <input type="hidden" value="{{request()->parent_id}}" name="parent_id" >
                                    <input type="hidden"  name="id" value="{{$item->id}}" >
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="name_en" id="area-{{$item->id}}-new-name_en" value="{{$item->title_en}}" >
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="name_ar" id="area-{{$item->id}}-new-name_ar" value="{{$item->title_ar}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <input type="file" class="form-control" name="imageFile" >
                                        </div>
                                        <div class="col-sm-6 text-center">
                                            <img id="area-{{$item->id}}-img" style="max-width: 50%;" src="{{ $item->image }}">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="editArea('{{$item->id}}')">{{__('Confirm')}}</button>
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! $serviceCategories->links() !!}

    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Add Service Category')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" enctype="multipart/form-data" action="{{route('serviceCategory.store')}}">
                    @csrf
                    <input type="hidden" value="{{request()->parent_id}}" name="parent_id" >
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="text"  placeholder="Title En..." class="form-control" required name="name_en">
                            </div>
                            <div class="col-sm-6">
                                <input type="text"  placeholder="Type Title Ar..." class="form-control" required name="name_ar">
                            </div>
                            <div class="col-sm-6">
                                <input type="file"  placeholder="Image" class="form-control" required name="imageFile">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" >{{__('Add')}}</button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>

                </form>


            </div>
        </div>
    </div>
</div>


<script>
    function editArea(id) {
        let newName = $("#area-"+id+"-new-name_en").val();
        let newNamear = $("#area-"+id+"-new-name_ar").val();
        if (!newName || !newNamear) {
            swal({"title": "Enter New Name", "icon": "warning"});
        } else {
            var fd = new FormData($("#form-"+id)[0]);
            $.ajax({
                url: "{{route('serviceCategory.update')}}",
                method: "post",
                data: fd,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false
            }).done(function (response) {
                if (response.success) {
                    $("#area-"+id+"-name_en").html(newName);
                    $("#area-"+id+"-name_ar").html(newNamear);
                    $("#editModal-area-"+id).modal("hide");
                    if ( response.img !== "") {
                        $("#area-"+id+"-img").attr("src",response.img);
                    }
                } else {
                    swal({"title": "Unable to update!", "icon": "error"});
                }
            });
        }
    }
    function deleteVenueType(id) {
        swal({
            title: "{{__('Are you sure you want to delete this item?')}}",
            text: "{{__('Be aware that deletion process is non-returnable')}}",
            icon: "warning",
            buttons: ["{{__('Cancel')}}", "{{__('Delete')}}"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                document.getElementById('destroy-form-area-'+id).submit();
            }
        });
    }
    function search(nameEn,nameAr) {
        var page="{{request()->page}}";
        var parent_id="{{request()->parent_id}}";
        var path="/admin/service-category?title_en="+nameEn+"&title_ar="+nameAr;
        if(page!=null||page!==undefined){
            path+="&page="+page;
        }
        if(parent_id!=null||parent_id!==undefined){
            path+="&parent_id="+parent_id;
        }
        window.location.href=path;
    }
    function refreshPage() {
        var path="/admin/service-category";
        var parent_id="{{request()->parent_id}}";
        if(parent_id!=null||parent_id!==undefined){
            path+="?parent_id="+parent_id;
        }
        window.location.href=path;
    }

</script>
