<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Models;

class Revision extends \Venturecraft\Revisionable\Revision
{
   public function user()
    {
        return $this->belongsTo(\Fico7489\Laravel\RevisionableUpgrade\Tests\Models\User::class);
    }
}
