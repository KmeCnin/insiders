import attributes from '../../../public/rules/attribute.json'

export default class AttributeStore {

    static findAll = () => {
        return attributes.map(attribute => new Attribute(attribute))
    }

    static find = (id) => {
        return new Attribute(attributes.find(attribute => attribute.id === id))
    }
}

export class Attribute {

    constructor(attribute) {
        this.id = attribute.id
        this.name = attribute.name
        this.enabled = attribute.enabled
        this.public = attribute.public
        this.pc = attribute.pc
        this.short = attribute.short
        this.description = attribute.description
    }
}