<?php

namespace App\Http\Controllers;

use App\Models\Key;
use App\Models\L;
use App\Models\S;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Storage;

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

        if ($customers == null || $customers == "") return redirect()->back()->with('alert', 'new_customer_id error!!');
        DB::insert('insert into licenses (customer_id, agent, expire_date, created_user_id, last_updated_user_id,
                        linux_info, mac_address, linux_date, last_validate, last_validate_ip, created_at, updated_at)
                        values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            , [$request['new_customer_id'], $request['new_agent'], $request['new_expire_date'],
                Auth::id(), Auth::id(),
                null, null, null,
                Carbon::now()->toDateTimeString(), $customers[0]->name, Carbon::now()->toDateTimeString(),
                Carbon::now()->toDateTimeString()]);
        return redirect()->back()->with('alert', 'success!!');
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
                                data-item-id =\"$li->id\"> <a href=\"/generate-license/$li->id\"
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

        $licenses = DB::select('select * from licenses where id = ?', [$id]);

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

        if ($licenses[0]->customer_id != $cusId) {
            return redirect()->back()->with('alert', 'customerId is error!');
        }

        if ($request['upload_customer_id'] == $cusId) {

            DB::table('licenses')
                ->where('id', $id)
                ->update(['customer_id' => $cusId, 'agent' => $agent,
                    'expire_date' => $expire, 'last_updated_user_id' => Auth::id(),
                    'linux_info' => $LinuxInfo, 'mac_address' => $MacAddress, 'linux_date' => $linuxDateStr,
                    'last_validate' => Carbon::now()->toDateTimeString(), 'last_validate_ip' => $customerName
                    , 'updated_at' => Carbon::now()->toDateTimeString()]);

            return redirect()->back()->with('alert', 'success!!');
        } else {
            return redirect()->back()->with('alert', 'error!');
        }

    }

    // 產生授權證書
    public function generateLicense(Request $request, $id)
    {

        $file = public_path() . "/storage/app/rowave.license";
        $headers = [
            'Content-Type: application/pdf',
        ];

        $lis = DB::table('licenses')
            ->join('customers', 'licenses.customer_id', '=', 'customers.id')
            ->select('licenses.*', 'customers.name')
            ->where('licenses.id', '=', $id)
            ->get();

        // put contenxt
        $pKey = self::loadPubKey();
        $s = new S(array('b' => self::rsaPubEncrypt($lis[0]->linux_info, $pKey),
            'c' => self::rsaPubEncrypt($lis[0]->mac_address, $pKey),
            'd' => self::rsaPubEncrypt($lis[0]->linux_date, $pKey)));
        $l = new L(array('agent' => self::rsaPubEncrypt($lis[0]->agent, $pKey),
            'expire' => self::rsaPubEncrypt($lis[0]->expire_date, $pKey),
            'customerId' => self::rsaPubEncrypt($lis[0]->customer_id, $pKey),
            'licenseId' => self::rsaPubEncrypt($lis[0]->id, $pKey),
            'customerName' => self::rsaPubEncrypt($lis[0]->name, $pKey)));
        $final = new Key(array('l' => $l, 's' => $s));

        $contents = base64_encode(base64_encode(json_encode(($final))));
        Storage::put(
            'rowave.license',
            $contents
        );

        return response()->download('../storage/app/rowave.license', 'rowave.license', $headers);
    }

    public function getLicenseStatus(Request $request)
    {
        $contents = $request->getContent();
        if ($contents == null) {
            $data = [
                "success" => "false",
                "msg" => "File error!"
            ];

            return response()->json($data);
        }

        $temp = base64_decode($contents);
        $jStr = trim($temp, "# # # # # #");
        $final = json_decode($jStr, true);

        $privateKey = self::loadPrivateKey("../private1.key");
        $licenseId = self::rsaPrivateDecrypt($final['license']['licenseId'], $privateKey);
        $customer_id = self::rsaPrivateDecrypt($final['license']['customerId'], $privateKey);

        // 檢核
        $licenses = DB::select('select * from licenses where id = ?', [$licenseId]);
        if ($licenses == null) {
            $data = [
                "success" => "false",
                "msg" => "licenseId not exist!!"
            ];

            return response()->json($data);
        }

        if ($final['key'] == null || $customer_id == null) {
            $data = [
                "success" => "false",
                "msg" => "license_key or customer_id is necessary"
            ];

            return response()->json($data);
        }

        $tag = DB::insert('insert into `keys` (license_key, customer_id, ip, created_at, updated_at) values (?, ?, ?, ?, ?)'
            , [$final['key'], $customer_id,
                $request->ip(), Carbon::now()->toDateTimeString(), Carbon::now()->toDateTimeString()]);

        if ($tag) {
            $data = [
                "success" => "true",
                "msg" => "success"
            ];

            return response()->json($data);
        } else {
            $data = [
                "success" => "false",
                "msg" => "Insert database error!"
            ];

            return response()->json($data);
        }

    }
    //========================================================================================================================

    // load private key
    public function loadPrivateKey($path)
    {
        $fp = fopen($path, 'r');
        $key = fread($fp, 8192);

        return $key;
    }


    // 私鑰解密
    public function rsaPrivateDecrypt($encrypt, $privateKey)
    {
        $encrypt = base64_decode($encrypt);
        openssl_private_decrypt($encrypt, $decrypt, $privateKey);

        return $decrypt;
    }

    public function rsaPrivateDecryptOnlyKey($encrypt, $privateKey)
    {
        openssl_private_decrypt($encrypt, $decrypt, $privateKey);

        return $decrypt;
    }

    // 讀取公鑰
    public function loadPubKey($path = "../pub.pem")
    {
        $fp = fopen($path, 'r');
        $key = fread($fp, 8192);
        if (stripos($key, "BEGIN PUBLIC KEY") == false) {
            $key = "-----BEGIN PUBLIC KEY-----\n" . $key;
        }
        if (stripos($key, "END PUBLIC KEY") == false) {
            $key = $key . "\n-----END PUBLIC KEY-----";
        }
        fclose($fp);
        return $key;
    }

    // 公鑰加密 所有欄位
    public function rsaPubDecrypt($encrypt, $key)
    {
        $encrypt = base64_decode($encrypt);
        openssl_public_decrypt($encrypt, $decrypt, $key);
        return $decrypt;
    }

    public function rsaPubEncrypt($source, $key)
    {
        openssl_public_encrypt($source, $encrypt, $key);
        return base64_encode($encrypt);
    }

    public function csrf(Request $request)
    {
        echo csrf_token();
    }

}
