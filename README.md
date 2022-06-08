# kirby-map-section
a custom kirby section for pages which have location data

## how to use in blueprint


```
...
'sections' => [
      'map' => [
          'type' => 'locations',
          'query' => 'kirby.page("some-page").children'
      ]
  ]
```
