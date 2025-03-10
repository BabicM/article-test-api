<?php


// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;


    public function test_it_has_the_expected_fillable_attributes()
    {
        $article = new Article();
        $this->assertEquals([
            'title',
            'perex',
            'content',
            'image',
            'author',
            'published_at',
            'reading_time'
        ], $article->getFillable());
    }

    /** @test */
    public function test_it_can_be_created_with_valid_data()
    {
        $data = [
            'title' => $this->faker->sentence(5),
            'perex' => $this->faker->sentence(10),
            'content' => $this->faker->paragraph(3),
            'author' => $this->faker->name(),
            'published_at' => now(),
        ];

        $article = Article::query()->create($data);

        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals($data['title'], $article->title);
        $this->assertEquals($data['perex'], $article->perex);
        $this->assertEquals($data['content'], $article->content);
        $this->assertEquals($data['author'], $article->author);
        $this->assertEquals($data['published_at'], $article->published_at);
    }

    public function test_it_cannot_be_created_with_invalid_data()
    {
        $data = [
            'title' => '',
            'perex' => '',
            'content' => '',
            'author' => '',
        ];

        $response = $this->postJson(route('article.store'), $data, self::getHeaders());

        $response->assertStatus(422);
    }

    public function test_it_can_be_created()
    {
        $data = [
            'title' => $this->faker->sentence(5),
            'perex' => $this->faker->sentence(10),
            'content' => $this->faker->paragraph(3),
            'author' => $this->faker->name(),
            'published_at' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson(route('article.store'), $data, self::getHeaders());

        $response->assertStatus(201)
            ->assertJson(['data' => $data]);
    }

    public function test_it_cannot_be_updated_with_invalid_data()
    {
        $data = [
            'title' => $this->faker->sentence(5),
            'perex' => $this->faker->sentence(10),
            'content' => $this->faker->paragraph(3),
            'author' => $this->faker->name(),
            'published_at' => now(),
        ];

        $article = Article::query()->create($data);

        $updatedData = [
            'title' => '',
            'perex' => '',
            'content' => '',
            'author' => '',
        ];

        $response = $this->putJson(route('article.update', [$article->id]), $updatedData, self::getHeaders());

        $response->assertStatus(422);
    }

    public function test_it_can_be_updated()
    {
        $data = [
            'title' => $this->faker->sentence(5),
            'perex' => $this->faker->sentence(10),
            'content' => $this->faker->paragraph(3),
            'author' => $this->faker->name(),
            'published_at' => now(),
        ];

        $article = Article::query()->create($data);

        $updatedData = [
            'title' => 'updated title',
            'perex' => 'updated perex',
            'content' => 'updated content',
            'author' => 'updated author',
        ];

        $response = $this->putJson(route('article.update', [$article->id]), $updatedData, self::getHeaders());

        $response->assertStatus(200)
            ->assertJson(['data' => $updatedData]);
    }

    public function test_it_can_be_deleted()
    {
        $data = [
            'title' => $this->faker->sentence(5),
            'perex' => $this->faker->sentence(10),
            'content' => $this->faker->paragraph(3),
            'author' => $this->faker->name(),
            'published_at' => now(),
        ];

        $article = Article::query()->create($data);

        $response = $this->deleteJson(route('article.destroy', [$article->id]), self::getHeaders());
        $this->assertSoftDeleted($article);

        $response->assertStatus(200);
    }

    public function test_it_can_be_get()
    {
        $count = 3;
        for ($i = 1; $i <= $count; $i++) {
            $data = [
                'title' => $this->faker->sentence(5),
                'perex' => $this->faker->sentence(10),
                'content' => $this->faker->paragraph(3),
                'author' => $this->faker->name(),
                'published_at' => now(),
            ];

            Article::query()->create($data);
        }

        $data = [
            'title' => $this->faker->sentence(5),
            'perex' => $this->faker->sentence(10),
            'content' => $this->faker->paragraph(3),
            'author' => $this->faker->name(),
            'published_at' => now(),
        ];

        $article = Article::query()->create($data);
        $article->delete();

        $this->assertSame($count, Article::query()->count());

        $data['published_at'] = now()->addDay();
        Article::query()->create($data);

        $this->assertSame($count + 1, Article::query()->count());

        $response = $this->getJson(route('article.index', ['per_page' => 2]));
        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'meta' => [
                    'per_page' => 2,
                    'total' => $count // deleted and not published
                ],
            ]);

    }
}
