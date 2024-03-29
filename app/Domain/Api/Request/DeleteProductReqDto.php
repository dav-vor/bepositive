<?php declare(strict_types = 1);

namespace App\Domain\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class DeleteProductReqDto
{

	/** @Assert\NotBlank(message="Id should not be blank") */
	public int $id;

}
