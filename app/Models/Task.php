<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
/**
 *
 * @OA\Schema(
 *    required={"creator_id","title","body"},
 *    @OA\Xml(name="User"),
 *    @OA\Property(property="id", type="integer", readOnly="true", example=1),
 *    @OA\Property(property="user_id", type="integer", example=1, description="ID of solver (User)"),
 *    @OA\Property(property="category", type="string", example="Task category"),
 *    @OA\Property(property="title", type="string", example="Task title"),
 *    @OA\Property(property="body", type="string", example="Task body"),
 *    @OA\Property(property="solved", type="integer", example=1, description="0=not, 1=yes"),
 * )
 *
 * Class Task
 *
 */
class Task extends Model
{
    use HasFactory;


    protected $fillable = [ 'user_id', 'category', 'title', 'body', 'solved' ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
