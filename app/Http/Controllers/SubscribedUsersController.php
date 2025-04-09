<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\SubscribedUsersExcel;
use App\Models\SubscribedUsers;
use Illuminate\Http\Request;
use DataTables;

class SubscribedUsersController extends Controller
{
    public function viewAllSubscribedUsers(Request $request){
        if ($request->ajax()) {

            $data = SubscribedUsers::orderBy('id', 'desc')->get();

            return Datatables::of($data)
                    ->editColumn('created_at', function($data) {
                        return date('l jS \o\f F Y h:i:s A', strtotime($data->created_at));
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                        $btn = ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" data-original-title="Delete" class="btn-sm btn-danger rounded deleteBtn"><i class="fas fa-trash-alt"></i></a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('backend.subscribed_users');
    }

    public function deleteSubscribedUsers($id){
        SubscribedUsers::where('id', $id)->delete();
        return response()->json(['success' => 'Deleted successfully.']);
    }

    public function downloadSubscribedUsersExcel(){
        return Excel::download(new SubscribedUsersExcel, 'subscribed_users.xlsx');
    }
}
