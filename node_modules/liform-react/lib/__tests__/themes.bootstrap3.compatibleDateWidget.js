'use strict';

var _expect = require('expect');

var _expect2 = _interopRequireDefault(_expect);

var _CompatibleDateWidget = require('../themes/bootstrap3/CompatibleDateWidget.js');

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _ = require('../');

var _2 = _interopRequireDefault(_);

var _testUtils = require('./test-utils');

var _enzyme = require('enzyme');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

describe('CompatibleDateWidget', function () {
    it('on null extracted value is empty', function () {
        (0, _expect2.default)((0, _CompatibleDateWidget.extractDateToken)(null)).toBe('');
    });
    it('on invalid format extracted value is empty', function () {
        (0, _expect2.default)((0, _CompatibleDateWidget.extractDateToken)('lala-land')).toBe('');
    });
    it('can extract month', function () {
        (0, _expect2.default)((0, _CompatibleDateWidget.extractDateToken)('1967-04-03', 1)).toBe('04');
    });

    it('should render a form', function () {
        var schema = {
            title: 'A schema',
            properties: {
                'date': {
                    type: 'string',
                    widget: 'compatible-date'
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
        (0, _expect2.default)(wrapper.find('select').length).toEqual(3);
    });
});