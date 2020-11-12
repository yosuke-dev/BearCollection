<?php

namespace Service\Repositories;

class BaseRepository
{
    public function filterArrayByKeys(array $input, array $column_keys){
        $encodedInput = json_decode(json_encode($input, JSON_UNESCAPED_UNICODE), true);
        $result = array();
        $column_keys = array_flip($column_keys); // getting keys as values
        foreach ($encodedInput as $key => $val) {
            // getting only those key value pairs, which matches $column_keys
            $result[$key] = array_intersect_key($val, $column_keys);
        }
        return $result;
    }
}
