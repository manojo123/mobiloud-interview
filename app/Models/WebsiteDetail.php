<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebsiteDetail extends Model
{
    /** @use HasFactory<\Database\Factories\WebsiteDetailFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    /**
     * Get the users for the website detail.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
