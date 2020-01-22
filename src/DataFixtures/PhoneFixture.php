<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PhoneFixture extends Fixture
{
    private $name = ['IPhone', 'Samsung'];
    private $colors = ['black', 'white'];
    private $fullName = ['Stephane', 'Jean'];
    private $resume = ['developpeur fullstack', 'developpeur PHP/symfony'];

    public function load(ObjectManager $manager)
    {
        for($i=1; $i <= 20; $i++) {

            $phone = new Phone();
            $phone->setName($this->name[rand(0, 1)] . '' . rand(5,8));
            $phone->setColor($this->colors[rand(0,1)]);
            $phone->setPrice(rand(500, 1000));
            $phone->setDescription('A wonderful phone with ' . rand(10, 50) . ' tricks');
            $phone->setShotDescription('phone with ' . rand(10, 50) . ' tricks');

            $author = new Author();
            $author->setFullName($this->fullName[rand(0, 1)]);
            $author->setDescription($this->resume[rand(0, 1)]);

            $phone->addAuthor($author);
            $manager->persist($phone);
        }
        
        $manager->flush();
    }
}
