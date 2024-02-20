<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AnswerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Answer
 *
 * @property int $id
 * @property string $answer
 * @property int|null $question_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Question|null $question
 * @property-read Collection<int, Question> $questions
 * @property-read int|null $questions_count
 * @method static AnswerFactory factory($count = null, $state = [])
 * @method static Builder|Answer newModelQuery()
 * @method static Builder|Answer newQuery()
 * @method static Builder|Answer query()
 * @method static Builder|Answer whereAnswer($value)
 * @method static Builder|Answer whereCreatedAt($value)
 * @method static Builder|Answer whereDeletedAt($value)
 * @method static Builder|Answer whereId($value)
 * @method static Builder|Answer whereQuestionId($value)
 * @method static Builder|Answer whereUpdatedAt($value)
 */
class Answer extends Model
{
    use HasFactory;

    protected $table = 'answers';

    protected $fillable = [
        'answer',
        'question_id'
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
