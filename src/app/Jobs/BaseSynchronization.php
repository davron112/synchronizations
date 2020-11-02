<?php

namespace Davron112\Synchronizations\Jobs;

use Davron112\Synchronizations\Models\Synchronization;
use Illuminate\Support\Arr;
use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseSynchronization
 * @package namespace Davron112\Synchronizations\Jobs
 */
abstract class BaseSynchronization extends Job
{
    /**
     * @var
     */
    protected $service;

    /**
     * @var
     */
    protected $updateColumns = [];

    /**
     * Create new synchronization.
     *
     * @param int $synchronizationType synchronization type
     *
     * @return mixed
     */
    protected function createNewSynchronization($synchronizationType)
    {
        $this->synchronization = Synchronization::create([
            'status' => Synchronization::ID_IN_PROGRESS,
            'type'   => $synchronizationType
        ]);
    }

    /**
     * Create new synchronization.
     *
     * @param $synchronizationStatus
     * @param int $rowsAffected
     * @param null $key
     */
    protected function updateSynchronization($synchronizationStatus, $rowsAffected = 0, $key = null)
    {
        $synchronization                = new Synchronization();
        $synchronization->status        = $synchronizationStatus;
        $synchronization->rows_affected = $rowsAffected;
        $synchronization->key           = $key;
        $synchronization->save();
    }

    /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed()
    {
        $this->updateSynchronization(Synchronization::ID_FAILURE);
    }

    /**
     * Check if file exists
     *
     * @param \App\Models\Model $model
     * @param string $value identificator
     * @param string $field key field in the DB
     *
     * @return mixed
     */
    protected function checkIfExists(Model $model, $value, $field)
    {
        return $model::where($field, '=', $value)->first();
    }

    /**
     * Update a row
     *
     * @param \App\Models\Model $model
     * @param array $data
     *
     * @return bool
     */
    protected function updateRow(Model $model, array $data)
    {
        $result = false;
        try {
            $fillData = [];
            foreach ($this->updateColumns as $column) {
                $fillData[$column] = Arr::get($data, $column);
            }

            $model->fill($fillData);
            if ($result = $model->save()) {
                //
            }
        } catch (Exception $e) {
            // log
        }

        return $result;
    }

    /**
     * Create a row
     *
     * @param \App\Models\Model $model
     * @param array $data contain data from CDK
     *
     * @return bool
     */
    protected function createRow(Model $model, array $data)
    {
        $result = $model::create($data);
        if (!empty($result->id)) {
            //
        }
    }

    /**
     * Get last synchronization job by type id.
     *
     * @param int $type synchronization type
     * @param int $status synchronization status
     *
     * @return int
     */
    protected function getLastSync(int $type, int $status)
    {
        $sync = Synchronization::where('type', '=', $type)
            ->where('status', '=', $status)
            ->orderBy('created_at', 'desc')
            ->first();


        return $sync ? $sync : 0;
    }

    /**
     * Send the array of items through mapper.
     *
     * @param $rows
     *
     * @return array
     */
    protected function filtrate($rows)
    {
        $items = [];

        foreach ($rows as $row) {
            if (is_array($row)) {
                $items[] = $this->mapper->map($row);
            }
        }

        return $items;
    }

    /**
     * Get items from 1c.
     *
     * @return array
     */
    protected function getIntegrationItems()
    {
        return $this->service->getAll();
    }

    /**
     * Get detail item from 1c.
     *
     * @param $id
     * @return mixed
     */
    protected function getIntegrationDetail($id)
    {
        return $this->service->get($id);
    }
}
