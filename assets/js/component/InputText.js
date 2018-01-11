import React, { Component } from 'react';

export default class InputText extends Component {
    render() {
        return (
            <div className="InputText">
                <label>{this.props.label}</label><br/>
                <input 
                    type="text"
                    value={this.props.value}
                    onChange={(event) => {
                        if (this.props.onChange) {
                            this.props.onChange(event)
                        }
                    }}
                />
            </div>
        );
    }
}
