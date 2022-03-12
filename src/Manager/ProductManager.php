<?php

namespace App\Manager;

use App\Entity\OptionChoice;
use App\Entity\Product;
use App\Entity\ProductOption;

class ProductManager {
	public function createProduct(
		string $name,
		string $picture, 
		float $price,
		?string $description = null
	): Product {
		$product = new Product();
		if($description) $product->setDescription($description);
		$product
			->setName($name)
			->setPicture($picture)
			->setPrice($price)
		;
		return $product;
	}

	public function createProductOption(
		string $directive,
		bool $isRequired = true,
		int $allowedChoices = 1
	): ProductOption {
		$option = new ProductOption();
		$option
			->setDirective($directive)
			->setIsRequired($isRequired)
			->setAllowedChoices($allowedChoices)
		;
		return $option;
	}
	
	public function createOptionChoice(
		string $name,
		bool $isMultiple = false,
		float $extraPrice = 0
	): OptionChoice {
		$choice = new OptionChoice();
		$choice
			->setName($name)
			->setIsMultiple($isMultiple)
			->setExtraPrice($extraPrice)
		;
		return $choice;
	}
}