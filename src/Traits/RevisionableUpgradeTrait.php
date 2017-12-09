<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Traits;

use Fico7489\Laravel\RevisionableUpgrade\Models\Revision;

trait RevisionableUpgradeTrait
{
    /**
     * Returns user which created this model
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function userCreated()
    {
        $revision = $this->getCreateRevision();
        return $revision ? $revision->revisionUser : null;
    }

    /**
     * Returns user which deleted this model
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function userDeleted()
    {
        $revision = $this->getDeletedRevision();
        return $revision ? $revision->revisionUser : null;
    }

    /**
     * Returns user which updated this model (last user if there are more)
     *
     * @param  string|null $key
     * @param  string|null $newValue
     * @param  string|null $oldValue
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function userUpdated($key = null, $newValue = null, $oldValue = null)
    {
        $revision = $this->getUpdatedRevision($key, $newValue, $oldValue);
        return $revision ? $revision->revisionUser : null;
    }

    /**
     * Returns revision for model created
     *
     * @return \Fico7489\Laravel\RevisionableUpgrade\Models\Revision|null
     */
    public function revisionCreated()
    {
        return $this->getCreateRevision();
    }

    /**
     * Returns revision for model deleted
     *
     * @return \Fico7489\Laravel\RevisionableUpgrade\Models\Revision|null
     */
    public function revisionDeleted()
    {
        return $this->getDeletedRevision();
    }

    /**
     * Returns revision for model updated (last revision if there are more)
     *
     * @param  string|null $key
     * @param  string|null $newValue
     * @param  string|null $oldValue
     * @return \Fico7489\Laravel\RevisionableUpgrade\Models\Revision|null
     */
    public function revisionUpdated($key = null, $newValue = null, $oldValue = null)
    {
        return $this->getUpdatedRevision($key, $newValue, $oldValue);
    }

    /**
     * Returns date for model updated (last revision if there are more)
     *
     * @param  string|null $key
     * @param  string|null $newValue
     * @param  string|null $oldValue
     * @return \Carbon\Carbon|string|null
     */
    public function dateUpdated($key = null, $newValue = null, $oldValue = null)
    {
        $revision = $this->getUpdatedRevision($key, $newValue, $oldValue);
        return $revision ? $revision->created_at : null;
    }

    /**
     * Returns revisions for model updated
     *
     * @param  string|null $key
     * @param  string|null $newValue
     * @param  string|null $oldValue
     * @return \Illuminate\Support\Collection
     */
    public function revisionsUpdated($key = null, $newValue = null, $oldValue = null)
    {
        return $this->getUpdatedRevision($key, $newValue, $oldValue, false);
    }

    /**
     * Returns users for model updated
     *
     * @param  string|null $key
     * @param  string|null $newValue
     * @param  string|null $oldValue
     * @return \Illuminate\Support\Collection
     */
    public function usersUpdated($key = null, $newValue = null, $oldValue = null)
    {
        $revisions = $this->getUpdatedRevision($key, $newValue, $oldValue, false);
        $users = collect();
        foreach ($revisions as $revision) {
            if ($revision->revisionUser) {
                $users->push($revision->revisionUser);
            }
        }
        return $users->unique();
    }

    private function getCreateRevision()
    {
        $primaryKey = $this->primaryKey;

        return Revision::where([
            'revisionable_id' => $this->$primaryKey,
            'revisionable_type' => static::class,
            'key' => 'created_at',
            'old_value' => null,
        ])->first();
    }

    private function getDeletedRevision()
    {
        $primaryKey = $this->primaryKey;

        return Revision::where([
            'revisionable_id' => $this->$primaryKey,
            'revisionable_type' => static::class,
            'key' => 'deleted_at',
            'old_value' => null,
        ])->first();
    }

    private function getUpdatedRevision($key, $newValue, $oldValue, $first = true)
    {
        $primaryKey = $this->primaryKey;

        $revision = Revision::where([
            'revisionable_id' => $this->$primaryKey,
            'revisionable_type' => static::class,
        ])->where('key', '<>', 'created_at');

        if ($key !== null) {
            $revision = $revision->where(['key' => $key]);
        }

        if ($newValue !== null) {
            $revision = $revision->where(['new_value' => $newValue]);
        }

        if ($oldValue !== null) {
            $revision = $revision->where(['old_value' => $oldValue]);
        }

        $revision = $revision->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        if ($first) {
            $revision = $revision->first();
        } else {
            $revision = $revision->get();
        }

        return $revision;
    }
}
