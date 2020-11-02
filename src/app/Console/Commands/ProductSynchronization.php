<?php

namespace Davron112\Synchronizations\Console\Commands;

use Illuminate\Console\Command;
use Davron112\Integrations\IntegrationServiceInterface;
use Davron112\Synchronizations\Jobs\Contracts\ProductSynchronization as SynchronizationJob;

/**
 * Class ProductSynchronization
 * @package namespace Davron112\Synchronizations\Console\Commands
 */
class ProductSynchronization extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '1c:product-synchronization:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update products.';

    /**
     * @var IntegrationServiceInterface
     */
    protected $integrationService;

    /**
     * ProductSynchronization constructor.
     *
     * @param IntegrationServiceInterface $service
     */
    public function __construct(IntegrationServiceInterface $service)
    {
        parent::__construct();
        $this->integrationService = $service;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $job = app(SynchronizationJob::class, [$this->integrationService]);
        app('Illuminate\Bus\Dispatcher')->dispatch($job);
    }
}
