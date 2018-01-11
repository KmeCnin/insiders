'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _StringWidget = require('./StringWidget');

var _StringWidget2 = _interopRequireDefault(_StringWidget);

var _TextareaWidget = require('./TextareaWidget');

var _TextareaWidget2 = _interopRequireDefault(_TextareaWidget);

var _EmailWidget = require('./EmailWidget');

var _EmailWidget2 = _interopRequireDefault(_EmailWidget);

var _NumberWidget = require('./NumberWidget');

var _NumberWidget2 = _interopRequireDefault(_NumberWidget);

var _MoneyWidget = require('./MoneyWidget');

var _MoneyWidget2 = _interopRequireDefault(_MoneyWidget);

var _PercentWidget = require('./PercentWidget');

var _PercentWidget2 = _interopRequireDefault(_PercentWidget);

var _ArrayWidget = require('./ArrayWidget');

var _ArrayWidget2 = _interopRequireDefault(_ArrayWidget);

var _CheckboxWidget = require('./CheckboxWidget');

var _CheckboxWidget2 = _interopRequireDefault(_CheckboxWidget);

var _ObjectWidget = require('./ObjectWidget');

var _ObjectWidget2 = _interopRequireDefault(_ObjectWidget);

var _PasswordWidget = require('./PasswordWidget');

var _PasswordWidget2 = _interopRequireDefault(_PasswordWidget);

var _SearchWidget = require('./SearchWidget');

var _SearchWidget2 = _interopRequireDefault(_SearchWidget);

var _UrlWidget = require('./UrlWidget');

var _UrlWidget2 = _interopRequireDefault(_UrlWidget);

var _ColorWidget = require('./ColorWidget');

var _ColorWidget2 = _interopRequireDefault(_ColorWidget);

var _ChoiceWidget = require('./ChoiceWidget');

var _ChoiceWidget2 = _interopRequireDefault(_ChoiceWidget);

var _oneOfChoiceWidget = require('./oneOfChoiceWidget');

var _oneOfChoiceWidget2 = _interopRequireDefault(_oneOfChoiceWidget);

var _DateWidget = require('./DateWidget');

var _DateWidget2 = _interopRequireDefault(_DateWidget);

var _TimeWidget = require('./TimeWidget');

var _TimeWidget2 = _interopRequireDefault(_TimeWidget);

var _DateTimeWidget = require('./DateTimeWidget');

var _DateTimeWidget2 = _interopRequireDefault(_DateTimeWidget);

var _CompatibleDateWidget = require('./CompatibleDateWidget');

var _CompatibleDateWidget2 = _interopRequireDefault(_CompatibleDateWidget);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    object: _ObjectWidget2.default,
    string: _StringWidget2.default,
    textarea: _TextareaWidget2.default,
    email: _EmailWidget2.default,
    integer: _NumberWidget2.default,
    number: _NumberWidget2.default,
    money: _MoneyWidget2.default,
    percent: _PercentWidget2.default,
    array: _ArrayWidget2.default,
    boolean: _CheckboxWidget2.default,
    password: _PasswordWidget2.default,
    search: _SearchWidget2.default,
    url: _UrlWidget2.default,
    color: _ColorWidget2.default,
    choice: _ChoiceWidget2.default,
    date: _DateWidget2.default,
    datetime: _DateTimeWidget2.default,
    time: _TimeWidget2.default,
    OneOfChoiceWidget: _oneOfChoiceWidget2.default,
    'compatible-date': _CompatibleDateWidget2.default
};