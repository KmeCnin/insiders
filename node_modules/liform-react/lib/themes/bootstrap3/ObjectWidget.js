'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _propTypes = require('prop-types');

var _propTypes2 = _interopRequireDefault(_propTypes);

var _renderFields = require('../../renderFields');

var _renderFields2 = _interopRequireDefault(_renderFields);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Widget = function Widget(props) {
    return _react2.default.createElement(
        'div',
        { className: 'objectType' },
        props.label && _react2.default.createElement(
            'legend',
            null,
            props.label
        ),
        (0, _renderFields2.default)(props.schema, props.theme, props.fieldName && props.fieldName + '.', props.context)
    );
};

Widget.propTypes = {
    schema: _propTypes2.default.object.isRequired,
    fieldName: _propTypes2.default.string,
    label: _propTypes2.default.string,
    theme: _propTypes2.default.object,
    context: _propTypes2.default.object
};

exports.default = Widget;