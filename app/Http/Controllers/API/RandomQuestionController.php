<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;

class RandomQuestionController extends Controller
{
    public function randomQuestion(Request $request)
    {
        try {
            $randomQuestion = Question::inRandomOrder()->where('is_active', true)->first();

            if (!$randomQuestion) {
                throw new Exception('Failed get random question');
            }

            $randomQuestion->increment('amount_appear');

            return ResponseFormatter::success($randomQuestion, 'Succes get random question');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
}
