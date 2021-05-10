<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $customers = DB::select('select * from customers ');

        return view('customers.index', ['customers' => $customers]);
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::insert('insert into customers (name, phone_number, email) values (?, ?, ?)'
            , [$request['new_name'], $request['new_phone_number'], $request['new_email']]);
        $customers = DB::select('select * from customers ');

        return view('customers.index', ['customers' => $customers]);
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
        DB::update('update customers set `name` = ? , `email` = ?, `phone_number` = ?
                where id = ?',
            [$request['name'],$request['email'],$request['phone_number'], $id]);
        $customers = DB::select('select * from customers ');

        return view('customers.index', ['customers' => $customers]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::delete('delete from customers where id = ?'
            , [$id]);
        $customers = DB::select('select * from customers ');

        return view('customers.index', ['customers' => $customers]);
    }
}
