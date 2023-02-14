import {Icon, LatLng, Marker as LeafletMarker} from "leaflet";
import paths from "../data/path.json";
import {Feature} from "geojson";

export default class Marker extends LeafletMarker
{
    public properties

    constructor(latLng: LatLng, feature: Feature)
    {
        super(latLng, {
            icon: Marker.getIconByType(feature.properties.type)
        })

        this.properties = feature.properties

        this.createTooltip()
        this.bindEvents()
    }

    private createTooltip(): void
    {
        this.bindTooltip(this.properties.title, {
            direction: 'top',
            offset: [0, -5],
            sticky: true
        })
    }

    private bindEvents(): void
    {
        this.on('click', () => document.location.href = this.properties.url)
    }

    private static getIconByType(type): Icon
    {
        return new Icon({
            iconUrl: paths.markerBasePath + type + '.' + paths.markerFileExtension,
            iconSize: [28, 40],
            iconAnchor: [14, 40]
        })
    }
}
