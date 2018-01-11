'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.FormFrame = undefined;

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _redux = require('redux');

var _reactRedux = require('react-redux');

var _reduxForm = require('redux-form');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var makeStore = function makeStore() {
    return (0, _redux.createStore)((0, _redux.combineReducers)({ form: _reduxForm.reducer }));
};

var FormFrame = function FormFrame(props) {
    var store = makeStore();
    return _react2.default.createElement(
        _reactRedux.Provider,
        { store: store },
        _react.Children.only(props.children)
    );
};

exports.FormFrame = FormFrame;