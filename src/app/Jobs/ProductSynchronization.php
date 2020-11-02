<?php

namespace Davron112\Synchronizations\Jobs;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Davron112\Synchronizations\Mappers\Product as Mapper;
use Davron112\Synchronizations\Mappers\ProductDetail as ProductDetailMapper;
use Davron112\Integrations\IntegrationServiceInterface;
use Davron112\Integrations\Services\ProductService;
use Davron112\Synchronizations\Jobs\Contracts\ProductSynchronization as ProductSynchronizationInterface;
use Davron112\Synchronizations\Models\Synchronization;

/**
 * Class ProductSynchronization
 * @package namespace Davron112\Synchronizations\Jobs
 */
class ProductSynchronization extends BaseSynchronization implements ProductSynchronizationInterface
{
    /**
     * @var string[]
     */
    protected $updateColumns = ['price', 'quantity'];

    /**
     * Product Service
     *
     * @var ProductService
     */
    protected $service;

    /**
     * @var ProductDetailMapper
     */
    protected $productDetailMapper;

    /**
     * @var \Davron112\Synchronizations\Mappers\Product
     */
    protected $mapper;

    /**
     * Last sync timestamp.
     *
     * @var mixed
     */
    protected $lastSync = null;
    /**
     * Constructor.
     *
     * ProductSynchronization constructor.
     *
     * @param IntegrationServiceInterface $service
     */
    public function __construct(IntegrationServiceInterface $service)
    {
        $this->service = $service->getProductService();
    }

    /**
     * @param Synchronization $synchronizationModel
     * @param Product $model
     * @param Mapper $mapper
     * @param ProductDetailMapper $productDetailMapper
     * @param DB $db
     * @return mixed|void
     */
    public function handle(
        Synchronization $synchronizationModel,
        Product $model,
        Mapper $mapper,
        ProductDetailMapper $productDetailMapper,
        DB $db
    ) {
        $items = $this->getIntegrationItems();
        $this->synchronizationModel       = $synchronizationModel;
        $this->productDetailMapper       = $productDetailMapper;
        $this->mapper       = $mapper;

        $this->createNewSynchronization(Synchronization::ID_PRODUCT);

        $products = [];
        foreach ($items as $item) {
            array_push($products, $mapper->map($item));
        }

        $this->processData($db, $products, $model, 'ext_id', 'ext_id');
    }


    /**
     * Process data
     *
     * @param DB $db
     * @param mixed $items
     * @param \App\Models\Model $model
     * @param string $dataKey
     * @param string $dbKey
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected function processData(DB $db, $items, $model, $dataKey, $dbKey)
    {
        $rowsAffected = 0;
        if (!empty($items)) {
            $rowsAffected = $db::transaction(function () use ($items, $model, $dataKey, $dbKey, $rowsAffected) {
                $i = 0;
                foreach ($items as $item) {
                    $i++;
                    if ($i > 10) {
                        break;
                    }
                    if ($result = $this->checkIfExists($model, $item[$dataKey], $dbKey)) {
                        $item = $this->mapper->map($item);
                        $this->updateRow($result, $item);
                    } else {
                        ProductDetailSynchronization::dispatch($item['ext_id']);
                    }
                    $rowsAffected++;
                }
                return $rowsAffected;
            });
        } else {
            $result = true;
        }

        if (empty($items) || $rowsAffected) {
            $this->updateSynchronization(Synchronization::ID_SUCCESS, $rowsAffected);
        } else {
            $this->updateSynchronization(Synchronization::ID_FAILURE);
        }
    }

    public function fillDetail(&$item)
    {
        $data = $this->getIntegrationDetail($item['ext_id']);

        if ($item && !empty($item)) {
            $item = $this->productDetailMapper->map($data);
        }
    }
}
