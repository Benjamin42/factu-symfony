<?php

namespace Factu\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Factu\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $manager->persist($this->createUser('Admin', array('ROLE_ADMIN')));
    $manager->persist($this->createUser('Olivier', array('ROLE_ADMIN')));
    $manager->persist($this->createUser('Juliette', array('ROLE_USER')));

    $manager->flush();
  }


  private function createUser($name, $roles)
  {
    $user = new User();

    $user->setUsername($name);
    $user->setPassword($name);   

    $user->setSalt('');
    
    $user->setRoles($roles);
    return $user;
  }
}