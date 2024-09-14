<?php

function compress(int $id): void
{
    $json = file_get_contents("../json/$id.json");
    $data = json_decode($json, true);
    $simplified_data = [];

    foreach ($data['data'] as $item) {
        $simplified_data[] = [
            'meta' => [
                'map' => $item['meta']['map']['name'],
                'started_at' => $item['meta']['started_at'],
                'season' => $item['meta']['season']['short']
            ],
            'stats' => [
                'team' => $item['stats']['team'],
                'kills' => $item['stats']['kills'],
                'deaths' => $item['stats']['deaths'],
                'assists' => $item['stats']['assists'],
                'shots' => [
                    'head' => $item['stats']['shots']['head'],
                    'body' => $item['stats']['shots']['body'],
                    'leg' => $item['stats']['shots']['leg']
                ],
                'damage' => [
                    'made' => $item['stats']['damage']['made'],
                    'received' => $item['stats']['damage']['received']
                ]
            ],
            'teams' => [
                'red' => $item['teams']['red'],
                'blue' => $item['teams']['blue']
            ]
        ];
    }

    $simplifiedJson = json_encode(['data' => $simplified_data], 0);
    file_put_contents("../json/$id.json", $simplifiedJson);
}
