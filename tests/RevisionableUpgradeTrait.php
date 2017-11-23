<?php

namespace Fico7489\Laravel\Pivot\Tests;

use Fico7489\Laravel\Pivot\Tests\Models\Role;
use Fico7489\Laravel\Pivot\Tests\Models\User;
use Illuminate\Database\Eloquent\Model;

class PivotEventTraitTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        User::create(['name' => 'example@example.com']);
        User::create(['name' => 'example2@example.com']);

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'customer']);
    }

    public function test_detach_events()
    {
       $test->assertTrue(true);
    }
}