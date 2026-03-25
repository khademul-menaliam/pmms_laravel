<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $type = $request->string('type')->toString();

        $categories = Category::query()
            ->ownedBy($user)
            ->when($type, fn ($query) => $query->where('type', $type))
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('categories.index', [
            'categories' => $categories,
            'type' => $type,
            'summary' => [
                'income' => Category::query()->ownedBy($user)->income()->count(),
                'expense' => Category::query()->ownedBy($user)->expense()->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('categories.create', [
            'category' => new Category(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['name'], $data['type']);

        Category::create($data);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(string $id): never
    {
        abort(404);
    }

    public function edit(Request $request, string $category): View
    {
        $category = $this->resolveOwnedModel($request, 'category', Category::class);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, string $category): RedirectResponse
    {
        /** @var \App\Models\Category $category */
        $category = $this->resolveOwnedModel($request, 'category', Category::class);

        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['name'], $data['type'], $category);

        $category->update($data);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Request $request, string $category): RedirectResponse
    {
        /** @var \App\Models\Category $category */
        $category = $this->resolveOwnedModel($request, 'category', Category::class);

        if ($category->incomes()->exists() || $category->expenses()->exists()) {
            return back()->with('error', 'This category already has transactions and cannot be deleted.');
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(array_keys(Category::TYPE_OPTIONS))],
            'color' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    protected function uniqueSlug(string $name, string $type, ?Category $ignore = null): string
    {
        $baseSlug = Str::slug($name) ?: $type.'-category';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            Category::query()
                ->ownedBy(auth()->user())
                ->where('type', $type)
                ->where('slug', $slug)
                ->when($ignore, fn ($query) => $query->whereKeyNot($ignore))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
