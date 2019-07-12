<?php

namespace App\DataFixtures;

use App\Entity\Butlleti;
use App\Entity\LiniaButlleti;
use App\Entity\Ordre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;

    private const USERS = [
		[
			'pickCode'=>'671',
			'password'=>'12345',
			'roles'=>[USER::ROLE_ADMIN],
			'name'=>'Claw',
			'surenames'=>'the ghost'
		],
		[
			'pickCode'=>'111',
			'password'=>'12345',
			'roles'=>[USER::ROLE_USER],
			'name'=>'Jordi',
			'surenames'=>'el Almendro'
		],
		[
			'pickCode'=>'112',
			'password'=>'12345',
			'roles'=>[USER::ROLE_USER],
			'name'=>'Cesar',
			'surenames'=>'tutancabron'
		],
		[
			'pickCode'=>'113',
			'password'=>'12345',
			'roles'=>[USER::ROLE_USER],
			'name'=>'Ludi',
			'surenames'=>'apagalaluzquequierodormir'
		],
		[
			'pickCode'=>'114',
			'password'=>'12345',
			'roles'=>[USER::ROLE_USER],
			'name'=>'Jaume',
			'surenames'=>'fa armes de destruccio massives'
		],
		[
			'pickCode'=>'115',
			'password'=>'12345',
			'roles'=>[USER::ROLE_USER],
			'name'=>'becario',
			'surenames'=>'fresh meat'
		]
    ];

    private const ESTATS = [
        '',
        'V',
        'T'
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = \Faker\Factory::create();
    }
    
    public function load(ObjectManager $manager)
    {
        $this->laodUsers($manager);
        $this->loadOrdres($manager);
        $this->loadButlletins($manager);
        $this->loadLiniesButlleti($manager);
    }

    public function laodUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $user){
            $personal = new User();
            $personal->setPickCode($user['pickCode']);
            $personal->setPassword(
                $this->passwordEncoder->encodePassword($personal, $user['password'])
            );
			$personal->setRoles($user['roles']);
			$personal->setName($user['name']);
			$personal->setSurenames($user['surenames']);

            $this->addReference('user_'.$user['pickCode'], $personal);

            $manager->persist($personal);
        }
        $manager->flush();
    }

    public function loadButlletins(ObjectManager $manager)
    {
        for ($i=0; $i < 100; $i++)
        {
            $butlleti = new Butlleti();
            $butlleti->setDataButlleti($this->faker->dateTimeThisMonth);

            $butlleti->setCreat($this->faker->dateTimeInInterval($butlleti->getDataButlleti()));

            $user = $this->getRandomUser($butlleti);
            $butlleti->setUser($user);

            $this->setReference("butlleti".$i, $butlleti);
            $manager->persist($butlleti);
        }
        $manager->flush();
    }

    public function loadOrdres(ObjectManager $manager)
    {
        for ($i=0; $i <100; $i++)
        {
           $orde = new Ordre();
           $orde->setCodiOrdre($this->faker->ean8);

           $randomEstat = self::ESTATS[ (rand(0,2))];
           $orde->setEstat($randomEstat);
           $orde->setObertura($this->faker->dateTime);
           $orde->setDescripcio($this->faker->text(50));

           $this->setReference("ordre".$i, $orde);

           $admin = $this->getReference('user_671');
           $admin->addOrdre($orde);

           $manager->persist($orde);
        }
        $manager->flush();
    }

    public function loadLiniesButlleti(ObjectManager $manager)
    {
        for ($i=0; $i<100; $i++)
        {
            for ($j=0; $j<rand(1,4); $j++)
            {
                $liniaButlleti = new LiniaButlleti();

                $user = $this->getRandomUser($liniaButlleti);
                $liniaButlleti->setUser($user);

                $ordre = $this->getReference("ordre".$i);
                $liniaButlleti->setOrdre($ordre);

                $butlleti = $this->getReference("butlleti".$i);
                $liniaButlleti->setButlleti($butlleti);
                $liniaButlleti->setHores(rand(1,8));
                $liniaButlleti->setObservacions($this->faker->text);
                $liniaButlleti->setCreat($this->faker->dateTimeInInterval($butlleti->getCreat()));

                $this->setReference("liniaButlleti".$i, $liniaButlleti);

                $manager->persist($liniaButlleti);
            }
            $manager->flush();
        }
    }

    public function getRandomUser($entity): User
    {
        $randomUser = self::USERS[ (rand(0,5))];

        if ($entity instanceof Ordre && !$randomUser['roles']== USER::ROLE_ADMIN){
            return $this->getRandomUser($entity);
        }
        return $this->getReference('user_'.$randomUser['pickCode']);
    }
}
