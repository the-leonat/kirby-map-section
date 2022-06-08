# kirby-map-section
a custom kirby section for pages which have location data

<img width="1304" alt="Screenshot 2022-06-08 at 20 19 30" src="https://user-images.githubusercontent.com/3268017/172688766-a18e3f8e-32d6-40df-9e6b-1ab88eefc674.png">

## how to use in blueprint


```yaml

sections: 
    map:
        type: locations                                
        label: Locations                            # section label
        query: kirby.page("some-page").children     # query must return a collection of pages, files or a structure
   
        #  optional parameters (with defaults)
          
        markerLocationLat: page.location.yaml.lat   # lat coordinate
        markerLocationLong: page.location.yaml.lon  # lon coordinate
        markerLabel: '{{page.title}}'               # title for marker popup
        markerImage: page.image.resize(200).url     # image for marker popup
        markerInfo: ''                              # info text for marker popup
```
