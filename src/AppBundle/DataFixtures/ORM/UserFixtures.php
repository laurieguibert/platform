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

class UserFixtures extends Fixture implements ContainerAwareInterface
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
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setUsername('user'.$i);
            $password = $encoder->encodePassword($user, 'user'.$i);
            $user->setPassword($password);
            $user->setEmail('user' .$i . '@gmail.com');
            $user->setRoles(array('ROLE_USER'));
            $user->setLastLogin(null);
            $user->setSituation(null);
            $user->setImage(null);
            $user->setImageFile(null);
            $manager->persist($user);
            $this->addReference('user'.$i, $user);
        }

        for ($i = 5; $i < 10; $i++) {
            $userAdmin = new User();
            $userAdmin->setUsername('user'.$i);
            $password = $encoder->encodePassword($userAdmin, 'user'.$i);
            $userAdmin->setPassword($password);
            $userAdmin->setEmail('user' .$i . '@gmail.com');
            $userAdmin->setRoles(array('ROLE_ADMIN'));
            $userAdmin->setLastLogin(null);
            $userAdmin->setSituation(null);
            $userAdmin->setImage(null);
            $userAdmin->setImageFile(null);
            $manager->persist($userAdmin);
            $this->addReference('userAdmin'.$i, $userAdmin);
        }

        for ($i = 11; $i < 15; $i++) {
            $userFormer = new User();
            $userFormer->setUsername('user'.$i);
            $password = $encoder->encodePassword($userFormer, 'user'.$i);
            $userFormer->setPassword($password);
            $userFormer->setEmail('user' .$i . '@gmail.com');
            $userFormer->setRoles(array('ROLE_FORMER'));
            $userFormer->setLastLogin(null);
            $userFormer->setSituation(null);
            $userFormer->setImage(null);
            $userFormer->setImageFile(null);
            $manager->persist($userFormer);
            $this->addReference('userFormer'.$i, $userFormer);
        }

        $manager->flush();
    }
}