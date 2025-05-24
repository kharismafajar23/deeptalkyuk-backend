<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionCategory\CreateQuestionCategoryRequest;
use App\Http\Requests\QuestionCategory\UpdateQuestionCategoryRequest;
use App\Models\QuestionCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionCategoryController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $categoryQuery = QuestionCategory::query();

        if ($id) {
            $category = $categoryQuery->find($id);

            if ($category) {
                return ResponseFormatter::success($category, 'Category Found');
            }

            return ResponseFormatter::error('Category Not Found', 500);
        }

        if ($name) {
            $categoryQuery->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success($categoryQuery->paginate($limit), 'Category Found');
    }

    public function create(CreateQuestionCategoryRequest $request)
    {
        try {
            // Upload icon
            if ($request->hasFile('icon')) {
                $path = $request->file('icon')->store('category-question-icon');
            }

            $category = QuestionCategory::create([
                'name' => $request->name,
                'icon' => $path ?? ''
            ]);

            if (!$category) {
                throw new Exception('Failed to create Question Category');
            }

            return ResponseFormatter::success($category, 'Success create Question Category');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateQuestionCategoryRequest $request, $id)
    {
        try {
            $questioncategory = QuestionCategory::find($id);

            if (!$questioncategory) {
                throw new Exception('Question category not found');
            }

            if ($request->hasFile('icon')) {
                Storage::delete($questioncategory->icon);
                $path = $request->file('icon')->store('category-question-icon');
            }

            $questioncategory->update([
                'name' => $request->name,
                'icon' => $path ?? $questioncategory->icon
            ]);

            return ResponseFormatter::success($questioncategory, 'Success Update Question Category');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get Category
            $questioncategory = QuestionCategory::find($id);

            if (!$questioncategory) {
                throw new Exception('Question Category not found!');
            }

            // Delete category
            $questioncategory->delete();

            return ResponseFormatter::success('Success delete Question Category');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
}
