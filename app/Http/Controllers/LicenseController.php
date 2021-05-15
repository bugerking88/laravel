<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Storage;
use App\Tools\Rsa;

class LicenseController extends Controller
{
    const PRIVATE_KEY = '../private.key';
    const PRIVATE_KEY1 = '../private1.key';
    const PUBLIC_KEY = '../public.pem';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $customers = DB::select('select * from customers ');

        return view('licenses.index', ['customers' => $customers]);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $customers = DB::select('select * from customers where id = ?', [$request['new_customer_id']]);

        if ($customers == null || $customers == "" ) return redirect()->back() ->with('alert', 'new_customer_id error!!');
        DB::insert('insert into licenses (customer_id, agent, expire_date, created_user_id, last_updated_user_id,
                        linux_info, mac_address, linux_date, last_validate, last_validate_ip, created_at, updated_at)
                        values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            , [$request['new_customer_id'], $request['new_agent'], $request['new_expire_date'],
                Auth::id(), Auth::id(),
                null, null, null,
                Carbon::now()->toDateTimeString(), $customers[0] -> name, Carbon::now()->toDateTimeString(),
                Carbon::now()->toDateTimeString()]);
        return redirect()->back() ->with('alert', 'success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {

        if ($request['status'] != null) {
            DB::update('update licenses set `status` = ?
                where id = ?',
                [$request['status'], $id]);
        }

        if ($request['agent'] != null && $request['expire_date'] != null) {
            DB::update('update licenses set `agent` = ?, `expire_date` = ?
                where id = ?',
                [$request['agent'], $request['expire_date'], $id]);
            return back();
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getData(Request $request)
    {
        $allNum = count(DB::select('select * from licenses '));
        $pageStart = 0;
        $pageEnd = $request['length'];
        if ($request['draw'] > 1) {
            $pageStart = $request['start'];
            $pageEnd = $pageStart + $request['length'];
        }

        switch ($request['order'][0]['column']) {
            case 0:
                $lis = DB::table('licenses')
                    ->join('customers', 'licenses.customer_id', '=', 'customers.id')
                    ->select('licenses.*', 'customers.name')
                    ->skip($pageStart)->take($pageEnd)
                    ->orderBy('id', $request['order'][0]['dir'])->get();

                break;
            case 1:
                $lis = DB::table('licenses')
                    ->join('customers', 'licenses.customer_id', '=', 'customers.id')
                    ->select('licenses.*', 'customers.name')
                    ->skip($pageStart)->take($pageEnd)
                    ->orderBy('agent', $request['order'][0]['dir'])->get();
                break;
            case 3:
                $lis = DB::table('licenses')
                    ->join('customers', 'licenses.customer_id', '=', 'customers.id')
                    ->select('licenses.*', 'customers.name')
                    ->skip($pageStart)->take($pageEnd)
                    ->orderBy('status', $request['order'][0]['dir'])->get();
                break;
            case 4:
                $lis = DB::table('licenses')
                    ->join('customers', 'licenses.customer_id', '=', 'customers.id')
                    ->select('licenses.*', 'customers.name')
                    ->skip($pageStart)->take($pageEnd)
                    ->orderBy('expire_date', $request['order'][0]['dir'])->get();
                break;

            case 7:
                $lis = DB::table('licenses')
                    ->join('customers', 'licenses.customer_id', '=', 'customers.id')
                    ->select('licenses.*', 'customers.name')
                    ->skip($pageStart)->take($pageEnd)
                    ->orderBy('last_validate', $request['order'][0]['dir'])->get();
                break;
            case 8:
                $lis = DB::table('licenses')
                    ->join('customers', 'licenses.customer_id', '=', 'customers.id')
                    ->select('licenses.*', 'customers.name')
                    ->skip($pageStart)->take($pageEnd)
                    ->orderBy('last_validate_ip', $request['order'][0]['dir'])->get();
                break;
        }

        $tagIdArray = collect($lis)->map(function ($li) {
            $state = "";
            if ($li->status == "enabled") {
                $state = "啟用";
            } else {
                $state = "停用";
            }

            $check = "";
            if ($li->status == "enabled") {
                $check = "checked";
            }

            $url = env('APP_URL', '');
            $o = (object)[
                'id' => $li->id,
                'agent' => $li->agent,
                'customer_name' => $li->name,
                'status' => $state,
                'expire_date' => $li->expire_date,
                'mac_address' => $li->mac_address,
                'linux_info' => $li->linux_info,
                'last_validate' => $li->last_validate,
                'last_validate_ip' => $li->last_validate_ip,
                'actions' => "<input type=\"checkbox\" $check
                                data-toggle=off data-on=\"啟用\" data-off=\"停用\"
                                data-onstyle=\"danger\" data-offstyle=\"secondary\"
                                onchange =\"changeStatus($li->id,this)\" class=\"change-status btn-switch\" >
                                <input type=\"button\" class=\"page-btn btn-success\" id=\"btnUpload\" value=\"上傳\"
                                data-item-id =\"$li->id\" onclick=\"identityUpload($li->id,$li->customer_id)\">
                                <input type=\"button\" class=\"page-btn btn-primary\" id=\"edit-item\" value=\"編輯\"
                                data-item-id =\"$li->id\"> <a href=\"$url/generate-license/$li->id\"
                                 class=\"page-btn btn-dark\" id=\"generateLicense\" data-item-id =\"$li->id\"> 產生授權證書</a>",
            ];

            return $o;
        })->all();

        $data = [
            "data" => $tagIdArray,
            "recordsTotal" => $allNum,
            "length" => $allNum,
            "recordsFiltered" => $allNum,
            "draw" => $request['draw']
        ];

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function identityUpload(Request $request, $id)
    {
        Storage::put(
            'avatars/' . $id,
            file_get_contents($request->file('identity')->getRealPath())
        );
        $contents = Storage::get("avatars/$id");
        $temp = base64_decode(base64_decode($contents));
        $jStr = trim($temp, "# # # # # #");
        $final = json_decode($jStr, true);

        $privateKey = self::loadPrivateKey("../private1.key");

        $cusId = self::rsaPrivateDecrypt($final['l']['customerId'], $privateKey);
        $agent = self::rsaPrivateDecrypt($final['l']['agent'], $privateKey);
        $expire = self::rsaPrivateDecrypt($final['l']['expire'], $privateKey);
        $customerName = self::rsaPrivateDecrypt($final['l']['customerName'], $privateKey);
        $LinuxInfo = self::rsaPrivateDecrypt($final['s']['b'], $privateKey);
        $MacAddress = self::rsaPrivateDecrypt($final['s']['c'], $privateKey);
        $linuxDateStr = new DateTime(self::rsaPrivateDecrypt($final['s']['d'], $privateKey));

        if ($id != $cusId) {
            return redirect()->back() ->with('alert', 'customerId is error!');
        }

        if ($request['upload_customer_id'] == $cusId) {
            DB::update ('update licenses set `customer_id` = ?, `agent` = ?, `expire_date` = ?,
                        `last_updated_user_id` = ?, `linux_info` = ?, `mac_address` = ?,
                         `linux_date` = ? , `last_validate` = ? , `last_validate_ip` = ?
                         , `updated_at` = ? '
                , [$cusId, $agent, $expire,
                    Auth::id(), $LinuxInfo, $MacAddress, $linuxDateStr,
                    Carbon::now()->toDateTimeString(), $customerName, Carbon::now()->toDateTimeString()]);
            return redirect()->back() ->with('alert', 'success!!');
        } else {
            return redirect()->back() ->with('alert', 'error!');
        }

    }

    // load private key
    public function loadPrivateKey($path)
    {
        $fp = fopen($path, 'r');
        $key = fread($fp, 8192);

        return $key;
    }


    public function rsaPrivateDecrypt($encrypt, $privateKey)
    {
        $encrypt = base64_decode($encrypt);
        openssl_private_decrypt($encrypt, $decrypt, $privateKey);

        return $decrypt;
    }


}
