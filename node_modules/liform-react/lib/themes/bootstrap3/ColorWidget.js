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

var ColorWidget = function ColorWidget(props) {
    return _react2.default.createElement(_BaseInputWidget2.default, _extends({ type: 'color' }, props));
};

_BaseInputWidget2.default.propTypes = {
    schema: _propTypes2.default.object.isRequired,
    type: _propTypes2.default.string.isRequired,
    required: _propTypes2.default.bool,
    fieldName: _propTypes2.default.string,
    label: _propTypes2.default.string
};

exports.default = ColorWidget;