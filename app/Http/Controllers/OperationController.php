<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operation;
use App\Models\OperationConfirm;
use Storage;
use DB;

class OperationController extends Controller
{
    public function get()
    {
        $operations = Operation::get()->toJson();
        return $operations;
    }

    public function getOperationConfirm($id)
    {
        $operationConfrim = OperationConfirm::where('operation_id', $id)->get()->toJson();
        return $operationConfrim;
    }

    public function saveOperationConfirm(Request $request, $operationId)
    {
        $rows = $request->all()['rows'];
        $operationConfrim = OperationConfirm::where('operation_id', $operationId)->delete();
        $idCol = $rows['id'];
        $filteredArr = [];
        foreach($idCol as $index => $id){
            $filteredArr[] = [
                'id' => $id,
                'description' => $rows['description'][$index],
                'uploadFile' => $rows['uploadFile'][$index],
                'file' => $rows['file'][$index],
            ];
        }

        foreach($filteredArr as $row){
            if($row['description']){
                $operation = new OperationConfirm();

                if($row['uploadFile']){
                    $fileName = pathinfo($row['uploadFile']->getClientOriginalName())['filename'] . "_". time() . "." . $row['uploadFile']->extension();;
                    $path = Storage::putFileAs('public/operationConfirm', $row['uploadFile'], $fileName);
                    $operation->fileUrl = '/storage/operationConfirm/' . $fileName;
                } else if($row['file']){
                    $operation->fileUrl = $row['file'];
                }
                $operation->description = $row['description'];
                $operation->operation_id = $operationId;

                $operation->save();
            }
        }

        return OperationConfirm::where('operation_id', $operationId)->get()->toJson();
    }
}   
