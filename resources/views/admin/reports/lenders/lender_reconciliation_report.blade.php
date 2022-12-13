<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Lender Reconciliation</title>

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
        letter-spacing: 1px;
        text-align: left;
        font-weight: 800;
        font-size: 15px;
    }
    .subtotal tr td {
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
        <td style="width: 25%"> <span style="font-size: 18px;font-weight: bold;">Lender Commission Reconciliation</span></td>
        <td> <span style="width: 60%;">For Statements Received between {{ $date_from.' to '.$date_to }}</span></td>
    </tr>
    </tbody>
</table>

@if(count($lenders)>0)
    <table style="width: 100%;margin-top: 5px" >
        <thead class="thead_style">
        <tr>
            <th style="width:12.5%;">Received</th>
            <th style="width:12.5%;">Agg Upfront</th>
            <th style="width:12.5%;">Trail</th>
            <th style="width:12.5%;">Trail No Gst</th>
            <th style="width:12.5%;">ABP Upfront</th>
            <th style="width:12.5%;">Trail</th>
            <th style="width:12.5%;">Trail No Gst</th>
            <th style="width:12.5%;">Total</th>

        </tr>
        </thead>
        <tbody class="body_class">
    @foreach($lenders as $lender)
        <?php
        $commssions=\App\Models\DealCommission::
        select(\DB::raw('deals.gst_applies,deal_commissions.id,date_statement,
            Sum(If(`type`=12 AND deals.gst_applies=1,agg_amount,0)) AS agg_gst,
            Sum(If(`type`=12 AND deals.gst_applies=0,agg_amount,0)) AS agg_no_gst,
            Sum(If(`type`=13,`agg_amount`,0)) AS agg_upfront,
            Sum(If(`type`=13 ,broker_amount,0)) AS abp_upfront,
            Sum(If(`type`=12 AND deals.gst_applies=0,broker_amount,0)) AS abp_no_gst,
            Sum(If(`type`=12 AND deals.gst_applies=1,broker_amount,0)) AS abp_gst,deal_id
            '))
            ->join('deals','deals.id','=','deal_commissions.deal_id')
            ->join('lenders','lenders.id','=','deals.lender_id')->where('lenders.id',$lender->id)
            ->where('date_statement','>=',date('Y-m-d',strtotime($date_from)))
        ->where('date_statement','<=',date('Y-m-d',strtotime($date_to)))
            ->groupBy('date_statement')->where(function ($qu){
                $qu->where('agg_amount','>',0)->orWhere('broker_amount','>',0);
            })->get();
        ?>
        <?php
        $total_abp_upfront=0;
        $total_agg_upfront=0;
        $total_abp_no_gst=0;
        $total_agg_no_gst=0;
        $total_abp_gst=0;
        $total_agg_gst=0;
        $total_com=0;

        ?>
            @if(count($commssions))
                <tr style="">
                    <td colspan="8" style="width: 100%;border-bottom: 1px solid;"> <span style="font-weight: bold;"><?php
                            echo $lender->code??'';
                            ?></span></td>
                </tr>
                @foreach($commssions as $commssion)
                    <tr>
                        <td style="width:12.5%;">{{ $commssion->date_statement }}</td>
                        <td style="width:12.5%;">
                            <?php
                            echo $commssion->agg_upfront;
                            $total_agg_upfront=+$commssion->agg_upfront;
                            ?>
                        </td>
                        <td style="width:12.5%;"><?php
                            echo $commssion->agg_gst;
                            $total_agg_gst+=$commssion->agg_gst;
                            ?></td>
                        <td style="width:12.5%;"><?php
                            echo $commssion->agg_no_gst;
                            $total_agg_no_gst+=$commssion->agg_no_gst;
                            ?></td>
                        <td style="width:12.5%;"><?php
                            echo $commssion->abp_upfront;
                            $total_abp_upfront+=$commssion->abp_upfront;
                            ?></td>
                        <td style="width:12.5%;">
                            <?php
                            echo $commssion->abp_gst;
                            $total_abp_gst+=$commssion->abp_gst;
                            ?>
                        </td>
                        <td style="width:12.5%;">
                            <?php
                            echo $commssion->abp_no_gst;
                            $total_abp_no_gst+=$commssion->abp_no_gst;
                            ?>
                        </td>
                        <td style="width:12.5%;">
                            <?php $total=$commssion->abp_no_gst+$commssion->abp_gst+$commssion->abp_upfront+$commssion->agg_gst+$commssion->agg_upfront+$commssion->agg_no_gst;
                            echo $total;
                            $total_com+=$total;
                            ?>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8">
                        <table style="width: 100%">
                            <tbody class="subtotal" style="text-align: left">
                            <tr>
                                <td style="width:12.5%;"></td>
                                <td style="width:12.5%;">${{ $total_agg_upfront }}</td>
                                <td style="width:12.5%;">${{ $total_agg_gst }}</td>
                                <td style="width:12.5%;">${{ $total_agg_no_gst }}</td>
                                <td style="width:12.5%;">${{ $total_abp_upfront }}</td>
                                <td style="width:12.5%;">${{ $total_abp_gst }}</td>
                                <td style="width:12.5%;">${{ $total_abp_no_gst }}</td>
                                <td style="width:12.5%;">${{ $total_com }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endif

    @endforeach
    </tbody>
    </table>
@else

    <table style="width: 100%;margin-top: 5px" >
        <thead class="thead_style">
        <tr>
            <th>Received</th>
            <th>Agg Upfront</th>
            <th>Trail</th>
            <th>Trail No Gst</th>
            <th>ABP Upfront</th>
            <th>Trail</th>
            <th>Trail No Gst</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody class="body_class">
        </tbody>
    </table>
@endif

</body>
</html>
