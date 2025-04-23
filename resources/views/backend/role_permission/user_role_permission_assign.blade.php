@extends('backend.master')

@section('header_css')
    <link href="{{url('assets')}}/plugins/switchery/switchery.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('page_title')
    User Role Permission
@endsection
@section('page_heading')
    Assign User Role Permission
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Assign Role to this User</h4>

                    <form class="needs-validation" method="POST" action="{{url('save/assigned/role/permission')}}" enctype="multipart/form-data">
                        @csrf
                            <input type="hidden" name="user_id" value="{{$userId}}">

                            @php
                                $userRoles = App\Models\UserRole::orderBy('id', 'desc')->get();
                            @endphp

                            <div class="row">
                                <div class="col-lg-12">
                                    @foreach ($userRoles as $userRoles)
                                    @php
                                        $permissionsUnderRole = '';
                                        $rolePermissions = App\Models\RolePermission::where('role_id', $userRoles->id)->get();
                                        foreach($rolePermissions as $rolePermission){
                                            $permissionsUnderRole .= $rolePermission->route_name.", ";
                                        }
                                    @endphp
                                    <div class="form-group border-bottom">
                                        <table>
                                            <tr>
                                                <td style="padding-right: 10px; vertical-align: middle;">
                                                    <input type="checkbox" @if(App\Models\UserRolePermission::where('user_id', $userId)->where('role_id', $userRoles->id)->exists()) checked @endif data-size="small" id="role{{$userRoles->id}}" value="{{$userRoles->id}}" name="role_id[]" data-toggle="switchery" data-color="#08da82" data-secondary-color="#df3554"/>
                                                </td>
                                                <td style="padding-top: 5px; vertical-align: middle;">
                                                    <label for="role{{$userRoles->id}}" style="cursor: pointer">
                                                        {{$userRoles->name}} [ {{rtrim($permissionsUnderRole, ", ")}} ]
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    @endforeach
                                </div>
                            </div>


                            @php
                               $all_permissionRoutes = App\Models\PermissionRoutes::orderBy('id', 'asc')->orderBy('route', 'asc')->count();
                                $chunkSize = ceil($all_permissionRoutes / 3);
                                $permissionRoutes1 = App\Models\PermissionRoutes::orderBy('id', 'asc')->orderBy('route', 'asc')->skip(0)->limit($chunkSize)->get();
                                $permissionRoutes2 = App\Models\PermissionRoutes::orderBy('id', 'asc')->orderBy('route', 'asc')->skip($chunkSize)->limit($chunkSize)->get();
                                $permissionRoutes3 = App\Models\PermissionRoutes::orderBy('id', 'asc')->orderBy('route', 'asc')->skip($chunkSize * 2)->limit($chunkSize)->get();

                                // Check if 'home' route exists and mark it as checked
                                $homeRoute = App\Models\PermissionRoutes::where('route', 'home')->first();
                            @endphp

                            <h4 class="card-title mb-4 mt-4">Assign Permission to this User</h4>
                            <div class="row">
                                <div class="col-lg-4 border-right">
                                    @foreach ($permissionRoutes1 as $permissionRoute)

                                    @if($permissionRoute->route == 'home')
                                        <input type="checkbox" checked hidden id="per{{$permissionRoute->id}}" value="{{$permissionRoute->id}}" name="permission_id[]"/>
                                    @continue
                                    @endif
                                    
                                    <div class="form-group border-bottom" style="margin-bottom: .3rem;">
                                        <table>
                                            <tr>
                                                <td style="padding-right: 10px; vertical-align: middle;">
                                                    <input type="checkbox" @if($permissionRoute->route == 'home' || App\Models\UserRolePermission::where('user_id', $userId)->where('permission_id', $permissionRoute->id)->exists()) checked @endif data-size="small" id="per{{$permissionRoute->id}}" value="{{$permissionRoute->id}}" name="permission_id[]" data-toggle="switchery" data-color="#08da82" data-secondary-color="#df3554"/>
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
                                                    <input type="checkbox" @if(App\Models\UserRolePermission::where('user_id', $userId)->where('permission_id', $permissionRoute->id)->exists()) checked @endif data-size="small" id="per{{$permissionRoute->id}}" value="{{$permissionRoute->id}}" name="permission_id[]" data-toggle="switchery" data-color="#08da82" data-secondary-color="#df3554"/>
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
                                                    <input type="checkbox" @if(App\Models\UserRolePermission::where('user_id', $userId)->where('permission_id', $permissionRoute->id)->exists()) checked @endif data-size="small" id="per{{$permissionRoute->id}}" value="{{$permissionRoute->id}}" name="permission_id[]" data-toggle="switchery" data-color="#08da82" data-secondary-color="#df3554"/>
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
                            <button class="btn btn-primary m-2" type="submit"><i class="fas fa-save"></i>&nbsp; Assign Role Permission</button>
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