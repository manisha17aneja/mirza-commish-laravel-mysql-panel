<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Commission Monitoring</title>

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
<table style="margin-top: 5px;margin-bottom:5px;width: 100%">
    <tbody>
    <tr>
        <td style="width: 25%"> <span style="font-size: 18px;font-weight: bold;">ABP Commission Monitoring</span></td>
        <td> <span style="width: 60%;font-weight: bold;">For Dates Paid from {{ $date_from.' to '.$date_to }}</span></td>
    </tr>
    </tbody>
</table>

@if(count($brokers)>0)

    @foreach($brokers as $broker)
        <table class="row" style="margin-top: 5px;margin-bottom:5px;width: 100%">
            <tbody>
            <tr>
                <td style="width: 25%;background-color: #ffff99"> <span style="font-weight: bold;"><?php
                        echo $broker->surname??'';
                        ?></span></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        
        <?php
        $broker_est_loan_amt=0;
        $broker_est_upfront=0;
        $broker_est_brokerage=0;

        ?>

        <table style="width: 100%;margin-top: 5px" >
            <thead class="thead_style">
            <tr>
                <th style="width: 3%">Deal</th>
                <th style="width: 20%">Client</th>
                <th style="width: 20%">Lender</th>
                <th style="width: 20%">Loan Amount Esst.</th>
                <th style="width: 20%">Actual</th>
                <th style="width: 10%">Diff</th>
                <th style="width: 20%">Upfront Paid</th>
                <th style="width: 20%">Est</th>
                <th style="width: 7%">Actual</th>
                <th style="width: 7%">Diff</th>
                <th style="width: 7%">Trail Paid</th>
                <th style="width: 7%">Est</th>
                <th style="width: 7%">Actual</th>
                <th style="width: 7%">Diff</th>
            </tr>
            </thead>
            <tbody class="body_class">
            @if(count($broker->deals) > 0)

            @foreach($broker->deals as $deal_list)
            
                <?php
                $deal_trail=\App\Models\DealCommission::where('type',12)->where('deal_id',$deal_list->id)->orderBy('id','desc')->first();

                ?>
                <tr>

                    <td>{{ $deal_list->id }}</td>
                    <td>{{ $deal_list->client->surname??'' }}</td>
                    <td>{{ $deal_list->lender->name??'' }}</td>
                    <td>${{ $deal_list->broker_est_loan_amt??'0' }}</td>
                    <td>${{ $deal_list->broker_est_loan_amt-$deal_list->actual_loan }}</td>
                    @if(count($deal_list->deal_commission) > 0)
                        <td>{{ $deal_list->deal_commission->bro_amt_date_paid??''}}</td>
                        <td>{{ $deal_list->deal_commission->broker_amount }}</td>
                    @else
                        <td></td>
                        <td></td>
                    @endif
                    <td>{{ $deal_list->broker_est_upfront }}</td>
                    @if(count($deal_list->deal_commission) > 0)
                        <td>{{ $deal_list->deal_commission->broker_amount-$deal_list->broker_est_upfront }}</td>
                    @else
                        <td></td>
                    @endif
                   @if($deal_trail != null)
                        <?php
                            $broker_est_loan_amt+=$deal_list->broker_est_loan_amt;
                            $broker_est_brokerage+=$deal_list->broker_est_brokerage;
                            $broker_est_upfront+=$deal_list->broker_est_upfront;
                        ?>
                        <td>{{ $deal_trail->bro_amt_date_paid }}</td>
                        <td>{{ $deal_trail->broker_amount }}</td>
                        <td>{{ $deal_trail->broker_est_trail}}</td>
                        <td>{{ $deal_trail->broker_amount-$deal_trail->broker_est_trail}}</td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      @endif
                </tr>
               
            @endforeach
                @endif
            </tbody>
            <thead class="subtotal">
            <tr>
                <th colspan="4"><?php
                    echo 'Subtotals';
                    ?></th>
                <th style="text-align: left;width: 7%">${{ $broker_est_loan_amt }}</th>
                <th style="text-align: left;width: 7%">${{$broker_est_upfront}}</th>
                <th style="text-align: left;width: 7%">${{ $broker_est_brokerage }}</th>
            </tr>

            </thead>
        </table>

    @endforeach
@else

    <table style="width: 100%;margin-top: 5px" >
        <thead class="thead_style">
        <tr>
            <th style="">Deal</th>
            <th style="">Client</th>
            <th style="">Lender</th>
            <th style="">Loan Amount Esst.</th>
            <th style="">Actual</th>
            <th style="">Diff</th>
            <th style="">Upfront Paid</th>
            <th style="">Est</th>
            <th style="">Actual</th>
            <th style="">Diff</th>
            <th style="">Trail Paid</th>
            <th style="">Est</th>
            <th style="">Actual</th>
            <th style="">Diff</th>
        </tr>
        </thead>
        <tbody class="body_class">
        </tbody>
    </table>
@endif

</body>
</html>
