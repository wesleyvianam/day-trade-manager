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
    private $gain = 0;

    private $medValue = 0;

    public function index()
    {
        $operation = Operation::where('user_id', Auth::user()->id)
            ->whereNull('end_at')
            ->with(['executions' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->get();

        $open = $gain = 0;
        foreach ($operation as $item) {
            $item->start_at = Execution::translateDateToBRL($item->start_at);
            $item->average_value = Execution::toFloat($item->average_value);
            $item->gain = Execution::toFloat($item->gain);

            foreach ($item->executions as $execution) {
                if ($execution->end_at === null) $open += 1;

                $execution->start_value = Execution::toFloat($execution->start_value);
                $execution->end_value = Execution::toFloat($execution->end_value);
                $execution->gain = Execution::toFloat($execution->gain);
                $execution->start_at = Execution::translateDateToBRL($execution->start_at);
            }
        }

        return view('operation.index')
            ->with('operations', $operation)
            ->with('open', $open)
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

        $sumGain = Execution::toFloat($operations->sum('gain'));
        foreach ($operations as $operation) {
            $operation->start_at = Execution::translateDateToBRL($operation->start_at);
            $operation->end_at = Execution::translateDateToBRL($operation->end_at);
            $operation->gain = Execution::toFloat($operation->gain);

            foreach ($operation->executions as $execution) {
                $execution->start_at = Execution::translateDateToBRL($execution->start_at);
                $execution->end_at = Execution::translateDateToBRL($execution->end_at);
                $execution->gain = Execution::toFloat($execution->gain);
                $execution->start_dollar_value = Execution::toFloat($execution->start_dollar_value);
                $execution->end_dollar_value = Execution::toFloat($execution->end_dollar_value);
                $execution->start_value = Execution::toFloat($execution->start_value);
                $execution->end_value = Execution::toFloat($execution->end_value);
            }
        }

        [ $highGain, $midGain, $gainTotal, $count ] = $this->calculateSummary();

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
                $sumGain[$execution->id] = $execution->gain;
            }
        }
        $count = $operations->count();
        $highGain = Execution::toFloat($sumGain ? max($sumGain) : 0);
        $midGain = Execution::toFloat(count($sumGain) ? array_sum($sumGain) / count($sumGain) : 0);
        $sum = Execution::toFloat(array_sum($sumGain));

        return [
            $highGain,
            $midGain,
            $sum,
            $count
        ];
    }

    private function generateOperation($request)
    {
        DB::transaction(function() use($request) {
            $operation = Operation::create([
                'code' => $request->code,
                'start_at' => now(),
                'user_id' => Auth::user()->id,
            ]);

            $this->generateOrder($request, $operation);
        });
    }

    private function generateOrder($request, $operation)
    {
        DB::transaction(function() use($request, $operation) {
            $operation->executions()->create([
                'type' => $request->type_action === 'vender' ? 'S' : 'P',
                'start_value' => Execution::toInteger($request->value),
                'start_dollar_value' => Execution::toInteger($request->dollar_value),
                'start_at' => now(),
            ]);

            [$averageValue, $gain] = $this->calcAverage($operation);

            $operation->update([
                'average_value' => $averageValue,
                'gain' => $gain,
            ]);
        });
    }

    private function finishOneOrder($order, $request)
    {
        DB::transaction(function() use($order, $request) {
            $this->calcGain($order, $request);

            $order->update([
                'end_at' => now(),
                'end_dollar_value' => Execution::toInteger($request->dollar_value),
                'end_value' => Execution::toInteger($request->value),
                'gain' => $this->gain,
            ]);

            $operation = Operation::where('id', $order->operation_id)->first();

            [$averageValue, $gain] = $this->calcAverage($operation);

            $operation->update([
                'average_value' => $averageValue,
                'gain' => $gain,
            ]);
        });
    }

    private function finishAllOrders($orders, $request)
    {
        foreach ($orders as $order) {
            $this->finishOneOrder($order, $request);
        }

        return to_route("operation.index");
    }

    private function calcGain($order, $request)
    {
        $operation = Operation::where('id', $order->operation_id)->with('executions')->first();
        if ($request->type_action === Execution::PURCHASED) {
            $this->gain = $operation->average_value - Execution::toInteger($request->value);
        } else {
            $this->gain = Execution::toInteger($request->value) - $operation->average_value;
        }
    }

    private function calcAverage($operation)
    {
        $orders = Execution::where('operation_id', $operation->id)->get();

        $open = $orders->filter(function ($execution) {
            return $execution->end_at === null;
        });

        $gain = $orders->map(function ($execution) {
            if ($execution->end_at !== null) {
                return $execution->gain;
            }
            return 0;
        });

        $avarageValue = $orders->map(function ($execution) {
            return $execution->end_at === null ? $execution->start_value : 0;
        });

        if ($open->count() > 0) {
            $soma = ($avarageValue->sum() / $open->count()) - $gain->sum();
            return [$soma, $gain->sum()];
        }

        return [0, $gain->sum()];
    }

    private function recalculateOperation($order)
    {
        $operation = Operation::where('id', $order->operation_id)->with('executions')->first();

        $isOpen = $operation->executions->filter(function ($execution) {
            return is_null($execution->end_at);
        })->isNotEmpty();

        $isFinish = $operation->executions->filter(function ($execution) {
            return $execution->end_at !== null;
        });

        $gain = $isFinish->map(function ($execution) use ($isOpen) {
            return $execution->gain;
        });

        $averageValue = $operation->executions->map(function ($execution) {
            return $execution->end_at === null ? $execution->start_value : 0;
        });

        $operation->update([
            'average_value' => $averageValue,
            'gain' => $gain->sum(),
        ]);
    }
}
