'use strict';

var _expect = require('expect');

var _expect2 = _interopRequireDefault(_expect);

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _ = require('../');

var _2 = _interopRequireDefault(_);

var _testUtils = require('./test-utils');

var _enzyme = require('enzyme');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

describe('ArrayWidget', function () {
    it('should render a form with children', function () {
        var schema = {
            title: 'A Schema',
            properties: {
                'tasks': {
                    'type': 'array',
                    'title': 'A list of objects',
                    'items': {
                        'type': 'object',
                        'properties': {
                            'name': {
                                'type': 'string',
                                'title': 'Name of the Task'
                            },
                            'dueTo': {
                                'type': 'string',
                                'title': 'Due To',
                                'widget': 'datetime',
                                'format': 'date-time'
                            }
                        }
                    }
                }
            }
        };

        var Component = _react2.default.createElement(
            _testUtils.FormFrame,
            null,
            _react2.default.createElement(_2.default, { schema: schema })
        );

        var wrapper = (0, _enzyme.render)(Component);

        (0, _expect2.default)(wrapper.find('.btn').length).toEqual(2);
    });
});