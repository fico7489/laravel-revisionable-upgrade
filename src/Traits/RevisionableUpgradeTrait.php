<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Traits;

use Fico7489\Laravel\RevisionableUpgrade\Models\Revision;

trait RevisionableUpgradeTrait
{
    /*private $primaryKey
    public function __construct(){
        
    }*/
    
    public function userCreated()
    {
        $revision = $this->getCreateRevision();
        return $revision ? $revision->user : null;
    }
    
    public function userDeleted()
    {
        $revision = $this->getDeletedRevision();
        return $revision ? $revision->user : null;
    }
    
    public function userUpdated($key = null, $newValue = null, $oldValue = null)
    {
        $revision = $this->getUpdatedRevision($key, $newValue, $oldValue);
        return $revision ? $revision->user : null;
    }
    
    public function revisionCreated()
    {
        return $this->getCreateRevision();
    }
    
    public function revisionDeleted()
    {
        return $this->getDeletedRevision();
    }
    
    public function revisionUpdated($key = null, $newValue = null, $oldValue = null)
    {
        return $this->getUpdatedRevision($key, $newValue, $oldValue);
    }
    
    public function dateUpdated($key = null, $newValue = null, $oldValue = null)
    {
        $revision = $this->getUpdatedRevision($key, $newValue, $oldValue);
        return $revision ? $revision->created_at : null;
    }
    
    public function revisionsUpdated($key = null, $newValue = null, $oldValue = null)
    {
        return $this->getUpdatedRevision($key, $newValue, $oldValue, false);
    }
    
    public function usersUpdated($key = null, $newValue = null, $oldValue = null)
    {
        $revisions = $this->getUpdatedRevision($key, $newValue, $oldValue, false);
        $users = collect();
        foreach($revisions as $revision){
            if($revision->user){
                $users->push($revision->user);
            }
        }
        return $users->unique();
    }
    
    private function getCreateRevision(){
        $primaryKey = $this->primaryKey;
        
        return Revision::where([
            'revisionable_id' => $this->$primaryKey,
            'revisionable_type' => static::class,
            'key' => 'created_at',
            'old_value' => null,
        ])->first();
    }
    
    private function getDeletedRevision(){
        $primaryKey = $this->primaryKey;
        
        return Revision::where([
            'revisionable_id' => $this->$primaryKey,
            'revisionable_type' => static::class,
            'key' => 'deleted_at',
            'old_value' => null,
        ])->first();
    }
    
    private function getUpdatedRevision($key, $newValue, $oldValue, $first = true){
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
        
        if($first){
            $revision = $revision->first();
        }else{
            $revision = $revision->get();
        }
        
        return $revision;
    }
}
