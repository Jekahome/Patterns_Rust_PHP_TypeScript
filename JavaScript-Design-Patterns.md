## Function Patters

### Callback - API Pattern

```javascript
function doSomething(callback) {
  // do something
  callback(param);
}
```

Can optionally pass the callback object:

```javascript
function findNodes(callback, callbackObj) {
  if (typeof callback === 'function') {
    callback.call(callbackObj, found);
  }
  // or if callback is a string
  if (typeof callback === 'string') {
    callback = callbackObj[callback];
    callback.call(callbackObj, found);
  }
}
```


### Closure Pattern

```javascript
function counter() {
  let count = 0;
  // closes over the parent function
  return () => count =+ 1
}
```


### Lazy Function Definition - Performance Pattern

```javascript
let scareMe = () => {
  console.log('Boo!');
  scareMe = () => console.log('Double boo!');
};
```


### Self Invoking Function - Initialization Pattern

```javascript
(() => console.log('do it'))();

// pass parameters into an IIFE
((global) => console.log(`do it in ${global}`))(window);
```


### Self Invoking Object - Initialization Pattern

```javascript
({
  something: 'something',
  init() {
    console.log('this is like an IIFE, but worse');
    return this;
  }
}.init());
```


### Load Time Branching - Initialization Pattern

```javascript
const utils = {
  addListener: null,
  removeListener: null,
};

if (window.addEventListener) {
  utils.addListener = (el, type, fn) => el.addEventListener(type, fn, false);
  utils.removeListener = (el, type, fn) => el.removeListener(type, fn, false);
} else if (document.attachEvent) {
  utils.addListener = (el, type, fn) => el.attachEvent('on' + type, fn);
  utils.removeListener = (el, type, fn) => el.detachEvent('on' + type, fn);
} else {
  utils.addListener = (el, type, fn) => el['on' + type] = fn;
  utils.removeListener = (el, type, fn) => el['on' + type] = null;
}
```

### Function Properties - Memoization - Performance Pattern

```javascript
const myFunc = param => {
  if (!myFunc.cache[param]) {
    let result = {};
    // expensive operation
    myFunc.cache[param] = result;
  }
  return myFunc.cache[param];
}
// assumes the param is single and primitive
// more complex inputs would need to be serialized
// const cacheKey = JSON.stringify(Array.prototype.slice.call(arguments));
myFunc.cache = {};
```


### Configuration Object - API Pattern

```javascript
const myFunc = ({ first, last, dob, gender, address }) => {
  // pass a configuration object instead of a list of parameters
}
```


### Partial Function Application - Curry - API Pattern

```javascript
const add = (x, y) => {
  if (typeof y === 'undefined') { // partial
    return newY => x + newY;
  }
  return x + y; // full
}
```

More generic curry

```javascript
function curry() {
  const slice = Array.prototype.slice;
  const storedArgs = slice.call(arguments, 1);
  return function() {
    const newArgs = slice.call(arguments);
    const args = storedArgs.concat(newArgs);
    return fn.apply(null args);
  }
};
```

ES2015 curry

```javascript
const curry = (fn, ...first) => {
  return (...second) => {
    return fn(...first, ...second);
  };
};
```

