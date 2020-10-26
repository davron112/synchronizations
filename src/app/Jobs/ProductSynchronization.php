<?php

namespace Davron112\Synchronizations\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Log\Logger;
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
     * Product Service
     *
     * @var ProductService
     */
    protected $service;
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
     * Handler.
     *
     * @param Synchronization $synchronizationModel
     * @param DB $db
     */
    public function handle(
        Synchronization $synchronizationModel,
        DB $db
    ) {
        $this->synchronizationModel       = $synchronizationModel;

        $rows = $this->getIntegrationItems();

       /* $this->createNewSynchronization(Synchronization::ID_PRODUCT);

        foreach ($rows as $row) {
            $product = $mapper->map($row);
            if (!empty($product['prices']) && !empty($product['sizes'])) {
                $products[] = $mapper->map($row);
            }
        }

        $this->processData($db, $products, $model, 'ext_id', 'ext_id');*/
    }

    /**
     * After creation
     *
     * @param int $id item ID
     * @param array $data
     *
     * @return void
     */
    public function afterCreation(int $id, array $data)
    {
        //
    }

    /**
     * After update
     *
     * @param int $id item ID
     * @param array $data
     *
     * @return void
     */
    public function afterUpdate(int $id, array $data)
    {
        //
    }

    /**
     * Create all prices
     *
     * @param int $id
     * @param array $prices
     */
    protected function createPrices(int $id, array $prices)
    {
        //
    }

    /**
     * Update all prices
     *
     * @param int $id item ID
     * @param array $prices
     *
     * @return void
     */
    protected function updatePrices(int $id, array $prices)
    {
        //
    }

    /**
     * Update all sizes
     *
     * @param int $id item ID
     * @param array $sizes
     *
     * @return void
     */
    protected function updateSizes(int $id, array $sizes)
    {
        //
    }
}
