<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\LessonType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LessonTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = ['Texte', 'Vidéo', 'Image', 'Fiche', 'Questionnaire'];
        $i = 0;
        foreach ($data as $d){
            $lessonType = new LessonType();
            $lessonType->setName($d);
            $manager->persist($lessonType);
            $this->addReference('lessonType'.$i, $lessonType);
            $i ++;
        }

        $manager->flush();
    }
}