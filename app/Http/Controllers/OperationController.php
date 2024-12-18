<?php

namespace App\Http\Controllers;

use App\Models\Execution;
use App\Models\Operation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\s;


class OperationController extends Controller
{
    public function index()
    {
        $operation = Operation::where('user_id', Auth::user()->id)
            ->whereNull('end_at')
            ->with(['executions' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->get();

        $open = $totalValue = $gain = 0;
        $isSale = $isPurchase = false;
        foreach ($operation as $item) {
            $item->start_at = Execution::translateDateToBRL($item->start_at);
            foreach ($item->executions as $execution) {
                if ($execution->end_at === null) {
                    $open += 1;
                    $totalValue += $execution->purchase_value;
                    if ($execution->type === 'S') $isSale = true;
                    if ($execution->type === 'P') $isPurchase = true;
                } else {
                    $gain += $execution->average_value;
                }

                $execution->average_value = Execution::toFloat($execution->average_value);
                $execution->purchase_value = Execution::toFloat($execution->purchase_value);
                $execution->sale_value = Execution::toFloat($execution->sale_value);
                $execution->start_at = Execution::translateDateToBRL($execution->start_at);
            }
        }

        $medValue = $this->getMedValue($gain, $totalValue, $isSale, $isPurchase, $open);

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

    public function history()
    {
        $operations = Operation::where('user_id', Auth::user()->id)
            ->whereNotNull('end_at')
            ->with('executions')
            ->orderBy('start_at', 'desc')
            ->paginate(10);

        $sumGain = [];
        foreach ($operations as $operation) {
            $operation->start_at = Execution::translateDateToBRL($operation->start_at);
            $operation->end_at = Execution::translateDateToBRL($operation->end_at);

            foreach ($operation->executions as $execution) {
                if (!array_key_exists($execution->operation_id, $sumGain)) {
                    $sumGain[$execution->operation_id] = $execution->average_value;
                } else {
                    $sumGain[$execution->operation_id] += $execution->average_value;
                }

                $execution->start_at = Execution::translateDateToBRL($execution->start_at);
                $execution->end_at = Execution::translateDateToBRL($execution->end_at);
                $execution->average_value = Execution::toFloat($execution->average_value);
                $execution->purchase_dollar_value = Execution::formatDollar($execution->purchase_dollar_value);
                $execution->sale_dollar_value = Execution::formatDollar($execution->sale_dollar_value);
                $execution->purchase_value = Execution::toFloat($execution->purchase_value);
                $execution->sale_value = Execution::toFloat($execution->sale_value);
            }
        }

        [ $highGain, $gainTotal, $midGain, $sumGain, $count ] = $this->calculateSummary();

        return view('operation.history')
            ->with('operations', $operations)
            ->with('gains', $sumGain)
            ->with('totalGain', $gainTotal)
            ->with('highGain', $highGain)
            ->with('midGain', $midGain)
            ->with('count', $count);
    }

    public function store(Request $request)
    {
        $operation = Operation::where('user_id', Auth::user()->id)->whereNull('end_at')->first();

        if (!$operation && request()->type_action === 'zerar') {
            return to_route('operation.index');
        }

        if (!$operation) {
            $this->generateOperation($request);
            return to_route('operation.index');
        }

        $orders = Execution::where('operation_id', $operation->id)->whereNull('end_at')->orderBy('start_at')->get();

        // Zerar Todos
        if ($request->type_action === 'zerar') {
            if (!$orders) return to_route('operation.index');
            return $this->finishAllOrders($orders, $request);
        }

        // Gera Novo / Vende / Compra
        if ($orders->isEmpty()) {
            $this->generateOrder($request, $operation);
            return to_route('operation.index');
        } else {
            $lastOrder = $orders->first();

            if ($request->type_action === 'vender') {
                $lastOrder->type === 'P'
                    ? $this->finishOneOrder($lastOrder, $request)
                    : $this->generateOrder($request, $operation);
            }

            if ($request->type_action === 'comprar') {
                $lastOrder->type === 'S'
                    ? $this->finishOneOrder($lastOrder, $request)
                    : $this->generateOrder($request, $operation);
            }
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

    private function getMedValue($gain, $totalValue, $isSale, $isPurchase, $open)
    {
        if ($isSale && $isPurchase) return 'Sem preço médio';

        if ($isSale) {
            $gain < 0
                ? $totalValueGainLess = $totalValue - abs($gain)
                : $totalValueGainLess = $totalValue + $gain;

            return Execution::toFloat($open ? $totalValueGainLess / $open : 0);
        }

        $gain < 0
            ? $totalValueGainLess = $totalValue + abs($gain)
            : $totalValueGainLess = $totalValue - $gain;

        return Execution::toFloat($open ? $totalValueGainLess / $open : 0);
    }

    private function calculateSummary()
    {
        $operations = Operation::where('user_id', Auth::user()->id)
            ->whereNotNull('end_at')
            ->with('executions')
            ->get();

        $sumGain = [];
        foreach ($operations as $operation) {
            foreach ($operation->executions as $execution) {
                !array_key_exists($execution->operation_id, $sumGain)
                    ? $sumGain[$execution->operation_id] = $execution->average_value
                    : $sumGain[$execution->operation_id] += $execution->average_value;
            }
        }

        $count = $operations->count();
        $highGain = Execution::toFloat($sumGain ? max($sumGain) : 0);
        $gainTotal = Execution::toFloat(array_sum($sumGain));
        $midGain = Execution::toFloat(count($sumGain) ? array_sum($sumGain) / count($sumGain) : 0);
        $sumGain = array_map(function ($item) {
            return Execution::toFloat($item);
        }, $sumGain);

        return [
            $highGain,
            $gainTotal,
            $midGain,
            $sumGain,
            $count
        ];
    }

    private function generateOperation($request)
    {
        DB::transaction(function() use($request) {
            $operation = Operation::create([
                'code' =>  $request->code,
                'start_at' => now(),
                'user_id' =>  Auth::user()->id,
            ]);

            $operation->executions()->create([
                'type' => $request->type_action === 'vender' ? 'S' : 'P',
                'purchase_value' => Execution::toInteger($request->value),
                'purchase_dollar_value' => Execution::toInteger($request->dollar_value),
                'start_at' => now(),
            ]);
        });
    }

    private function generateOrder($request, $operation)
    {
        DB::transaction(function() use($request, $operation) {
            $operation->executions()->create([
                'type' => $request->type_action === 'vender' ? 'S' : 'P',
                'purchase_value' => Execution::toInteger($request->value),
                'purchase_dollar_value' => Execution::toInteger($request->dollar_value),
                'start_at' => now(),
            ]);
        });
    }

    private function finishOneOrder($order, $request)
    {
        DB::transaction(function() use($order, $request) {
            $order->update([
                'end_at' => now(),
                'sale_dollar_value' => Execution::toInteger($request->dollar_value),
                'sale_value' => $request->value,
                'average_value' => $this->calcGain($order->type, $order->purchase_value, $request->saleValue),
            ]);
        });
    }

    private function finishAllOrders($orders, $request)
    {
        DB::transaction(function() use($orders, $request) {
            foreach ($orders as $order) {
                $order->update([
                    'end_at' => now(),
                    'sale_dollar_value' => Execution::toInteger($request->dollar_value),
                    'sale_value' => $request->value,
                    'average_value' => $this->calcGain($order->type, $order->purchase_value, $request->saleValue),
                ]);
            }
        });

        return to_route("operation.index");
    }


    private function calcGain($type, $initialValue, $finalValue)
    {
        if ($type === Execution::PURCHASED_TYPE) {
            return $finalValue - $initialValue;
        } else {
            return $initialValue - $finalValue;
        }
    }
}
