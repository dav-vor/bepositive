<?php declare(strict_types = 1);

namespace App\Domain\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class ChangeOrderStateReqDto
{

	/** @Assert\NotBlank */
	public int $id;

	/** @Assert\NotBlank */
	public int $state;

}
