<?php

function compress(int $id): void
{
    $gz_file = file_get_contents(__DIR__ . "/../json/$id.json.gz");
    $json = gzdecode($gz_file);
    $data = json_decode($json, true);
    $simplified_data = [];

    foreach ($data['data'] as $item) {
        $simplified_data[] = [
            'mt' => [
                'mp' => $item['meta']['map']['name'],
                'st' => $item['meta']['started_at'],
                's' => $item['meta']['season']['short']
            ],
            'sts' => [
                't' => $item['stats']['team'],
                'k' => $item['stats']['kills'],
                'd' => $item['stats']['deaths'],
                'a' => $item['stats']['assists'],
                's' => [
                    'h' => $item['stats']['shots']['head'],
                    'b' => $item['stats']['shots']['body'],
                    'l' => $item['stats']['shots']['leg']
                ],
                'd' => [
                    'm' => $item['stats']['damage']['made'],
                    'r' => $item['stats']['damage']['received']
                ]
            ],
            'ts' => [
                'r' => $item['teams']['red'],
                'b' => $item['teams']['blue']
            ]
        ];
    }

    $simplified_json = json_encode(['d' => $simplified_data], 0);
    $simplified_gz = gzencode($simplified_json);
    file_put_contents(__DIR__ . "/../json/$id.json.gz", $simplified_gz);
}
