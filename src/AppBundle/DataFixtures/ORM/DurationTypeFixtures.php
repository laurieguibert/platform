<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\DurationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DurationTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = ['heures', 'jours', 'semaines', 'mois', 'années'];
        $i = 0;
        foreach ($data as $d){
            $durationType = new DurationType();
            $durationType->setName($d);
            $manager->persist($durationType);
            $this->addReference('durationType'.$i, $durationType);
            $i++;
        }

        $manager->flush();
    }
}