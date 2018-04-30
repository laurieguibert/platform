<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Situation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SituationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = ['CDI', 'CDD', 'Étudiant', 'En reconversion professionnelle', 'Sans travail'];
        $i = 0;
        foreach ($data as $d){
            $situation = new Situation();
            $situation->setName($d);
            $manager->persist($situation);
            $this->addReference('situation'.$i, $situation);
            $i ++;
        }

        $manager->flush();
    }
}