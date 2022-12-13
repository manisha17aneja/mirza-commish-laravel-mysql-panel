<?php

namespace App\Http\Controllers\Admin\Reports\FmDirect;

use App\Exports\DealsSettled;
use App\Exports\AIPExport;
use App\Exports\ClientExport;
use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\ContactSearch;
use App\Models\Deal;
use App\Models\DealCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class FmdirectController extends Controller
{
    //
    public function fmDirect()
    {
        return view('admin.reports.fm_direct.index', ['title' => 'Commish| FM Direct']);
    }

    public function dealsSettled()
    {
        return view('admin.reports.fm_direct.deals_settled_report', ['header' => 'Commish| Pipeline Report']);
    }

    public function getDealsSettledRecords(Request $request)
    {
        $deal = Deal::select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker_staff'])
            ->whereIn('status', [4]);
        if ($request['group_by'] == 'Product') {
            $deal->groupBy('product_id');
        } else if ($request['group_by'] == 'BrokerStaff') {
            $deal->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deal->groupBy('status');
        } else {
            $deal->groupBy('lender_id');
        }
        if (!empty($request['from_date'])) {
            $deal->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'] . ' 00:00:00')));
        }
        if (!empty($request['to_date'])) {
            $deal->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'] . ' 23:59:59')));
        }
        $pdf = PDF::loadView('admin.reports.fm_direct.deals_settled_report', ['deals' => $deal->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
            'group_by' => $request['group_by']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('deals_settled.pdf');
    }

    public function exportDealsSettled(Request $request)
    {
        $deal = Deal::select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker_staff'])
            ->whereIn('status', [4]);
        if ($request['group_by'] == 'Product') {
            $deal->groupBy('product_id');
        } else if ($request['group_by'] == 'BrokerStaff') {
            $deal->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deal->groupBy('status');
        } else {
            $deal->groupBy('lender_id');
        }
        if (!empty($request['from_date'])) {
            $deal->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'] . ' 00:00:00')));
        }
        if (!empty($request['to_date'])) {
            $deal->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'] . ' 23:59:59')));
        }
        $pdf = PDF::loadView('admin.reports.fm_direct.deals_settled_report', ['deals' => $deal->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
            'group_by' => $request['group_by']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('deals_settled.pdf');
    }

    public function dealsAIP()
    {
        return view('admin.reports.fm_direct.deals_aip_report', ['header' => 'Commish| AIP Report']);
    }

    public function getDealsAIPRecords(Request $request)
    {
        $deal = Deal::select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker_staff'])
            ->whereIn('status', [11]);
        if (!empty($request['from_date'])) {
            $deal->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'] . ' 00:00:00')));
        }
        if (!empty($request['to_date'])) {
            $deal->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'] . ' 23:59:59')));
        }
        $pdf = PDF::loadView('admin.reports.fm_direct.deals_aip_report', ['deals' => $deal->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
            'group_by' => $request['group_by']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('deals_aip_report.pdf');
    }

    public function exportAIPRecords(Request $request)
    {
        $deal = Deal::select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker_staff'])
            ->whereIn('status', [11]);
        if (!empty($request['from_date'])) {
            $deal->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'] . ' 00:00:00')));
        }
        if (!empty($request['to_date'])) {
            $deal->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'] . ' 23:59:59')));
        }
        $pdf = PDF::loadView('admin.reports.fm_direct.deals_aip_report', ['deals' => $deal->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
            'group_by' => $request['group_by']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('deals_aip_report.pdf');
    }

    public function clients()
    {
        return view('admin.reports.fm_direct.clients_report', ['header' => 'Commish| Clients Report']);
    }

    public function getClientRecords(Request $request)
    {
        $clients = ContactSearch::select('id', 'surname', 'preferred_name', 'middle_name', 'entity_name', 'general_mail_out',
            'work_phone', 'home_phone', 'mobile_phone',
            'email', 'mail_state', 'mail_postal_code',
            'street_number', 'street_name', 'street_type',
            'suburb'
        )->where('search_for', 1)->with('mail_state_table');
        if (!empty($request['from_date'])) {
            $clients->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'] . ' 00:00:00')));
        }
        if (!empty($request['to_date'])) {
            $clients->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'] . ' 23:59:59')));
        }
        $pdf = PDF::loadView('admin.reports.fm_direct.clients_report', ['clients' => $clients->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('clients_list.pdf');
    }

    public function exportClientRecords(Request $request)
    {
        $clients = ContactSearch::select('id', 'surname', 'preferred_name', 'middle_name', 'entity_name', 'general_mail_out',
            'work_phone', 'home_phone', 'mobile_phone',
            'email', 'mail_state', 'mail_postal_code',
            'street_number', 'street_name', 'street_type',
            'suburb'
        )->where('search_for', 1)->with('mail_state_table');
        if (!empty($request['from_date'])) {
            $clients->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'] . ' 00:00:00')));
        }
        if (!empty($request['to_date'])) {
            $clients->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'] . ' 23:59:59')));
        }
        $pdf = PDF::loadView('admin.reports.fm_direct.clients_report', ['clients' => $clients->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('clients_list.pdf');
    }

    public function birthday()
    {
        return view('admin.reports.fm_direct.birthday_report', ['header' => 'Commish| Birthdays Report']);
    }

    public function getBirthdayRecords(Request $request)
    {
        $clients = ContactSearch::select(
            \DB::raw('Day(dob) day, MONTH(dob) month'), 'id', 'surname', 'preferred_name', 'middle_name', 'entity_name', 'general_mail_out',
            'work_phone', 'home_phone', 'mobile_phone',
            'email', 'mail_state', 'mail_postal_code', 'dob',
            'street_number', 'street_name', 'street_type',
            'suburb'
        )->where('search_for', 1)->with('mail_state_table');
        if (!empty($request['from_date'])) {
            $clients->whereMonth('dob', '>=', date('m', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $clients->whereMonth('dob', '<=', date('m', strtotime($request['to_date'])));
        }
        $clients->orderBY('day', 'asc');
        $pdf = PDF::loadView('admin.reports.fm_direct.birthday_report', ['clients' => $clients->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('birthdays_list.pdf');
    }

    public function exportBirthdayRecords(Request $request)
    {
        $clients = ContactSearch::select(
            \DB::raw('Day(dob) day, MONTH(dob) month'), 'id', 'surname', 'preferred_name', 'middle_name', 'entity_name', 'general_mail_out',
            'work_phone', 'home_phone', 'mobile_phone',
            'email', 'mail_state', 'mail_postal_code', 'dob',
            'street_number', 'street_name', 'street_type',
            'suburb'
        )->where('search_for', 1)->with('mail_state_table');
        if (!empty($request['from_date'])) {
            $clients->whereMonth('dob', '>=', date('m', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $clients->whereMonth('dob', '<=', date('m', strtotime($request['to_date'])));
        }
        $clients->orderBY('day', 'asc');
        $pdf = PDF::loadView('admin.reports.fm_direct.birthday_report', ['clients' => $clients->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('birthdays_list.pdf');
    }

    public function getReferrerCommissionRatingPreview(Request $request)
    {
        $deals = Deal::select(\DB::raw('
            Sum(If(`broker_est_upfront`>0 Or `broker_est_brokerage`>0,1,0)) AS NumberOfLoansUpfront,
            Sum(If(`broker_est_upfront`>0 Or `broker_est_brokerage`>0,`broker_est_loan_amt`,0)) AS SumOfLoansUpfront,
            Sum(broker_est_upfront) AS SumOfdea_UpfrontEst_ABP,
            Sum(broker_est_brokerage) AS SumOfdea_BrokerageEst_ABP,
            Sum(`referror_split_agg_brk_sp_upfrt`*`broker_est_upfront`) AS ReferrorUpfront,referror_split_referror'))
            ->where(function ($query) {
                $query->where('broker_est_upfront', '>', 0)->orWhere('broker_est_loan_amt', '>', 0);
            })
            ->with('referrer')->groupBy('referror_split_referror');
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.referrer_commission_rating', ['deals' => $deals,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('referrer_commission_rating.pdf');
    }

    public function exportReferrerCommissionRating(Request $request)
    {
        $deals = Deal::select(\DB::raw('
            Sum(If(`broker_est_upfront`>0 Or `broker_est_brokerage`>0,1,0)) AS NumberOfLoansUpfront,
            Sum(If(`broker_est_upfront`>0 Or `broker_est_brokerage`>0,`broker_est_loan_amt`,0)) AS SumOfLoansUpfront,
            Sum(broker_est_upfront) AS SumOfdea_UpfrontEst_ABP,
            Sum(broker_est_brokerage) AS SumOfdea_BrokerageEst_ABP,
            Sum(`referror_split_agg_brk_sp_upfrt`*`broker_est_upfront`) AS ReferrorUpfront,referror_split_referror'))->whereStatus(4)
            ->where(function ($query) {
                $query->where('broker_est_upfront', '>', 0)->orWhere('broker_est_loan_amt', '>', 0);
            })
            ->with('referrer')->groupBy('referror_split_referror');
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.referrer_commission_rating', ['deals' => $deals,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('referrer_commission_rating.pdf');
    }

    public function getUpfrontOutstandingPreview(Request $request)
    {
        $deals = Deal::select('*')->whereStatus(4)
            ->where(function ($query) {
                $query->where('broker_est_upfront', '>', 0)->orWhere('broker_est_loan_amt', '>', 0);
            });
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if ($request['group_by'] == 'BrokerStaff') {
            $deals->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deals->groupBy('status');
        } else {
            $deals->groupBy('lender_id');
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.upfront_outstanding_report', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('upfront_outstanding.pdf');
    }

    public function exportUpfrontOutstanding(Request $request)
    {
        $deals = Deal::select('*')->whereStatus(4)
            ->where(function ($query) {
                $query->where('broker_est_upfront', '>', 0)->orWhere('broker_est_loan_amt', '>', 0);
            });
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if ($request['group_by'] == 'BrokerStaff') {
            $deals->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deals->groupBy('status');
        } else {
            $deals->groupBy('lender_id');
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.upfront_outstanding_report', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('upfront_outstanding.pdf');
    }


    public function getTrailOutstandingPreview(Request $request)
    {
        $deals = Deal::select('*')->whereStatus(4)
            ->where(function ($query) {
                $query->where('broker_est_upfront', '>', 0)->orWhere('broker_est_loan_amt', '>', 0);
            });
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if ($request['group_by'] == 'BrokerStaff') {
            $deals->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deals->groupBy('status');
        } else {
            $deals->groupBy('lender_id');
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.trail_outstanding_report', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('upfront_outstanding.pdf');
    }

    public function exportTrailOutstanding(Request $request)
    {
        $deals = Deal::select('*')->whereStatus(4)
            ->where(function ($query) {
                $query->where('broker_est_upfront', '>', 0)->orWhere('broker_est_loan_amt', '>', 0);
            });
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if ($request['group_by'] == 'BrokerStaff') {
            $deals->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deals->groupBy('status');
        } else {
            $deals->groupBy('lender_id');
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.trail_outstanding_report', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('upfront_outstanding.pdf');
    }

    public function getOutstandingCommissionPreview(Request $request)
    {
        $deals = Deal::select('*')->whereStatus(4);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if ($request['group_by'] == 'BrokerStaff') {
            $deals->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deals->groupBy('status');
        } else {
            $deals->groupBy('lender_id');
        }
        $deals = $deals->get();

        $pdf = PDF::loadView('admin.reports.fm_direct.outstanding_commission_report', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('outstanding_commission.pdf');
    }

    public function exportOutstandingCommission(Request $request)
    {
        $deals = Deal::select('*')->whereStatus(4)
            ->where(function ($query) {
                $query->where('broker_est_upfront', '>', 0)->orWhere('broker_est_loan_amt', '>', 0);
            });
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if ($request['group_by'] == 'BrokerStaff') {
            $deals->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deals->groupBy('status');
        } else {
            $deals->groupBy('lender_id');
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.outstanding_commission_report', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('outstanding_commission.pdf');
    }

    public function getLeadsDNP(Request $request)
    {

        $deals = \App\Models\Deal::query()->select('deals.*')->whereStatus(5)->with(['lender', 'client', 'deal_status', 'product', 'broker_staff']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if ($request['group_by'] == 'BrokerStaff') {
            $deals->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deals->groupBy('status');
        } else {
            $deals->groupBy('lender_id');
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.deals_dnp_report', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('deals_dnp_report.pdf');

    }

    public function exportLeadsDNP(Request $request)
    {
        $deals = \App\Models\Deal::query()->select('deals.*')->whereStatus(5)->with(['lender', 'client', 'deal_status', 'product', 'broker_staff']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if ($request['group_by'] == 'BrokerStaff') {
            $deals->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deals->groupBy('status');
        } else {
            $deals->groupBy('lender_id');
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.deals_dnp_report', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('deals_dnp_report.pdf');
    }

    public function getDealTrack(Request $request)
    {

        $deals = \App\Models\Deal::query()->select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker_staff']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if ($request['group_by'] == 'BrokerStaff') {
            $deals->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deals->groupBy('status');
        } else {
            $deals->groupBy('lender_id');
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.deals_to_track', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('deals_to_track.pdf');

    }

    public function exportDealTrack(Request $request)
    {
        $deals = \App\Models\Deal::query()->select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker_staff']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if ($request['group_by'] == 'BrokerStaff') {
            $deals->groupBy('broker_staff_id');
        } else if ($request['group_by'] === 'Status') {
            $deals->groupBy('status');
        } else {
            $deals->groupBy('lender_id');
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.deals_to_track', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('deals_to_track.pdf');
    }

    public function getSales(Request $request)
    {

        $deals = \App\Models\Deal::query()->select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker_staff', 'deal_commission']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
//        if($request['group_by']=='BrokerStaff'){
//            $deals->groupBy('broker_staff_id');
//        }else if($request['group_by']==='Status'){
//            $deals->groupBy('status');
//        }else{
//            $deals->groupBy('lender_id');
//        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.sales', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('sales.pdf');

    }

    public function exportSales(Request $request)
    {
        $deals = \App\Models\Deal::query()->select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker_staff', 'deal_commission']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.sales', ['deals' => $deals,
            'group_by' => $request['group_by'],
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('sales.pdf');
    }

    public function getReferrerCommissionUpfront(Request $request)
    {
        $deals = Deal::select(\DB::raw('
            Sum(If(`broker_est_upfront`>0 Or `broker_est_brokerage`>0,1,0)) AS NumberOfLoansUpfront,
            Sum(If(`broker_est_upfront`>0 Or `broker_est_brokerage`>0,`broker_est_loan_amt`,0)) AS SumOfLoansUpfront,
            Sum(broker_est_upfront) AS SumOfdea_UpfrontEst_ABP,
            Sum(broker_est_brokerage) AS SumOfdea_BrokerageEst_ABP,
            Sum(`referror_split_agg_brk_sp_upfrt`*`broker_est_upfront`) AS ReferrorUpfront,referror_split_referror'))
            ->where(function ($query) {
                $query->where('broker_est_upfront', '>', 0)->orWhere('broker_est_loan_amt', '>', 0);
            })
            ->with('referrer')->groupBy('referror_split_referror');
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.referrer_commission_upfront', ['deals' => $deals,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('referrer_commission_upfront.pdf');
    }

    public function exportReferrerCommissionUpfront(Request $request)
    {
        $deals = Deal::select(\DB::raw('
            Sum(If(`broker_est_upfront`>0 Or `broker_est_brokerage`>0,1,0)) AS NumberOfLoansUpfront,
            Sum(If(`broker_est_upfront`>0 Or `broker_est_brokerage`>0,`broker_est_loan_amt`,0)) AS SumOfLoansUpfront,
            Sum(broker_est_upfront) AS SumOfdea_UpfrontEst_ABP,
            Sum(broker_est_brokerage) AS SumOfdea_BrokerageEst_ABP,
            Sum(`referror_split_agg_brk_sp_upfrt`*`broker_est_upfront`) AS ReferrorUpfront,referror_split_referror'))->whereStatus(4)
            ->where(function ($query) {
                $query->where('broker_est_upfront', '>', 0)->orWhere('broker_est_loan_amt', '>', 0);
            })
            ->with('referrer')->groupBy('referror_split_referror');
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.referrer_commission_upfront', ['deals' => $deals,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('referrer_commission_upfront.pdf');
    }

    public function getDealsHistory(Request $request)
    {
        $deals = \App\Models\Deal::query()->select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker_staff', 'deal_commission']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if (!empty($request['product_id']) && $request['product_id'] != "Select Product") {
            $deals->where('product_id', $request['product_id']);
        }
        if (!empty($request['status'] && $request['status'] != "Select Status")) {
            $deals->where('status', $request['status']);
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.deals_history', ['deals' => $deals,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('deals_history.pdf');
    }

    public function exportDealsHistory(Request $request)
    {
        $deals = \App\Models\Deal::query()->select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker_staff', 'deal_commission']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        if (!empty($request['product_id']) && $request['product_id'] != "Select Product") {
            $deals->where('product_id', $request['product_id']);
        }
        if (!empty($request['status'] && $request['status'] != "Select Status")) {
            $deals->where('status', $request['status']);
        }
        $deals = $deals->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.deals_history', ['deals' => $deals,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('deals_history.pdf');
    }

    public function getSnapshotOfDeals(Request $request)
    {
        $deals = \App\Models\Deal::query()->select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker', 'deal_commission']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        $pdf = PDF::loadView('admin.reports.fm_direct.snapshot_of_all_deals', ['deals' => $deals->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('snapshot_of_all_deals.pdf');
    }

    public function exportSnapshotOfDeals(Request $request)
    {
        $deals = \App\Models\Deal::query()->select('deals.*')->with(['lender', 'client', 'deal_status', 'product', 'broker', 'deal_commission']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        $pdf = PDF::loadView('admin.reports.fm_direct.snapshot_of_all_deals', ['deals' => $deals->get(),
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('snapshot_of_all_deals.pdf');
    }

    public function getCommissionRanking(Request $request)
    {
        $deals = \App\Models\Deal::query()->select('deals.*')->selectRaw('SUM(deals.broker_est_upfront) as upfront,SUM(deals.broker_est_trail) as trail')->groupBy('deals.contact_id')->with(['client']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        $deals = $deals->orderBy('upfront', 'DESC')->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.commission_ranking', ['deals' => $deals,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('commission_ranking.pdf');
    }

    public function exportCommissionRanking(Request $request)
    {
        $deals = \App\Models\Deal::query()->select('deals.*')->selectRaw('SUM(deals.broker_est_upfront) as upfront,SUM(deals.broker_est_trail) as trail')->groupBy('deals.contact_id')->with(['client']);
        if (!empty($request['from_date'])) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
        }
        if (!empty($request['to_date'])) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
        }
        $deals = $deals->orderBy('upfront', 'DESC')->get();
        $pdf = PDF::loadView('admin.reports.fm_direct.commission_ranking', ['deals' => $deals,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('commission_ranking.pdf');
    }

    public function getTrailDiscrepancies(Request $request)
    {
        $variance = $request['variance'];
        $dealsData = [];
        $brokers = Broker::where('type', 1)->get();
        foreach ($brokers as $broker) {
            $deals = Deal::query()->select('deals.*')->where('broker_staff_id', $broker->id)->with(['lender', 'client', 'broker', 'deal_commission' => function ($q) use ($variance) {
            $q->where('variance', '>=', $variance)->where('type', 12);
            }]);
            if (!empty($request['from_date'])) {
                $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
            }
            if (!empty($request['to_date'])) {
                $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
            }
            $deals = $deals->get();

            foreach ($deals as $deal) {
                foreach($deal->deal_commission as $comm){
                    $trail_received = $comm->total_amount??0.00;
                    $total_trail = $deal->broker_est_trail??0.00;
                    if($total_trail != 0)  $trail_precentage = round(( $trail_received/ $total_trail) * 100, 2);
                    $comm_created = date('m/d/Y',strtotime($comm->created_at))??'';
                    $dealsData[$broker->surname][] = [

                        "deal_id" => $deal->id,
                        "client" => $deal->client->surname??'',
                        "lender" => $deal->lender->name??'',
                        "staff" => $deal->broker_staff->surname??'',
                        "loan" => $deal->actual_loan??0.00,
                        'date_settled' => date('m/d/Y',strtotime($deal->created_at))??'',
                        "date_received" => $comm_created,
                        "abp_trail" => $total_trail,
                        "receive_trail" => $trail_received,
                        "variance" => $comm->variance


                    ];
                }
            }
        }

        $pdf = PDF::loadView('admin.reports.fm_direct.trail_discrepancies', ['deals' => $dealsData,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
            'group_by' => 'BrokerStaff',
            'variance' => $request['variance']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('trail_discrepancies.pdf');
    }

    public function exportTrailDiscrepancies(Request $request)
    {
        $variance = $request['variance'];
        $dealsData = [];
        $brokers = Broker::where('type', 1)->get();
        foreach ($brokers as $broker) {
            $deals = Deal::query()->select('deals.*')->where('broker_staff_id', $broker->id)->with(['lender', 'client', 'broker', 'deal_commission' => function ($q) use ($variance) {
            $q->where('variance', '>=', $variance)->where('type', 12);
            }]);
            if (!empty($request['from_date'])) {
                $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
            }
            if (!empty($request['to_date'])) {
                $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
            }
            $deals = $deals->get();

            foreach ($deals as $deal) {
                foreach($deal->deal_commission as $comm){
                    $trail_received = $comm->total_amount??0.00;
                    $total_trail = $deal->broker_est_trail??0.00;
                    if($total_trail != 0)  $trail_precentage = round(( $trail_received/ $total_trail) * 100, 2);
                    $comm_created = date('m/d/Y',strtotime($comm->created_at))??'';
                    $dealsData[$broker->surname][] = [

                        "deal_id" => $deal->id,
                        "client" => $deal->client->surname??'',
                        "lender" => $deal->lender->name??'',
                        "staff" => $deal->broker_staff->surname??'',
                        "loan" => $deal->actual_loan??0.00,
                        'date_settled' => date('m/d/Y',strtotime($deal->created_at))??'',
                        "date_received" => $comm_created,
                        "abp_trail" => $total_trail,
                        "receive_trail" => $trail_received,
                        "variance" => $comm->variance


                    ];
                }
            }
        }


        $pdf = PDF::loadView('admin.reports.fm_direct.trail_discrepancies', ['deals' => $dealsData,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
            'group_by' => 'BrokerStaff',
            'variance' => $request['variance']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('trail_discrepancies.pdf');
    }

    public function getUpfrontDiscrepancies(Request $request)
    {
        $variance = $request['variance'];
        $dealsData = [];
        $brokers = Broker::where('type', 1)->get();
        foreach ($brokers as $broker) {
            $deals = Deal::query()->select('deals.*')->where('broker_staff_id', $broker->id)->with(['lender', 'client', 'broker', 'deal_commission' => function ($q) use ($variance) {
            $q->where('variance', '>=', $variance)->where('type', 13);
            }]);
            if (!empty($request['from_date'])) {
                $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
            }
            if (!empty($request['to_date'])) {
                $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
            }
            $deals = $deals->get();

            foreach ($deals as $deal) {
                foreach($deal->deal_commission as $comm){
                    $upfront_received = $comm->total_amount??0.00;
                    $brokerrage=DealCommission::whereDealId($deal->id)->where('type',4)->sum('broker_amount');
                    $total_upfront = $deal->broker_est_upfront??0.00;
                    if($total_upfront != 0)  $upfront_precentage = round(( $upfront_received/ $total_upfront) * 100, 2);
                    $comm_created = date('m/d/Y',strtotime($comm->created_at))??'';
                    $dealsData[$broker->surname][] = [

                        "deal_id" => $deal->id,
                        "client" => $deal->client->surname??'',
                        "lender" => $deal->lender->name??'',
                        "staff" => $deal->broker_staff->surname??'',
                        "loan" => $deal->actual_loan??0.00,
                        'date_settled' => date('m/d/Y',strtotime($deal->created_at))??'',
                        "date_received" => $comm_created,
                        "abp_upfront" => $total_upfront,
                        "abp_brokerage" => $deal->broker_est_brokerage,
                        "receive_upfront" => $upfront_received,
                        "brokerage" => $brokerrage,
                        "variance" => $comm->variance


                    ];
                }
            }
        }

        $pdf = PDF::loadView('admin.reports.fm_direct.upfront_discrepancies', ['deals' => $dealsData,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
            'group_by' => 'BrokerStaff',
            'variance' => $request['variance']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('upfront_discrepancies.pdf');
    }

    public function exportUpfrontDiscrepancies(Request $request)
    {$variance = $request['variance'];
        $dealsData = [];
        $brokers = Broker::where('type', 1)->get();
        foreach ($brokers as $broker) {
            $deals = Deal::query()->select('deals.*')->where('broker_staff_id', $broker->id)->with(['lender', 'client', 'broker', 'deal_commission' => function ($q) use ($variance) {
            $q->where('variance', '>=', $variance)->where('type', 13);
            }]);
            if (!empty($request['from_date'])) {
                $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($request['from_date'])));
            }
            if (!empty($request['to_date'])) {
                $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($request['to_date'])));
            }
            $deals = $deals->get();

            foreach ($deals as $deal) {
                foreach($deal->deal_commission as $comm){
                    $upfront_received = $comm->total_amount??0.00;
                    $total_upfront = $deal->broker_est_upfront??0.00;
                    if($total_upfront != 0)  $upfront_precentage = round(( $upfront_received/ $total_upfront) * 100, 2);
                    $comm_created = date('m/d/Y',strtotime($comm->created_at))??'';
                    $dealsData[$broker->surname][] = [

                        "deal_id" => $deal->id,
                        "client" => $deal->client->surname??'',
                        "lender" => $deal->lender->name??'',
                        "staff" => $deal->broker_staff->surname??'',
                        "loan" => $deal->actual_loan??0.00,
                        'date_settled' => date('m/d/Y',strtotime($deal->created_at))??'',
                        "date_received" => $comm_created,
                        "abp_upfront" => $total_upfront,
                        "receive_upfront" => $upfront_received,
                        "variance" => $comm->variance


                    ];
                }
            }
        }

        $pdf = PDF::loadView('admin.reports.fm_direct.upfront_discrepancies', ['deals' => $dealsData,
            'date_from' => $request['from_date'],
            'date_to' => $request['to_date'],
            'group_by' => 'BrokerStaff',
            'variance' => $request['variance']
        ])->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download('upfront_discrepancies.pdf');
    }
}
