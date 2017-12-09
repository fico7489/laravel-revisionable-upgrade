<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Revision extends \Venturecraft\Revisionable\Revision
{
    public function revisionUser()
    {
        $model = \Config::get('auth.model');
        $relation = $this->belongsTo($model, 'user_id');
        if ($this->classUseTrait($model, SoftDeletes::class)) {
            $relation = $relation->withTrashed();
        }
        return $this->belongsTo($model, 'user_id');
    }
    
    private function classUseTrait($model, $trait)
    {
        $traits = class_uses($model);
        return isset($traits[$trait]) ? true : false;
    }
}
