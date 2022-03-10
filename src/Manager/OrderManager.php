<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\UserAddress;
use DateTimeImmutable;

class OrderManager {
	public function createOrder(
		User $user, 
		UserAddress $address, 
		?DateTimeImmutable $createdAt = null,
		?DateTimeImmutable $deliveredAt = null,
	): Order {
		$order = new Order();
		if($createdAt) $order->setCreatedAt($createdAt);
		if($deliveredAt) $order->setDeliveredAt($deliveredAt);
		$order
			->setUser($user)
			->setAddress($address)
		;
		return $order;
	}

	public function createProductDetail(
		Order $order,
		Product $product,
		int $quantity,
		float $priceEach
	): OrderDetail {
		$detail = new OrderDetail();
		$detail
			->setOrderObj($order)
			->setProduct($product)
			->setQuantity($quantity)
			->setPriceEach($priceEach)
		;
		return $detail;
	}

}