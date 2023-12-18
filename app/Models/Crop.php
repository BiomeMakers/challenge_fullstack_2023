<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A crop
 * @property string $name The name of the crop
 */
class Crop extends Model
{
    use HasFactory;

    public function samples(){
        return $this->hasMany(Sample::class);
    }
}
