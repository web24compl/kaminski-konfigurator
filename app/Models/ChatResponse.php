<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ChatResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ChatResponse
 *
 * @property int $id
 * @property array $input
 * @property array $response
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $tokens
 * @property string|null $mail
 * @method static Builder|ChatResponse newModelQuery()
 * @method static Builder|ChatResponse newQuery()
 * @method static Builder|ChatResponse query()
 * @method static Builder|ChatResponse whereCreatedAt($value)
 * @method static Builder|ChatResponse whereId($value)
 * @method static Builder|ChatResponse whereInput($value)
 * @method static Builder|ChatResponse whereMail($value)
 * @method static Builder|ChatResponse whereResponse($value)
 * @method static Builder|ChatResponse whereTokens($value)
 * @method static Builder|ChatResponse whereUpdatedAt($value)
 * @method static ChatResponseFactory factory($count = null, $state = [])
 */
class ChatResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'input',
        'response',
        'tokens',
        'mail',
    ];

    protected $casts = [
        'input' => 'array',
        'response' => 'array',
    ];
}
