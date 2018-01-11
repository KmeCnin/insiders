'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _propTypes = require('prop-types');

var _propTypes2 = _interopRequireDefault(_propTypes);

var _classnames = require('classnames');

var _classnames2 = _interopRequireDefault(_classnames);

var _reduxForm = require('redux-form');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var renderInput = function renderInput(field) {
    var className = (0, _classnames2.default)(['form-group', { 'has-error': field.meta.touched && field.meta.error }]);
    return _react2.default.createElement(
        'div',
        { className: className },
        _react2.default.createElement(
            'label',
            { className: 'control-label', htmlFor: 'field-' + field.name },
            field.label
        ),
        _react2.default.createElement(
            'div',
            { className: 'input-group' },
            _react2.default.createElement(
                'span',
                { className: 'input-group-addon' },
                '\u20AC '
            ),
            _react2.default.createElement('input', _extends({}, field.input, { type: 'number', className: 'form-control', id: 'field-' + field.name, required: field.required, placeholder: field.placeholder }))
        ),
        field.meta.touched && field.meta.error && _react2.default.createElement(
            'span',
            { className: 'help-block' },
            field.meta.error
        ),
        field.description && _react2.default.createElement(
            'span',
            { className: 'help-block' },
            field.description
        )
    );
};

var MoneyWidget = function MoneyWidget(props) {
    return _react2.default.createElement(_reduxForm.Field, {
        component: renderInput,
        label: props.label,
        name: props.fieldName,
        required: props.required,
        id: 'field-' + props.fieldName,
        placeholder: props.schema.default,
        description: props.schema.description
    });
};

MoneyWidget.propTypes = {
    schema: _propTypes2.default.object.isRequired,
    fieldName: _propTypes2.default.string,
    label: _propTypes2.default.string,
    theme: _propTypes2.default.object,
    multiple: _propTypes2.default.bool,
    required: _propTypes2.default.bool
};

exports.default = MoneyWidget;