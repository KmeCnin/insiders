'use strict';

var _expect = require('expect');

var _expect2 = _interopRequireDefault(_expect);

var _compileSchema = require('../compileSchema');

var _compileSchema2 = _interopRequireDefault(_compileSchema);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

describe('createLiform', function () {
    var schema = {
        definitions: {
            nameref: {
                type: 'string'
            }
        },
        title: 'A schema',
        properties: {
            name: {
                '$ref': '#/definitions/nameref'
            }
        }
    };

    it('should resolve $refs', function () {

        var schemaCompiled = (0, _compileSchema2.default)(schema);
        (0, _expect2.default)(schemaCompiled.properties.name.type).toExist();
        (0, _expect2.default)(schemaCompiled.properties.name.type).toEqual('string');
    });
});