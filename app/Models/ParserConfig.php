<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParserConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'selectors',
        'mapping',
        'url',
        'is_active',
        'has_js',
        'last_parsed_at',
        'has_ajax',
        'ajax_url',
        'ajax_selectors',
    ];

    protected $casts = [
        'selectors' => 'array',
        'mapping' => 'array',
        'is_active' => 'boolean',
        'has_js' => 'boolean',
        'has_ajax' => 'boolean',
        'ajax_selectors' => 'array',
    ];
}
