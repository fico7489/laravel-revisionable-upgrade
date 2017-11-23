<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Traits;

use Fico7489\Laravel\RevisionableUpgrade\Models\Revision;

trait RevisionableUpgradeTrait
{
    public function userCreated()
    {
        $revision = Revision::where([
            'revisionable_id' => $this->id,
            'revisionable_type' => static::class,
            'key' => 'created_at',
            'old_value' => null,
        ])->first();

        if ($revision) {
            return $revision->user;
        }

        return null;
    }
    
    public function userDeleted()
    {
        $revision = Revision::where([
            'revisionable_id' => $this->id,
            'revisionable_type' => static::class,
            'key' => 'deleted_at',
            'old_value' => null,
        ])->first();

        if ($revision) {
            return $revision->user;
        }

        return null;
    }
    
    public function userUpdated($key = null, $newValue = null, $oldValue = null)
    {
        $revision = Revision::where([
            'revisionable_id' => $this->id,
            'revisionable_type' => static::class,
        ]);
        
        if ($key !== null) {
            $revision = $revision->where(['key' => $key]);
        }
        
        if ($newValue !== null) {
            $revision = $revision->where(['new_value' => $newValue]);
        }
        
        if ($oldValue !== null) {
            $revision = $revision->where(['old_value' => $oldValue]);
        }
        
        $revision = $revision->orderBy('created_at', 'desc')->orderBy('id', 'desc')->first();

        if ($revision) {
            return $revision->user;
        }

        return null;
    }
}
