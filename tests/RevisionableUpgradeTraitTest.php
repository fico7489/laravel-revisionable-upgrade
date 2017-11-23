<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Tests;

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
    
    public function test_userUpdatedWithoutParams()
    {
        
        $this->be(User::find(1));
        $user = User::create(['name' => 'test']);
        
        $this->be(User::find(2));
        $user->update(['name' => 'test6', 'email' => 'test6']);
        $this->assertEquals(2, $user->userUpdated()->id);
        $this->assertEquals(2, $user->userUpdated('name')->id);
        
        $this->be(User::find(3));
        $user->update(['name' => 'test7']);
        $this->assertEquals(3, $user->userUpdated()->id);
        $this->assertEquals(3, $user->userUpdated('name')->id);
        $this->assertEquals(2, $user->userUpdated('email')->id);
        
        $this->be(User::find(4));
        $user->update(['name' => 'test8']);
        $this->assertEquals(2, $user->userUpdated('name', 'test6')->id);
        $this->assertEquals(3, $user->userUpdated('name', 'test7')->id);
        $this->assertEquals(3, $user->userUpdated('name', 'test7', 'test6')->id);
        
        $this->assertEquals(null, $user->userUpdated('namee'));
        $this->assertEquals(null, $user->userUpdated('name', 'test9'));
        $this->assertEquals(null, $user->userUpdated('name', 'test7', 'test9'));
        
    }
}