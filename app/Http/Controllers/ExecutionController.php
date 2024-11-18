<?php

namespace App\Http\Controllers;

use App\Models\Execution;
use App\Models\Operation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExecutionController
{
    public function show(Execution $execution): Execution
    {
        return $execution;
    }

    public function finish(Execution $execution, Request $request): RedirectResponse
    {
        $execution->update([
           'end_at' => now(),
           'sale_value' => $request->sale_value,
           'sale_dollar_value' => $request->sale_dollar_value
        ]);

        $this->analiseOperation();

        return to_route('operation.index');
    }

    public function destroy(Execution $execution): RedirectResponse
    {
        $execution->delete();

        return to_route('operation.index');
    }

    private function analiseOperation()
    {
        $operation = DB::query('executions as e')
        ->where('e.user_id', '=', Auth::user()->id)
        ->where('e.end_at', 'is null');

//        dd($operation);
//        $operations = Operation::where('user_id', Auth::user()->id)->with('executions')->get();

    }
}
