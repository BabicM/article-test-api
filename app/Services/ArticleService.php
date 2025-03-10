<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

final class ArticleService
{
    public function saveArticle(array $data): Article
    {
        if (! isset($data['published_at'])) {
            $data['published_at'] = now();
        }

        $data['reading_time'] = $this->getReadingTime($data);

        return Article::query()->create($data);
    }

    public function updateArticle(Article $article, array $data): Article
    {
        if (! isset($data['published_at'])) {
            $data['published_at'] = now();
        }

        $data['reading_time'] = $this->getReadingTime($data);

        $article->update($data);

        return $article;
    }
    public function getArticles(int $perPage, array $orderBy, ?int $categoryId): LengthAwarePaginator
    {
        $fields = ['title', 'published_at', 'reading_time', 'created_at', 'updated_at'];

        abort_if(
            !in_array(key($orderBy), $fields, true),
            422,
            'Sorting is available only for fields : ' . implode(', ', $fields)
        );

        return Article::query()
            ->where(function (Builder $builder) use ($categoryId) {
                $builder->where('published_at', '<=', now())
                    ->orWhereNull('published_at');
            })
            ->when($categoryId, function (Builder $builder) use ($categoryId) {
                $builder->whereHas('categories', function (Builder $builder) use ($categoryId) {
                   $builder->where('categories.id', $categoryId);
                });
            })
            ->orderBy(key($orderBy), $orderBy[key($orderBy)])
            ->paginate($perPage);
    }

    public function syncCategoriesForArticle(Article $article, array $categoryIds): Article
    {
        $article->categories()->sync($categoryIds);

        return $article;
    }

    public function deleteArticlesByCategory(Category $category): int
    {
        return $category->articles()->delete();
    }

    private function getReadingTime(array $data): int|float
    {
        $count = Str::of($data['content'])->wordCount();
        return ceil($count / 200);
    }

}
