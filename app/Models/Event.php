<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $fillable = [
        'summary', 
        'event_google_id',
        'description', 
        'hongoutlink',
        'htmllink',
        'kind',
        'location',
        'creator_id' 
    ]; 
}
