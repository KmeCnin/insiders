import abilities from '../../../public/rules/ability.json'
import {Increase} from "./IncreaseStore"

export default class AbilityStore {

    /**
     * @return Ability[]
     */
    static findAll = () => {
        return abilities.map(ability => new Ability(ability))
    }

    /**
     * @return Ability
     */
    static find = (id) => {
        return new Ability(abilities.find((ability) => ability.id === id))
    }

    /**
     * @return Ability
     */
    static findByArcane = (arcaneId) => {
        return new Ability(abilities.filter(ability => ability.arcane === arcaneId))
    }
}

export class Ability {

    constructor(ability) {
        this.id = ability.id
        this.name = ability.name
        this.enabled = ability.enabled
        this.public = ability.public
        this.arcane = ability.arcane
        this.short = ability.short
        this.description = ability.description
        this.increases = ability.increases.map((increase, index) => new Increase(ability, index))
    }
}