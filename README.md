# kirby-map-section
a custom kirby section for pages which have location data

## how to use in blueprint


```
...
'sections' => [
      'map' => [
          'type' => 'locations',
          'query' => 'kirby.page("some-page").children',
          
          # ---- optional parameters (with defaults) ----
          
          'markerLocationLat' => 'page.location.yaml.lat',        # lat coordinate
          'markerLocationLong' => 'page.location.yaml.lon',       # lon coordinate
          'markerLabel' => '{{page.title}}',                      # label for marker popup
          'markerImage' => 'page.image.resize(200).url'           # image for marker popup
          'markerInfo' => ''                                      # info text for marker popup
      ]
  ]
```
