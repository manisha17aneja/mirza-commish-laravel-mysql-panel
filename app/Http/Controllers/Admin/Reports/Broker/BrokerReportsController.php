<?php

namespace App\Http\Controllers\Admin\Reports\Broker;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\CommissionData;
use App\Models\Deal;
use App\Models\DealCommission;
use App\Models\Lenders;
use App\Models\Products;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
class BrokerReportsController extends Controller
{
    //
    public function index(){
        $brokers=array();
        $lenders=array();
        $products=array();
        $brokers=Broker::where('type',1)->get();
        if(isset($_GET['report_type'])&&$_GET['report_type']=='performance_report'){
            $lenders=Lenders::all();
            $products=Products::all();
        }
        return view('admin.reports.brokers.index',['Commish| Broker Report',
            'brokers'=>$brokers,
            'lenders'=>$lenders,
            'products'=>$products,
            ]);
    }
    public function brokersList(){

    }
    public function exportBrokersList(){

    }
    public function referrerCommissionSummary(){

    }
    public function getPerformanceRecords(Request $request){
        $deal=Deal::select('deals.*')->with(['lender','broker','product'])
            ->whereIn('status',[1,2,3,4,5]);
        if($request['group_by']=='Product'){
            $deal->groupBy('product_id');
        }else if($request['group_by']=='Broker'){
            $deal->groupBy('broker_id');
        }else if($request['group_by']=='BrokerStaff'){
            $deal->groupBy('broker_staff_id');
        }else if($request['group_by']==='Status'){
            $deal->groupBy('status');
        }else{
            $deal->groupBy('lender_id');
        }
        if(!empty($request['from_date'])){
            $deal->where('created_at','>=', date('Y-m-d H:i:s',strtotime($request['from_date'].' 00:00:00')));
        }if(!empty($request['to_date'])){
            $deal->where('created_at','<=', date('Y-m-d H:i:s',strtotime($request['to_date'].' 23:59:59')));
        }

        $pdf=PDF::loadView('admin.reports.brokers.performance_report',[
            'deals'=>$deal->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date'],
            'group_by'=>$request['group_by']
        ])
            ->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('performance_report.pdf');

    }
    public function exportPerformanceRecords(Request $request){
        $deal=Deal::select('deals.*')->with(['lender','broker','product'])
            ->whereIn('status',[1,2,3,4,5]);
        if($request['group_by']=='Product'){
            $deal->groupBy('product_id');
        }else if($request['group_by']=='BrokerStaff'){
            $deal->groupBy('broker_staff_id');
        }else if($request['group_by']==='Status'){
            $deal->groupBy('status');
        }else{
            $deal->groupBy('lender_id');
        }
        if(!empty($request['from_date'])){
            $deal->where('created_at','>=', date('Y-m-d H:i:s',strtotime($request['from_date'].' 00:00:00')));
        }if(!empty($request['to_date'])){
            $deal->where('created_at','<=', date('Y-m-d H:i:s',strtotime($request['to_date'].' 23:59:59')));
        }
        $pdf=PDF::loadView('admin.reports.brokers.performance_report',['deals'=>$deal->get()])
            ->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('performance_report.pdf');

    }
    public function getBrokerInvoice(Request $request){
       $brokers=DealCommission::whereHas('deal')->whereHas('deal.broker')->with(['deal','deal.broker','deal.broker.city_info','deal.broker.state_info'])
           ->join('deals','deals.id','=','deal_commissions.deal_id')
           ->groupBy('deals.broker_id');
        if(!empty($request['from_date'])){
            $brokers->where('bro_amt_date_paid','>=', date('Y-m-d',strtotime($request['from_date'])));
        }if(!empty($request['to_date'])){
            $brokers->where('bro_amt_date_paid','<=', date('Y-m-d',strtotime($request['to_date'])));
        }
        if(!empty($request['broker_id'])){
            $brokers->where('deal_id', '>', 0)->whereHas('deal',function ($q)use($request){
                $q->where('broker_id',$request['broker_id']);
            });
        }

        $pdf=PDF::loadView('admin.reports.brokers.broker_invoice',[
            'brokers'=>$brokers->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date'],
            ])
            ->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('broker_invoice.pdf');

    }
    public function exportBrokerInvoice(Request $request){
        $brokers=DealCommission::whereHas('deal')->whereHas('deal.broker')->with(['deal','deal.broker','deal.broker.city_info','deal.broker.state_info'])->groupBy('broker_id');
        if(!empty($request['from_date'])){
            $brokers->where('settlement_date','>=', date('Y-m-d',strtotime($request['from_date'])));
        }if(!empty($request['to_date'])){
            $brokers->where('settlement_date','<=', date('Y-m-d',strtotime($request['to_date'])));
        }
        if(!empty($request['broker_id'])){
            $brokers->whereHas('deal',function ($q)use($request){
                $q->where('broker_id',$request['broker_id']);
            });
        }
        $pdf=PDF::loadView('admin.reports.brokers.broker_invoice',[
            'brokers'=>$brokers->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date'],
        ])
            ->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('broker_invoice.pdf');

    }
    public function getCommissionMonitoringReport(Request $request){
        $deals=Broker::whereHas('deals',function ($q)use($request){
            $q->where('status_date','>=',date('Y-m-d',strtotime($request['from_date'])))
                ->where('status_date','<=',date('Y-m-d',strtotime($request['to_date']))) ;
        })->with(['deals','deals.deal_commission'=>function($r){
            $r->whereType(13)->select('broker_amount','bro_amt_date_paid','deal_id','id')->first();
        }]);
        if(!empty($request['broker_id'])){
            $deals->whereId($request['broker_id']);
        }
        $pdf=PDF::loadView('admin.reports.brokers.commission_monitoring',[
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date'],
            'brokers'=>$deals->get()])
            ->setPaper('a4', 'landscape')->setWarnings(false);

            return $pdf->stream('commission_monitoring.pdf');

    }
    public function exportCommissionMonitoringReport(Request $request){
        $deals=Broker::whereHas('deals',function ($q)use($request){
            $q->where('deals.status',5)
                ->where('status_date','>=',date('Y-m-d',strtotime($request['from_date'])))
                ->where('status_date','<=',date('Y-m-d',strtotime($request['to_date']))) ;
        })->with(['deals','deals.deal_commission'=>function($r){
            $r->whereType(13)->select('broker_amount','bro_amt_date_paid','deal_id','id')->first();
        }]);
        if(!empty($request['broker_id'])){
            $deals->whereId($request['broker_id']);
        }
        $pdf=PDF::loadView('admin.reports.brokers.commission_monitoring',[
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date'],
            'brokers'=>$deals->get()])
            ->setPaper('a4', 'landscape')->setWarnings(false);

            return $pdf->stream('commission_monitoring.pdf');

    }
    public function getBrokerRecords(Request $request){
        $brokers=Broker::select('id','entity_name','work_phone','home_phone','mobile_phone','fax', 'email'
        );
        if(!empty($request['from_date'])){
            $brokers->where('created_at','>=', date('Y-m-d H:i:s',strtotime($request['from_date'].' 00:00:00')));
        }if(!empty($request['to_date'])){
            $brokers->where('created_at','<=', date('Y-m-d H:i:s',strtotime($request['to_date'].' 23:59:59')));
        }if(!empty($request['broker_type'])){
            $brokers->where('type', $request['broker_type']);
        }
        $pdf = PDF::loadView('admin.reports.brokers.brokers_report',['brokers'=>$brokers->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('brokers_list.pdf');
    }
    public function exportBrokerRecords(Request $request){
        $brokers=Broker::select('id','entity_name','work_phone','home_phone','mobile_phone','fax', 'email'
        );
        if(!empty($request['from_date'])){
            $brokers->where('created_at','>=', date('Y-m-d H:i:s',strtotime($request['from_date'].' 00:00:00')));
        }if(!empty($request['to_date'])){
            $brokers->where('created_at','<=', date('Y-m-d H:i:s',strtotime($request['to_date'].' 23:59:59')));
        }if(!empty($request['broker_type'])){
            $brokers->where('type', $request['broker_type']);
        }
        $pdf = PDF::loadView('admin.reports.brokers.brokers_report',['brokers'=>$brokers->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('brokers_list.pdf');
    }
}
