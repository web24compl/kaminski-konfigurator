<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\QAndATreeItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\QAndATreeItem
 *
 * @property int $id
 * @property string|null $question_text
 * @property string|null $answer_text
 * @property int|null $parent_question_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, QAndATreeItem> $answers
 * @property-read int|null $answers_count
 * @property-read bool $is_first_item
 * @property-read bool $is_last_item
 * @property-read QAndATreeItem|null $parentQuestion
 * @method static QAndATreeItemFactory factory($count = null, $state = [])
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
        'parent_question_id'
    ];

    protected $appends = [
        'is_first_item',
        'is_last_item',
    ];

    public function parentQuestion(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_question_id', 'id');
    }

    public function answers(): HasMany
    {
        return $this->HasMany(__CLASS__, 'parent_question_id', 'id');
    }

    public function getIsFirstItemAttribute(): bool
    {
        return !$this->parent_question_id;
    }

    public function getIsLastItemAttribute(): bool
    {
        return empty($this->question_text);
    }
}
