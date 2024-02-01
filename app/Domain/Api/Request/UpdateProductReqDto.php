<?php declare(strict_types = 1);

namespace App\Domain\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

final class UpdateProductReqDto
{

	/** @Assert\NotBlank(message="ID should not be blank") */
	#[NotBlank(message: 'id is required.')]
	public int $id;

	public string $name;

	public float $value;

	public string $stockDesignation;

	public int $stockValue;

}
