<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lenders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LendersController extends Controller
{
    public function index() {
        return view('admin.lenders.index');
    }

    public function saveData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:lenders',
            'code' => 'required|unique:lenders'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $lenders = new Lenders();
        $lenders->name = $request['name'];
        $lenders->code = $request['code'];
        $lenders->commis_type = $request['commis_type'];
        $lenders->created_at = Carbon::now('utc')->toDateTimeString();
        $lenders->updated_at = Carbon::now('utc')->toDateTimeString();
        $lenders->save();

        return response()->json(['success'=>'Record added successfully!']);
    }

    public function getRecords(Request $request)
    {
        try{
            $input =  $request->all();

            $datatableData = arrangeArrayPair( obj2Arr( $input ), 'name', 'value' );

            $start = 0;
            $rowperpage = 25;
            if ( isset( $datatableData['iDisplayStart'] ) && $datatableData['iDisplayLength'] != '-1' ) {
                $start =  intval( $datatableData['iDisplayStart'] );
                $rowperpage = intval( $datatableData['iDisplayLength'] );
            }

            // Ordering
            $sortableColumnsName = [
                'id',
                'code',
                'name',
                'commis_type',
                'created_at',
                'updated_at',
                '',
            ];
            $columnName              = "created_at";
            $columnSortOrder              = "DESC";
            if ( isset( $datatableData['iSortCol_0'] ) ) {
                $sOrder = "ORDER BY  ";
                for ( $i = 0; $i < intval( $datatableData['iSortingCols'] ); $i ++ ) {
                    if ( (bool) $datatableData[ 'bSortable_' . intval( $datatableData[ 'iSortCol_' . $i ] ) ] == TRUE && isset( $datatableData[ 'sSortDir_' . $i ] ) ) {
                        $columnSortOrder = ( strcasecmp( $datatableData[ 'sSortDir_' . $i ], 'ASC' ) == 0 ) ? 'ASC' : 'DESC';
                        $columnName  = $sortableColumnsName[ intval( $datatableData[ 'iSortCol_' . $i ] ) ];

                    }
                }

                if ( $columnName == "" ) {
                    $columnName              = "created_at";
                    $columnSortOrder              = "DESC";
                }
            }
            $searchValue = '';
            if ( isset( $datatableData['sSearch'] ) && $datatableData['sSearch'] != "" ) {
                $searchValue = $datatableData['sSearch'];
            }
            // Total records
            $totalRecords =Lenders::select('count(*) as allcount')->whereNull('deleted_at')->count();
             $filterSql =Lenders::select('count(*) as allcount')->whereNull('deleted_at');
            if($searchValue!='')
                $sql =$filterSql->where('name', 'like', '%' .$searchValue . '%');

            $totalRecordswithFilter = $filterSql->count();
            // Fetch records
            $sql =Lenders::select(DB::raw('id, name, code, IF(commis_type=1,"%","Fee per Deal") as commis_type, DATE_FORMAT(created_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_created_at, DATE_FORMAT(updated_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_updated_at'))->orderBy($columnName,
                $columnSortOrder)->whereNull('deleted_at')
                                ->skip($start)
                                ->take($rowperpage);
                if($searchValue!='')
                    $sql =$sql->where('name', 'like', '%' .$searchValue . '%');

            $records = $sql->get();

            $response = array(
                "sEcho" => intval( ( isset( $datatableData['sEcho'] ) ? $datatableData['sEcho'] : 0 ) ),
                "draw" => intval( ( isset( $datatableData['sEcho'] ) ? $datatableData['sEcho'] : 0 ) ),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $records
            );

            return response()->json(['success'=>true,'payload'=>$response]);
        } catch (\Exception $e)
        {
            return response()->json(['error'=>$e->getMessage()]);
        }
    }

    public function getRecord(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id' => "required|integer"
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            $record =Lenders::find($request->id);
            if(!$record)
            {
                return response()->json(['error'=>'Record not found']);
            }
            return response()->json(['success'=>true,'payload'=>$record]);
        } catch (\Exception $e)
        {
            return response()->json(['error'=>$e->getMessage()]);
        }
    }

    public function updateData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => "required|integer",
            'name' => "required|unique:lenders,id,".$request->id,
            'code' => "required|unique:lenders,id,".$request->id
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $LendersLenders =Lenders::find($request->id);
        if(!$LendersLenders)
        {
            return response()->json(['error'=>'Invalid record!']);
        }
        $LendersLenders->name = $request['name'];
        $LendersLenders->commis_type = $request['commis_type'];
        $LendersLenders->code = $request['code'];
        $LendersLenders->save();

        return response()->json(['success'=>'Record updated successfully!']);
    }

    public function deleteData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => "required|integer"
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $LendersLenders =Lenders::find($request->id);
        if(!$LendersLenders)
        {
            return response()->json(['error'=>'Invalid record!']);
        }

        $LendersLenders->delete();
        return response()->json(['success'=>'Record deleted successfully!']);
    }
}
