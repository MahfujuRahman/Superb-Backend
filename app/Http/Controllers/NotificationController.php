<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class NotificationController extends Controller
{
    public function sendNotificationPage(){
        return view("backend.push_notification.send");
    }

    public function sendPushNotification(Request $request){

        $serverKey = $request->server_key;
        $fcmUrl = $request->fcm_url;
        $topic = $request->topic;
        $title = $request->title;
        $description = $request->description;


        //push notification start
        $notification = [
            'title' => $topic,
            'body' => $description,
            'image' => '',
        ];

        $fcmNotification = [
            'to' => $topic,
            'notification' => $notification,
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        //push notification end

        Notification::insert([
            'server_key' => $serverKey,
            'fcm_url' => $fcmUrl,
            'topic' => $topic,
            'title' => $title,
            'description' => $description,
            'created_at' => Carbon::now()
        ]);

        Toastr::success('Notification has Sent', 'Successfully');
        return back();

    }

    public function viewAllNotifications(Request $request){
        if ($request->ajax()) {

            $data = Notification::orderBy('id', 'desc')->get();

            return Datatables::of($data)
                    ->editColumn('created_at', function($data) {
                        return date("Y-m-d h:i:s a", strtotime($data->created_at));
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" data-original-title="Delete" class="btn-sm btn-danger rounded deleteBtn"><i class="fas fa-trash-alt"></i></a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
        }

        return view('backend.push_notification.view');
    }

    public function deleteNotification($id){
        Notification::where('id', $id)->delete();
        return response()->json(['success' => 'Notification Deleted Successfully.']);
    }

    public function deleteNotificationRangeWise(){

        $currentDate = date("Y-m-d H:i:s");
        $prevDate = date('Y-m-d', strtotime('-15 day', strtotime($currentDate)));
        Notification::where('created_at', '<=', $prevDate)->delete();
        Toastr::error('Notifications are Deleted', 'Successful');
        return back();

    }
}
