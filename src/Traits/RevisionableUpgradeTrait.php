<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Traits;

trait RevisionableUpgradeTrait
{
    public function userCreated()
    {
        $revision = Revision::where([
            'revisionable_id' => $this->id
            'revisionable_type' => static::class,
            'key' => 'created_at',
            'old_value' => null,
        ])->first();

        if ($revision) {
            return $revision->user;
        }

        return null;
    }
}
