<html lang="en">
<head>
    <title>Referrer Commission Rating Summary</title>

</head>
<body style=" font-family: system-ui, system-ui, sans-serif;">
<style>
    table {
        border: 0;
        border-collapse: separate;
        border-spacing: 0 5px;
    }
    tbody tr td {
        letter-spacing: 1px;
    }

    .thead_style tr th {
        border-bottom: 1px solid grey;
        font-family: Arial, sans-serif;
        border-collapse: separate;
        border-spacing: 5px 5px;
        text-align: left;
        font-weight: 800;
        letter-spacing: 2px;
        font-size: 13px;
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
        <td style="width: 40%"> <span style="font-size: 24px;font-weight: bold;font-family: Arial, Helvetica, sans-serif">Referrer Commission Rating Summary</span></td>
        <td> <span style="width: 50%;">For Dates paid from {{ $date_from.' to '.$date_to }}</span></td>
    </tr>
    </tbody>
</table>
<table style="margin-top: 15px;margin-bottom:5px;margin-left: auto;margin-right: auto;width: 50%;border-bottom: 1px solid">
    <tbody>
    <tr>
        <td style="text-align: center;font-size: 18px;font-weight: bold;margin-left: auto;margin-right: auto">UPFRONT COMMISSION</td>
    </tr>
    </tbody>
</table>
@if(count($deals)>0)
    @foreach($deals as $deal)

        <table style="width: 100%;margin-top: 5px" >
            <thead class="thead_style">
            <tr>
                <th>Referrer</th>
                <th>No.of Loans</th>
                <th>Loan Amount</th>
                <th>Gross Upfront</th>
                <th>% Paid to Ref</th>
                <th>Referrer Upfront</th>
                <th>FMD Upfront</th>
                <th>Average FMD Upfront</th>
            </tr>
            </thead>
            <tbody class="body_class">
                @foreach($deals as $deal)
                    <tr>
                        <td>{{ $deal->referrer->surname??'' }}</td>
                        <td>{{ $deal->NumberOfLoansUpfront }}</td>
                        <td>{{ $deal->SumOfLoansUpfront }}</td>
                        <td>{{ $deal->SumOfdea_UpfrontEst_ABP+$deal->SumOfdea_BrokerageEst_ABP }}</td>
                        <td><?php
                            $d=\App\Models\Deal::where('referror_split_referror',$deal->referror_split_referror)->orderBy('id','desc')->first();
                            echo $d->referror_split_agg_brk_sp_upfrt;
                            ?></td>
                        <td>{{ $deal->ReferrorUpfront }}</td>
                        <td>{{ ($deal->SumOfdea_UpfrontEst_ABP+$deal->SumOfdea_BrokerageEst_ABP)-$deal->ReferrorUpfront }}</td>
                        <td><?php
                            if($deal->NumberOfLoansUpfront){
                               echo (($deal->SumOfdea_UpfrontEst_ABP+$deal->SumOfdea_BrokerageEst_ABP)-($deal->ReferrorUpfront))/$deal->NumberOfLoansUpfront;
                            }else{
                                echo "0";
                            }
                            ?></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endforeach
@else
    <table style="width: 100%;margin-top: 5px" >
        <thead class="thead_style">
        <tr>
            <th>Referrer</th>
            <th>No.of Loans</th>
            <th>Loan Amount</th>
            <th>Gross Upfront</th>
            <th>% Paid to Ref</th>
            <th>Referrer Upfront</th>
            <th>FMD Upfront</th>
            <th>Average FMD Upfront</th>
        </tr>
        </thead>
        <tbody class="body_class">
        </tbody>
    </table>
@endif

@if(count($deals)>0)
    <table style="width: 100%;margin-top: 5px" >
        <tr>
            <td>
                <table class="grand_total"  style="width: 100%">
                    <tr>
                        <th colspan="9"><?php echo 'Grand Total' ?></th>

                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endif

</body>
</html>
