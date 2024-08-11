<?php

namespace TheBachtiarz\Base\Helpers\General;

use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ModelHelper
{
    /**
     * Get table column(s) from model
     *
     * @param ModelInterface|Model $model
     * @param boolean $refreshCache
     * @return array
     */
    public static function getTableColumnsFromModel(ModelInterface|Model $model, bool|null $refreshCache = false): array
    {
        $table = $model->getTable();
        $cacheName = sprintf('__descTable_%s_fields', $table);

        $iterable = 0;
        $result = [];

        if ($refreshCache) {
            goto PROCESS_GET_COLUMNS;
        }

        PROCESS_CHECK_CACHE:
        $isCacheExist = CacheHelper::hasCache(cacheName: $cacheName);
        if (!$isCacheExist) {
            goto PROCESS_GET_COLUMNS;
        }

        PROCESS_GET_CACHE:
        $result = CacheHelper::getCache(cacheName: $cacheName);
        goto PROCESS_CHECK_COLUMNS;

        PROCESS_GET_COLUMNS:
        $result = collect(value: Schema::getColumnListing($table))->toArray();

        PROCESS_SET_CACHE:
        CacheHelper::setCache(cacheName: $cacheName, value: $result);
        goto PROCESS_RETURN_RESULT;

        PROCESS_CHECK_COLUMNS:
        if ($iterable < 1 && count(value: $result) < 1) {
            $iterable++;
            goto PROCESS_GET_COLUMNS;
        }

        PROCESS_RETURN_RESULT:

        return $result;
    }
}
