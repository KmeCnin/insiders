'use strict';

var _expect = require('expect');

var _expect2 = _interopRequireDefault(_expect);

var _processSubmitErrors = require('../processSubmitErrors');

var _processSubmitErrors2 = _interopRequireDefault(_processSubmitErrors);

var _reduxForm = require('redux-form');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

describe('processSubmitErrors', function () {

    it('raises exception if there is an error', function () {
        var response = {
            'code': null,
            'message': 'Validation Failed',
            'errors': {
                'children': {
                    'name': {
                        'errors': ['This value should not be equal to \'Mary\'.']
                    },
                    'color': []
                }
            }
        };
        (0, _expect2.default)(function () {
            (0, _processSubmitErrors2.default)(response);
        }).toThrow(_reduxForm.SubmissionError);
    });

    it('does not raise exception if there is no error', function () {
        var response = {
            'code': null,
            'message': 'Validation Failed'
        };
        (0, _expect2.default)(function () {
            (0, _processSubmitErrors2.default)(response);
        }).toNotThrow(_reduxForm.SubmissionError);
    });
});