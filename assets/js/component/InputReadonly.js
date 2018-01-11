import React, { Component } from 'react';

export default class InputReadonly extends Component {
    render() {
        return (
            <div className="InputText">
                <label>{this.props.label}</label><br/>
                <div>{this.props.value}</div>
            </div>
        );
    }
}
