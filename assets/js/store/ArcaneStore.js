import arcanes from '../../../public/rules/arcane.json'

export default class ArcaneStore {

    /**
     * @return Arcane[]
     */
    static findAll = () => {
        return arcanes.map(arcane => new Arcane(arcane))
    }

    /**
     * @return Arcane
     */
    static find = (id) => {
        return arcanes.find(arcane => new Arcane(arcane.id === id))
    }
}

export class Arcane {

    constructor(arcane) {
        this.id = arcane.id
        this.name = arcane.name
        this.enabled = arcane.enabled
        this.public = arcane.public
        this.short = arcane.short
        this.description = arcane.description
    }
}