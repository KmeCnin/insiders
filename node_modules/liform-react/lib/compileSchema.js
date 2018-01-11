'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

function isObject(thing) {
    return (typeof thing === 'undefined' ? 'undefined' : _typeof(thing)) === 'object' && thing !== null && !Array.isArray(thing);
}

function compileSchema(schema, root) {
    if (!root) {
        root = schema;
    }
    var newSchema = void 0;

    if (isObject(schema)) {
        newSchema = {};
        for (var i in schema) {
            if (schema.hasOwnProperty(i)) {
                if (i === '$ref') {
                    newSchema = compileSchema(resolveRef(schema[i], root), root);
                } else {
                    newSchema[i] = compileSchema(schema[i], root);
                }
            }
        }
        return newSchema;
    }

    if (Array.isArray(schema)) {
        newSchema = [];
        for (var _i = 0; _i < schema.length; _i += 1) {
            newSchema[_i] = compileSchema(schema[_i], root);
        }
        return newSchema;
    }

    return schema;
}

function resolveRef(uri, schema) {
    uri = uri.replace('#/', '');
    var tokens = uri.split('/');
    var tip = tokens.reduce(function (obj, token) {
        return obj[token];
    }, schema);

    return tip;
}

exports.default = compileSchema;