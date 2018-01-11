'use strict';

var _expect = require('expect');

var _expect2 = _interopRequireDefault(_expect);

var _buildSyncValidation = require('../buildSyncValidation.js');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

describe('setError', function () {

    it('Converts between ajv format and object', function () {
        var errors = (0, _buildSyncValidation.setError)({}, { dataPath: '.one.two', message: 'I\'m an error' });
        (0, _expect2.default)(errors).toBeA('object').toIncludeKey('one');
    });
});