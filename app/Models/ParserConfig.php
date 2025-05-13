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
        'has_get',
        'method',
        'post_url',
        'post_params',
        'json_paths',
        'json_path_to_array',
        'post_url',
        'params_to',
        'params_from',
        'vocabulary',
        'response_form',
        'city_id',
        'country_id',
        'state_id',
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

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
