<?php

use \Kirby\Cms\App as Kirby;
use Kirby\Cms\Collection;
use Kirby\Cms\Pages;
use Kirby\Toolkit\Query;
use Kirby\Toolkit\Str;

Kirby::plugin('leonat/locator-section', [
    'sections' => [
        'locations' => [
            'props' => [
                'label' => function (string $label = 'Locations') {
                    return $label;
                },
                // return a collection
                'query' => function (string $query) {
                    return $query;
                },
                'markerLocationLat' => function (string $markerLocation = 'page.location.yaml.lat') {
                    return $markerLocation;
                },
                'markerLocationLong' => function (string $markerLocation = 'page.location.yaml.lon') {
                    return $markerLocation;
                },
                'markerLabel' => function (string $markerLabel = '{{page.title}}') {
                    return $markerLabel;
                },
                'markerImage' => function (string $markerImage = 'page.image.resize(200).url') {
                    return $markerImage;
                },
                'markerInfo' => function (string $markerInfo = '{{page.district}}-{{page.groupShort}}-{{page.num}}') {
                    return $markerInfo;
                },
            ],
            'computed' => [
                'markers' => function () {
                    // var_dump($this);
                    // exit;
                    $query = new Query($this->query, [
                        'site' => site(),
                        'kirby' => kirby(),
                        'page' => page()
                    ]);
                    $result = $query->result();
                    if (!is_a($result,  "\Kirby\Cms\Pages")) {
                        throw new InvalidArgumentException('Query result must be a collection of pages');
                    }
                    $markerArray = [];
                    foreach ($result as $page) {
                        $queryData = [
                            'page' => $page
                        ];
                        $markerLocationLat = (new Query($this->markerLocationLat, $queryData))->result();
                        $markerLocationLong = (new Query($this->markerLocationLong, $queryData))->result();
                        $markerLabel = Str::safeTemplate($this->markerLabel, $queryData);
                        $markerImage = (new Query($this->markerImage, $queryData))->result();
                        $markerInfo = Str::safeTemplate($this->markerInfo, $queryData);

                        if ($markerLocationLat != null && $markerLocationLong != null) {
                            $markerArray[] = [
                                'text' => $markerLabel,
                                'position' => [
                                    'lat' => floatval($markerLocationLat),
                                    'lon' => floatval($markerLocationLong),
                                ],
                                'image' => [
                                    'src' => $markerImage,
                                ],
                                'info' => $markerInfo,
                                'link' => $page->panelPath()
                            ];
                        }
                    }
                    return $markerArray;
                }
            ]
        ]
    ]
]);
