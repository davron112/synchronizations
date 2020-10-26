<?php

namespace Davron112\Synchronizations\Jobs\Contracts;

use Illuminate\Support\Facades\DB;
use Davron112\Synchronizations\Models\Synchronization;

/**
 * Interface ProductSynchronization
 * @package Davron112\Synchronizations\Jobs\Contracts
 */
interface ProductSynchronization
{
    /**
     * Handler.
     *
     * @param Synchronization $synchronizationModel
     * @param DB $db
     */
    public function handle(
        Synchronization $synchronizationModel,
        DB $db
    );
}
