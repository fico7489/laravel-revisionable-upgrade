<?php

namespace Fico7489\Laravel\Pivot\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Fico7489\Laravel\RevisionableUpgrade\Traits\RevisionableUpgradeTrait;

class BaseModel extends Model
{
    use RevisionableUpgradeTrait;
}
