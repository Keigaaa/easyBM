<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Folder;



class AdminController extends Controller
{

    public function index()
    {
        if (Auth::user()->is_admin) {
            return view('admin/index');
        } else {
            return "Vous devez être administrateur pour accéder à cette page !";
        }
    }

    public function listUser()
    {
        $users = User::all();
        if (Auth::user()->is_admin) {
            return view("admin/manageuser")->with('users', $users);
        } else {
            return "Vous devez être administrateur pour accéder à cette page !";
        }
    }

    public function createUser()
    {
        if (Auth::user()->is_admin) {
            return view("admin/createuser");
        } else {
            return "Vous devez être administrateur pour accéder à cette page !";
        }
    }


    public function postCreateUser(Request $request)
    {
        if (Auth::user()->is_admin) {
            $input = $request->all();
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique(User::class),
                ],

            ])->validate();

            $user = User::create(['isadmin' => false, "name" => $input['name'], "email" => $input['email'], "password" => Hash::make($input['password'])]);
            return redirect("admin/manageuser");
        } else {
            return "Vous devez être administrateur pour accéder à cette page !";
        }
    }

    public function deleteUser(User $user)
    {
        if (Auth::user()->is_admin) {
            $folders = DB::table('folders')
                ->where('idOwnerFolder', '=', $user->id)
                ->delete();
            $bookmarks = DB::table('bookmarks')
                ->where('idOwnerBookmark', '=', $user->id)
                ->delete();
            $user->delete();
            return redirect('admin/manageuser');
        } else {
            return "Vous devez être administrateur pour accéder à cette page !";
        }
    }

    public function adminUser(User $user)
    {
        if (Auth::user()->is_admin) {
            $user->is_admin = true;
            $user->save();
            return redirect('admin/manageuser');
        } else {
            return "Vous devez être administrateur pour accéder à cette page  !";
        }
    }
}
