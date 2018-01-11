'use strict';

var _expect = require('expect');

var _expect2 = _interopRequireDefault(_expect);

var _CompatibleDateTimeWidget = require('../themes/bootstrap3/CompatibleDateTimeWidget.js');

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _ = require('../');

var _2 = _interopRequireDefault(_);

var _testUtils = require('./test-utils');

var _enzyme = require('enzyme');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

describe('CompatibleDateTimeWidget', function () {
    it('on null extracted value is empty', function () {
        (0, _expect2.default)((0, _CompatibleDateTimeWidget.extractDateTimeToken)(null)).toBe('');
    });
    it('on invalid format extracted value is empty', function () {
        (0, _expect2.default)((0, _CompatibleDateTimeWidget.extractDateTimeToken)('lala-land')).toBe('');
    });
    it('can extract month', function () {
        (0, _expect2.default)((0, _CompatibleDateTimeWidget.extractDateTimeToken)('1967-04-03T23:04:16', 1)).toBe('04');
    });

    it('should render a form', function () {
        var schema = {
            title: 'A schema',
            properties: {
                'date': {
                    type: 'string',
                    widget: 'compatible-datetime'
                }
            }
        };

        var Component = _react2.default.createElement(
            _testUtils.FormFrame,
            null,
            _react2.default.createElement(_2.default, { schema: schema })
        );
        var wrapper = (0, _enzyme.render)(Component);

        (0, _expect2.default)(wrapper.find('form').length).toEqual(1);
        (0, _expect2.default)(wrapper.find('select').length).toEqual(6);
    });
});