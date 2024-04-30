<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationSettings extends Model
{
    protected $table = "application_settings";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'app_version',
        'blog_name',
        'navbar_color',
        'navbar_text_color',
        'footer_color',
        'footer_text_color',
        'logo_filename',
        'email',
        'phone_number'
    ];

}
