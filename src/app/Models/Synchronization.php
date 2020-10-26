<?php

namespace Davron112\Synchronizations\Models;

use App\Models\Traits\TableName;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Synchronization
 * @package namespace Davron112\Synchronizations\Models
 */
class Synchronization extends Model
{

    /**
     * Standard synchronizations.
     */
    const ID_PRODUCT    = 'PRODUCT';
    const ID_ORDER      = 'ORDER';
    const ID_CONTACT    = 'CONTACT';

    /**
     * Standard statuses.
     */
    const ID_IN_PROGRESS = 'PROGRESS';
    const ID_SUCCESS     = 'SUCCESS';
    const ID_FAILURE     = 'FAILURE';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'status',
        'report',
        'rows_affected',
        'key',
        'created_at',
        'updated_at'
    ];
}
