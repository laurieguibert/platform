<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Sector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SectorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = ['Ressources humaines', 'Informatique', 'Bâtiment', 'Médecine', 'Comptabilité'];
        $i = 0;
        foreach ($data as $d){
            $sector = new Sector();
            $sector->setName($d);
            $sector->setCreatedAt(date_create(date("Y-m-d H:i:s")));
            $sector->setUpdatedAt(null);
            $sector->setParentSector(null);
            $manager->persist($sector);
            $this->addReference('sector'.$i, $sector);
            $i++;
        }

        $manager->flush();
    }
}