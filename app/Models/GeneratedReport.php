<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedReport extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'report_type',
        'title',
        'filename',
        'file_format',
        'file_size',
        'report_params',
        'report_id_external',
        'status',
        'error_message',
        'expires_at',
    ];

    protected $casts = [
        'report_params' => 'array',
        'expires_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
