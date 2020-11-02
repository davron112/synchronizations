<?php
namespace Davron112\Synchronizations\Jobs;

use App\Models\Product;
use Davron112\Integrations\IntegrationserviceInterface;
use Davron112\Integrations\Services\ProductService;
use Davron112\Synchronizations\Mappers\ProductDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductDetailSynchronization extends BaseSynchronization implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Product Service
     *
     * @var ProductService
     */
    protected $service;

    /**
     * @var string
     */
    protected $uuid1c;

    /**
     * ProductDetailSynchronization constructor.
     * @param $uuid1c
     * @param IntegrationserviceInterface $service
     */
    public function __construct($uuid1c)
    {
        $this->uuid1c = $uuid1c;
    }

    /**
     * @param ProductDetail $transformer
     * @param IntegrationserviceInterface $service
     */
    public function handle(
        ProductDetail $transformer,
        IntegrationserviceInterface $service
    )
    {
        $this->service = $service->getProductService();
        $data = $this->getIntegrationDetail($this->uuid1c);
        $product = $transformer->map($data);

        $this->createProduct($product);
    }

    public function createProduct($data)
    {
        Product::create($data);
    }
}
