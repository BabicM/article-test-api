<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\SyncArticleCategoriesRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleService $articleService
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $articles = $this->articleService->getArticles(
            perPage: (int) $request->input('per_page', 5),
            orderBy: $request->input('order_by', ['updated_at' => 'desc']),
            categoryId: (int) $request->input('category_id'),
        );

        return ArticleResource::collection($articles);
    }

    public function store(StoreArticleRequest $request): JsonResource
    {
        $article = $this->articleService->saveArticle($request->validated());

        return new ArticleResource($article);
    }

    public function show(Article $article): ArticleResource
    {
        return new ArticleResource($article);
    }

    public function update(UpdateArticleRequest $request, Article $article): ArticleResource
    {
        $article = $this->articleService->updateArticle($article, $request->validated());

        return new ArticleResource($article);
    }

    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json(['status' => 'deleted'], Response::HTTP_OK);
    }

    public function categorySync(Article $article, SyncArticleCategoriesRequest $request): ArticleResource
    {
        $article = $this->articleService->syncCategoriesForArticle($article, $request->input('category_ids'));

        return new ArticleResource($article->refresh());
    }
}
