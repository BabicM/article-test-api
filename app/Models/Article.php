<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory,
        SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'title',
        'perex',
        'content',
        'image',
        'author',
        'published_at',
        'reading_time'
    ];

    protected $casts = [
        'published_at' => 'datetime:Y-m-d H:i:s',
        'reading_time' => 'integer'
    ];

    protected $with = ['categoryIds'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'article_categories');
    }

    public function categoryIds(): BelongsToMany
    {
        return $this->categories()->withPivot(['category_id'])->select(['category_id']);
    }

}
