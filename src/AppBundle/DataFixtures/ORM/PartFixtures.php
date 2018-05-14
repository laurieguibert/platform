<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Part;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PartFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $part = new Part();
            $part->setName('part'.$i);
            $part->setContent('Content of part ' . $i);
            $part->setCreatedAt(date_create(date("Y-m-d H:i:s")));
            $part->setUpdatedAt(null);
            $manager->persist($part);
        }

        $manager->flush();
    }
}