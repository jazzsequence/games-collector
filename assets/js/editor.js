/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 6);
/******/ })
/************************************************************************/
/******/ ({

/***/ 19:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("var icon = wp.element.createElement(\n    'svg',\n    { width: '20', height: '20', viewBox: '0 0 35 35', xmlns: 'http://www.w3.org/2000/svg' },\n    wp.element.createElement('path', { d: 'M27 6h-16c-2.75 0-5 2.25-5 5v16c0 2.75 2.25 5 5 5h16c2.75 0 5-2.25 5-5v-16c0-2.75-2.25-5-5-5zM13 28c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM13 16c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM19 22c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM25 28c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM25 16c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM25.899 4c-0.467-2.275-2.491-4-4.899-4h-16c-2.75 0-5 2.25-5 5v16c0 2.408 1.725 4.432 4 4.899v-19.899c0-1.1 0.9-2 2-2h19.899z'\n    })\n);\n\n/* harmony default export */ __webpack_exports__[\"a\"] = (icon);//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMTkuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvYmxvY2tzL2ljb24uanM/OGNlMCJdLCJzb3VyY2VzQ29udGVudCI6WyJ2YXIgaWNvbiA9IHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChcbiAgICAnc3ZnJyxcbiAgICB7IHdpZHRoOiAnMjAnLCBoZWlnaHQ6ICcyMCcsIHZpZXdCb3g6ICcwIDAgMzUgMzUnLCB4bWxuczogJ2h0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnJyB9LFxuICAgIHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudCgncGF0aCcsIHsgZDogJ00yNyA2aC0xNmMtMi43NSAwLTUgMi4yNS01IDV2MTZjMCAyLjc1IDIuMjUgNSA1IDVoMTZjMi43NSAwIDUtMi4yNSA1LTV2LTE2YzAtMi43NS0yLjI1LTUtNS01ek0xMyAyOGMtMS42NTcgMC0zLTEuMzQzLTMtM3MxLjM0My0zIDMtMyAzIDEuMzQzIDMgMy0xLjM0MyAzLTMgM3pNMTMgMTZjLTEuNjU3IDAtMy0xLjM0My0zLTNzMS4zNDMtMyAzLTMgMyAxLjM0MyAzIDMtMS4zNDMgMy0zIDN6TTE5IDIyYy0xLjY1NyAwLTMtMS4zNDMtMy0zczEuMzQzLTMgMy0zIDMgMS4zNDMgMyAzLTEuMzQzIDMtMyAzek0yNSAyOGMtMS42NTcgMC0zLTEuMzQzLTMtM3MxLjM0My0zIDMtMyAzIDEuMzQzIDMgMy0xLjM0MyAzLTMgM3pNMjUgMTZjLTEuNjU3IDAtMy0xLjM0My0zLTNzMS4zNDMtMyAzLTMgMyAxLjM0MyAzIDMtMS4zNDMgMy0zIDN6TTI1Ljg5OSA0Yy0wLjQ2Ny0yLjI3NS0yLjQ5MS00LTQuODk5LTRoLTE2Yy0yLjc1IDAtNSAyLjI1LTUgNXYxNmMwIDIuNDA4IDEuNzI1IDQuNDMyIDQgNC44OTl2LTE5Ljg5OWMwLTEuMSAwLjktMiAyLTJoMTkuODk5eidcbiAgICB9KVxuKTtcblxuZXhwb3J0IGRlZmF1bHQgaWNvbjtcblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL2Fzc2V0cy9qcy9ibG9ja3MvaWNvbi5qc1xuLy8gbW9kdWxlIGlkID0gMTlcbi8vIG1vZHVsZSBjaHVua3MgPSAxIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///19\n");

/***/ }),

/***/ 6:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("Object.defineProperty(__webpack_exports__, \"__esModule\", { value: true });\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__sass_editor_scss__ = __webpack_require__(7);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__sass_editor_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__sass_editor_scss__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__icon__ = __webpack_require__(19);\n/**\n * Games Collector Editor\n *\n * All the js that is admin-only (e.g. Gutenberg).\n */\n\n// Define the textdomain.\nwp.i18n.setLocaleData({ '': {} }, 'games-collector');\n\n// Load the editor styles.\n\n\n// Load the Gutenberg icon.\n\n\n// Load internal Gutenberg stuff.\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\nvar Spinner = wp.components.Spinner;\nvar withSelect = wp.data.withSelect;\n\n\nregisterBlockType('games-collector/add-all-games', {\n    title: __('All Games', 'games-collector'),\n    description: __('Add all games to any post or page.', 'games-collector'),\n    category: 'widgets',\n    icon: {\n        src: __WEBPACK_IMPORTED_MODULE_1__icon__[\"a\" /* default */]\n    },\n    keywords: [__('Games Collector', 'games-collector'), __('game list', 'games-collector'), __('all games', 'games-collector')],\n    attributes: {\n        message: {\n            type: 'array',\n            source: 'children',\n            selector: '.message-body'\n        }\n    },\n    edit: function edit(props) {\n        var message = props.attributes.message,\n            className = props.className,\n            setAttributes = props.setAttributes;\n\n        var onChangeMessage = function onChangeMessage(message) {\n            setAttributes({ message: message });\n        };\n        return wp.element.createElement(\n            'div',\n            { className: className },\n            wp.element.createElement(\n                'h2',\n                null,\n                __('Call to Action', 'games-collector')\n            ),\n            wp.element.createElement(RichText, {\n                tagName: 'div',\n                multiline: 'p',\n                placeholder: __('Add your custom message', 'games-collector'),\n                onChange: onChangeMessage,\n                value: message\n            })\n        );\n    },\n    save: function save(props) {\n        var message = props.attributes.message;\n\n        return wp.element.createElement(\n            'div',\n            null,\n            wp.element.createElement(\n                'h2',\n                null,\n                __('Call to Action', 'games-collector')\n            ),\n            wp.element.createElement(\n                'div',\n                { 'class': 'message-body' },\n                message\n            )\n        );\n    }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiNi5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL2Fzc2V0cy9qcy9ibG9ja3MvaW5kZXguanM/ZWVhYSJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEdhbWVzIENvbGxlY3RvciBFZGl0b3JcbiAqXG4gKiBBbGwgdGhlIGpzIHRoYXQgaXMgYWRtaW4tb25seSAoZS5nLiBHdXRlbmJlcmcpLlxuICovXG5cbi8vIERlZmluZSB0aGUgdGV4dGRvbWFpbi5cbndwLmkxOG4uc2V0TG9jYWxlRGF0YSh7ICcnOiB7fSB9LCAnZ2FtZXMtY29sbGVjdG9yJyk7XG5cbi8vIExvYWQgdGhlIGVkaXRvciBzdHlsZXMuXG5pbXBvcnQgJy4uLy4uL3Nhc3MvZWRpdG9yLnNjc3MnO1xuXG4vLyBMb2FkIHRoZSBHdXRlbmJlcmcgaWNvbi5cbmltcG9ydCBpY29uIGZyb20gJy4vaWNvbic7XG5cbi8vIExvYWQgaW50ZXJuYWwgR3V0ZW5iZXJnIHN0dWZmLlxudmFyIF9fID0gd3AuaTE4bi5fXztcbnZhciByZWdpc3RlckJsb2NrVHlwZSA9IHdwLmJsb2Nrcy5yZWdpc3RlckJsb2NrVHlwZTtcbnZhciBTcGlubmVyID0gd3AuY29tcG9uZW50cy5TcGlubmVyO1xudmFyIHdpdGhTZWxlY3QgPSB3cC5kYXRhLndpdGhTZWxlY3Q7XG5cblxucmVnaXN0ZXJCbG9ja1R5cGUoJ2dhbWVzLWNvbGxlY3Rvci9hZGQtYWxsLWdhbWVzJywge1xuICAgIHRpdGxlOiBfXygnQWxsIEdhbWVzJywgJ2dhbWVzLWNvbGxlY3RvcicpLFxuICAgIGRlc2NyaXB0aW9uOiBfXygnQWRkIGFsbCBnYW1lcyB0byBhbnkgcG9zdCBvciBwYWdlLicsICdnYW1lcy1jb2xsZWN0b3InKSxcbiAgICBjYXRlZ29yeTogJ3dpZGdldHMnLFxuICAgIGljb246IHtcbiAgICAgICAgc3JjOiBpY29uXG4gICAgfSxcbiAgICBrZXl3b3JkczogW19fKCdHYW1lcyBDb2xsZWN0b3InLCAnZ2FtZXMtY29sbGVjdG9yJyksIF9fKCdnYW1lIGxpc3QnLCAnZ2FtZXMtY29sbGVjdG9yJyksIF9fKCdhbGwgZ2FtZXMnLCAnZ2FtZXMtY29sbGVjdG9yJyldLFxuICAgIGF0dHJpYnV0ZXM6IHtcbiAgICAgICAgbWVzc2FnZToge1xuICAgICAgICAgICAgdHlwZTogJ2FycmF5JyxcbiAgICAgICAgICAgIHNvdXJjZTogJ2NoaWxkcmVuJyxcbiAgICAgICAgICAgIHNlbGVjdG9yOiAnLm1lc3NhZ2UtYm9keSdcbiAgICAgICAgfVxuICAgIH0sXG4gICAgZWRpdDogZnVuY3Rpb24gZWRpdChwcm9wcykge1xuICAgICAgICB2YXIgbWVzc2FnZSA9IHByb3BzLmF0dHJpYnV0ZXMubWVzc2FnZSxcbiAgICAgICAgICAgIGNsYXNzTmFtZSA9IHByb3BzLmNsYXNzTmFtZSxcbiAgICAgICAgICAgIHNldEF0dHJpYnV0ZXMgPSBwcm9wcy5zZXRBdHRyaWJ1dGVzO1xuXG4gICAgICAgIHZhciBvbkNoYW5nZU1lc3NhZ2UgPSBmdW5jdGlvbiBvbkNoYW5nZU1lc3NhZ2UobWVzc2FnZSkge1xuICAgICAgICAgICAgc2V0QXR0cmlidXRlcyh7IG1lc3NhZ2U6IG1lc3NhZ2UgfSk7XG4gICAgICAgIH07XG4gICAgICAgIHJldHVybiB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoXG4gICAgICAgICAgICAnZGl2JyxcbiAgICAgICAgICAgIHsgY2xhc3NOYW1lOiBjbGFzc05hbWUgfSxcbiAgICAgICAgICAgIHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChcbiAgICAgICAgICAgICAgICAnaDInLFxuICAgICAgICAgICAgICAgIG51bGwsXG4gICAgICAgICAgICAgICAgX18oJ0NhbGwgdG8gQWN0aW9uJywgJ2dhbWVzLWNvbGxlY3RvcicpXG4gICAgICAgICAgICApLFxuICAgICAgICAgICAgd3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFJpY2hUZXh0LCB7XG4gICAgICAgICAgICAgICAgdGFnTmFtZTogJ2RpdicsXG4gICAgICAgICAgICAgICAgbXVsdGlsaW5lOiAncCcsXG4gICAgICAgICAgICAgICAgcGxhY2Vob2xkZXI6IF9fKCdBZGQgeW91ciBjdXN0b20gbWVzc2FnZScsICdnYW1lcy1jb2xsZWN0b3InKSxcbiAgICAgICAgICAgICAgICBvbkNoYW5nZTogb25DaGFuZ2VNZXNzYWdlLFxuICAgICAgICAgICAgICAgIHZhbHVlOiBtZXNzYWdlXG4gICAgICAgICAgICB9KVxuICAgICAgICApO1xuICAgIH0sXG4gICAgc2F2ZTogZnVuY3Rpb24gc2F2ZShwcm9wcykge1xuICAgICAgICB2YXIgbWVzc2FnZSA9IHByb3BzLmF0dHJpYnV0ZXMubWVzc2FnZTtcblxuICAgICAgICByZXR1cm4gd3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFxuICAgICAgICAgICAgJ2RpdicsXG4gICAgICAgICAgICBudWxsLFxuICAgICAgICAgICAgd3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFxuICAgICAgICAgICAgICAgICdoMicsXG4gICAgICAgICAgICAgICAgbnVsbCxcbiAgICAgICAgICAgICAgICBfXygnQ2FsbCB0byBBY3Rpb24nLCAnZ2FtZXMtY29sbGVjdG9yJylcbiAgICAgICAgICAgICksXG4gICAgICAgICAgICB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoXG4gICAgICAgICAgICAgICAgJ2RpdicsXG4gICAgICAgICAgICAgICAgeyAnY2xhc3MnOiAnbWVzc2FnZS1ib2R5JyB9LFxuICAgICAgICAgICAgICAgIG1lc3NhZ2VcbiAgICAgICAgICAgIClcbiAgICAgICAgKTtcbiAgICB9XG59KTtcblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL2Fzc2V0cy9qcy9ibG9ja3MvaW5kZXguanNcbi8vIG1vZHVsZSBpZCA9IDZcbi8vIG1vZHVsZSBjaHVua3MgPSAxIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///6\n");

/***/ }),

/***/ 7:
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiNy5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL2Fzc2V0cy9zYXNzL2VkaXRvci5zY3NzPzk0MzEiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL2Fzc2V0cy9zYXNzL2VkaXRvci5zY3NzXG4vLyBtb2R1bGUgaWQgPSA3XG4vLyBtb2R1bGUgY2h1bmtzID0gMSJdLCJtYXBwaW5ncyI6IkFBQUEiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///7\n");

/***/ })

/******/ });