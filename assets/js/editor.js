/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ 20
(__unused_webpack_module, exports, __webpack_require__) {

var __webpack_unused_export__;
/**
 * @license React
 * react-jsx-runtime.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */
var f=__webpack_require__(540),k=Symbol.for("react.element"),l=Symbol.for("react.fragment"),m=Object.prototype.hasOwnProperty,n=f.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,p={key:!0,ref:!0,__self:!0,__source:!0};
function q(c,a,g){var b,d={},e=null,h=null;void 0!==g&&(e=""+g);void 0!==a.key&&(e=""+a.key);void 0!==a.ref&&(h=a.ref);for(b in a)m.call(a,b)&&!p.hasOwnProperty(b)&&(d[b]=a[b]);if(c&&c.defaultProps)for(b in a=c.defaultProps,a)void 0===d[b]&&(d[b]=a[b]);return{$$typeof:k,type:c,key:e,ref:h,props:d,_owner:n.current}}__webpack_unused_export__=l;exports.jsx=q;exports.jsxs=q;


/***/ },

/***/ 287
(__unused_webpack_module, exports) {

/**
 * @license React
 * react.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */
var l=Symbol.for("react.element"),n=Symbol.for("react.portal"),p=Symbol.for("react.fragment"),q=Symbol.for("react.strict_mode"),r=Symbol.for("react.profiler"),t=Symbol.for("react.provider"),u=Symbol.for("react.context"),v=Symbol.for("react.forward_ref"),w=Symbol.for("react.suspense"),x=Symbol.for("react.memo"),y=Symbol.for("react.lazy"),z=Symbol.iterator;function A(a){if(null===a||"object"!==typeof a)return null;a=z&&a[z]||a["@@iterator"];return"function"===typeof a?a:null}
var B={isMounted:function(){return!1},enqueueForceUpdate:function(){},enqueueReplaceState:function(){},enqueueSetState:function(){}},C=Object.assign,D={};function E(a,b,e){this.props=a;this.context=b;this.refs=D;this.updater=e||B}E.prototype.isReactComponent={};
E.prototype.setState=function(a,b){if("object"!==typeof a&&"function"!==typeof a&&null!=a)throw Error("setState(...): takes an object of state variables to update or a function which returns an object of state variables.");this.updater.enqueueSetState(this,a,b,"setState")};E.prototype.forceUpdate=function(a){this.updater.enqueueForceUpdate(this,a,"forceUpdate")};function F(){}F.prototype=E.prototype;function G(a,b,e){this.props=a;this.context=b;this.refs=D;this.updater=e||B}var H=G.prototype=new F;
H.constructor=G;C(H,E.prototype);H.isPureReactComponent=!0;var I=Array.isArray,J=Object.prototype.hasOwnProperty,K={current:null},L={key:!0,ref:!0,__self:!0,__source:!0};
function M(a,b,e){var d,c={},k=null,h=null;if(null!=b)for(d in void 0!==b.ref&&(h=b.ref),void 0!==b.key&&(k=""+b.key),b)J.call(b,d)&&!L.hasOwnProperty(d)&&(c[d]=b[d]);var g=arguments.length-2;if(1===g)c.children=e;else if(1<g){for(var f=Array(g),m=0;m<g;m++)f[m]=arguments[m+2];c.children=f}if(a&&a.defaultProps)for(d in g=a.defaultProps,g)void 0===c[d]&&(c[d]=g[d]);return{$$typeof:l,type:a,key:k,ref:h,props:c,_owner:K.current}}
function N(a,b){return{$$typeof:l,type:a.type,key:b,ref:a.ref,props:a.props,_owner:a._owner}}function O(a){return"object"===typeof a&&null!==a&&a.$$typeof===l}function escape(a){var b={"=":"=0",":":"=2"};return"$"+a.replace(/[=:]/g,function(a){return b[a]})}var P=/\/+/g;function Q(a,b){return"object"===typeof a&&null!==a&&null!=a.key?escape(""+a.key):b.toString(36)}
function R(a,b,e,d,c){var k=typeof a;if("undefined"===k||"boolean"===k)a=null;var h=!1;if(null===a)h=!0;else switch(k){case "string":case "number":h=!0;break;case "object":switch(a.$$typeof){case l:case n:h=!0}}if(h)return h=a,c=c(h),a=""===d?"."+Q(h,0):d,I(c)?(e="",null!=a&&(e=a.replace(P,"$&/")+"/"),R(c,b,e,"",function(a){return a})):null!=c&&(O(c)&&(c=N(c,e+(!c.key||h&&h.key===c.key?"":(""+c.key).replace(P,"$&/")+"/")+a)),b.push(c)),1;h=0;d=""===d?".":d+":";if(I(a))for(var g=0;g<a.length;g++){k=
a[g];var f=d+Q(k,g);h+=R(k,b,e,f,c)}else if(f=A(a),"function"===typeof f)for(a=f.call(a),g=0;!(k=a.next()).done;)k=k.value,f=d+Q(k,g++),h+=R(k,b,e,f,c);else if("object"===k)throw b=String(a),Error("Objects are not valid as a React child (found: "+("[object Object]"===b?"object with keys {"+Object.keys(a).join(", ")+"}":b)+"). If you meant to render a collection of children, use an array instead.");return h}
function S(a,b,e){if(null==a)return a;var d=[],c=0;R(a,d,"","",function(a){return b.call(e,a,c++)});return d}function T(a){if(-1===a._status){var b=a._result;b=b();b.then(function(b){if(0===a._status||-1===a._status)a._status=1,a._result=b},function(b){if(0===a._status||-1===a._status)a._status=2,a._result=b});-1===a._status&&(a._status=0,a._result=b)}if(1===a._status)return a._result.default;throw a._result;}
var U={current:null},V={transition:null},W={ReactCurrentDispatcher:U,ReactCurrentBatchConfig:V,ReactCurrentOwner:K};function X(){throw Error("act(...) is not supported in production builds of React.");}
exports.Children={map:S,forEach:function(a,b,e){S(a,function(){b.apply(this,arguments)},e)},count:function(a){var b=0;S(a,function(){b++});return b},toArray:function(a){return S(a,function(a){return a})||[]},only:function(a){if(!O(a))throw Error("React.Children.only expected to receive a single React element child.");return a}};exports.Component=E;exports.Fragment=p;exports.Profiler=r;exports.PureComponent=G;exports.StrictMode=q;exports.Suspense=w;
exports.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED=W;exports.act=X;
exports.cloneElement=function(a,b,e){if(null===a||void 0===a)throw Error("React.cloneElement(...): The argument must be a React element, but you passed "+a+".");var d=C({},a.props),c=a.key,k=a.ref,h=a._owner;if(null!=b){void 0!==b.ref&&(k=b.ref,h=K.current);void 0!==b.key&&(c=""+b.key);if(a.type&&a.type.defaultProps)var g=a.type.defaultProps;for(f in b)J.call(b,f)&&!L.hasOwnProperty(f)&&(d[f]=void 0===b[f]&&void 0!==g?g[f]:b[f])}var f=arguments.length-2;if(1===f)d.children=e;else if(1<f){g=Array(f);
for(var m=0;m<f;m++)g[m]=arguments[m+2];d.children=g}return{$$typeof:l,type:a.type,key:c,ref:k,props:d,_owner:h}};exports.createContext=function(a){a={$$typeof:u,_currentValue:a,_currentValue2:a,_threadCount:0,Provider:null,Consumer:null,_defaultValue:null,_globalName:null};a.Provider={$$typeof:t,_context:a};return a.Consumer=a};exports.createElement=M;exports.createFactory=function(a){var b=M.bind(null,a);b.type=a;return b};exports.createRef=function(){return{current:null}};
exports.forwardRef=function(a){return{$$typeof:v,render:a}};exports.isValidElement=O;exports.lazy=function(a){return{$$typeof:y,_payload:{_status:-1,_result:a},_init:T}};exports.memo=function(a,b){return{$$typeof:x,type:a,compare:void 0===b?null:b}};exports.startTransition=function(a){var b=V.transition;V.transition={};try{a()}finally{V.transition=b}};exports.unstable_act=X;exports.useCallback=function(a,b){return U.current.useCallback(a,b)};exports.useContext=function(a){return U.current.useContext(a)};
exports.useDebugValue=function(){};exports.useDeferredValue=function(a){return U.current.useDeferredValue(a)};exports.useEffect=function(a,b){return U.current.useEffect(a,b)};exports.useId=function(){return U.current.useId()};exports.useImperativeHandle=function(a,b,e){return U.current.useImperativeHandle(a,b,e)};exports.useInsertionEffect=function(a,b){return U.current.useInsertionEffect(a,b)};exports.useLayoutEffect=function(a,b){return U.current.useLayoutEffect(a,b)};
exports.useMemo=function(a,b){return U.current.useMemo(a,b)};exports.useReducer=function(a,b,e){return U.current.useReducer(a,b,e)};exports.useRef=function(a){return U.current.useRef(a)};exports.useState=function(a){return U.current.useState(a)};exports.useSyncExternalStore=function(a,b,e){return U.current.useSyncExternalStore(a,b,e)};exports.useTransition=function(){return U.current.useTransition()};exports.version="18.3.1";


/***/ },

/***/ 540
(module, __unused_webpack_exports, __webpack_require__) {



if (true) {
  module.exports = __webpack_require__(287);
} else // removed by dead control flow
{}


/***/ },

/***/ 848
(module, __unused_webpack_exports, __webpack_require__) {



if (true) {
  module.exports = __webpack_require__(20);
} else // removed by dead control flow
{}


/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};

// EXTERNAL MODULE: ./node_modules/react/jsx-runtime.js
var jsx_runtime = __webpack_require__(848);
;// ./assets/js/blocks/icons.js

/**
 * Games Collector SVG Icons
 */
const icons = {};
icons.dice = /*#__PURE__*/(0,jsx_runtime.jsx)("svg", {
  width: "20",
  height: "20",
  viewBox: "0 0 35 35",
  xmlns: "http://www.w3.org/2000/svg",
  children: /*#__PURE__*/(0,jsx_runtime.jsx)("path", {
    d: "M27 6h-16c-2.75 0-5 2.25-5 5v16c0 2.75 2.25 5 5 5h16c2.75 0 5-2.25 5-5v-16c0-2.75-2.25-5-5-5zM13 28c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM13 16c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM19 22c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM25 28c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM25 16c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM25.899 4c-0.467-2.275-2.491-4-4.899-4h-16c-2.75 0-5 2.25-5 5v16c0 2.408 1.725 4.432 4 4.899v-19.899c0-1.1 0.9-2 2-2h19.899z"
  })
});
icons.diceAlt = /*#__PURE__*/(0,jsx_runtime.jsx)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  width: "20",
  height: "20",
  viewBox: "0 0 20 20",
  children: /*#__PURE__*/(0,jsx_runtime.jsx)("path", {
    d: "M688 352h-256c-44 0-80 36-80 80v256c0 44 36 80 80 80h256c44 0 80-36 80-80v-256c0-44-36-80-80-80zM464 704c-26.508 0-48-21.492-48-48s21.492-48 48-48 48 21.492 48 48-21.492 48-48 48zM464 512c-26.508 0-48-21.492-48-48s21.492-48 48-48 48 21.492 48 48-21.492 48-48 48zM560 608c-26.508 0-48-21.492-48-48s21.492-48 48-48 48 21.492 48 48-21.492 48-48 48zM656 704c-26.508 0-48-21.492-48-48s21.492-48 48-48 48 21.492 48 48-21.492 48-48 48zM656 512c-26.508 0-48-21.492-48-48s21.492-48 48-48 48 21.492 48 48-21.492 48-48 48zM670.375 320c-7.465-36.404-39.854-64-78.375-64h-256c-44 0-80 36-80 80v256c0 38.519 27.596 70.918 64 78.375v-318.375c0-17.6 14.4-32 32-32h318.375z"
  })
});
icons.age = /*#__PURE__*/(0,jsx_runtime.jsx)("svg", {
  className: "gc-icon svg gc-icon-age",
  xmlns: "http://www.w3.org/2000/svg",
  width: "20",
  height: "28",
  viewBox: "0 0 20 28",
  children: /*#__PURE__*/(0,jsx_runtime.jsx)("path", {
    d: "M18.562 8.563l-4.562 4.562v12.875c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-6h-1v6c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-12.875l-4.562-4.562c-0.578-0.594-0.578-1.531 0-2.125 0.594-0.578 1.531-0.578 2.125 0l3.563 3.563h5.75l3.563-3.563c0.594-0.578 1.531-0.578 2.125 0 0.578 0.594 0.578 1.531 0 2.125zM13.5 6c0 1.937-1.563 3.5-3.5 3.5s-3.5-1.563-3.5-3.5 1.563-3.5 3.5-3.5 3.5 1.563 3.5 3.5z"
  })
});
icons.time = /*#__PURE__*/(0,jsx_runtime.jsx)("svg", {
  className: "gc-icon svg gc-icon-time",
  xmlns: "http://www.w3.org/2000/svg",
  width: "24",
  height: "28",
  viewBox: "0 0 24 28",
  children: /*#__PURE__*/(0,jsx_runtime.jsx)("path", {
    d: "M14 8.5v7c0 0.281-0.219 0.5-0.5 0.5h-5c-0.281 0-0.5-0.219-0.5-0.5v-1c0-0.281 0.219-0.5 0.5-0.5h3.5v-5.5c0-0.281 0.219-0.5 0.5-0.5h1c0.281 0 0.5 0.219 0.5 0.5zM20.5 14c0-4.688-3.813-8.5-8.5-8.5s-8.5 3.813-8.5 8.5 3.813 8.5 8.5 8.5 8.5-3.813 8.5-8.5zM24 14c0 6.625-5.375 12-12 12s-12-5.375-12-12 5.375-12 12-12 12 5.375 12 12z"
  })
});
icons.players = /*#__PURE__*/(0,jsx_runtime.jsx)("svg", {
  className: "gc-icon svg gc-icon-players",
  xmlns: "http://www.w3.org/2000/svg",
  width: "30",
  height: "28",
  viewBox: "0 0 30 28",
  children: /*#__PURE__*/(0,jsx_runtime.jsx)("path", {
    d: "M9.266 14c-1.625 0.047-3.094 0.75-4.141 2h-2.094c-1.563 0-3.031-0.75-3.031-2.484 0-1.266-0.047-5.516 1.937-5.516 0.328 0 1.953 1.328 4.062 1.328 0.719 0 1.406-0.125 2.078-0.359-0.047 0.344-0.078 0.688-0.078 1.031 0 1.422 0.453 2.828 1.266 4zM26 23.953c0 2.531-1.672 4.047-4.172 4.047h-13.656c-2.5 0-4.172-1.516-4.172-4.047 0-3.531 0.828-8.953 5.406-8.953 0.531 0 2.469 2.172 5.594 2.172s5.063-2.172 5.594-2.172c4.578 0 5.406 5.422 5.406 8.953zM10 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4zM21 10c0 3.313-2.688 6-6 6s-6-2.688-6-6 2.688-6 6-6 6 2.688 6 6zM30 13.516c0 1.734-1.469 2.484-3.031 2.484h-2.094c-1.047-1.25-2.516-1.953-4.141-2 0.812-1.172 1.266-2.578 1.266-4 0-0.344-0.031-0.688-0.078-1.031 0.672 0.234 1.359 0.359 2.078 0.359 2.109 0 3.734-1.328 4.062-1.328 1.984 0 1.937 4.25 1.937 5.516zM28 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4z"
  })
});
icons.difficulty = /*#__PURE__*/(0,jsx_runtime.jsx)("svg", {
  className: "gc-icon svg gc-icon-difficulty",
  xmlns: "http://www.w3.org/2000/svg",
  width: "26",
  height: "28",
  viewBox: "0 0 26 28",
  children: /*#__PURE__*/(0,jsx_runtime.jsx)("path", {
    d: "M26 17.156c0 1.609-0.922 2.953-2.625 2.953-1.906 0-2.406-1.734-4.125-1.734-1.25 0-1.719 0.781-1.719 1.937 0 1.219 0.5 2.391 0.484 3.594v0.078c-0.172 0-0.344 0-0.516 0.016-1.609 0.156-3.234 0.469-4.859 0.469-1.109 0-2.266-0.438-2.266-1.719 0-1.719 1.734-2.219 1.734-4.125 0-1.703-1.344-2.625-2.953-2.625-1.641 0-3.156 0.906-3.156 2.703 0 1.984 1.516 2.844 1.516 3.922 0 0.547-0.344 1.031-0.719 1.391-0.484 0.453-1.172 0.547-1.828 0.547-1.281 0-2.562-0.172-3.828-0.375-0.281-0.047-0.578-0.078-0.859-0.125l-0.203-0.031c-0.031-0.016-0.078-0.016-0.078-0.031v-16c0.063 0.047 0.984 0.156 1.141 0.187 1.266 0.203 2.547 0.375 3.828 0.375 0.656 0 1.344-0.094 1.828-0.547 0.375-0.359 0.719-0.844 0.719-1.391 0-1.078-1.516-1.937-1.516-3.922 0-1.797 1.516-2.703 3.172-2.703 1.594 0 2.938 0.922 2.938 2.625 0 1.906-1.734 2.406-1.734 4.125 0 1.281 1.156 1.719 2.266 1.719 1.797 0 3.578-0.406 5.359-0.5v0.031c-0.047 0.063-0.156 0.984-0.187 1.141-0.203 1.266-0.375 2.547-0.375 3.828 0 0.656 0.094 1.344 0.547 1.828 0.359 0.375 0.844 0.719 1.391 0.719 1.078 0 1.937-1.516 3.922-1.516 1.797 0 2.703 1.516 2.703 3.156z"
  })
});
icons.tags = /*#__PURE__*/(0,jsx_runtime.jsx)("svg", {
  className: "gc-icon svg gc-icon-tags",
  xmlns: "http://www.w3.org/2000/svg",
  width: "30",
  height: "28",
  viewBox: "0 0 30 28",
  children: /*#__PURE__*/(0,jsx_runtime.jsx)("path", {
    d: "M7 7c0-1.109-0.891-2-2-2s-2 0.891-2 2 0.891 2 2 2 2-0.891 2-2zM23.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578s-1.047-0.219-1.406-0.578l-11.172-11.188c-0.797-0.781-1.422-2.297-1.422-3.406v-6.5c0-1.094 0.906-2 2-2h6.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422zM29.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578-0.812 0-1.219-0.375-1.75-0.922l7.344-7.344c0.359-0.359 0.578-0.875 0.578-1.406s-0.219-1.047-0.578-1.422l-11.172-11.156c-0.797-0.797-2.312-1.422-3.422-1.422h3.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422z"
  })
});
/* harmony default export */ const blocks_icons = (icons);
;// ./assets/js/blocks/index.js
/**
 * Games Collector Editor
 *
 * All the js that is admin-only (e.g. Gutenberg).
 */

// Define the textdomain.
wp.i18n.setLocaleData({
  '': {}
}, 'games-collector');

// Load WP.
// import WPAPI from 'wpapi';
// import 'whatwg-fetch';
// import apiFetch from '@wordpress/api-fetch';
// import { addQueryArgs } from '@wordpress/url';

// Load the editor-specific styles.


// Load front-end styles.


// Load the Gutenberg icons.


// Load internal Gutenberg stuff.

const {
  __
} = wp.i18n;
const {
  registerBlockType
} = wp.blocks;
const {
  Spinner,
  TextControl
} = wp.components;
const {
  withSelect
} = wp.data;

/**
 * Make the first letter of a string uppercase.
 *
 * Mirror's PHP's ucfirst function.
 *
 * @param  {string} string The string to process.
 * @return {string}        The string with the first letter capitalized.
 */
function ucfirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

// Register the all games Gutenberg block.
registerBlockType('games-collector/add-all-games', {
  title: __('All Games', 'games-collector'),
  description: __('Add all games to any post or page.', 'games-collector'),
  category: 'widgets',
  icon: {
    src: blocks_icons.dice
  },
  keywords: [__('Games Collector', 'games-collector'), __('game list', 'games-collector'), __('all games', 'games-collector')],
  edit: withSelect(query => {
    return {
      posts: query('core').getEntityRecords('postType', 'gc_game', {
        per_page: -1,
        orderby: 'title',
        order: 'asc'
      })
    };
  })(({
    posts,
    className,
    isSelected
  }) => {
    if (!posts) {
      return /*#__PURE__*/(0,jsx_runtime.jsxs)("p", {
        className: className,
        children: [/*#__PURE__*/(0,jsx_runtime.jsx)(Spinner, {}), __('Loading Posts', 'games-collector')]
      });
    }
    if (0 === posts.length) {
      return /*#__PURE__*/(0,jsx_runtime.jsx)("p", {
        children: __('No Posts', 'games-collector')
      });
    }
    return /*#__PURE__*/(0,jsx_runtime.jsx)("div", {
      className: className,
      children: posts.map(post => {
        let divId = `game-${post.id}-info`,
          title = 'undefined' !== typeof post.url ? `
								<a href=${post.url.toString()}><span className="game-title" id="game-${post.id}-title">${post.title.rendered}</span></a>` : `<span className="game-title" id="game-${post.id}-title">${post.title.rendered}</span>`,
          numPlayers = {
            id: `game-${post.id}-num-players`,
            total: 'undefined' === typeof post.max_players || post.min_players[0] === post.max_players[0] ? post.min_players[0] : `${post.min_players[0]} - ${post.max_players[0]}`
          },
          playingTime = {
            id: `game-${post.id}-playing-time`,
            message: `${post.time} minutes`
          },
          age = {
            id: `game-${post.id}-age`,
            message: `${post.age}+`
          },
          difficulty = {
            id: `game-${post.id}-difficulty`
          },
          attributes = {
            id: `game-${post.id}-attributes`,
            message: `${post.attributes.join(', ')}`
          };
        return /*#__PURE__*/(0,jsx_runtime.jsxs)("div", {
          className: className,
          children: [/*#__PURE__*/(0,jsx_runtime.jsx)("div", {
            dangerouslySetInnerHTML: {
              __html: title
            }
          }), /*#__PURE__*/(0,jsx_runtime.jsxs)("div", {
            className: "game-info",
            id: divId,
            children: [/*#__PURE__*/(0,jsx_runtime.jsx)("span", {
              className: "gc-icon icon-game-players",
              children: blocks_icons.players
            }), /*#__PURE__*/(0,jsx_runtime.jsx)("span", {
              className: "game-num-players",
              id: numPlayers.id,
              children: numPlayers.total
            }), /*#__PURE__*/(0,jsx_runtime.jsx)("span", {
              className: "gc-icon icon-game-time",
              children: blocks_icons.time
            }), /*#__PURE__*/(0,jsx_runtime.jsx)("span", {
              className: "game-playing-time",
              id: playingTime.id,
              children: playingTime.message
            }), /*#__PURE__*/(0,jsx_runtime.jsx)("span", {
              className: "gc-icon icon-game-age",
              children: blocks_icons.age
            }), /*#__PURE__*/(0,jsx_runtime.jsx)("span", {
              className: "game-age",
              id: age.id,
              children: age.message
            }), /*#__PURE__*/(0,jsx_runtime.jsx)("span", {
              className: "gc-icon icon-game-difficulty",
              children: blocks_icons.difficulty
            }), /*#__PURE__*/(0,jsx_runtime.jsx)("span", {
              className: "game-difficulty",
              id: difficulty.id,
              children: ucfirst(post.difficulty[0])
            }), /*#__PURE__*/(0,jsx_runtime.jsxs)("div", {
              className: "game-attributes",
              children: [/*#__PURE__*/(0,jsx_runtime.jsx)("span", {
                className: "gc-icon icon-game-attributes",
                children: blocks_icons.tags
              }), /*#__PURE__*/(0,jsx_runtime.jsx)("span", {
                className: "game-attributes",
                id: attributes.id,
                children: attributes.message
              })]
            })]
          })]
        });
      })
    });
  }) // end withSelect
  ,

  // end edit
  save() {
    // Rendering in PHP
    return null;
  }
});
/******/ })()
;