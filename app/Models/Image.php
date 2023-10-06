<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Gallery;

class Image extends Model
{   
    use HasFactory;

    protected $fillable = [
        'image_url',
        'gallery_id',
    ];
    

    public function gallerie() {
        return $this->belongsTo(Gallery::class);
    }
}
