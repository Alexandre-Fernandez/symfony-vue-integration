<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

class EntityManager {
	public function __construct(private EntityManagerInterface $em) {}

	public function flushEntity(object $entity): void {
		$this->em->persist($entity);
		$this->em->flush();
	}

	public function removeEntity(object $entity): void {
		$this->em->remove($entity);
		$this->em->flush();
	}
}