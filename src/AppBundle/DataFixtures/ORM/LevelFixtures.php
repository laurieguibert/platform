<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LevelFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = ['Débutant', 'Facile', 'Intermédiaire', 'Difficile', 'Expert'];
        $i = 0;
        foreach ($data as $d){
            $level = new Level();
            $level->setName($d);
            $manager->persist($level);
            $this->addReference('level'.$i, $level);
            $i++;
        }

        $manager->flush();
    }
}