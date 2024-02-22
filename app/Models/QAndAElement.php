<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\QAndAElement
 *
 * @property int $id
 * @property string $question_text
 * @property string $answer_text
 * @property int|null $parent_question_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, QAndAElement> $answers
 * @property-read int|null $answers_count
 * @property-read QAndAElement|null $parentQuestion
 * @method static Builder|QAndAElement newModelQuery()
 * @method static Builder|QAndAElement newQuery()
 * @method static Builder|QAndAElement query()
 * @method static Builder|QAndAElement whereAnswerText($value)
 * @method static Builder|QAndAElement whereCreatedAt($value)
 * @method static Builder|QAndAElement whereId($value)
 * @method static Builder|QAndAElement whereParentQuestionId($value)
 * @method static Builder|QAndAElement whereQuestionText($value)
 * @method static Builder|QAndAElement whereUpdatedAt($value)
 */
class QAndAElement extends Model
{
    use HasFactory;

    protected $table = 'q_and_a_tree';

    protected $fillable = [
        'question_text',
        'answer_text',
    ];

    public function parentQuestion(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_question_id', 'id');
    }

    public function answers(): HasMany
    {
        return $this->HasMany(__CLASS__, 'parent_question_id', 'id');
    }
}
