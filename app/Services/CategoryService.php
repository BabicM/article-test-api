<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

final class CategoryService
{
    public function createCategory(string $name): Category
    {
        return Category::query()->create([
            'title' => $name
        ]);
    }

    public function getCategories(): Collection|array
    {
        return Category::query()->get();
    }

    public function updateCategory(Category $category, array $data): Category
    {
        $category->update($data);

        return $category;
    }

}
