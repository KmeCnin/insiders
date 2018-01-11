'use strict';

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _expect = require('expect');

var _expect2 = _interopRequireDefault(_expect);

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reactAddonsTestUtils = require('react-addons-test-utils');

var _reactAddonsTestUtils2 = _interopRequireDefault(_reactAddonsTestUtils);

var _ = require('../');

var _2 = _interopRequireDefault(_);

var _reactRedux = require('react-redux');

var _redux = require('redux');

var _reduxForm = require('redux-form');

var _enzyme = require('enzyme');

var _testUtils = require('./test-utils');

var _sinon = require('sinon');

var _sinon2 = _interopRequireDefault(_sinon);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

describe('createLiform', function () {
    var schema = {
        title: 'A schema',
        properties: {
            'name': {
                type: 'string'
            }
        }

        //const schemaWrong = {
        //    title: 'A schema',
        //    properties: {
        //        'name' : {
        //            type: 'asdf',
        //        }
        //    }
        //}

    };it('should render a form', function () {

        var Component = _react2.default.createElement(
            _testUtils.FormFrame,
            null,
            _react2.default.createElement(_2.default, { schema: schema })
        );
        var wrapper = (0, _enzyme.render)(Component);
        //console.log(render( Component).html());
        (0, _expect2.default)(wrapper.find('form').length).toEqual(1);
    });

    it('can pass a context', function () {
        var CustomString = function CustomString(field) {
            var fun = field.context.fun;

            fun();
            return _react2.default.createElement('input', _extends({}, field.input, { className: 'form-control', type: 'email' }));
        };
        var CustomWidget = function CustomWidget(props) {
            return _react2.default.createElement(_reduxForm.Field, {
                component: CustomString,
                name: props.fieldName,
                context: props.context
            });
        };
        var myTheme = _extends({}, _.DefaultTheme, { 'string': CustomWidget });

        var fun = _sinon2.default.spy();

        var Component = _react2.default.createElement(
            _testUtils.FormFrame,
            null,
            _react2.default.createElement(_2.default, { schema: schema, context: { fun: fun }, theme: myTheme })
        );
        var wrapper = (0, _enzyme.render)(Component);
        _sinon2.default.assert.calledOnce(fun);
        (0, _expect2.default)(wrapper.find('form').length).toEqual(1);
    });
});