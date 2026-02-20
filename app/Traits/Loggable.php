<?php

namespace App\Traits;

use App\Models\LogAktivitas;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini

trait Loggable
{
    protected static function bootLoggable()
    {
        // Log Create
        static::created(function ($model) {
            self::logActivity('create', $model, null, $model->toArray());
        });

        // Log Update
        static::updated(function ($model) {
            $changes = $model->getChanges();
            $original = $model->getOriginal();
            
            // Ambil hanya field yang berubah
            $dataLama = array_intersect_key($original, $changes);
            $dataBaru = $changes;
            
            self::logActivity('update', $model, $dataLama, $dataBaru);
        });

        // Log Delete
        static::deleted(function ($model) {
            self::logActivity('delete', $model, $model->toArray(), null);
        });

        // Log Restore - HANYA jika model pakai SoftDeletes!
        if (in_array(SoftDeletes::class, class_uses_recursive(static::class))) {
            static::restored(function ($model) {
                self::logActivity('restore', $model, null, $model->toArray());
            });
        }
    }

    protected static function logActivity($action, $model, $oldData = null, $newData = null)
    {
        $modelName = class_basename($model);
        $tableName = $model->getTable();
        
        LogAktivitas::catat(
            aktivitas: $action,
            modul: $tableName,
            deskripsi: self::generateDescription($action, $model, $modelName),
            dataLama: $oldData,
            dataBaru: $newData
        );
    }

    protected static function generateDescription($action, $model, $modelName)
    {
        $identifier = $model->name ?? $model->title ?? $model->nama ?? $model->id ?? 'ID ' . $model->id;
        
        return match($action) {
            'create' => "Menambahkan {$modelName} baru: {$identifier}",
            'update' => "Mengupdate {$modelName}: {$identifier}",
            'delete' => "Menghapus {$modelName}: {$identifier}",
            'restore' => "Merestore {$modelName}: {$identifier}",
            default => "Melakukan {$action} pada {$modelName}: {$identifier}"
        };
    }
}