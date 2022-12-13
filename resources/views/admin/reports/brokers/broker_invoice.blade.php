<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Broker Invoice</title>

</head>
<body style=" font-family: system-ui, system-ui, sans-serif;">

<style>
    table {
        border: 0;
        border-collapse: separate;
        border-spacing: 0 5px;
    }

    .thead_style tr th {
        border-bottom: 1px solid grey;
        font-family: system-ui, system-ui, sans-serif;
        border-collapse: separate;
        border-spacing: 5px 5px;
        text-align: left;
        font-weight: 800;
        font-size: 12px;
    }.gross_tota_trail tr th {
        border-top: 1px solid grey;
        font-family: system-ui, system-ui, sans-serif;
        border-collapse: separate;
        border-spacing: 5px 5px;
        text-align: left;
        font-weight: 800;
        font-size: 12px;
    }
    .subtotal tr th {
        border-top: 1px solid grey;
        font-family: system-ui, system-ui, sans-serif;
        border-collapse: separate;
        border-spacing: 5px 5px;
        text-align: left;
        font-size: 12px;
    }.grand_total tr th {
         border-top: 2px solid grey;
         font-family: system-ui, system-ui, sans-serif;
         border-collapse: separate;
         border-spacing: 5px 5px;
         text-align: left;
         font-size: 14px;
     }
    .body_class tr td {
        font-family: system-ui, system-ui, sans-serif;
        border-collapse: separate;
        border-spacing: 5px 5px;
        text-align: left;
        font-size: 12px;
    }
    .body_class tbody{
        border-collapse: separate;
        border-spacing: 5px 5px;
        border-bottom: 1px solid;
    }
    thead{display: table-header-group;}
    tfoot {display: table-row-group;}
    tr {page-break-inside: avoid;}
</style>
<table style="margin-top: 5px;margin-bottom:20px;width: 100%">
    <tbody>
    <tr>
        <td style="width: 25%"> Receipt Created <span style="font-size: 18px;font-weight: bold;">Tax Invoice </span></td>
    </tr>
    </tbody>
</table>
@if(count($brokers)>0)
    @foreach($brokers as $broker)
        <table style="border:1px solid;width: 350px;height: 30px" >
            <thead>
            <tr>
                <td style="margin: 7px">{{$broker->deal->broker->surname??''}}</td>
            </tr>
            <tr>
                <td style="margin: 7px">{{$broker->deal->broker->business??''}}</td>
            </tr>
            <tr>
                <td style="margin: 7px">
                    <span>{{$broker->deal->broker->city_info->name??$broker->deal->broker->city}}</span>
                    <span style="margin: 0px 7px">{{$broker->deal->broker->state_info->name??$broker->deal->broker->state}}</span>
                    <span style="margin: 0px 7px">{{$broker->deal->broker->pincode}}</span>
                </td>
            </tr>
            <tr>
                <td style="margin: 7px">ABN: {{$broker->deal->broker->abn}}</td>
            </tr>
            <tr>
                <td style="margin: 7px;"></td>
            </tr>
            
            </thead>

        </table>
        <?php
            $broker_staffs=\App\Models\Deal::where('broker_id',$broker->deal->broker_id)
                ->with(['broker_staff','deal_commission'=>function($que)use($date_from,$date_to){
                    $que->with('deal','deal.client','deal.lender');
                    $que->whereHas('deal');
                    if(!empty($date_from)){
                        $que->where('date_statement','>=', date('Y-m-d',strtotime($date_from)));
                    }if(!empty($date_to)){
                        $que->where('date_statement','<=', date('Y-m-d',strtotime($date_to)));
                    }
                }])->whereHas('deal_commission',function ($q) use($broker,$date_from,$date_to){
                    if(!empty($date_from)){
                        $q->where('bro_amt_date_paid','>=', date('Y-m-d',strtotime($date_from)));
                    }if(!empty($date_to)){
                        $q->where('bro_amt_date_paid','<=', date('Y-m-d',strtotime($date_to)));
                    }
                    $q->where('deal_id', ">", 0);
                })->groupBy('broker_staff_id')->get();


        ?>
        <table style="margin-top: 5px;margin-bottom:5px;width: 100%">
            <tbody>
            <tr>
                <td style="width: 100%"> <span style="margin-right: 50px; border-bottom: 1px solid">For commission due for the period of </span><span>{{ $date_from.' to '.$date_to }}</span></td>
            </tr>
            </tbody>
        </table>
        <table style="width: 100%;margin-top: 5px" >
            <thead class="thead_style">
            <tr>
                <th>Client</th>
                <th>Institute</th>
                <th>Loan Amount</th>
                <th>Deal Id</th>
                <th>Model</th>
                <th>%</th>
                <th>Amount</th>
                <th>FMA</th>
                <th>Broker</th>
            </tr>
            </thead>
            <tbody class="body_class">
            @foreach($broker_staffs as $broker_staff)
                <?php
                $total_actual_loan=0;
                $total_agg_amount=0;
                $total_total_amount=0;
                $total_broker_amount=0;
                ?>
            <tr style="border-bottom: 1px solid;padding-bottom: 5px">
                <td style="border-bottom: 1px solid;padding-bottom: 5px" colspan="2"> Broker Staff </td>
                <td style="border-bottom: 1px solid;padding-bottom: 5px;" colspan="7"><span style="background-color:#ffff99">{{ !empty($broker_staffs->broker_staff)?$broker_staffs->broker_staff->surname:'' }}<br>
                        Trail</span>
               </td>
            </tr>
                @foreach($broker_staff->deal_commission as $deal)
                    <tr>
                        <td style="width: 12%">{{ $deal->deal->client->surname??'' }}</td>
                        <td style="width: 11%">{{ $deal->deal->lender->code??'' }}</td>
                        <td style="width: 11%">{{ $deal->deal->actual_loan }}</td>
                        <td style="width: 11%">{{ $deal->deal->id }}</td>
                        @if($deal->deal->commission_model == 1)
                            <td style="width: 11%">Fixed Rate</td>
                        @elseif($deal->deal->commission_model == 2)
                            <td style="width: 11%">Flat Rate</td>
                        @elseif($deal->deal->commission_model == 3)
                            <td style="width: 11%">Variable Rate</td>
                        @else
                            <td></td>
                        @endif
                        <td style="width: 11%">{{ $deal->deal->broker_split_agg_brk_sp_trail }}</td>
                        <td style="width: 11%">{{ $deal->total_amount }}</td>
                        <td style="width: 11%">{{ $deal->agg_amount }}</td>
                        <td style="width: 11%">{{ $deal->broker_amount }}</td>
                        <?php
                        $total_actual_loan+=$deal->deal->actual_loan;
                        $total_agg_amount+=$deal->agg_amount;
                        $total_total_amount+=$deal->total_amount;
                        $total_broker_amount+=$deal->broker_amount;
                        ?>
                    </tr>
                @endforeach
            <table style="width: 100%;margin-top: 5px" >
                <thead class="gross_tota_trail">
                    <tr>
                        <th  ></th>
                        <th style="width: 12%">Total Trail</th>
                        <th style="width: 11%">{{ $total_actual_loan }}</th>
                        <th style="width: 11%"></th>
                        <th style="width: 11%"></th>
                        <th style="width: 11%"></th>
                        <th style="width: 11%">{{$total_total_amount}}</th>
                        <th style="width: 11%">{{ $total_agg_amount }}</th>
                        <th style="width: 11%">{{ $total_broker_amount }}</th>
                    </tr>
                </thead>
            </table>
            @endforeach
            </tbody>

        </table>

    @endforeach
@else
    <table style="width: 100%;margin-top: 5px" >
        <thead class="thead_style">
        <tr>
            <th>Client</th>
            <th>Institute</th>
            <th>Loan Amount</th>
            <th>Deal Id</th>
            <th>Model</th>
            <th>%</th>
            <th>Amount</th>
            <th>FMA</th>
            <th>Broker</th>
        </tr>
        </thead>
        <tbody class="body_class">
        </tbody>
    </table>
@endif

</body>
</html>
