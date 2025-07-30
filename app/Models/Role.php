<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_name',
        'description',
    ];

    public $timestamps = false;
    
    /**
     * Get the display name for the role.
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->role_name) {
                    'A' => 'Admin',
                    'S' => 'Subscriber',
                    'C' => 'Contributor',
                    default => ucfirst($this->role_name),
                };
            }
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(related: User::class, table: 'user_role');
    }
}