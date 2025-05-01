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
        'ajax_selectors',
        'has_post',
        'method',
        'post_url',
        'post_params',
        'json_paths',
        'json_clear_params',
        'post_url',
        'params_to',
        'params_from',
        'response_form',
    ];

    protected $casts = [
        'selectors' => 'array',
        'mapping' => 'array',
        'is_active' => 'boolean',
        'has_js' => 'boolean',
        'has_ajax' => 'boolean',
        'ajax_selectors' => 'array',
        'has_post' => 'boolean',
        'post_params' => 'array',
        'json_paths' => 'array',
        'json_clear_params' => 'array',
    ];
}
