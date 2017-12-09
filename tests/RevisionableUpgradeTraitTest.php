<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Tests;

use Fico7489\Laravel\RevisionableUpgrade\Tests\Models\User;

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
        
        $this->be(User::find(2));
        $user->update(['name' => 'test5']);
        $this->assertEquals(2, $user->userCreated()->id);
    }
    
    public function test_userDeleted()
    {
        $this->be(User::find(1));
        $user = User::create(['name' => 'test']);
        $user->delete();
        $this->assertEquals(1, $user->userDeleted()->id);
        
        $user = User::create(['name' => 'test']);
        $this->be(User::find(2));
        $user->delete();
        $this->assertEquals(2, $user->userDeleted()->id);
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
    
    public function test_dateUpdated()
    {
        $this->be(User::find(1));
        $user = User::create(['name' => 'test']);
        $this->assertNull($user->dateUpdated());
        
        $user->update(['name' => 'test2']);
        $this->assertNotNull($user->dateUpdated());
    }
    
    public function test_revisionCreated()
    {
        $this->be(User::find(1));
        $user = User::create(['name' => 'test']);
        $this->assertNotNull($user->revisionCreated());
    }
    
    public function test_revisionDeleted()
    {
        $this->be(User::find(1));
        $user = User::create(['name' => 'test']);
        $this->assertNull($user->revisionDeleted());
        
        $user->delete();
        $this->assertNotNull($user->revisionDeleted());
    }
    
    public function test_revisionUpdated()
    {
        $this->be(User::find(1));
        $user = User::create(['name' => 'test']);
        $this->assertNull($user->revisionUpdated());
        
        $user->update(['name' => 'test2']);
        $this->assertNotNull($user->revisionUpdated());
    }
    
    public function test_revisionsUpdated()
    {
        $this->be(User::find(2));
        $user = User::create(['name' => 'test']);
        $this->assertEquals(0, $user->revisionsUpdated()->count());
        
        $user->update(['name' => 'test2']);
        $this->assertEquals(1, $user->revisionsUpdated()->count());
        
        $user->update(['name' => 'test3']);
        $this->assertEquals(2, $user->revisionsUpdated()->count());
    }
    
    public function test_usersUpdated()
    {
        $this->be(User::find(2));
        $user = User::create(['name' => 'test']);
        $this->assertEquals(0, $user->usersUpdated()->count());
        
        $user->update(['name' => 'test2']);
        $this->assertEquals(1, $user->usersUpdated()->count());
        $this->assertEquals(2, $user->usersUpdated()->first()->id);
        
        $user->update(['name' => 'test3']);
        $this->assertEquals(1, $user->usersUpdated()->count());
        
        $this->be(User::find(3));
        $user->update(['name' => 'test4']);
        $this->assertEquals(2, $user->usersUpdated()->count());
        $this->assertEquals(3, $user->usersUpdated()->first()->id);
        $this->assertEquals(2, $user->usersUpdated()->last()->id);
    }
}
