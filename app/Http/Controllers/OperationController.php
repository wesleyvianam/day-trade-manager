<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OperationController extends Controller
{
    public function index()
    {
        $operations = Operation::where('user_id', Auth::user()->id)->get();
        foreach ($operations as &$operation) {
            $operation->purchase_value = Operation::toFloat($operation->purchase_value);
            $operation->start_at = Operation::translateDateToBRL($operation->start_at);
        }
        return view('operation.index')->with('operations', $operations);
    }

    public function show(Operation $operation)
    {
        return $operation;
    }

    public function store(Request $request)
    {
        $operation = DB::transaction(function() use($request) {
            $save = Operation::create([
                'code' =>  $request->code,
                'quantity' =>  $request->quantity,
                'type' =>  Operation::PURCHASED_TYPE,
                'purchase_value' => Operation::toInteger($request->purchase_value),
                'start_at' =>  new DateTime(),
                'user_id' =>  Auth::user()->id,
            ]);

            return $save;
        });

        return to_route('operation.index');
    }

    public function destroy(Operation $operation)
    {
        $operation->delete();

        return to_route('operation.index');
    }
}
