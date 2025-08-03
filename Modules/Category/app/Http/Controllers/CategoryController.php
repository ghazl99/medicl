<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Models\Category;
use Modules\Category\Repositories\CategoryRepository;
use Modules\Category\Services\CategoryService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryController extends Controller
{
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048|mimes:png,jpg',
            'subcategories' => 'nullable|array',
            'subcategories.*' => 'nullable|string|max:255',
        ]);

        $this->categoryService->store($validated + ['image' => $request->file('image')]);

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
        return view('category::show');
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
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048|mimes:png,jpg',
            'subcategories' => 'nullable|array',
            'subcategories.*' => 'nullable|string|max:255',
        ]);

        $image = $request->file('image');

        $success = $this->categoryService->updateCategory($category, $validated, $image);

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
