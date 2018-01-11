'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _reduxForm = require('redux-form');

var processSubmitErrors = function processSubmitErrors(response) {
    var errors = {};
    if (response.hasOwnProperty('errors')) {
        for (var field in response.errors.children) {
            var value = response.errors.children[field];
            if (value.hasOwnProperty('errors')) {
                errors[field] = value.errors[0];
            }
        }
        throw new _reduxForm.SubmissionError(errors);
    }
    return {};
};

exports.default = processSubmitErrors;