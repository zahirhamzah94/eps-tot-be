<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CourseCategoryResource;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    /*
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedSorts = ['name', 'code', 'created_at'];
        $sort = in_array($request->get('sort'), $allowedSorts, true)
            ? $request->get('sort')
            : 'name';

        $categories = CourseCategory::withCount('courses')
            ->when($request->query('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort)
            ->paginate($request->integer('per_page', 15));

        return CourseCategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = CourseCategory::create($request->validated());

        return new CourseCategoryResource($category->loadCount('courses'));
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseCategory $courseCategory)
    {
        return new CourseCategoryResource($courseCategory->loadCount('courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, CourseCategory $courseCategory)
    {
        $courseCategory->update($request->validated());

        return new CourseCategoryResource($courseCategory->loadCount('courses'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseCategory $courseCategory)
    {
        if ($courseCategory->courses()->exists()) {
            return response()->json([
                'message' => 'Cannot delete category with existing courses.',
            ], 422);
        }

        $courseCategory->delete();

        return response()->noContent();
    }
}
