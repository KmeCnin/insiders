'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _propTypes = require('prop-types');

var _propTypes2 = _interopRequireDefault(_propTypes);

var _BaseInputWidget = require('./BaseInputWidget');

var _BaseInputWidget2 = _interopRequireDefault(_BaseInputWidget);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var EmailWidget = function EmailWidget(props) {
    return _react2.default.createElement(_BaseInputWidget2.default, _extends({ type: 'email' }, props));
};

EmailWidget.propTypes = {
    schema: _propTypes2.default.object.isRequired,
    fieldName: _propTypes2.default.string,
    label: _propTypes2.default.string,
    theme: _propTypes2.default.object,
    multiple: _propTypes2.default.bool,
    required: _propTypes2.default.bool
};

exports.default = EmailWidget;