<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class LicenseController extends Controller
{
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        if ($request['draw'] > 1){
            $pageStart = $request['start'] ;
            $pageEnd = $pageStart + $request['length'];
        }

            switch ($request['order'][0]['column']){
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
            if ($li->status == "enabled"){
                $state = "啟用";
            } else {
                $state = "停用";
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
                'actions' => "<input type=\"checkbox\" checked
                                data-toggle=\"toggle\" data-on=\"啟用\" data-off=\"停用\"
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
//        return "{\"draw\":1,\"recordsTotal\":4,\"recordsFiltered\":4,\"data\":[{\"id\":85,\"agent\":1000,\"customer_name\":\"45.77.171.14\",\"status\":\"\u555f\u7528\",\"expire_date\":\"2030-12-31\",\"mac_address\":\"eth0      Link encap:Ethernet  HWaddr 56:00:03:47:60:F8\",\"linux_info\":\"Linux testuse 2.6.32-754.35.1.el6.x86_64 #1 SMP Sat Nov 7 12:42:14 UTC 2020 x86_64 x86_64 x86_64 GNU\/Linux\",\"last_validate\":\"2021-05-10 20:11:19\",\"last_validate_ip\":\"45.77.171.14\",\"actions\":\"<input type=\"checkbox\" checked data-toggle=\"toggle\" data-on=\"\u555f\u7528\" data-off=\"\u505c\u7528\" data-onstyle=\"danger\" data-offstyle=\"secondary\" onchange =\"changeStatus(85,this)\" class=\"change-status btn-switch\" > <input type=\"button\" class=\"page-btn btn-success\" id=\"btnUpload\" value=\"\u4e0a\u50b3\" data-item-id =\"85\" onclick=\"identityUpload(85,77)\"> <input type=\"button\" class=\"page-btn btn-primary\" id=\"edit-item\" value=\"\u7de8\u8f2f\" data-item-id =\"85\"> <a href=\"http:\/\/149.28.154.196:8088\/generate-license\/85\" class=\"page-btn btn-dark\" id=\"generateLicense\" data-item-id =\"85\"> \u7522\u751f\u6388\u6b0a\u8b49\u66f8<\/a>\"},{\"id\":86,\"agent\":1000,\"customer_name\":\"47.52.40.251\",\"status\":\"\u555f\u7528\",\"expire_date\":\"2030-12-31\",\"mac_address\":\"eth0      Link encap:Ethernet  HWaddr 00:16:3E:06:60:B1\",\"linux_info\":\"Linux iZj6ci3tl66grsw4jtncjwZ 2.6.32-754.31.1.el6.x86_64 #1 SMP Wed Jul 15 16:02:21 UTC 2020 x86_64 x86_64 x86_64 GNU\/Linux\",\"last_validate\":\"2021-05-10 20:26:06\",\"last_validate_ip\":\"47.52.40.251\",\"actions\":\"<input type=\"checkbox\" checked data-toggle=\"toggle\" data-on=\"\u555f\u7528\" data-off=\"\u505c\u7528\" data-onstyle=\"danger\" data-offstyle=\"secondary\" onchange =\"changeStatus(86,this)\" class=\"change-status btn-switch\" > <input type=\"button\" class=\"page-btn btn-success\" id=\"btnUpload\" value=\"\u4e0a\u50b3\" data-item-id =\"86\" onclick=\"identityUpload(86,78)\"> <input type=\"button\" class=\"page-btn btn-primary\" id=\"edit-item\" value=\"\u7de8\u8f2f\" data-item-id =\"86\"> <a href=\"http:\/\/149.28.154.196:8088\/generate-license\/86\" class=\"page-btn btn-dark\" id=\"generateLicense\" data-item-id =\"86\"> \u7522\u751f\u6388\u6b0a\u8b49\u66f8<\/a>\"},{\"id\":87,\"agent\":40,\"customer_name\":\"45.77.171.14\",\"status\":\"\u505c\u7528\",\"expire_date\":\"2021-05-05\",\"mac_address\":\"eth0      Link encap:Ethernet  HWaddr 00:16:3E:01:9F:0C\",\"linux_info\":\"Linux CallSys 2.6.32-754.33.1.el6.x86_64 #1 SMP Tue Aug 25 15:29:40 UTC 2020 x86_64 x86_64 x86_64 GNU\/Linux\",\"last_validate\":\"2021-04-28 13:26:47\",\"last_validate_ip\":\"47.242.7.56\",\"actions\":\"<input type=\"checkbox\"  data-toggle=\"toggle\" data-on=\"\u555f\u7528\" data-off=\"\u505c\u7528\" data-onstyle=\"danger\" data-offstyle=\"secondary\" onchange =\"changeStatus(87,this)\" class=\"change-status btn-switch\" > <input type=\"button\" class=\"page-btn btn-success\" id=\"btnUpload\" value=\"\u4e0a\u50b3\" data-item-id =\"87\" onclick=\"identityUpload(87,77)\"> <input type=\"button\" class=\"page-btn btn-primary\" id=\"edit-item\" value=\"\u7de8\u8f2f\" data-item-id =\"87\"> <a href=\"http:\/\/149.28.154.196:8088\/generate-license\/87\" class=\"page-btn btn-dark\" id=\"generateLicense\" data-item-id =\"87\"> \u7522\u751f\u6388\u6b0a\u8b49\u66f8<\/a>\"},{\"id\":88,\"agent\":40,\"customer_name\":\"47.242.7.56\",\"status\":\"\u555f\u7528\",\"expire_date\":\"2021-06-01\",\"mac_address\":\"eth0      Link encap:Ethernet  HWaddr 00:16:3E:01:9F:0C\",\"linux_info\":\"Linux CallSys 2.6.32-754.33.1.el6.x86_64 #1 SMP Tue Aug 25 15:29:40 UTC 2020 x86_64 x86_64 x86_64 GNU\/Linux\",\"last_validate\":\"2021-05-10 19:09:31\",\"last_validate_ip\":\"47.242.7.56\",\"actions\":\"<input type=\"checkbox\" checked data-toggle=\"toggle\" data-on=\"\u555f\u7528\" data-off=\"\u505c\u7528\" data-onstyle=\"danger\" data-offstyle=\"secondary\" onchange =\"changeStatus(88,this)\" class=\"change-status btn-switch\" > <input type=\"button\" class=\"page-btn btn-success\" id=\"btnUpload\" value=\"\u4e0a\u50b3\" data-item-id =\"88\" onclick=\"identityUpload(88,79)\"> <input type=\"button\" class=\"page-btn btn-primary\" id=\"edit-item\" value=\"\u7de8\u8f2f\" data-item-id =\"88\"> <a href=\"http:\/\/149.28.154.196:8088\/generate-license\/88\" class=\"page-btn btn-dark\" id=\"generateLicense\" data-item-id =\"88\"> \u7522\u751f\u6388\u6b0a\u8b49\u66f8<\/a>\"}],\"re\":{\"draw\":\"1\",\"columns\":[{\"data\":\"id\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"true\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"agent\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"true\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"customer_name\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"status\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"true\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"expire_date\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"true\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"mac_address\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"true\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"linux_info\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"true\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"last_validate\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"true\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"last_validate_ip\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"true\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"actions\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}}],\"order\":[{\"column\":\"0\",\"dir\":\"asc\"}],\"start\":\"0\",\"length\":\"10\",\"search\":{\"value\":null,\"regex\":\"false\"},\"_token\":\"Me2ygtIhuRZwVKN9O6pTBMe9Fat6zfqp1vqOKhmU\"}}";
    }

    private function getDatagrid(){

    }

}
