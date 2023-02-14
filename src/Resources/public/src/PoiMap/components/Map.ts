import {Map as LeafletMap, GeoJSON, LatLng, MapOptions, TileLayer} from "leaflet";
import {MarkerClusterGroup} from "leaflet.markercluster/src";
import {Feature, GeoJsonObject} from "geojson";
import paths from "../data/path.json";
import Marker from "./Marker";

export default class Map extends LeafletMap
{
    public mapOptions: MapOptions
    public geoJson: GeoJsonObject

    public cluster: MarkerClusterGroup
    public marker: Marker[] = []

    constructor(id: string, geoJson: GeoJsonObject, mapOptions: MapOptions)
    {
        super(id, mapOptions)

        this.geoJson = geoJson
        this.mapOptions = mapOptions

        this.applyTiles()
        this.createCluster()
        this.loadMarker()
    }

    private applyTiles(): void
    {
        new TileLayer(paths.tileLayerPath, {
            maxZoom: this.options.maxZoom,
            attribution: '© OpenStreetMap'
        }).addTo(this)
    }

    private createCluster(): void
    {
        this.cluster = new MarkerClusterGroup({
            showCoverageOnHover: false,
            removeOutsideVisibleBounds: true,
            spiderfyOnMaxZoom: true,
            zoomToBoundsOnClick: true,
            spiderLegPolylineOptions: {
                weight: 1.5,
                color: '#222',
                opacity: 0.5
            }
        })

        this.addLayer(this.cluster)
    }

    private loadMarker(): void
    {
        new GeoJSON(this.geoJson, {
            pointToLayer: (feature: Feature, latLng: LatLng) => {
                this.createMarker(feature, latLng)
                return null
            }
        })
    }

    private createMarker(feature: Feature, latLng: LatLng): void
    {
        const marker = new Marker(latLng, feature)

        // Add marker to marker collection
        this.marker.push(marker)

        // Add marker to cluster
        this.cluster.addLayer(marker)
    }
}
