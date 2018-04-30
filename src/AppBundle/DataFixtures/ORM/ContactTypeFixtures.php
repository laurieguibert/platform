<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ContactType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ContactTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = ['Demande d\'informations', 'Problèmes de souscription', 'Postuler pour être formateur'];
        $i = 0;
        foreach ($data as $d){
            $contactType = new ContactType();
            $contactType->setName($d);
            $manager->persist($contactType);
            $this->addReference('contactType'.$i, $contactType);
            $i++;
        }

        $manager->flush();
    }
}