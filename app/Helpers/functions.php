<?php

function concat($_)
{
    if (is_array($_)) {
         return implode($_);
    }

    return implode(func_get_args());
}

function comma_separate($list, $spacing = false, $comma = ',')
{
    if (is_string($spacing) && strlen($spacing)) {
        $separator = concat($comma, $spacing);
    } elseif ($spacing) {
        $separator = concat($comma, ' ');
    } else {
        $separator = $comma;
    }

    return implode($separator, $list);
}

function escape_and_quote($data)
{
    if (is_integer($data)) {
        return $data;
    }

    if (is_float($data)) {
        return $data;
    }

    return app('db')->getPdo()->quote($data);
}

function sql_in_list($list)
{
    // fix for IN ()
    if (!count($list)) {
        return 'null';
    }

    foreach ($list as &$item) {
        if (is_null($item)) {
            $item = 'null';
            continue;
        }

        if (is_integer($item)) {
            continue;
        }

        if (is_float($item)) {
            continue;
        }

        $item = app('db')->getPdo()->quote($item);
    }

    return comma_separate($list);
}

function sql_insert_line($insert)
{
    return sprintf('(%s)', sql_in_list($insert));
}
