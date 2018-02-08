import abilities from '../../../public/rules/ability.json'

export default class IncreaseStore {

    /**
     * @returns Increase[]
     */
    static findAll = () => {
        const increases = []
        abilities.forEach(ability => {
            ability.increases.forEach(
                (increase, index) => increases.push(new Increase(ability, index))
            )
        })

        return increases
    }

    /**
     * @returns Increase[]
     */
    static findByAbilities = (abilityIds) => {
        const increases = []
        abilities.forEach(ability => {
            if (-1 !== abilityIds.indexOf(ability.id)) {
                ability.increases.forEach(
                    (increase, index) => increases.push(new Increase(ability, index))
                )
            }
        })

        return increases
    }
}

export class Increase {

    constructor(abilityData, index) {
        this.id = abilityData.id+'-'+(index+1)
        this.rank = (index+1)
        this.ability = abilityData.id
        this.short = abilityData.increases[index].short
        this.description = abilityData.increases[index].description
    }
}