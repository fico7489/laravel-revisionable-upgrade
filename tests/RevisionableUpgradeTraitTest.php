<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Tests;

use Fico7489\Laravel\RevisionableUpgrade\Tests\Models\Role;
use Fico7489\Laravel\RevisionableUpgrade\Tests\Models\User;
use Illuminate\Database\Eloquent\Model;

class RevisionableUpgradeTraitTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        User::create(['name' => 'example@example.com']);
        User::create(['name' => 'example2@example.com']);
        User::create(['name' => 'example3@example.com']);
        User::create(['name' => 'example4@example.com']);

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'customer']);
    }

    public function test_userCreated()
    {
        $this->be(User::find(1));
        $user = User::create(['name' => 'test']);
        $this->assertEquals(1, $user->userCreated()->id);
        
        $this->be(User::find(2));
        $user = User::create(['name' => 'test']);
        $this->assertEquals(2, $user->userCreated()->id);
        
        $count = \DB::table('revisions')->count();
        $this->be(User::find(2));
        $user->update(['name' => 'test5']);
        $this->assertEquals(2, $user->userCreated()->id);
        
        $countNew = \DB::table('revisions')->count();
        $this->assertEquals(($count + 1), $countNew);
    }
    
    public function test_userUpdated()
    {
        
        $this->be(User::find(1));
        $user = User::create(['name' => 'test']);
        
        $this->be(User::find(2));
        $user->update(['name' => 'test5']);
        $this->assertEquals(2, $user->userUpdated()->id);
    }
}