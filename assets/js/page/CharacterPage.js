import React, { Component } from 'react'
// InputForm
import MultipleSelect from '../component/MultipleSelect'
import InputText from '../component/InputText'
import InputNumber from '../component/InputNumber'
import InputReadonly from '../component/InputReadonly'
// StoreData
import AbilityStore from '../store/AbilityStore'
import AttributeStore from '../store/AttributeStore'
import ArcaneStore from '../store/ArcaneStore'
import IncreaseStore from '../store/IncreaseStore'

export default class CharacterPage extends Component {

    constructor(props) {
        super(props);

        this.state = {
            name: '',
            FPmax: 14,
            FPcurrent: 0,
            imp: 1,
            res: 1,
            dev: 1,
            dis: 1,
            abilities: [],
            arcanes: [],
            increases: [],
            attributes: [],
            carats: 0,
        }
    }

    static handleSubmit(event) {
        event.preventDefault()
    }

    computeFP = () => {
        return this.state.abilities.length * 2
        + this.state.arcanes.length
        + this.state.attributes.reduce((sum, attributeId) => {
            return sum + AttributeStore.find(attributeId).pc
        }, 0)
        + this.state.increases.length
        + this.state.imp
        + this.state.res
        + this.state.dev
        + this.state.dis
    }

    isAvailableFor = (cost) => {
        return this.remainingPC() >= cost
    }

    remainingPC = () => {
        return this.state.FPmax - this.computeFP()
    }

    render() {
        return (
        <form onSubmit={this.handleSubmit}>
            <div className="form-group">
                <InputText
                    label="Nom"
                    value={this.state.name}
                    onChange={(event) => {this.setState({
                        name: event.target.value
                    })}}
                />
                <InputNumber
                    label="FP max"
                    value={this.state.FPmax}
                    onChange={(event) => {this.setState({
                        FPmax: parseInt(event.target.value)
                    })}}
                />
                <InputReadonly
                    label="FP actuel"
                    value={this.computeFP()}
                />
            </div>
            <div className="form-group">
                <InputNumber
                    label="Imposer"
                    value={this.state.imp}
                    onChange={(event) => {this.setState({
                        imp: parseInt(event.target.value)
                    })}}
                    min="1"
                    max={this.state.imp + this.remainingPC()}
                />
                <InputNumber
                    label="Résister"
                    value={this.state.res}
                    onChange={(event) => {this.setState({
                        res: parseInt(event.target.value)
                    })}}
                    min="1"
                    max={this.state.res + this.remainingPC()}
                />
                <InputNumber
                    label="Dévoiler"
                    value={this.state.dev}
                    onChange={(event) => {this.setState({
                        dev: parseInt(event.target.value)
                    })}}
                    min="1"
                    max={this.state.dev + this.remainingPC()}
                />
                <InputNumber
                    label="Dissimuler"
                    value={this.state.dis}
                    onChange={(event) => {this.setState({
                        dis: parseInt(event.target.value)
                    })}}
                    min="1"
                    max={this.state.dis + this.remainingPC()}
                />
            </div>
            <div className="form-group">
                <MultipleSelect
                    label="Voies arcaniques"
                    selected={this.state.arcanes}
                    list={ArcaneStore.findAll().map(arcane => {
                        return {
                            key: arcane.id,
                            name: arcane.name,
                            disabled: !this.isAvailableFor(1)
                                && -1 === this.state.arcanes.indexOf(arcane.id),
                        }
                    })}
                    onChange={(event) => {
                        // Get new selected arcanes.
                        this.setState({
                            arcanes: [].slice
                                .call(event.target.selectedOptions)
                                .map(o => o.value)
                        })
                        // Remove abilities not in selected arcanes.
                        this.setState(this.state.abilities.filter(
                            abilityId => -1 !== this.state.arcanes.indexOf(
                                AbilityStore.find(abilityId).arcane
                            )
                        ))
                    }}
                />
                <MultipleSelect
                    label="Capacités"
                    selected={this.state.abilities}
                    list={
                        AbilityStore.findAll()
                            .filter((ability, index) =>
                                -1 !== this.state.arcanes.indexOf(ability.arcane)
                            )
                            .map((ability, index) => ({
                                key: ability.id,
                                name: ability.name,
                                disabled: !this.isAvailableFor(2)
                                    && -1 === this.state.abilities.indexOf(ability.id),
                            }))
                    }
                    onChange={(event) => {this.setState({
                        abilities: [].slice
                            .call(event.target.selectedOptions)
                            .map(o => o.value)
                    })}}
                />
                {<MultipleSelect
                    label="Augmentations"
                    selected={this.state.increases}
                    list={IncreaseStore.findByAbilities(this.state.abilities).map(increase => {
                        const ability = AbilityStore.find(increase.ability)
                        return {
                            key: increase.id,
                            name: ability.name+' '+increase.rank+' ('+increase.short+')',
                            disabled: !this.isAvailableFor(1)
                                && -1 === this.state.increases.indexOf(increase.id),
                        }
                    })}
                    onChange={(event) => {
                        console.log(this.state.increases)
                        this.setState({
                        increases: [].slice
                            .call(event.target.selectedOptions)
                            .map(o => o.value)
                    })}}
                />}
            </div>
            <div className="form-group">
                <MultipleSelect
                    label="Attributs physiques"
                    selected={this.state.attributes}
                    list={AttributeStore.findAll().map(attribute => {
                        return {
                            key: attribute.id,
                            name: attribute.name,
                            disabled: !this.isAvailableFor(parseInt(attribute.pc))
                                && -1 === this.state.attributes.indexOf(attribute.id),
                        }
                    })}
                    onChange={(event) => {this.setState({
                        attributes: [].slice
                            .call(event.target.selectedOptions)
                            .map(o => o.value)
                    })}}
                />
            </div>
            <div className="form-group">
                <InputNumber
                    label="Carats"
                    value={this.state.carats}
                    onChange={(event) => {this.setState({
                        carats: parseInt(event.target.value)
                    })}}
                />
            </div>
            <div className="form-group">
                <input type="submit" value="Submit" />
            </div>
        </form>
        )
    }
}