<?php

namespace App\Http\Controllers\Admin\Reports\FmDirect;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealTask;
use App\Models\Processor;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DateTime;


class PipelinereportController extends Controller
{
    //
    public function pipeline(){
        return view('admin.reports.fm_direct.pipeline_report',['header'=>'Commish| Pipeline Report']);
    }
    public function getPreviewPipeline(Request $request){
        $deal=Deal::select('deals.*')->with(['lender','client','deal_status','product','broker_staff'])
            ->whereIn('status',[1,2,3,4]);
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
        $pdf = PDF::loadView('admin.reports.fm_direct.pipeline_report',['deals'=>$deal->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date'],
            'group_by'=>$request['group_by']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('pipeline.pdf');
    }
    public function exportPipelineRecords(Request $request){
        $deal=Deal::select('deals.*')->with(['lender','client','deal_status','product','broker_staff'])
            ->whereIn('status',[1,2,3,4]);
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

        $pdf = PDF::loadView('admin.reports.fm_direct.pipeline_report',['deals'=>$deal->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date'],
            'group_by'=>$request['group_by']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('pipeline.pdf');
    }
    public function monthlyPipeline(){
        return view('admin.reports.fm_direct.monthly_pipeline_report',['header'=>'Commish| Pipeline Report']);
    }
    public function getPreviewMonthlyPipeline(Request $request){
        $deal=Deal::select(\DB::raw('count(id) as `data`'), \DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),
            \DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
            ->groupby('year','month')
            ->with(['lender','client','deal_status','product','broker_staff'])
            ->whereIn('status',[1,2,3,4]);
        if(!empty($request['from_date'])){
            $deal->where('created_at','>=', date('Y-m-d H:i:s',strtotime($request['from_date'].' 00:00:00')));
        }if(!empty($request['to_date'])){
            $deal->where('created_at','<=', date('Y-m-d H:i:s',strtotime($request['to_date'].' 23:59:59')));
        }
        $pdf = PDF::loadView('admin.reports.fm_direct.monthly_pipeline_report',['deals'=>$deal->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date'],
            'group_by'=>$request['group_by']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('pipeline.pdf');
    }
    public function exportMonthlyPipelineRecords(Request $request){
        $deal=Deal::select(\DB::raw('count(id) as `data`'), \DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),
            \DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
            ->groupby('year','month')
            ->with(['lender','client','deal_status','product','broker_staff'])
            ->whereIn('status',[1,2,3,4]);
        if(!empty($request['from_date'])){
            $deal->where('created_at','>=', date('Y-m-d H:i:s',strtotime($request['from_date'].' 00:00:00')));
        }if(!empty($request['to_date'])){
            $deal->where('created_at','<=', date('Y-m-d H:i:s',strtotime($request['to_date'].' 23:59:59')));
        }
        $pdf = PDF::loadView('admin.reports.fm_direct.monthly_pipeline_report',['deals'=>$deal->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date'],
            'group_by'=>$request['group_by']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('monthly_pipeline.pdf');
    }
    public function dealTasks(){
        return view('admin.reports.fm_direct.pipeline_report',['header'=>'Commish| Pipeline Report']);
    }
    public function getDealTasks(Request $request){
        $processors=Processor::whereHas('deal_tasks',function ($q)use($request){
            if(!empty($request['from_date'])){
                $q->where('followup_date','>=', date('Y-m-d H:i:s',strtotime($request['from_date'].' 00:00:00')));
            }if(!empty($request['to_date'])){
                $q->where('followup_date','<=', date('Y-m-d H:i:s',strtotime($request['to_date'].' 23:59:59')));
            }
        })->with(['deal_tasks'=>function ($q)use($request){
            if(!empty($request['from_date'])){
                $q->where('followup_date','>=', date('Y-m-d H:i:s',strtotime($request['from_date'].' 00:00:00')));
            }if(!empty($request['to_date'])){
                $q->where('followup_date','<=', date('Y-m-d H:i:s',strtotime($request['to_date'].' 23:59:59')));
            }
        },'deal_tasks.deal'=>function($que){
            $que->select('id','contact_id');
        },'deal_tasks.deal.deal_status','deal_tasks.deal.client'=>function($query){
            $query->select('id','surname','preferred_name','middle_name');
        }]);

        $pdf = PDF::loadView('admin.reports.fm_direct.deal_tasks_report',['processors'=>$processors->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('deal_tasks.pdf');
    }
    public function exportDealTasks(Request $request){
        $processors=Processor::whereHas('deal_tasks',function ($q)use($request){
            if(!empty($request['from_date'])){
                $q->where('followup_date','>=', date('Y-m-d H:i:s',strtotime($request['from_date'].' 00:00:00')));
            }if(!empty($request['to_date'])){
                $q->where('followup_date','<=', date('Y-m-d H:i:s',strtotime($request['to_date'].' 23:59:59')));
            }
        })->with(['deal_tasks'=>function ($q)use($request){
            if(!empty($request['from_date'])){
                $q->where('followup_date','>=', date('Y-m-d H:i:s',strtotime($request['from_date'].' 00:00:00')));
            }if(!empty($request['to_date'])){
                $q->where('followup_date','<=', date('Y-m-d H:i:s',strtotime($request['to_date'].' 23:59:59')));
            }
        },'deal_tasks.deal'=>function($que){
            $que->select('id','contact_id');
        },'deal_tasks.deal.deal_status','deal_tasks.deal.client'=>function($query){
            $query->select('id','surname','preferred_name','middle_name');
        }]);
        $pdf = PDF::loadView('admin.reports.fm_direct.deal_tasks_report',['processors'=>$processors->get(),
            'date_from'=>$request['from_date'],
            'date_to'=>$request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('deal_tasks.pdf');
    }
}
