<?php

namespace App\Http\Controllers;

use App\Models\Execution;
use App\Models\Operation;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OperationController extends Controller
{
    public function index()
    {
        $operations = Operation::where('user_id', Auth::user()->id)->with('executions')->get();
//        dd($operations);

        foreach ($operations as &$operation) {
//            $operation->start_at = Execution::translateDateToBRL($operation->start_at);
            foreach ($operation->executions as &$execution) {
                $execution->purchase_value = Execution::toFloat($execution->purchase_value);
//                $execution->start_at = Execution::translateDateToBRL($operation->start_at);
            }
        }
        return view('operation.index')->with('operations', $operations);
    }

    public function show(Operation $operation)
    {
        return $operation;
    }

    public function store(Request $request)
    {

        // VERIFICA SE TEM ALGUMA OPERAÇÃO ABERTA
        $operation = Operation::whereNull('end_at')->first();

        if (!$operation) {
            DB::transaction(function() use($request) {
                $operation = Operation::create([
                    'code' =>  $request->code,
                    'start_at' => now(),
                    'user_id' =>  Auth::user()->id,
                ]);

                for ($i = 0; $i <= $request->quantity; $i++) {
                    $operation->executions()->create([
                        'quantity' => 1,
                        'type' => $request->type,
                        'purchase_value' => Execution::toInteger($request->purchase_value),
                        'start_at' => now(),
                    ]);
                }
            });
        } else {
            DB::transaction(function() use($request, $operation) {
                for ($i = 1; $i <= $request->quantity; $i++) {
                    $operation->executions()->create([
                        'quantity' => 1,
                        'type' => $request->type,
                        'purchase_value' => Execution::toInteger($request->purchase_value),
                        'purchase_dollar_value' => Execution::toInteger($request->purchase_dollar_value),
                        'start_at' => now(),
                    ]);
                }
            });
        }

        return to_route('operation.index');
    }

    public function destroy(Operation $operation): RedirectResponse
    {
        $operation->delete();

        return to_route('operation.index');
    }
}
