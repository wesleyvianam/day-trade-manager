<?php

namespace App\Http\Controllers;

use App\Models\Execution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExecutionController
{
    private array $orderIds;
    private string $dollarValue;
    private string $saleValue;

    public function show(Execution $execution): View
    {
        return view('execution.show', compact('execution'));
    }

    public function finish(Request $request): RedirectResponse|View
    {
        $this->orderIds = explode(',', $request->ids);
        $this->dollarValue = Execution::toInteger($request->finish_dollar_value);
        $this->saleValue = Execution::toInteger($request->finish_value);

        $orders = Execution::whereIn('id', $this->orderIds)->get();

        if ($request->type_action === 'zerar') {
            $this->finishOrder($orders);
            return to_route('operation.index');
        }

        $this->finishAndCreateNew($orders, $request->type_action === 'vender' ? Execution::SOLD_TYPE : Execution::PURCHASED_TYPE );

        return to_route('operation.index');
    }

    public function destroy(Execution $execution): RedirectResponse
    {
        $execution->delete();

        return to_route('operation.index');
    }

    private function finishOrder($orders): void
    {
        foreach ($orders as $order) {
            if (!$order->end_at) {
                $order->update([
                    'end_at' => now(),
                    'sale_dollar_value' => Execution::toInteger($this->dollarValue),
                    'sale_value' => $this->saleValue,
                    'average_value' => $this->calcGain($order->type, $order->purchase_value, $this->saleValue),
                ]);
            }
        }
    }

    private function finishAndCreateNew($orders, $type)
    {
        foreach ($orders as $order) {
            if (!$order->end_at) {
                Execution::create([
                    'operation_id' => $order->operation_id,
                    'type' => $type,
                    'purchase_value' => $this->saleValue,
                    'purchase_dollar_value' => $this->dollarValue,
                    'start_at' => now(),
                ]);

                $order->update([
                    'end_at' => now(),
                    'sale_dollar_value' => Execution::toInteger($this->dollarValue),
                    'sale_value' => $this->saleValue,
                    'average_value' => $this->calcGain($order->type, $order->purchase_value, $this->saleValue),
                ]);
            }
        }
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
