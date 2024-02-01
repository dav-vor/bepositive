<?php declare(strict_types = 1);

namespace App\Module\V1;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Domain\Api\Facade\ProductsFacade;
use App\Domain\Api\Request\CreateProductReqDto;
use App\Domain\Api\Request\DeleteProductReqDto;
use App\Domain\Api\Request\UpdateProductReqDto;
use Doctrine\DBAL\Exception\DriverException;
use Nette\Http\IResponse;

/**
 * @Apitte\Path("/products")
 * @Apitte\Tag("Products")
 */
class ProductsManagementController extends BaseV1Controller
{

	private ProductsFacade $productsFacade;

	public function __construct(ProductsFacade $productsFacade)
	{
		$this->productsFacade = $productsFacade;
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Create new product.
	 * ")
	 * @Apitte\Path("/create")
	 * @Apitte\Method("POST")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\CreateProductReqDto")
	 */
	public function create(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/**
	* @var CreateProductReqDto $dto
*/
		$dto = $request->getParsedBody();

		try {
			$this->productsFacade->create($dto);

			return $response->withStatus(IResponse::S201_Created)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot create product')
				->withPrevious($e);
		}
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Update customer.
	 * ")
	 * @Apitte\Path("/update")
	 * @Apitte\Method("POST")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\UpdateProductReqDto")
	 */
	public function update(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/**
	* @var UpdateProductReqDto $dto
*/
		$dto = $request->getParsedBody();

		try {
			$this->productsFacade->update($dto);

			return $response->withStatus(IResponse::S200_OK)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot update product')
				->withPrevious($e);
		}
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: delete product.
	 * ")
	 * @Apitte\Path("/delete")
	 * @Apitte\Method("POST")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\DeleteProductReqDto")
	 */
	public function delete(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/**
	* @var DeleteProductReqDto $dto
*/
		$dto = $request->getParsedBody();

		try {
			$this->productsFacade->delete($dto);

			return $response->withStatus(IResponse::S200_OK)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot delete product')
				->withPrevious($e);
		}
	}

}
