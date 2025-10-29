<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'phone',
        'company',
        'status',
        'notes',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function markAsRead()
    {
        $this->status = 'read';
        $this->save();
    }

    public function markAsReplied()
    {
        $this->status = 'replied';
        $this->save();
    }

    public function archive()
    {
        $this->status = 'archived';
        $this->save();
    }
}
