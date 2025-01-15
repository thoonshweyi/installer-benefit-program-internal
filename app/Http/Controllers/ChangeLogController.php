<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromotionChangeLog;
use Yajra\DataTables\Facades\DataTables;

class ChangeLogController extends Controller
{
    protected function promotion_change()
    {
        return new PromotionChangeLog();
    }

    public function promotion_change_log(Request $request)
    {

        if ($request->ajax()) {
            $user_uuid = (!empty($_GET["user_uuid"])) ? ($_GET["user_uuid"]) : ('');
            $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
            $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
            $promotion_name = (!empty($_GET["promotion_name"])) ? ($_GET["promotion_name"]) : ('');
            $customer_phone_no = (!empty($_GET["customer_phone_no"])) ? ($_GET["customer_phone_no"]) : ('');
            $result =  $this->promotion_change();
            if ($promotion_name != "") {
                $result = $result->whereHas('promotions', function ($q) use ($promotion_name) {
                    $q->where('name', 'ilike', '%' . $promotion_name . '%');
                });
            }
            if ($start_date != "" && $end_date != "") {
                $start_date = date('Y-m-d H:i:s' ,strtotime($start_date));
                $end_date = date('Y-m-d H:i:s' ,strtotime($end_date));
                $result = $result->whereDate('date', '>=', $start_date)->whereDate('date', '<=', $end_date);

            }
            if ($start_date != "") {
                $result = $result->whereDate('date', '>=', $start_date);
            }
            if ($end_date != "") {
                $result = $result->whereDate('date', '<=', $end_date);
            }
            $result = $result->get();
            return DataTables::of($result)
                ->editColumn('user_uuid', function ($data) {
                     if (isset($data->user_uuid)) {
                    return $data->users->name;
                }
                return '';
            })
                ->editColumn('promotion_uuid', function ($data) {
                    if (isset($data->promotion_uuid)) {
                return $data->promotions->name;
            }
            return '';
            })
                ->addColumn('action', function ($data) {
                    return 'action';
                })
                ->make(true);
        }
       return view('logs.promotion_change_log');
    }

    function promotion_change_show($uuid)
        {
            $change_log = PromotionChangeLog::where('uuid', $uuid)->first();
            return view('logs.promotion_change_show',compact('change_log'));
        }
}
