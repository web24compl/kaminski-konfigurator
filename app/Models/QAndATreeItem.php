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
 * @property-read Collection<int, QAndATreeItem> $answers
 * @property-read int|null $answers_count
 * @property-read QAndATreeItem|null $parentQuestion
 * @method static Builder|QAndATreeItem newModelQuery()
 * @method static Builder|QAndATreeItem newQuery()
 * @method static Builder|QAndATreeItem query()
 * @method static Builder|QAndATreeItem whereAnswerText($value)
 * @method static Builder|QAndATreeItem whereCreatedAt($value)
 * @method static Builder|QAndATreeItem whereId($value)
 * @method static Builder|QAndATreeItem whereParentQuestionId($value)
 * @method static Builder|QAndATreeItem whereQuestionText($value)
 * @method static Builder|QAndATreeItem whereUpdatedAt($value)
 */
class QAndATreeItem extends Model
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
