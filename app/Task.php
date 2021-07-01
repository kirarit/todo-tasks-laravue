<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Ramsey\Uuid\Uuid;

class Task extends Model
{
    use SoftDeletes;

    /**
     * Hidden attrs
     * @var string[]
     */
    protected $hidden = [
        'deleted_at'
    ];

    /**
     * Autofill the uuid field when the model instance is being created.
     */
    // protected static function boot()
    // {
    //     parent::boot();
    //     self::creating(function ($todoInstance) {
    //         $todoInstance->uuid = Uuid::uuid4()->toString();
    //     });
    // }
  
    public function getCreatedAtAttribute($value)
    {
        return date("d F Y H:i", strtotime($value));
    }
}
