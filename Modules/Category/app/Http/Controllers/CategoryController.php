<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Models\Category;
use Modules\Category\Repositories\CategoryRepository;
use Modules\Category\Services\CategoryService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:المشرف', only: ['create', 'store', 'edit', 'update', 'destroy']),

            new Middleware('role:مورد|المشرف|صيدلي', only: ['index', 'showImage','show','sidebar']),
        ];
    }

    public function __construct(
        protected CategoryService $categoryService,
        protected CategoryRepository $categoryRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryService->getAllcategories();

        return view('category::admin.index', compact('categories'));
    }

    public function sidebar()
    {
        $subcategories = $this->categoryService->getAllSubcategories();
        return view('core::layouts.main-sidebar', compact('subcategories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $validatedData = $request->validated();

        $this->categoryService->store($validatedData + ['image' => $request->file('image')]);

        return redirect()->route('category.index')->with('success', 'تمت إضافة الصنف بنجاح');
    }

    public function showImage(Media $media)
    {
        $path = $media->getPath();

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $subcategory = $this->categoryService->getSubcategoryWithMedicines($id);

        return view('category::admin.show', compact('subcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('category::admin.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
       $validatedData = $request->validated();

        $success = $this->categoryService->updateCategory($category, $validatedData);

        if ($success) {
            return redirect()->route('category.index')->with('success', 'تم تحديث الصنف بنجاح.');
        }

        return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء التحديث.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
