<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log an action in the audit log.
     *
     * @param string $action The action performed (e.g., 'create', 'update', 'delete')
     * @param Model $model The model instance being acted upon
     * @param array|null $details Additional details about the action
     * @return void
     */
    public static function log($action, Model $model, $details = null)
    {
        $user = Auth::user();
        if (!$user) {
            return; // Don't log if no user is authenticated (e.g., seeder or system job)
        }

        $logData = [
            'user_id' => $user->id,
            'action' => $action,
            'table_name' => $model->getTable(),
            'record_id' => $model->id,
            'description' => $details['description'] ?? self::generateDescription($action, $model),
        ];

        // Handle specific actions for detailed tracking
        if ($action === 'update') {
            $logData['old_values'] = $model->getOriginal();
            $logData['new_values'] = $model->getChanges();
            
            // Filter out timestamps from changes if they are the only thing that changed
            unset($logData['new_values']['updated_at']);
            
            // If nothing meaningful changed, don't log (optional, but good for noise reduction)
             if (empty($logData['new_values'])) {
                // Return or continue depending on preference. 
                // For now we log it as it might be an explicit 'touch' or related update.
            }
        } elseif ($action === 'delete') {
            $logData['old_values'] = $model->toArray(); // Store everything that was deleted
            $logData['new_values'] = null;
        } elseif ($action === 'create') {
            $logData['old_values'] = null;
            $logData['new_values'] = $model->toArray();
        }

        AuditLog::create($logData);
    }

    private static function generateDescription($action, $model)
    {
        $modelName = class_basename($model);
        switch ($action) {
            case 'create':
                return "Created new $modelName";
            case 'update':
                return "Updated $modelName details";
            case 'delete':
                return "Deleted $modelName record";
            default:
                return "$action $modelName";
        }
    }
}
