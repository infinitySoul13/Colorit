<?php

namespace App\Http\Controllers;

use App\User;
use App\Vinrequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['checkPhone', 'addName']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $users = User::orderBy('id', 'DESC')
//            ->paginate(15);
//        return view('admin.users.index', compact('users'))
//            ->with('i', ($request->get('page', 1) - 1) * 15);
        return view('admin.users.index');
    }

    public function get(Request $request)
    {
        $users = User::where('is_admin', false)->get();
        $deleted_users = User::onlyTrashed()->get();
        return response()
            ->json([
                'users' => $users,
                'deleted_users' => $deleted_users,
            ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view("admin.users.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'is_admin' => 'required',
        ]);

        $user = User::create([
            'name' => $request->get('name') ?? '',
            'email' => $request->get('email') ?? '',
            'password' => bcrypt($request->get('password')) ?? '',
            'phone' => $request->get('phone') ?? '',
            'is_admin' => $request->get('is_admin') ?? false,

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Пользователь успешно добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Promocode $promocodes
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Promocode $promocodes
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Promocode $promocodes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'is_admin' => 'required',
        ]);

        $user = User::find($id);
        $user->is_admin = $request->get("is_admin");
        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Пользователь успешно отредактирован');
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->restore();

        return response()
            ->json([
                "user" => $user,
                "message" => "Продукт восстановлен",
            ], 200);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!is_null($user))
            $user->delete();

        return response()
            ->json([
                "message" => !is_null($user) ?
                    "Пользователь успешно удален" :
                    "Данный пользователь был уже удален ранее!"
            ],200);
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->find($id);
        $user->forceDelete();

        return response()
            ->json([
                "message" => "Пользователь успешно полностью удалён",
            ], 200);
    }


    public function checkPhone($phone)
    {
        $user = User::where('phone', $phone)->first();
        if($user==null)
        {
            $user = User::create ([
                'name' => '',
                'phone' => $phone
            ]);
            return response()->json([
                "status" => 'new_user',
                "user_id" => $user->id
            ], 200);
        }
        
        $history = Vinrequest::where('user_id', $user->id)->get();
        return response()->json([
            "status" => 'old_user',
            "user_id" => $user->id
        ], 200);
    }

    public function addName(Request $request)
    {
       $user = User::where('phone', $request->phone)->first();
        if($user == null) {
            return response()->json([
                "status" => 'error'
            ], 200);
        }
        $user->update(['name'=> $request->name]);
        return response()->json([
            "status" => 'success'
        ], 200);
    }

}
