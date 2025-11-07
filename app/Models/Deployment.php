<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $issuer_id
 * @property string $deployment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Issuer $issuer
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereDeploymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereIssuerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Deployment extends Model
{
    protected $fillable = [
        'issuer_id',
        'deployment_id',
    ];

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(Issuer::class);
    }
}
