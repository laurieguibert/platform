<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\UserLesson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserLessonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $userLesson = new UserLesson();
            $userLesson->setUser($this->getReference('user'.$i));
            $userLesson->setLesson($this->getReference('lesson'.$i));
            $userLesson->setCertificated(1);
            $userLesson->setCertificatedDate(null);
            $userLesson->setStartedAt(date_create(date("Y-m-d H:i:s")));
            $userLesson->setEndedAt(null);
            $userLesson->setFavorite(0);
            $userLesson->setStatus($this->getReference('userLessonStatus1'));
            $manager->persist($userLesson);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LessonTypeFixtures::class,
            UserFixtures::class,
            UserLessonStatusFixtures::class
        );
    }
}