<?php declare(strict_types=1);

namespace OAS\Document;

/**
 * Extract sub hash map with keys provided
 *
 * @param array $keys
 * @param array $hashMap
 *
 * @return array
 */
function extract(array $keys, array $hashMap): array
{
    return array_reduce(
        $keys,
        function ($subHashMap, $key) use ($hashMap) {
            if (array_key_exists($key, $hashMap)) {
                $subHashMap[$key] = $hashMap[$key];
            }

            return $subHashMap;
        },
        []
    );
}

function zip($array, ...$arrays): array
{
    return array_map(null, $array, ...$arrays);
}