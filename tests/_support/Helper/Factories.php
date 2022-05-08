<?php
namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
use App\Entity\Bookings;
use App\Entity\Customer;
use App\Entity\Prices;
use League\FactoryMuffin\Faker\Facade as Faker;

class Factories extends \Codeception\Module
{

    public function _beforeSuite($settings = [])
    {
        $factory = $this->getModule('DataFactory');
        // let us get EntityManager from Doctrine
        $em = $this->getModule('Doctrine2')->_getEntityManager();
        $faker = Faker::instance()->getGenerator();

        $factory->_define(Customer::class, [
            'firstname' => $faker->firstName(),
            'lastname' => $faker->lastName(),
            'email' => $faker->email(),
            'car_registration' => $faker->text()
        ]);

        $factory->_define(Bookings::class, [
        ]);

        $factory->_define(Prices::class, [
            'enabled' => true
        ]);
    }
}
