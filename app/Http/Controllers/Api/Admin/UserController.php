<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        // get users
        $users = User::when(request()->q, function($users) {
            $users = $users->where('name', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);

        // return with Api Resource
        return new UserResource(true, 'List Data Users', $users);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if($user) {
            // return success with Api Resource
            return new UserResource(true, 'Data User Berhasil Disimpan', $user);
        }

        // return failed with Api Resource
        return new UserResource(false, 'Data User Gagal Disimpan', null);
    }

    public function show($id) {
        $user = User::whereId($id)->first();

        if($user){
            return new UserResource(true, 'Data User Berhasil Ditampilkan', $user);
        }

        return new UserResource(false, 'Data User Gagal Ditampilkan', null);

    }

    public function update(Request $request, User $user) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users, email,'.$user->id,
            'password' => 'confirmed'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if($request->password == "") {
            // update user without password
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            // kenapa harus dipisah - pisah, kenapa harus membuat update tanpa password dan dengan password
        } else {
            // update user with new password
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
        }

        if($user) {
            return new UserResource(true, 'Data User Berhasil Diupdate!', $user);
        }

        return new UserResource(false, 'Data User Gagal Diupdate!', null);
    }

    // kenapa langsung ke model bukan menggukana $id?
    public function destroy(User $user) {
        if($user->delete()) {
            // return success Api With Resource
            return new UserResource(true, 'Data User Berhasil Dihapus', null);
        }

        return new UserResource(false, 'Data User Gagal Dihapus!', null);
    }

}
