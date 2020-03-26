<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Tests\Models;

use Fico7489\Laravel\RevisionableUpgrade\Traits\RevisionableUpgradeTrait;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class BaseModel extends Model
{
    use RevisionableUpgradeTrait;
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;
}
