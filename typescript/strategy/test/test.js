// npm install mocha  --save-dev
// npm install chai --save-dev
// npm install sinon --save-dev
// npm install mocha-testcheck --save-dev
// npm install @types/mocha --save-dev

// запуск теста #base_duck
// $ mocha test -g base_duck

const chai = require('chai');
const sinon = require("sinon");
require('mocha-testcheck').install();

const assert = chai.assert;    // Using Assert style
const expect = chai.expect;    // Using Expect style
const should = chai.should();  // Using Should style

// $ mocha test -g base_duck
describe('Duck #base_duck', () => {

    let MallardDuck = require('../js/duck').MallardDuck;

    it('quack', () => {

        let return_quack = 'Кряк , кряк Duck';
        let duck = new MallardDuck();

        assert.equal(return_quack, duck.quack(), 'not equal quack');
    });
    it('swim', () => {

        let return_swim = 'DuckBrother плавает';
        let duck = new MallardDuck('DuckBrother');

        assert.equal(return_swim, duck.swim(), 'not equal swim');
    });
});
