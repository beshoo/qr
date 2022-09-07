<?php

function queryToSQL($query, $logQuery = true)
{
    $addSlashes = str_replace('?', "'?'", $query->toSql());
    $sql = str_replace('%', '#', $addSlashes);
    $sql = str_replace('?', '%s', $sql);
    $sql = vsprintf($sql, $query->getBindings());
    $sql = str_replace('#', '%', $sql);

    if ($logQuery) {
        Log::debug($sql);
    }

    return $sql;
}
