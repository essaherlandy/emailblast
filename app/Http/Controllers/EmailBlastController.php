<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\SendEmail;
use App\MemberLogin;
use App\Mail\SendEmailBlast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmailBlastController extends Controller
{
    public function index()
    {
        $getMembers = DB::select("SELECT nama_database FROM member_login WHERE nama_database != '' GROUP BY nama_database");
        return view('send-email',['getMembers' => $getMembers]);
    }

    public function sendEmail(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'title'                 => 'required',
            'description'           => 'required',
            'club'                  => 'required',
        ],[
            'title.required'         => 'Judul event tidak boleh kosong',
            'description.required'   => 'Deskripsi tidak boleh kosong',
            'club.required'          => 'Club tidak boleh kosong',
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }
        if($request->attachment == ''){
            $user_profiles                      = new SendEmail();
            $user_profiles->title               = $request->title;
            $user_profiles->description         = $request->description;
            $user_profiles->club                = $request->club;
            $user_profiles->attachment          = '';
            $user_profiles->save();

            $data = [
                'title'         => $request->title,
                'description'   => $request->description,
                'club'          => $request->club,
                'attachment'    => ''
            ];

            if($user_profiles){
                $userArray = MemberLogin::query()
                ->when($request->club != "all", function($query){
                return $query->where('nama_database', $request->club);
                })->get()->toArray();
                dd($userArray);
                foreach($userArray as $user){
                    $message->to($user['email'])->subject('Welcome!')->attach(public_path($this->data['attachment']));
                }
                Mail::to($user->email)->send(new SendEmailBlast($data));
                return redirect()->route('send-email')->with('success','Email berhasil dikirim');
            }else{
                return redirect()->route('send-email')->with('error','Email berhasil dikirim');
            }
        }else{
            $AttachmentFilePath          = public_path()."/attachment/";
            $NewAttachment               = $request->attachment;
            $NewAttachmentExtension      = $NewAttachment->extension();

            if($request->hasFile('attachment'))
            {
                $file = $request->attachment;
                $extension = $file->getClientOriginalExtension();
            }
            $AttachmentFileName       = "/attachment/".'AM-'.strtotime(date('Y-m-d H:i:s')).'.'.$NewAttachmentExtension;

            $NewAttachment->move($AttachmentFilePath, $AttachmentFileName);

            $user_profiles                      = new SendEmail();
            $user_profiles->title               = $request->title;
            $user_profiles->description         = $request->description;
            $user_profiles->club                = $request->club;
            $user_profiles->attachment          = $AttachmentFileName;
            $user_profiles->save();

            $data = [
                'title'         => $request->title,
                'description'   => $request->description,
                'club'          => $request->club,
                'attachment'    => $AttachmentFileName
            ];

            if($user_profiles){
                $userArray = DB::table('member_login')->when($request->club != 'all', function ($q) use($request){
                    return $q->where('nama_database', $request->club);
                })
                ->get()->toArray();

                foreach($userArray as $user){
                    Mail::to($user->email)->send(new SendEmailBlast($data));
                }
                
                return redirect()->route('send-email')->with('success','Email berhasil dikirim');
            }else{
                return redirect()->route('send-email')->with('error','Email berhasil dikirim');
            }
        }

    }
}
