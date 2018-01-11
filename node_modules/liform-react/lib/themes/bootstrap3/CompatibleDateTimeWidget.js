'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.extractDateTimeToken = undefined;

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _classnames = require('classnames');

var _classnames2 = _interopRequireDefault(_classnames);

var _reduxForm = require('redux-form');

var _DateSelector = require('./DateSelector');

var _DateSelector2 = _interopRequireDefault(_DateSelector);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

// produces an array [start..end-1]
var range = function range(start, end) {
    return Array.from({ length: end - start }, function (v, k) {
        return k + start;
    });
};

// produces an array [start..end-1] padded with zeros, (two digits)
var rangeZeroPad = function rangeZeroPad(start, end) {
    return Array.from({ length: end - start }, function (v, k) {
        return ('0' + (k + start)).slice(-2);
    });
};

var extractYear = function extractYear(value) {
    return extractDateTimeToken(value, 0);
};
var extractMonth = function extractMonth(value) {
    return extractDateTimeToken(value, 1);
};
var extractDay = function extractDay(value) {
    return extractDateTimeToken(value, 2);
};
var extractHour = function extractHour(value) {
    return extractDateTimeToken(value, 3);
};
var extractMinute = function extractMinute(value) {
    return extractDateTimeToken(value, 4);
};
var extractSecond = function extractSecond(value) {
    return extractDateTimeToken(value, 5);
};

var extractDateTimeToken = function extractDateTimeToken(value, index) {
    if (!value) {
        return '';
    }
    // Remove timezone Z
    value = value.substring(0, value.length - 1);
    var tokens = value.split(/[-T:]/);
    if (tokens.length != 6) {
        return '';
    }
    return tokens[index];
};

var CompatibleDateTime = function (_React$Component) {
    _inherits(CompatibleDateTime, _React$Component);

    function CompatibleDateTime(props, context) {
        _classCallCheck(this, CompatibleDateTime);

        var _this = _possibleConstructorReturn(this, (CompatibleDateTime.__proto__ || Object.getPrototypeOf(CompatibleDateTime)).call(this, props, context));

        _this.state = {
            year: null,
            month: null,
            day: null,
            hour: null,
            minute: null,
            second: null
        };
        _this.onBlur = _this.onBlur.bind(_this);
        return _this;
    }

    // Produces a RFC 3339 full-date from the state


    _createClass(CompatibleDateTime, [{
        key: 'buildRfc3339Date',
        value: function buildRfc3339Date() {
            var year = this.state.year || '';
            var month = this.state.month || '';
            var day = this.state.day || '';
            return year + '-' + month + '-' + day;
        }

        // Produces a RFC 3339 datetime from the state

    }, {
        key: 'buildRfc3339DateTime',
        value: function buildRfc3339DateTime() {
            var date = this.buildRfc3339Date();
            var hour = this.state.hour || '';
            var minute = this.state.minute || '';
            var second = this.state.second || '';
            return date + 'T' + hour + ':' + minute + ':' + second + 'Z';
        }
    }, {
        key: 'onChangeField',
        value: function onChangeField(field, e) {
            var _this2 = this;

            var value = e.target.value;
            var changeset = {};
            changeset[field] = value;
            this.setState(changeset, function () {
                _this2.props.input.onChange(_this2.buildRfc3339DateTime());
            });
        }
    }, {
        key: 'onBlur',
        value: function onBlur() {
            this.props.input.onBlur(this.buildRfc3339DateTime());
        }
    }, {
        key: 'render',
        value: function render() {
            var field = this.props;
            var className = (0, _classnames2.default)(['form-group', { 'has-error': field.meta.touched && field.meta.error }]);
            return _react2.default.createElement(
                'div',
                { className: className },
                _react2.default.createElement(
                    'label',
                    { className: 'control-label', htmlFor: field.id },
                    field.label
                ),
                _react2.default.createElement(
                    'ul',
                    { className: 'list-inline' },
                    _react2.default.createElement(
                        'li',
                        null,
                        _react2.default.createElement(_DateSelector2.default, _extends({ extractField: extractYear, range: range(field.startYear, field.endYear), emptyOption: 'year', onBlur: this.onBlur, onChange: this.onChangeField.bind(this, 'year') }, field))
                    ),
                    _react2.default.createElement(
                        'li',
                        null,
                        _react2.default.createElement(_DateSelector2.default, _extends({ extractField: extractMonth, range: rangeZeroPad(1, 13), emptyOption: 'month', onBlur: this.onBlur, onChange: this.onChangeField.bind(this, 'month') }, field))
                    ),
                    _react2.default.createElement(
                        'li',
                        null,
                        _react2.default.createElement(_DateSelector2.default, _extends({ extractField: extractDay, range: rangeZeroPad(1, 32), emptyOption: 'day', onBlur: this.onBlur, onChange: this.onChangeField.bind(this, 'day') }, field))
                    ),
                    _react2.default.createElement(
                        'li',
                        null,
                        _react2.default.createElement(_DateSelector2.default, _extends({ extractField: extractHour, range: rangeZeroPad(1, 25), emptyOption: 'hour', onBlur: this.onBlur, onChange: this.onChangeField.bind(this, 'hour') }, field))
                    ),
                    _react2.default.createElement(
                        'li',
                        null,
                        _react2.default.createElement(_DateSelector2.default, _extends({ extractField: extractMinute, range: rangeZeroPad(1, 61), emptyOption: 'minute', onBlur: this.onBlur, onChange: this.onChangeField.bind(this, 'minute') }, field))
                    ),
                    _react2.default.createElement(
                        'li',
                        null,
                        _react2.default.createElement(_DateSelector2.default, _extends({ extractField: extractSecond, range: rangeZeroPad(1, 61), emptyOption: 'second', onBlur: this.onBlur, onChange: this.onChangeField.bind(this, 'second') }, field))
                    )
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
        }
    }]);

    return CompatibleDateTime;
}(_react2.default.Component);

var CompatibleDateTimeWidget = function CompatibleDateTimeWidget(props) {
    return _react2.default.createElement(_reduxForm.Field, {
        component: CompatibleDateTime,
        label: props.label,
        name: props.fieldName,
        required: props.required,
        id: 'field-' + props.fieldName,
        placeholder: props.schema.default,
        description: props.schema.description,
        startYear: props.schema['start-year'] || 1900,
        endYear: props.schema['end-year'] || new Date().getFullYear() + 5,
        type: props.type
    });
};

exports.default = CompatibleDateTimeWidget;

// Only for testing purposes

exports.extractDateTimeToken = extractDateTimeToken;