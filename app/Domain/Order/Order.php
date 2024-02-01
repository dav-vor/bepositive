<?php declare(strict_types = 1);

namespace App\Domain\Order;

use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="OrderRepository")
 * @ORM\Table(name="`order`")
 * @ORM\HasLifecycleCallbacks
 */
class Order extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	public const STATE_PENDING = 1;
	public const STATE_PROCESSING = 2;
	public const STATE_SHIPPED = 3;
	public const STATE_DELIVERED = 4;
	public const STATE_COMPLETED = 5;
	public const STATE_CANCELLED = 6;

	public const STATES = [
	self::STATE_PENDING,
	self::STATE_PROCESSING,
	self::STATE_SHIPPED,
	self::STATE_DELIVERED,
	self::STATE_COMPLETED,
	self::STATE_CANCELLED,
	];

	/** @ORM\Column(type="integer", length=255, nullable=FALSE, unique=false) */
	private int $customerId;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $products;

	/** @ORM\Column(type="integer", length=10, nullable=FALSE, unique=false) */
	private int $orderState;

	/** @ORM\Column(type="float", length=255, nullable=FALSE, unique=false) */
	private float $price;

	/**
	 * @param OrderProduct[] $products
	 */
	public function __construct(int $customerId, string $products, int $orderState, float $price)
	{
		$this->customerId = $customerId;
		$this->products = $products;
		$this->orderState = $orderState;
		$this->price = $price;
	}

	public function getCustomerId(): int
	{
		return $this->customerId;
	}

	public function getProducts(): string
	{
		return $this->products;
	}

	public function getOrderState(): int
	{
		return $this->orderState;
	}

	public function getPrice(): float
	{
		return $this->price;
	}

	public function setOrderState(int $value): void
	{
		$this->orderState = $value;
	}

	public function setPrice(float $value): void
	{
		$this->price = $value;
	}

}
