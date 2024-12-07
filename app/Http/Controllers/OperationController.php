<?php

namespace App\Http\Controllers;

use App\Models\Execution;
use App\Models\Operation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OperationController extends Controller
{
    public function index()
    {
        $operation = Operation::where('user_id', Auth::user()->id)
            ->whereNull('end_at')
            ->with('executions')
            ->get();

        $open = $totalValue = $gain = $total = 0;
        foreach ($operation as $item) {
            $item->start_at = Execution::translateDateToBRL($item->start_at);
            $total = count($item->executions);
            foreach ($item->executions as $execution) {
                if ($execution->end_at === null) {
                    $open += 1;
                    $totalValue += $execution->purchase_value;
                } else {
                    $gain += $execution->average_value;
                }

                $execution->average_value = Execution::toFloat($execution->average_value);
                $execution->purchase_value = Execution::toFloat($execution->purchase_value);
                $execution->sale_value = Execution::toFloat($execution->sale_value);
                $execution->start_at = Execution::translateDateToBRL($execution->start_at);
            }
        }

        if ($gain < 0) {
            $totalValueGainLess = $totalValue - abs($gain);
        } else {
            $totalValueGainLess = $totalValue + $gain;
        }

        $medValue = Execution::toFloat($open ? $totalValueGainLess / $total : 0);

        return view('operation.index')
            ->with('operations', $operation)
            ->with('open', $open)
            ->with('medValue', $medValue)
            ->with('gain', Execution::toFloat($gain));
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

                for ($i = 1; $i <= $request->quantity; $i++) {
                    $operation->executions()->create([
                        'type' => $request->type,
                        'purchase_value' => Execution::toInteger($request->purchase_value),
                        'purchase_dollar_value' => Execution::toInteger($request->purchase_dollar_value),
                        'start_at' => now(),
                    ]);
                }
            });
        } else {
            DB::transaction(function() use($request, $operation) {
                for ($i = 1; $i <= $request->quantity; $i++) {
                    $operation->executions()->create([
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

    public function finish(Operation $operation): RedirectResponse
    {
        $operation->update([
            'end_at' => now(),
        ]);

        return to_route('operation.index');
    }
}
