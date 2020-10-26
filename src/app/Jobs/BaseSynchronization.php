<?php

namespace Davron112\Synchronizations\Jobs;

use Davron112\Synchronizations\Models\Synchronization;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseSynchronization
 * @package namespace Davron112\Synchronizations\Jobs
 */
abstract class BaseSynchronization extends Job
{
    /**
     * Create new synchronization.
     *
     * @param int $synchronizationType synchronization type
     *
     * @return mixed
     */
    protected function createNewSynchronization($synchronizationType)
    {
        $synchronizationModel       = $this->synchronizationModel;

        $this->synchronization = $synchronizationModel::create([
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
        $synchronization                = $this->synchronization;
        $synchronization->status_id     = $synchronizationStatus;
        $synchronization->rows_affected = $rowsAffected;
        $synchronization->key           = $key;
        $synchronization->save();
    }

    /**
     * After creation
     *
     * @param int $id item ID
     * @param array $data
     */
    abstract public function afterCreation(int $id, array $data);

    /**
     * After update
     *
     * @param int $id item ID
     * @param array $data
     */
    abstract public function afterUpdate(int $id, array $data);

    /**
     * Process data
     *
     * @param \DB $db
     * @param mixed $items
     * @param \App\Models\Model $model
     * @param string $dataKey
     * @param string $dbKey
     *
     * @return void
     */
    protected function processData(DB $db, $items, $model, $dataKey, $dbKey)
    {
        $rowsAffected = 0;
        if (!empty($items)) {
            $rowsAffected = $db::transaction(function () use ($items, $model, $dataKey, $dbKey, $rowsAffected) {
                foreach ($items as $item) {
                    if ($result = $this->checkIfExists($model, $item[$dataKey], $dbKey)) {
                        $this->updateRow($result, $item);
                    } else {
                        $this->createRow($model, $item);
                    }
                    $rowsAffected++;
                }
                return $rowsAffected;
            });
        } else {
            $result = true;
        }

        $synchronizationStatusModel = $this->synchronizationStatusModel;

        if (empty($items) || $rowsAffected) {
            $this->updateSynchronization(Synchronization::ID_SUCCESS, $rowsAffected);
        } else {
            $this->updateSynchronization(Synchronization::ID_FAILURE);
        }
    }

    /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed()
    {
        $synchronizationStatusModel = $this->synchronizationStatusModel;
        $this->updateSynchronization($synchronizationStatusModel::ID_FAILURE);
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
     * @param array $data contain data from CDK
     *
     * @return bool
     */
    protected function updateRow(Model $model, array $data)
    {
        $model->fill($data);
        if ($result = $model->save()) {
            $this->afterUpdate($model->id, $data);
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
            $this->afterCreation($result->id, $data);
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
        $sync = $this->synchronizationModel
            ->where('type_id', '=', $type)
            ->where('status_id', '=', $status)
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
     * Get items from B4M.
     *
     * @param mixed $syncModel
     *
     * @return array
     */
    protected function getIntegrationItems($syncModel = null)
    {
        if ($syncModel) {
            $items = $this->service->getUpdated(strtotime($syncModel->created_at));
        } else {
            $items = $this->service->getAll();
        }

        return $items;
    }
}
