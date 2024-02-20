<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Question
 *
 * @property int $id
 * @property string $question
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Answer> $answers
 * @property-read int|null $answers_count
 * @method static QuestionFactory factory($count = null, $state = [])
 * @method static Builder|Question newModelQuery()
 * @method static Builder|Question newQuery()
 * @method static Builder|Question onlyTrashed()
 * @method static Builder|Question query()
 * @method static Builder|Question whereCreatedAt($value)
 * @method static Builder|Question whereDeletedAt($value)
 * @method static Builder|Question whereId($value)
 * @method static Builder|Question whereQuestion($value)
 * @method static Builder|Question whereUpdatedAt($value)
 * @method static Builder|Question withTrashed()
 * @method static Builder|Question withoutTrashed()
 */
class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'questions';

    protected $fillable = [
        'question',
    ];

    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class);
    }
}
