<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Category::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'is_active' => $request->boolean(
                'is_active',
                true
            ),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'دسته‌بندی با موفقیت ایجاد شد.'
            );
    }

    public function edit(Category $category)
    {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    public function update(
        Request $request,
        Category $category
    ) {
        $data = $this->validateData(
            $request,
            $category
        );

        $category->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'دسته‌بندی با موفقیت ویرایش شد.'
            );
    }

    public function toggle(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active,
        ]);

        return back()->with(
            'success',
            $category->is_active
                ? 'دسته‌بندی فعال شد.'
                : 'دسته‌بندی غیرفعال شد.'
        );
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'این دسته‌بندی دارای محصول است و قابل حذف نیست. ابتدا محصولات آن را جابه‌جا کنید.'
            );
        }

        $category->delete();

        return back()->with(
            'success',
            'دسته‌بندی با موفقیت حذف شد.'
        );
    }

    private function validateData(
        Request $request,
        ?Category $category = null
    ): array {
        $uniqueSlug = 'unique:categories,slug';

        if ($category) {
            $uniqueSlug .= ',' . $category->id;
        }

        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9_-]+$/',
                $uniqueSlug,
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);
    }
}