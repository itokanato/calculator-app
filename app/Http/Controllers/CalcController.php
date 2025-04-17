<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalcController extends Controller
{
    /**
     * 計算機画面を表示
     *
     * @return \Illuminate\View\View
     */
    public function calc()
    {
        return view('calclator.calc');
    }

    /**
     * 計算を実行
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate(Request $request)
    {
        $firstNumber = $request->input('firstNumber');
        $secondNumber = $request->input('secondNumber');
        $operator = $request->input('operator');

        $result = 0;
        switch ($operator) {
            case '+':
                $result = $firstNumber + $secondNumber;
                break;
            case '-':
                $result = $firstNumber - $secondNumber;
                break;
            case '×':
                $result = $firstNumber * $secondNumber;
                break;
            case '÷':
                if ($secondNumber == 0) {
                    return response()->json(['error' => '0で割ることはできません'], 400);
                }
                $result = $firstNumber / $secondNumber;
                break;
            default:
                return response()->json(['error' => '無効な演算子です'], 400);
        }

        return response()->json(['result' => $result]);
    }
}
