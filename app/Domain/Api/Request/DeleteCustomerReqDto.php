<?php declare(strict_types = 1);

namespace App\Domain\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class DeleteCustomerReqDto
{

	/** @Assert\NotBlank */
	public int $id;

}
