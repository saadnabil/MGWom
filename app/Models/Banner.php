<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory,SoftDeletes;
    protected $date = ['deleted_at'];
    protected $guarded = [];

    public function getImageAttribute(){
        $lang = app()->getLocale();
        if($lang == 'en'){
            return $this->getImage($this->image_en);
        }
        return $this->getImage($this->image_ar);
    }

    private function getImage($image)
    {
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            // If the image is a valid URL, return it directly
            return $image;
        } else {
            // If the image is not a URL, assume it's a file path and return it with the asset helper
            return url('storage/' . $image);
        }
    }

}
