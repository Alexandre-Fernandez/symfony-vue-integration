<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\UserAddress;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager {
	public function __construct(private UserPasswordHasherInterface $hasher) {}

	public function createUser(string $email, string $plainPassword, ?array $roles = null): User {
		$user = new User();
		if($roles) $user->setRoles($roles);
		$user
			->setEmail($email)
			->setPassword($this->hasher->hashPassword($user, $plainPassword))
		;
		return $user;
	}

	public function createUserAddress(	
		User $user,	
		string $name, 
		string $street, 
		string $zip, 
		string $city, 
		string $country, 
		?string $phone = null
	) {
		$address = new UserAddress();
		if($phone) $address->setPhone($phone);
		$address
			->setUser($user)
			->setName($name)
			->setStreet($street)
			->setZip($zip)
			->setCity($city)
			->setCountry($country)
		;
		return $address;
	}
}