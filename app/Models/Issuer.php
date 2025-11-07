<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $issuer
 * @property string $client_id
 * @property string $key_set_url
 * @property string $auth_token_url
 * @property string $auth_login_url
 * @property string|null $auth_server
 * @property string $tool_private_key
 * @property string|null $kid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Deployment> $deployments
 * @property-read int|null $deployments_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer whereAuthLoginUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer whereAuthServer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer whereAuthTokenUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer whereIssuer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer whereKeySetUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer whereKid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer whereToolPrivateKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issuer whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Issuer extends Model
{
    protected $fillable = [
        'issuer',
        'client_id',
        'key_set_url',
        'auth_token_url',
        'auth_login_url',
        'auth_server',
        'tool_private_key',
        'kid',
    ];

    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class);
    }
}
