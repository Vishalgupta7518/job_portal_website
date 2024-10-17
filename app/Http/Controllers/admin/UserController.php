<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::orderBy('created_at','DESC')->paginate(10);

        return view('admin.users.list',['users' => $users]);
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('admin.users.edit',['user' => $user]);
    }

    public function update(Request $req,$id){

        $validator = Validator::make($req->all(),[
            'name' =>'required|min:5|max:20',
            'email'=>'required|email|unique:users,email,'.$id.',id',
        ]);

        if($validator->passes()){
            $user = User::find($id);
            $user->name = $req->name;
            $user->email = $req->email;
            $user->designation = $req->designation;
            $user->mobile = $req->mobile;
            $user->save();

            session()->flash('success','User information updated Successfully.');

            return response()->json([
                'status' => true,
                'errors' =>[],
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function destroy(Request $req){
        $id = $req->id;
        $user = User::find($id);

        if ($user == null) {
            session()->flash('error','User not found.');
            return response()->json([
                'status' => false,
            ]);
        }

        $user->delete();
        session()->flash('success','User deleted successfully.');
        return response()->json([
            'status' => true,
        ]);
    }
}
