<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\UserLessonStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserLessonStatusFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = ['En cours', 'Terminée'];
        $i = 0;
        foreach ($data as $d){
            $userLessonStatus = new UserLessonStatus();
            $userLessonStatus->setName($d);
            $manager->persist($userLessonStatus);
            $this->addReference('userLessonStatus'.$i, $userLessonStatus);
            $i ++;
        }

        $manager->flush();
    }
}