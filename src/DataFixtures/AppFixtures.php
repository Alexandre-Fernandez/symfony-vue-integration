<?php

namespace App\DataFixtures;

use App\Manager\OrderManager;
use App\Manager\ProductManager;
use App\Manager\UserManager;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
	private int $userCount = 12;
	private int $maxAddressesPerUser = 3;
	private int $maxOrdersPerUser = 15;
	private int $maxDetailsPerOrder = 6;
	private int $maxDetailsQuantity = 6;
	private int $maxDetailOptions = 3;
	private int $maxOptionsPerProduct = 3;
	private int $maxChoicesPerOption = 6;
	private float $maxChoiceExtraPrice = 3.0;

	public function __construct(
		private UserManager $userManager,
		private ProductManager $productManager,
		private OrderManager $orderManager
	) {}

    public function load(ObjectManager $manager): void
    {
		$faker = Faker\Factory::create();

		/* PRODUCTS */

		$products = [
			$this->productManager->createProduct(
				"Menu El Taquito", "El-Taquito.png", 8.48
			),
			$this->productManager->createProduct(
				"Menu El Classico", "El-Classico.png", 10.99
			),
			$this->productManager->createProduct(
				"Menu El Tacon", "El-Tacon.png", 13.99
			),
			$this->productManager->createProduct(
				"El Taquito", "El-Taquito.png", 6.99, "A small taco for a big kid"
			),
			$this->productManager->createProduct(
				"El Classico", "El-Classico.png", 9.5, "You can't go wrong with this one"
			),
			$this->productManager->createProduct(
				"El Tacon", "El-Tacon.png", 12.5, "Let's hope you're hungry"
			),
		];

		$productOptions = [];

		foreach($products as $product) {
			$manager->persist($product);

			/* PRODUCT OPTIONS */
			for($i = 0; $i < rand(0, $this->maxOptionsPerProduct); $i++) {
				$option = $this->productManager->createProductOption(
					directive: $faker->text(50),
					isRequired: (bool)rand(0, 1),
					allowedChoices: rand(0, $this->maxChoicesPerOption)
				);
				$product->addOption($option);
				$productOptions[] = $option;
				$manager->persist($option);
			}
		}

		/* OPTION CHOICES */

		foreach($productOptions as $productOption) {
			$maxChoices = $productOption->getAllowedChoices();
			// 0 allowedChoices means no choice limit, in other words maximum :
			if($maxChoices === 0) $maxChoices = $this->maxChoicesPerOption;
			$isMultiple = (bool)rand(0, 1);
			for($i = 0; $i < rand($maxChoices, $this->maxChoicesPerOption); $i++) {
				$extraPrice = $this->priceRand(0, $this->maxChoiceExtraPrice);
				if($extraPrice < 1) $extraPrice = 0;

				$choice = $this->productManager->createOptionChoice(
					$faker->text(20),
					$isMultiple,
					$extraPrice
				);
				$productOption->addChoice($choice);
				$manager->persist($choice);
			}
		}

		/* USERS */

		$users = [];

        $admin = $this->userManager->createUser("a@a.a", "aaaaaa", ["ROLE_ADMIN"]);
		$users[] = $admin;
		$manager->persist($admin);

		for($i = 0; $i < $this->userCount; $i++) {
			$user = $this->userManager->createUser($faker->email(), "aaaaaa");
			$users[] = $user;
			$manager->persist($user);
		}

		foreach($users as $user) {

			/* USER ADDRESSES */

			$userFullName = $faker->firstName() . " " . $faker->lastName();

			for($i = 0; $i < rand(1, $this->maxAddressesPerUser); $i++) {
				$address = $this->userManager->createUserAddress(
					$user,
					$userFullName, 
					$faker->streetAddress(), 
					$faker->postcode(),
					$faker->city(),
					$faker->country(),
					(bool)rand(0, 1) ? $faker->phoneNumber() : null 
				);
				$user->addAddress($address);
				$manager->persist($address);
			}

			/* ORDERS */

			for($i = 0; $i < rand(0, $this->maxOrdersPerUser); $i++) {
				$userAddresses = $user->getAddresses();
				$userAddressesCount = count($userAddresses);
				if($userAddressesCount <= 0) continue;
				$createdAt = $faker->dateTimeThisYear();
				$deliveredAt = $createdAt->add(
					new DateInterval("PT" . (string)rand(20,60) . "M")
				);
				$order = $this->orderManager->createOrder(
					$user,
					$userAddresses[rand(0, $userAddressesCount - 1)],
					DateTimeImmutable::createFromMutable($createdAt),
					DateTimeImmutable::createFromMutable($deliveredAt)
				);
				$user->addOrder($order);
				$manager->persist($order);

				for($j = 0; $j < rand(1, $this->maxDetailsPerOrder); $j++) {
					$product = $products[rand(0, count($products) - 1)];
					$detail = $this->orderManager->createProductDetail(
						$order,
						$product,
						rand(1, $this->maxDetailsQuantity),
						$product->getPrice()
					);

					$options = $product->getOptions();
					// logic to get the order detail option choices :
					if(count($options) > 0) { 
						$k = 0;
						// choosing what options to keep :
						$options = array_filter( 
							$options->toArray(), 
							function($option) use($k) {
								$k++;
								if($option->getIsRequired()) return true;
								if($k > $this->maxDetailOptions) return false;
								return (bool)rand(0, 1);
							}
						);
						
						foreach($options as $option) { // getting choices :
							$choosenChoicesCount = rand(1, $option->getAllowedChoices());
							$choices = $option->getChoices();
							$chosenChoices = array_slice(
								$choices->toArray(), 
								rand(0, count($choices) - $choosenChoicesCount - 1),
								$choosenChoicesCount
							);

							$priceEach = $detail->getPriceEach();
							foreach($chosenChoices as $choseChoice) {
								$priceEach += $choseChoice->getExtraPrice();
								$detail->addOptionChoice($choseChoice);
							}
							$detail->setPriceEach($priceEach);
						}
					}

					$order->addDetail($detail);
					$manager->persist($detail);
				}
			}
		}

        $manager->flush();
    }

	private function priceRand(float $min, float $max) {
		return round($min + ($max - $min) * rand(0, 100) * 0.01, 2);
	}
}
