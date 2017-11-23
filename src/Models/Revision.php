<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Models;

class Revision extends \Venturecraft\Revisionable\Revision
{
   public function revisionUser()
    {
        $model = \Config::get('revisionable-upgrade.model');
        return $this->belongsTo($model, 'user_id');
    }
}
