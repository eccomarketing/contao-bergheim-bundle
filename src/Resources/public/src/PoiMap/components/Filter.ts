import App from "../App";

enum FilterType {
    TAG      = 'tag',
    TYPE     = 'type',
    BRANCH   = 'branch',
    CATEGORY = 'category'
}

export default class Filter
{
    public selector: string

    private groups: {}
    private inputs: HTMLInputElement[] = []

    constructor(selector)
    {
        this.selector = selector

        this.prepareFilter()
    }

    private prepareFilter(): void
    {
        const urlParams = new URLSearchParams(window.location.search);
        let instantFiltering = false

        for(const inputElement of document.querySelectorAll(this.selector))
        {
            const input = <HTMLInputElement> inputElement

            // Preselection if get parameter matches input value and filter mode
            if(urlParams.has(input.dataset.filter + '[]') &&  urlParams.getAll(input.dataset.filter + '[]').includes(input.value))
            {
                input.checked = !input.checked
                instantFiltering = true
            }

            // Register event
            input.addEventListener('change', (e) => this.filter())

            this.inputs.push(input)
        }

        if(instantFiltering)
        {
            this.filter()
        }
    }

    public filter(): void
    {
        this.resetFilterGroups()

        for(const marker of App.map.marker)
        {
            if(
                this.checkCumulative(marker.properties.type, FilterType.TYPE) &&
                this.checkDistinctive(marker.properties.categories, FilterType.CATEGORY) &&
                this.checkDistinctive(marker.properties.tags, FilterType.TAG) &&
                this.checkDistinctive([marker.properties.branch], FilterType.BRANCH)
            )
                App.map.cluster.addLayer(marker)
            else
                App.map.cluster.removeLayer(marker)
        }
    }

    private checkCumulative(checkValue, type: FilterType): boolean
    {
        if(!this.groups.hasOwnProperty(type))
        {
            return true
        }

        return !!this.groups[type].filter(input => input.checked && input.value === checkValue).length
    }

    private checkDistinctive(checkValue, type: FilterType): boolean
    {
        if(!this.groups.hasOwnProperty(type))
        {
            return true
        }

        const group = this.groups[type]

        const found = group.filter(input => input.checked && checkValue.includes(parseInt(input.value)))
        const empty = group.every(input => !input.checked)

        return !!found.length || empty
    }

    private resetFilterGroups(): void
    {
        this.groups = {}

        for (const input of this.inputs)
        {
            if(!(input.dataset.filter in this.groups))
            {
                this.groups[input.dataset.filter] = []
            }

            this.groups[input.dataset.filter].push(input)
        }
    }
}
