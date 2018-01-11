'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var DateSelector = function DateSelector(props) {
    return _react2.default.createElement(
        'select',
        { value: props.extractField(props.input.value), onBlur: props.onBlur, onChange: props.onChange, className: 'form-control', id: 'props-' + props.name, required: props.required },
        !props.required && _react2.default.createElement(
            'option',
            { key: '', value: '' },
            props.emptyOption
        ),
        props.range.map(function (idx) {
            return _react2.default.createElement(
                'option',
                { key: idx, value: idx },
                idx
            );
        })
    );
};

exports.default = DateSelector;