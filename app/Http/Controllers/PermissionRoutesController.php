<?php

namespace App\Http\Controllers;

use App\Models\PermissionRoutes;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use DataTables;

class PermissionRoutesController extends Controller
{
    public function viewAllPermissionRoutes(Request $request){
        if ($request->ajax()) {

            $data = PermissionRoutes::orderBy('id', 'desc')->get();
            return Datatables::of($data)
                    ->editColumn('created_at', function($data) {
                        return date("Y-m-d h:i:s a", strtotime($data->created_at));
                    })
                    ->editColumn('updated_at', function($data) {
                        if($data->updated_at){
                            return date("Y-m-d h:i:s a", strtotime($data->updated_at));
                        }
                    })
                    ->addIndexColumn()
                    ->make(true);

        }
        return view('backend.role_permission.permisson_routes');
    }

    public function regeneratePermissionRoutes(){
        $routes = Route::getRoutes();
        foreach($routes as $route){

            if(str_contains($route->uri(), '_ignition') == false && str_contains($route->uri(), 'sanctum') == false && str_contains($route->uri(), 'api') == false){
                if(!PermissionRoutes::where('route', $route->uri())->exists()){
                    PermissionRoutes::insert([
                        'route' => $route->uri(),
                        'name' => $route->getName(),
                        'method' => $route->methods()[0],
                        'created_at' => Carbon::now()
                    ]);
                } else {
                    $routeInfo = PermissionRoutes::where('route', $route->uri())->first();
                    PermissionRoutes::where('id', $routeInfo->id)->update([
                        'route' => $route->uri(),
                        'name' => $route->getName(),
                        'method' => $route->methods()[0],
                        'updated_at' => Carbon::now()
                    ]);
                }
            }

        }

        Toastr::success('Permission Routes Regenerated', 'Success');
        return back();
    }
}
