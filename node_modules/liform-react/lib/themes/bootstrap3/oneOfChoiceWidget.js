'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _propTypes = require('prop-types');

var _propTypes2 = _interopRequireDefault(_propTypes);

var _classnames = require('classnames');

var _classnames2 = _interopRequireDefault(_classnames);

var _reduxForm = require('redux-form');

var _reactRedux = require('react-redux');

var _renderField = require('../../renderField');

var _renderField2 = _interopRequireDefault(_renderField);

var _lodash = require('lodash');

var _lodash2 = _interopRequireDefault(_lodash);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var OneOfChoiceWidget = function (_Component) {
    _inherits(OneOfChoiceWidget, _Component);

    function OneOfChoiceWidget(props) {
        _classCallCheck(this, OneOfChoiceWidget);

        var _this = _possibleConstructorReturn(this, (OneOfChoiceWidget.__proto__ || Object.getPrototypeOf(OneOfChoiceWidget)).call(this, props));

        _this.state = {
            choice: 0
        };
        _this.renderOption = _this.renderOption.bind(_this);
        _this.selectItem = _this.selectItem.bind(_this);
        return _this;
    }

    _createClass(OneOfChoiceWidget, [{
        key: 'render',
        value: function render() {
            var field = this.props;
            var className = (0, _classnames2.default)(['form-group']);
            var schema = field.schema;
            var options = schema.oneOf;

            return _react2.default.createElement(
                'div',
                { className: className },
                _react2.default.createElement(
                    'label',
                    { className: 'control-label', htmlFor: 'field-' + field.fieldName },
                    schema.title
                ),
                _react2.default.createElement(
                    'select',
                    { className: 'form-control', onChange: this.selectItem.bind(this), id: 'field-' + field.fieldName, required: field.required, multiple: false },
                    _lodash2.default.map(options, function (item, idx) {
                        return _react2.default.createElement(
                            'option',
                            { key: options.indexOf(item), value: idx },
                            item.title || idx
                        );
                    })
                ),
                _react2.default.createElement(
                    'div',
                    null,
                    this.renderOption()
                ),
                field.description && _react2.default.createElement(
                    'span',
                    { className: 'help-block' },
                    field.description
                )
            );
        }
    }, {
        key: 'renderOption',
        value: function renderOption() {
            var field = this.props;
            var schema = field.schema.oneOf[this.state.choice];
            return (0, _renderField2.default)(schema, field.fieldName, field.theme, field.prefix, field.context);
        }
    }, {
        key: 'selectItem',
        value: function selectItem(e) {
            var _props = this.props,
                schema = _props.schema,
                context = _props.context,
                dispatch = _props.dispatch;

            for (var property in schema.oneOf[this.state.choice].properties) {
                dispatch((0, _reduxForm.change)(context.formName, property, null));
            }
            this.setState({ choice: e.target.value });
        }
    }]);

    return OneOfChoiceWidget;
}(_react.Component);

OneOfChoiceWidget.propTypes = {
    schema: _propTypes2.default.object.isRequired,
    fieldName: _propTypes2.default.string,
    label: _propTypes2.default.string,
    theme: _propTypes2.default.object,
    multiple: _propTypes2.default.bool,
    required: _propTypes2.default.bool
};

exports.default = (0, _reactRedux.connect)()(OneOfChoiceWidget);