<?php

namespace Api\DataFixtures;

use Api\Entity\User;
use Api\Repository\UserRepository;

class UserFixtures
{
    public static function load()
    {
        $userRepository = new UserRepository();
        $userRepository->removeAll();

        for ($i=1; $i<= 10; $i++) 
        {
            $user = new User();
            $user
                ->setEmail("user$i@email.com")
                ->setUsername("username$i")
                ->setPassword(password_hash("password$i", PASSWORD_DEFAULT));
            
            $userRepository->addFakeUser($user);

        }
    }
}