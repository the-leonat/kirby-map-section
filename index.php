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
                'query' => function (string $query = 'site.index') {
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
                'markerImage' => function (string $markerImage = 'structureItem.image.resize(200).url') {
                    return $markerImage;
                },
                'markerInfo' => function (string $markerInfo = '{{page.district}}-{{page.groupShort}}-{{page.num}}') {
                    return $markerInfo;
                },
                'markerLink' => function (string $markerLink = null) {
                    return $markerLink;
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
                    $results = $query->result();
                    if (
                        !is_a($results,  "\Kirby\Cms\Pages")
                        && !is_a($results,  "\Kirby\Cms\Structure")
                        && !is_a($results,  "\Kirby\Cms\Files")
                    ) {
                        throw new InvalidArgumentException('Query result must be a collection of pages, files or a structure');
                    }
                    $markerArray = [];
                    foreach ($results as $result) {
                        $queryData = [
                            'page' => $result,
                            'structureItem' => $result,
                            'file' => $result
                        ];
                        $markerLocationLat = null;
                        $markerLocationLong = null;
                        $markerLabel = null;
                        $markerImage = null;
                        $markerLink = null;
                        try {
                            $markerLocationLat = (new Query($this->markerLocationLat, $queryData))->result();
                            $markerLocationLong = (new Query($this->markerLocationLong, $queryData))->result();
                            $markerLabel = Str::safeTemplate($this->markerLabel, $queryData);
                            $markerImage = (new Query($this->markerImage, $queryData))->result();
                            $markerLink = $this->markerLink ? (new Query($this->markerLink, $queryData))->result() : null;
                        } catch (Exception $e) {
                            // throw $e;
                        }
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
                                'link' => $markerLink
                            ];
                        }
                    }
                    return $markerArray;
                }
            ]
        ]
    ]
]);
