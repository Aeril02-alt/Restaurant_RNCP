<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testThanAnUserHasAnEmail()
    {
        $user = new User();
        $user->setEmail('kKqjg@example.com');

        $this->assertEquals('kKqjg@example.com', $user->getEmail());
    }
}
