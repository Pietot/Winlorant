<?php

function compress(int $id): void
{
    $gzFile = gzopen(__DIR__ . "/../json/$id.json.gz", 'r');
    $json = '';
    if ($gzFile) {
        while (!gzeof($gzFile)) {
            $json .= gzread($gzFile, 4096);
        }
        gzclose($gzFile);
    }
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

    $simplifiedJson = json_encode(['data' => $simplified_data], 0);
    file_put_contents(__DIR__ . "/../json/$id.json.gz", $simplifiedJson);
}
