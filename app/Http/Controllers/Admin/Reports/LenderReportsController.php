<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\CommissionData;
use App\Models\ContactSearch;
use App\Models\Deal;
use App\Models\Lenders;
use App\Models\Products;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;

class LenderReportsController extends Controller
{
    //
    public function index(){
        return view('admin.reports.lenders.index',['Commish| Lender Report',
        ]);
    }
    public function getLenderRecords(Request $request){
        $lenders=Lenders::select('id','name');
        $pdf = PDF::loadView('admin.reports.lenders.lenders_report',['lenders'=>$lenders->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('lenders_list.pdf');
    }
    public function exportLenderRecords(Request $request){
        $lenders=Lenders::select('id','name','code');
        $pdf = PDF::loadView('admin.reports.lenders.lenders_report',['lenders'=>$lenders->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('lenders_list.pdf');
    }
    public function getLenderReconciliationRecords(Request $request){
        $lenders=Lenders::select('id','name','code')->with('deals');
        $lender_ids = $lenders->pluck('id')->toArray();
       /* return view('admin.reports.lenders.lender_reconciliation_report',['lenders'=>$lenders->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ]);*/

        $pdf = PDF::loadView('admin.reports.lenders.lender_reconciliation_report',['lenders'=>$lenders->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('lenders_commission_reconciliation.pdf');
    }
    public function exportLenderReconciliationRecords(Request $request){
        $lenders=Lenders::select('id','name','code')->with('deals');
        $pdf = PDF::loadView('admin.reports.lenders.lender_reconciliation_report',['lenders'=>$lenders->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('lenders_commission_reconciliation.pdf');
    }
    public function getTrailCommissionNotReceivedRecords(Request $request){
        // $lenders=Lenders::select('id','name','code')->with('deals');
         $lenders=Lenders::select('lenders.*', 'deals.*')->leftjoin('deals', 'deals.lender_id', 'lenders.id')->where('deals.status', 4);
         // dd($lenders->get());
        $pdf = PDF::loadView('admin.reports.lenders.trail_commission_not_received',['lenders'=>$lenders->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('trail_commission_not_received.pdf');
    }
    public function exportTrailCommissionNotReceivedRecords(Request $request){
        $lenders=Lenders::select('lenders.*', 'deals.*')->leftjoin('deals', 'deals.lender_id', 'lenders.id')->where('deals.status', 4);
        $pdf = PDF::loadView('admin.reports.lenders.trail_commission_not_received',['lenders'=>$lenders->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('trail_commission_not_received.pdf');
    }
    public function getUpfrontCommissionNotReceivedRecords(Request $request){
        $lenders=Lenders::select('lenders.*', 'deals.*')->leftjoin('deals', 'deals.lender_id', 'lenders.id')->where('deals.status', 4);
        $pdf = PDF::loadView('admin.reports.lenders.upfront_commission_not_received',['lenders'=>$lenders->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('upfront_commission_not_received.pdf');
    }
    public function exportGetUpfrontCommissionNotReceivedRecords(Request $request){
        $lenders=Lenders::select('lenders.*', 'deals.*')->leftjoin('deals', 'deals.lender_id', 'lenders.id')->where('deals.status', 4);
        $pdf = PDF::loadView('admin.reports.lenders.upfront_commission_not_received',['lenders'=>$lenders->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('upfront_commission_not_received.pdf');
    }
    public function getCommissionOtstanding(){
        $outstandings = [];
        $dealIds =  Deal::query()->pluck('id')->toArray();
            foreach ($dealIds as $dealId){
                $commissionTrail = CommissionData::query()->where(['deal_id' => $dealId, 'commission_type' => 12])->first();

                if($commissionTrail == null){
                    $outstandings[] = [$dealId, 'Trail'];
                }
                $commissionUpfront = CommissionData::query()->where(['deal_id' => $dealId, 'commission_type' => 13])->first();

                if($commissionUpfront == null){
                    $outstandings[] = [$dealId, 'Upfront'];
                }
            }
        return view('admin.reports.commission.index', compact('outstandings'));
    }
}
