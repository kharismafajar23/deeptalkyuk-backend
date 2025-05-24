<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\CreateQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $status = $request->input('status');
        $question_category_id = $request->input('question_category_id');
        $limit = $request->input('limit', 10);

        $questionQuery = Question::query();

        // Get Single Data
        if ($id) {
            $question = $questionQuery->find($id);

            if ($question) {
                return ResponseFormatter::success($question, 'Question Found');
            }

            return ResponseFormatter::error('Question Not Found', 400);
        }

        // Multiple Data
        if ($title) {
            $questionQuery->where('title', 'like', '%' . $title . '%');
        }

        if ($status) {
            $questionQuery->where('is_active', $status);
        }

        if ($question_category_id) {
            $questionQuery->where('question_category_id', $question_category_id);
        }

        return ResponseFormatter::success(
            $questionQuery->paginate($limit),
            'Question Found'
        );
    }

    public function create(CreateQuestionRequest $request)
    {
        try {
            $question = Question::create([
                'title' => $request->title,
                'question_category_id' => $request->question_category_id,
                'amount_appear' => 0,
                'is_active' => false
            ]);

            if (!$question) {
                throw new Exception('Failed create question');
            };

            return ResponseFormatter::success($question, 'Success create question');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateQuestionRequest $request, $id)
    {
        try {
            // Cari Question dengan id
            $question = Question::find($id);

            if (!$question) {
                throw new Exception('Question Not Found');
            }

            // Update Question
            $question->update([
                'title' => $request->title,
                'is_active' => $request->is_active,
                'amount_appear' => $request->amount_appear,
                'question_category_id' => $request->question_category_id
            ]);

            return ResponseFormatter::success($question, 'Success update question');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $question = Question::find($id);

            if (!$question) {
                throw new Exception('Question not found');
            }

            $question->delete();

            return ResponseFormatter::success('Question deleted');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
}
