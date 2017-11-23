<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Fixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        for ($i = 0; $i < 10; $i++) {
            $userAdmin = new User();
            $userAdmin->setUsername('user'.$i);
            $password = $encoder->encodePassword($userAdmin, 'user'.$i);
            $userAdmin->setPassword($password);
            $userAdmin->setEmail('user' .$i . '@gmail.com');
            $userAdmin->setRoles(array('ROLE_ADMIN'));
            $manager->persist($userAdmin);
        }

        $manager->flush();
    }
}