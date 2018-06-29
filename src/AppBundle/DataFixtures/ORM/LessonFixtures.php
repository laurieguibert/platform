<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Lesson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LessonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $lesson = new Lesson();
            $lesson->setName('lesson'.$i);
            $lesson->setDescription("Description of lesson" .$i);
            $lesson->setDuration(3);
            $lesson->setCertificate(true);
            $lesson->setCreatedAt(date_create(date("Y-m-d H:i:s")));
            $lesson->setUpdatedAt(null);
            $lesson->setDurationType($this->getReference('durationType'.$i));
            $lesson->setLessonType($this->getReference('lessonType'.$i));
            $lesson->setLevel($this->getReference('level'.$i));
            $lesson->setSector($this->getReference('sector'.$i));
            $lesson->setImage(null);
            $lesson->setSummary($this->getReference('summary'.$i));
            $lesson->setPrice(99.99);
            $lesson->setAuthor($this->getReference('user'.$i));
            $lesson->setTags(array("SQL", "PHP"));
            $manager->persist($lesson);
            $this->addReference('lesson'.$i, $lesson);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LessonTypeFixtures::class,
            SectorFixtures::class,
            LevelFixtures::class,
            DurationTypeFixtures::class,
            UserFixtures::class,
            SummaryFixtures::class,
            UserFixtures::class
        );
    }
}