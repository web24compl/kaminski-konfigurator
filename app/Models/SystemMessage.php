<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\SystemMessage
 *
 * @property int $id
 * @property string $content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|SystemMessage newModelQuery()
 * @method static Builder|SystemMessage newQuery()
 * @method static Builder|SystemMessage onlyTrashed()
 * @method static Builder|SystemMessage query()
 * @method static Builder|SystemMessage whereContent($value)
 * @method static Builder|SystemMessage whereCreatedAt($value)
 * @method static Builder|SystemMessage whereDeletedAt($value)
 * @method static Builder|SystemMessage whereId($value)
 * @method static Builder|SystemMessage whereUpdatedAt($value)
 * @method static Builder|SystemMessage withTrashed()
 * @method static Builder|SystemMessage withoutTrashed()
 */
class SystemMessage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'content',
    ];
}
