import 'leaflet/dist/leaflet.css'
import 'leaflet.markercluster/dist/MarkerCluster.css'
import 'leaflet.markercluster/dist/MarkerCluster.Default.css'
import "./styles/map.scss"

import {MapOptions} from 'leaflet'
import {GeoJsonObject} from "geojson"
import Map from './components/Map'
import Filter from "./components/Filter";

export interface AppProps
{
    filterSelector: string
    mapId: string
    mapOptions: MapOptions
    geoJson: GeoJsonObject
}

export default class App
{
    public static map: Map
    public static filter: Filter

    public options: AppProps

    constructor(options: AppProps)
    {
        this.options = options

        this.createMap()
        this.createFilter()
    }

    private createMap(): void
    {
        App.map = new Map(
            this.options.mapId,
            this.options.geoJson,
            this.options.mapOptions
        )
    }

    private createFilter(): void
    {
        App.filter = new Filter(
            this.options.filterSelector
        )
    }
}
