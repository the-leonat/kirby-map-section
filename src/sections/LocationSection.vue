<template>
    <section :input="_uid" class="k-locator-field" @click.stop.prevent.self="">
        <header class="k-section-header">
            <k-headline>{{ sectionLabel }}</k-headline>
        </header>
        <l-map style="height: 60vh" :zoom="zoom" :bounds="bounds">
            <l-tile-layer :url="url" :attribution="attribution" />
            <l-marker
                v-for="item in markers"
                :key="item.id"
                :icon="icon"
                :lat-lng="item.position"
            >
                <l-popup :options="popupOptions">
                    <k-item v-bind="item" layout="cards" />
                   <!-- <header class="k-item-content">
                        <h3 class="k-item-title">{{ item.label }}</h3>
                        <span class="k-item-info">Neustadt-G2-1</span>
                    </header>
                    <k-link :to="item.pageUrl"> 
                        <img v-bind:src="item.imgUrl" />
                    </k-link> !-->
                </l-popup>
            </l-marker>
        </l-map>
    </section>
</template>

<script>
import { latLngBounds, latLng } from "leaflet";
import { LMap, LTileLayer, LMarker, LPopup } from "vue2-leaflet";
import 'leaflet/dist/leaflet.css';

export default {
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup,
    },
    data() {
        return {
            sectionLabel: null,
            markers: null,
            zoom: 15,
            url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            popupOptions: {
                minWidth: 200,
            },
        };
    },
    created() {
        this.load().then((response) => {
            this.sectionLabel = response.label;
            this.markers = response.markers;
        });
    },
    computed: {
        icon() {
            let color = this.markerColor;
            color = color == "light" ? "#efefef" : color;
            color = color == "dark" ? "#161719" : color;

            let icon =
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 142"><path fill="' +
                color +
                '" d="M60,0A59.68,59.68,0,0,0,27.37,9.62c-.31.19-.61.39-.92.6A55.74,55.74,0,0,0,7.57,30.71,59.75,59.75,0,0,0,2.63,77.35q.45,1.53,1,3a83.85,83.85,0,0,0,20.53,32.08c9.72,9.51,20.75,17.68,31.2,26.45l3.7,3.09h2c4.7-3.67,9.69-7,14-11.1,9.2-8.69,18.47-17.37,26.92-26.77.49-.54,1-1.09,1.44-1.64l.3-.36c.43-.54.86-1.06,1.28-1.6s.9-1.16,1.34-1.76,1-1.31,1.4-2,.8-1.21,1.19-1.82c.06-.09.12-.18.17-.27.36-.56.72-1.14,1.06-1.72l.08-.13c.29-.5.58-1,.86-1.51.46-.82.91-1.64,1.34-2.48a58.77,58.77,0,0,0,5.35-13s0,0,0,0A59.85,59.85,0,0,0,60,0Zm0,37.55a22.23,22.23,0,1,1-22.2,22.33A22.15,22.15,0,0,1,60,37.55Z"/></svg>';
            let iconUrl = "data:image/svg+xml;base64," + btoa(icon);
            const iconZoom = 0.8;
            return L.icon({
                iconUrl: iconUrl,
                iconSize: [40 * iconZoom, 47 * iconZoom],
                iconAnchor: [20 * iconZoom , 47 * iconZoom],
            });
        },
        bounds() {
            const latlngs = this.markers?.map((marker) =>
                latLng(marker.position)
            );
            if (!latlngs) return undefined;
            const bounds = latLngBounds(latlngs);
            return bounds;
        },
        attribution() {
            if (this.tiles == "mapbox" || this.tiles == "mapbox.custom") {
                return '&copy; <a href="http://www.openstreetmap.org/copyright" target="_blank" rel="noreferrer">OpenStreetMap</a>, &copy; <a href="https://www.mapbox.com/">Mapbox</a>';
            } else if (this.tiles == "wikimedia") {
                return '&copy; <a href="http://www.openstreetmap.org/copyright" target="_blank" rel="noreferrer">OpenStreetMap</a>, &copy; <a href="https://maps.wikimedia.org" target="_blank" rel="noreferrer">Wikimedia</a>';
            } else if (this.tiles == "openstreetmap") {
                return '&copy; <a href="http://www.openstreetmap.org/copyright" target="_blank" rel="noreferrer">OpenStreetMap</a>';
            } else if (this.tiles == "light_all" || this.tiles == "voyager") {
                return '&copy; <a href="http://www.openstreetmap.org/copyright" target="_blank" rel="noreferrer">OpenStreetMap</a>, &copy; <a href="https://carto.com/attribution" target="_blank" rel="noreferrer">CARTO</a>';
            } else
                return '&copy; <a href="http://www.openstreetmap.org/copyright" target="_blank" rel="noreferrer">OpenStreetMap</a>';
        },
    },
    mounted() {},
    methods: {
        markerClickHandler(pageUrl) {
            console.log(pageUrl);
            if (!pageUrl) return;
            this.$go(pageUrl);
        },
    },
};
</script>
<style>
    .leaflet-container a {
        color: inherit;
    }
    .leaflet-popup-content p {
        margin: inherit;
    }
</style>
