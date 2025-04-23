@extends('backend.master')

@section('header_css')
    <link href="{{url('assets')}}/plugins/switchery/switchery.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('page_title')
    User Role
@endsection
@section('page_heading')
    Create New User Role
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">New User Role Form</h4>

                    <form class="needs-validation" method="POST" action="{{url('save/user/role')}}" enctype="multipart/form-data">
                        @csrf

                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Role Name <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Role Name" required>
                                    <div class="invalid-feedback" style="display: block;">
                                        @error('name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-sm-2 col-form-label">Role Description</label>
                                <div class="col-sm-10">
                                    <textarea id="description" name="description" class="form-control" placeholder="Role Description Here"></textarea>
                                    <div class="invalid-feedback" style="display: block;">
                                        @error('description')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>

                            @php
                                $all_permissionRoutes = App\Models\PermissionRoutes::orderBy('id', 'asc')->orderBy('route', 'asc')->count();
                                $chunkSize = ceil($all_permissionRoutes / 3);
                                $permissionRoutes1 = App\Models\PermissionRoutes::orderBy('id', 'asc')->orderBy('route', 'asc')->skip(0)->limit($chunkSize)->get();
                                $permissionRoutes2 = App\Models\PermissionRoutes::orderBy('id', 'asc')->orderBy('route', 'asc')->skip($chunkSize)->limit($chunkSize)->get();
                                $permissionRoutes3 = App\Models\PermissionRoutes::orderBy('id', 'asc')->orderBy('route', 'asc')->skip($chunkSize * 2)->limit($chunkSize)->get();

                                // Check if 'home' route exists and mark it as checked
                                $homeRoute = App\Models\PermissionRoutes::where('route', 'home')->first();
                            @endphp

                            <h4 class="card-title mb-4 mt-4">Assign Permission to this Role</h4>
                            <div class="row">
                                <div class="col-lg-4 border-right">
                                    @foreach ($permissionRoutes1 as $permissionRoute)
                                    <div class="form-group border-bottom" style="margin-bottom: .3rem;">
                                        <table>
                                            <tr>
                                                <td style="padding-right: 10px; vertical-align: middle;">
                                                    <input type="checkbox" data-size="small" @if($permissionRoute->route == 'home') checked @endif id="per{{$permissionRoute->id}}" value="{{$permissionRoute->id}}" name="permission_id[]" data-toggle="switchery" data-color="#08da82" data-secondary-color="#df3554"/>
                                                </td>
                                                
                                                <td style="padding-top: 5px; vertical-align: middle;">
                                                    <label for="per{{$permissionRoute->id}}" style="cursor: pointer">
                                                        Route: {{$permissionRoute->route}}<br>
                                                        Name: {{$permissionRoute->name}}
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="col-lg-4 border-right">
                                    @foreach ($permissionRoutes2 as $permissionRoute)
                                    <div class="form-group border-bottom" style="margin-bottom: .3rem;">
                                        <table>
                                            <tr>
                                                <td style="padding-right: 10px; vertical-align: middle;">
                                                    <input type="checkbox" data-size="small" id="per{{$permissionRoute->id}}" value="{{$permissionRoute->id}}" name="permission_id[]" data-toggle="switchery" data-color="#08da82" data-secondary-color="#df3554"/>
                                                </td>
                                                <td style="padding-top: 5px; vertical-align: middle;">
                                                    <label for="per{{$permissionRoute->id}}" style="cursor: pointer">
                                                        Route: {{$permissionRoute->route}}<br>
                                                        Name: {{$permissionRoute->name}}
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="col-lg-4">
                                    @foreach ($permissionRoutes3 as $permissionRoute)
                                    <div class="form-group border-bottom" style="margin-bottom: .3rem;">
                                        <table>
                                            <tr>
                                                <td style="padding-right: 10px; vertical-align: middle;">
                                                    <input type="checkbox" data-size="small" id="per{{$permissionRoute->id}}" value="{{$permissionRoute->id}}" name="permission_id[]" data-toggle="switchery" data-color="#08da82" data-secondary-color="#df3554"/>
                                                </td>
                                                <td style="padding-top: 5px; vertical-align: middle;">
                                                    <label for="per{{$permissionRoute->id}}" style="cursor: pointer">
                                                        Route: {{$permissionRoute->route}}<br>
                                                        Name: {{$permissionRoute->name}}
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        <div class="form-group text-center pt-3">
                            <button class="btn btn-primary m-2" type="submit"><i class="fas fa-save"></i>&nbsp; Create New Role</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer_js')
    <script src="{{url('assets')}}/plugins/switchery/switchery.min.js"></script>
    <script type="text/javascript">
        $('[data-toggle="switchery"]').each(function (idx, obj) {
            new Switchery($(this)[0], $(this).data());
        });
    </script>
@endsection