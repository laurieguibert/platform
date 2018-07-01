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
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
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
            $user->setFirstname('firstname'.$i);
            $user->setLastname('lastname'. $i);
            $user->setUsername('user'.$i);
            $password = $encoder->encodePassword($user, 'user'.$i);
            $user->setPassword($password);
            $user->setEmail('user' .$i . '@gmail.com');
            $user->setRoles(array('ROLE_USER'));
            $user->setLastLogin(null);
            $user->setSituation($this->getReference('situation1'));
            $user->setCurrentPosition('position'.$i);
            $user->setSector($this->getReference('sector1'));
            $user->setStudiesLevel($this->getReference('studiesLevel1'));
            $user->setSchool('user_school'.$i);
            $user->setCountry($this->getReference('country1'));
            $user->setImage(null);
            $user->setImageFile(null);
            $user->setCreatedAt(date_create(date("Y-m-d H:i:s")));
            $manager->persist($user);
            $this->addReference('user'.$i, $user);
        }

        for ($i = 5; $i < 10; $i++) {
            $userAdmin = new User();
            $userAdmin->setFirstname('firstname'.$i);
            $userAdmin->setLastname('lastname'. $i);
            $userAdmin->setUsername('user'.$i);
            $password = $encoder->encodePassword($userAdmin, 'user'.$i);
            $userAdmin->setPassword($password);
            $userAdmin->setEmail('user' .$i . '@gmail.com');
            $userAdmin->setRoles(array('ROLE_ADMIN'));
            $userAdmin->setLastLogin(null);
            $userAdmin->setSituation($this->getReference('situation2'));
            $userAdmin->setCurrentPosition('position'.$i);
            $userAdmin->setSector($this->getReference('sector2'));
            $userAdmin->setStudiesLevel($this->getReference('studiesLevel2'));
            $userAdmin->setSchool('user_school'.$i);
            $userAdmin->setCountry($this->getReference('country2'));
            $userAdmin->setImage(null);
            $userAdmin->setImageFile(null);
            $userAdmin->setCreatedAt(date_create(date("Y-m-d H:i:s")));
            $manager->persist($userAdmin);
            $this->addReference('userAdmin'.$i, $userAdmin);
        }

        for ($i = 11; $i < 15; $i++) {
            $userTrainer = new User();
            $userTrainer->setFirstname('firstname'.$i);
            $userTrainer->setLastname('lastname'. $i);
            $userTrainer->setUsername('user'.$i);
            $password = $encoder->encodePassword($userTrainer, 'user'.$i);
            $userTrainer->setPassword($password);
            $userTrainer->setEmail('user' .$i . '@gmail.com');
            $userTrainer->setRoles(array('ROLE_TRAINER'));
            $userTrainer->setLastLogin(null);
            $userTrainer->setSituation($this->getReference('situation3'));
            $userTrainer->setCurrentPosition('position'.$i);
            $userTrainer->setSector($this->getReference('sector3'));
            $userTrainer->setStudiesLevel($this->getReference('studiesLevel3'));
            $userTrainer->setSchool('user_school'.$i);
            $userTrainer->setCountry($this->getReference('country3'));
            $userTrainer->setImage(null);
            $userTrainer->setImageFile(null);
            $userTrainer->setCreatedAt(date_create(date("Y-m-d H:i:s")));
            $manager->persist($userTrainer);
            $this->addReference('userFormer'.$i, $userTrainer);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            SituationFixtures::class,
            SectorFixtures::class,
            CountryFixtures::class,
            StudiesLevelFixtures::class
        );
    }
}