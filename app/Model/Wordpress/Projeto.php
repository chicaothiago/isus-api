<?php

namespace App\Model\Wordpress;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Projeto extends Model
{
    public function categoria()
    {
        return $this->belongsTo('App\Model\Categoria');
    }
}
