<?php

namespace App\Http\Resources;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Article $article */
        $article = $this->resource;

        $articleData = $article->toArray();
        $categoryIds = [];

        foreach (Arr::get($articleData, 'category_ids', []) as $articleCategory) {
            $categoryIds[] = $articleCategory['category_id'];
        }

        $articleData['category_ids'] = $categoryIds;

        return $articleData;
    }
}
