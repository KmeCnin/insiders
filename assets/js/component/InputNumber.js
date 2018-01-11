import React, { Component } from 'react';

export default class InputNumber extends Component {
    render() {
        return (
            <div className="InputText">
                <label>{this.props.label}</label><br/>
                <input
                    type="number"
                    value={this.props.value}
                    onChange={(event) => {
                        if (this.props.onChange) {
                            this.props.onChange(event)
                        }
                    }}
                    min={this.props.min}
                    max={this.props.max}
                />
            </div>
        );
    }
}
