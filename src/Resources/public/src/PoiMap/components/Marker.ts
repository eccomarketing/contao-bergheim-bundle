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

        this.createPopup()
        //this.createTooltip()
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

    private createPopup(): void
    {
        let content = '<div class="poi_tooltip_default is--loading"></div>';
        this.bindPopup(content, {
            offset: [0, -25],
            closeButton: false
        })
    }

    private bindEvents(): void
    {
        this.on('popupopen', async (e) => {
            await this.getPopupData(this.properties.id)
        })
    }

    private async getPopupData(id)
    {
        const response = await fetch(paths.apiRoute + id)
            .then((response) => response.json())
            .then((data) => data)

        const content = await response.template
        this.setPopupContent(content)
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
