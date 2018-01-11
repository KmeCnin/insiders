import React, { Component } from 'react';

export default class MultipleSelect extends Component {
    render() {
        return (
            <div className="MultipleSelect">
                <label>{this.props.label}</label><br/>
                <select
                    onChange={(event) => {
                        if (this.props.onChange) {
                            this.props.onChange(event)
                        }
                    }}
                    selected={this.props.selected}
                    multiple="multiple"
                >
                    {this.props.list.map((element, index) => {
                        return (
                        <option
                            value={element.key}
                            disabled={element.disabled ? "disabled" : false}
                        >
                            {element.name}
                        </option>
                        );
                    })}
                </select>
            </div>
        );
    }
}
