<?php

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 14:17
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ContactFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $contact = new Contact();
            $contact->setMessage("Message". $i);
            $contact->setEmail("email" . $i . "@test.fr");
            $contact->setCreatedAt(date_create(date("Y-m-d H:i:s")));
            $contact->setContactType($this->getReference('contactType'.$i));
            $manager->persist($contact);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ContactTypeFixtures::class
        );
    }
}