<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $users;

    public function __construct(User $users)
    {
        $this->users = $users;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::select('select * from users ');

        return view('users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function store(Request $request)
    {
        DB::insert('insert into users (name, username, email, password) values (?, ?, ?, ?)'
            , [$request['username'], $request['username'], $request['email'], Hash::make($request['password'])]);
        $users = DB::select('select * from users ');

        return view('users.index', ['users' => $users]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::update('update users set `name` = ? , `email` = ?, `phone_number` = ?
                where id = ?',
            [$request['name'],$request['email'],$request['phone_number'], $id]);
        $users = DB::select('select * from users ');

        return view('users.index', ['users' => $users]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::delete('delete from users where id = ?'
            , [$id]);
        $users = DB::select('select * from users ');

        return view('users.index', ['users' => $users]);
    }

    public function changePassword(Request $request)
    {
        $id = Auth::id();
        DB::update('update users set `password` = ?
                where id = ?',
            [Hash::make($request['password']), $id]);

        return view('home');
    }
}
