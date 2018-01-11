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

var _lodash = require('lodash');

var _lodash2 = _interopRequireDefault(_lodash);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var renderSelect = function renderSelect(field) {
    var className = (0, _classnames2.default)(['form-group', { 'has-error': field.meta.touched && field.meta.error }]);
    var options = field.schema.enum;
    var optionNames = field.schema.enum_titles || options;

    var selectOptions = _lodash2.default.zipObject(options, optionNames);
    return _react2.default.createElement(
        'div',
        { className: className },
        _react2.default.createElement(
            'label',
            { className: 'control-label', htmlFor: 'field-' + field.name },
            field.label
        ),
        _react2.default.createElement(
            'select',
            _extends({}, field.input, { className: 'form-control', id: 'field-' + field.name, required: field.required, multiple: field.multiple }),
            !field.required && !field.multiple && _react2.default.createElement(
                'option',
                { key: '', value: '' },
                field.placeholder
            ),
            _lodash2.default.map(selectOptions, function (name, value) {
                return _react2.default.createElement(
                    'option',
                    { key: value, value: value },
                    name
                );
            })
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

var ChoiceWidget = function ChoiceWidget(props) {
    return _react2.default.createElement(_reduxForm.Field, {
        component: renderSelect,
        label: props.label,
        name: props.fieldName,
        required: props.required,
        id: 'field-' + props.fieldName,
        placeholder: props.schema.default,
        description: props.schema.description,
        schema: props.schema,
        multiple: props.multiple
    });
};

ChoiceWidget.propTypes = {
    schema: _propTypes2.default.object.isRequired,
    fieldName: _propTypes2.default.string,
    label: _propTypes2.default.string,
    theme: _propTypes2.default.object,
    multiple: _propTypes2.default.bool,
    required: _propTypes2.default.bool
};

exports.default = ChoiceWidget;