'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reduxForm = require('redux-form');

var _classnames = require('classnames');

var _classnames2 = _interopRequireDefault(_classnames);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var processFile = function processFile(onChange, e) {
    var files = e.target.files;
    return new Promise(function () {
        var reader = new FileReader();
        reader.addEventListener('load', function () {
            onChange(reader.result);
        }, false);
        reader.readAsDataURL(files[0]);
    });
};

var File = function File(field) {
    var className = (0, _classnames2.default)(['form-group', { 'has-error': field.meta.touched && field.meta.error }]);
    return _react2.default.createElement(
        'div',
        { className: className },
        _react2.default.createElement(
            'label',
            { className: 'control-label', htmlFor: field.id },
            field.label
        ),
        _react2.default.createElement('input', { name: field.name, onBlur: field.onBlur, onChange: processFile.bind(undefined, field.input.onChange), required: field.required, className: 'form-control', type: 'file' }),
        field.meta.touched && field.meta.error && _react2.default.createElement(
            'span',
            { className: 'help-block' },
            field.meta.error
        ),
        field.description && _react2.default.createElement(
            'span',
            null,
            field.description
        )
    );
};

var FileWidget = function FileWidget(props) {
    return _react2.default.createElement(_reduxForm.Field, {
        component: File,
        label: props.label,
        name: props.fieldName,
        required: props.required,
        id: 'field-' + props.fieldName,
        placeholder: props.schema.default,
        description: props.schema.description,
        type: props.type
    });
};

exports.default = FileWidget;