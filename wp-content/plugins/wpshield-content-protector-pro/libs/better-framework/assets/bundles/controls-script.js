var BetterStudio;
/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./ControlBase.js":
/*!************************!*\
  !*** ./ControlBase.js ***!
  \************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ControlBase": function() { return /* binding */ ControlBase; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_3__);




var ControlBase = /*#__PURE__*/function () {
  function ControlBase(props) {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, ControlBase);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__["default"])(this, "_props", void 0);

    this._props = lodash__WEBPACK_IMPORTED_MODULE_3___default().extend({
      onChange: function onChange() {}
    }, props);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(ControlBase, [{
    key: "propsSet",
    value: function propsSet(options) {
      this._props = lodash__WEBPACK_IMPORTED_MODULE_3___default().extend(this._props, options);
    }
  }, {
    key: "props",
    value: function props(index) {
      if (typeof index === "undefined" || index === null) {
        return this._props;
      }

      if (typeof this._props[index] !== "undefined") {
        return this._props[index];
      }
    }
  }, {
    key: "onChange",
    value: function onChange(value) {
      this._props.onChange(value, this);

      return true;
    }
  }]);

  return ControlBase;
}();

/***/ }),

/***/ "./js/AjaxRequest.js":
/*!***************************!*\
  !*** ./js/AjaxRequest.js ***!
  \***************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "RequestOptions": function() { return /* binding */ RequestOptions; },
/* harmony export */   "config": function() { return /* binding */ config; },
/* harmony export */   "fetch_data": function() { return /* binding */ fetch_data; },
/* harmony export */   "fetch_secure_props": function() { return /* binding */ fetch_secure_props; },
/* harmony export */   "load_data": function() { return /* binding */ load_data; },
/* harmony export */   "load_secure_props": function() { return /* binding */ load_secure_props; },
/* harmony export */   "request": function() { return /* binding */ request; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _Cache__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Cache */ "./js/Cache.js");





var cache = new _Cache__WEBPACK_IMPORTED_MODULE_4__.CacheMemory();
var config = {
  endPoint: "betterstudio/v1/control-data"
};
function request(_x, _x2) {
  return _request.apply(this, arguments);
}

function _request() {
  _request = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().mark(function _callee(path, data) {
    var result;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().wrap(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            _context.next = 2;
            return wp.apiRequest({
              path: path,
              data: data,
              method: 'POST'
            }).promise();

          case 2:
            result = _context.sent;

            if (result !== null && result !== void 0 && result.data) {
              _context.next = 5;
              break;
            }

            throw false;

          case 5:
            return _context.abrupt("return", Promise.resolve(result.data));

          case 6:
          case "end":
            return _context.stop();
        }
      }
    }, _callee);
  }));
  return _request.apply(this, arguments);
}

function fetch_data(_x3, _x4) {
  return _fetch_data.apply(this, arguments);
}

function _fetch_data() {
  _fetch_data = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().mark(function _callee2(controlType, data) {
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().wrap(function _callee2$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            return _context2.abrupt("return", request(config.endPoint, {
              type: controlType,
              params: data
            }));

          case 1:
          case "end":
            return _context2.stop();
        }
      }
    }, _callee2);
  }));
  return _fetch_data.apply(this, arguments);
}

function load_data(_x5, _x6, _x7) {
  return _load_data.apply(this, arguments);
}

function _load_data() {
  _load_data = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().mark(function _callee3(controlType, data, cacheKey) {
    var result;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().wrap(function _callee3$(_context3) {
      while (1) {
        switch (_context3.prev = _context3.next) {
          case 0:
            if (!cache.cache_exists(cacheKey)) {
              _context3.next = 2;
              break;
            }

            return _context3.abrupt("return", cache.cache_get(cacheKey));

          case 2:
            result = fetch_data(controlType, data).then(function (response) {
              cache.cache_set(cacheKey, response);
              return response;
            })["catch"](function (error) {
              cache.cache_delete(cacheKey);
              return error;
            });
            cache.cache_set(cacheKey, result);
            return _context3.abrupt("return", result);

          case 5:
          case "end":
            return _context3.stop();
        }
      }
    }, _callee3);
  }));
  return _load_data.apply(this, arguments);
}

function fetch_secure_props(_x8, _x9) {
  return _fetch_secure_props.apply(this, arguments);
}

function _fetch_secure_props() {
  _fetch_secure_props = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().mark(function _callee4(controlType, props) {
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().wrap(function _callee4$(_context4) {
      while (1) {
        switch (_context4.prev = _context4.next) {
          case 0:
            return _context4.abrupt("return", request("betterstudio/v1/control-props", {
              type: controlType,
              props: props
            }));

          case 1:
          case "end":
            return _context4.stop();
        }
      }
    }, _callee4);
  }));
  return _fetch_secure_props.apply(this, arguments);
}

function load_secure_props(_x10, _x11, _x12) {
  return _load_secure_props.apply(this, arguments);
}

function _load_secure_props() {
  _load_secure_props = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().mark(function _callee5(controlType, props, cacheKey) {
    var result;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().wrap(function _callee5$(_context5) {
      while (1) {
        switch (_context5.prev = _context5.next) {
          case 0:
            if (!cache.cache_exists(cacheKey)) {
              _context5.next = 2;
              break;
            }

            return _context5.abrupt("return", cache.cache_get(cacheKey));

          case 2:
            result = fetch_secure_props(controlType, props).then(function (response) {
              cache.cache_set(cacheKey, response);
              return response;
            })["catch"](function (error) {
              cache.cache_delete(cacheKey);
              return error;
            });
            cache.cache_set(cacheKey, result);
            return _context5.abrupt("return", result);

          case 5:
          case "end":
            return _context5.stop();
        }
      }
    }, _callee5);
  }));
  return _load_secure_props.apply(this, arguments);
}

var RequestOptions = /*#__PURE__*/function () {
  function RequestOptions() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, RequestOptions);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(RequestOptions, null, [{
    key: "url",
    value: function url(append) {
      var _wpApiSettings;

      var base = ((_wpApiSettings = wpApiSettings) === null || _wpApiSettings === void 0 ? void 0 : _wpApiSettings.root) || "";
      return base.replace(/\/$/, "") + "/" + append;
    }
  }, {
    key: "nonce",
    value: function nonce() {
      var _wpApiSettings2;

      return ((_wpApiSettings2 = wpApiSettings) === null || _wpApiSettings2 === void 0 ? void 0 : _wpApiSettings2.nonce) || "";
    }
  }, {
    key: "params",
    value: function params() {
      return {
        _wpnonce: this.nonce()
      };
    }
  }]);

  return RequestOptions;
}();

/***/ }),

/***/ "./js/Cache.js":
/*!*********************!*\
  !*** ./js/Cache.js ***!
  \*********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "CacheMemory": function() { return /* binding */ CacheMemory; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");



var CacheMemory = /*#__PURE__*/function () {
  function CacheMemory() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, CacheMemory);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__["default"])(this, "storage", void 0);

    this.storage = new Map();
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(CacheMemory, [{
    key: "cache_exists",
    value: function cache_exists(key) {
      return this.storage.has(key);
    }
  }, {
    key: "cache_get",
    value: function cache_get(key) {
      return this.storage.get(key);
    }
  }, {
    key: "cache_set",
    value: function cache_set(key, value) {
      this.storage.set(key, value);
      return true;
    }
  }, {
    key: "cache_delete",
    value: function cache_delete(key) {
      return this.storage["delete"](key);
    }
  }, {
    key: "cache_flush",
    value: function cache_flush() {
      this.storage.clear();
      return true;
    }
  }]);

  return CacheMemory;
}();

/***/ }),

/***/ "./js/Hooks.js":
/*!*********************!*\
  !*** ./js/Hooks.js ***!
  \*********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/**
 * @file A WordPress-like hook system for JavaScript.
 *
 * This file demonstrates a simple hook system for JavaScript based on the hook
 * system in WordPress. The purpose of this is to make your code extensible and
 * allowing other developers to hook into your code with their own callbacks.
 *
 * There are other ways to do this, but this will feel right at home for
 * WordPress developers.
 *
 * @author Rheinard Korf
 * @license GPL2 (https://www.gnu.org/licenses/gpl-2.0.html)
 *
 * @requires underscore.js (http://underscorejs.org/)
 */
var Hooks = {};
Hooks.actions = {}; // Registered actions

Hooks.filters = {}; // Registered filters

/**
 * Add a new Action callback to Hooks.actions
 *
 * @param tag The tag specified by do_action()
 * @param callback The callback function to call when do_action() is called
 * @param priority The order in which to call the callbacks. Default: 10 (like WordPress)
 */

Hooks.add_action = function (tag, callback, priority) {
  if (typeof priority === "undefined") {
    priority = 10;
  } // If the tag doesn't exist, create it.


  Hooks.actions[tag] = Hooks.actions[tag] || [];
  Hooks.actions[tag].push({
    priority: priority,
    callback: callback
  });
};
/**
 * Add a new Filter callback to Hooks.filters
 *
 * @param tag The tag specified by apply_filters()
 * @param callback The callback function to call when apply_filters() is called
 * @param priority Priority of filter to apply. Default: 10 (like WordPress)
 */


Hooks.add_filter = function (tag, callback, priority) {
  if (typeof priority === "undefined") {
    priority = 10;
  } // If the tag doesn't exist, create it.


  Hooks.filters[tag] = Hooks.filters[tag] || [];
  Hooks.filters[tag].push({
    priority: priority,
    callback: callback
  });
};
/**
 * Remove an Anction callback from Hooks.actions
 *
 * Must be the exact same callback signature.
 * Warning: Anonymous functions can not be removed.
 * @param tag The tag specified by do_action()
 * @param callback The callback function to remove
 */


Hooks.remove_action = function (tag, callback) {
  Hooks.actions[tag] = Hooks.actions[tag] || [];
  Hooks.actions[tag].forEach(function (filter, i) {
    if (filter.callback === callback) {
      Hooks.actions[tag].splice(i, 1);
    }
  });
};
/**
 * Remove a Filter callback from Hooks.filters
 *
 * Must be the exact same callback signature.
 * Warning: Anonymous functions can not be removed.
 * @param tag The tag specified by apply_filters()
 * @param callback The callback function to remove
 */


Hooks.remove_filter = function (tag, callback) {
  Hooks.filters[tag] = Hooks.filters[tag] || [];
  Hooks.filters[tag].forEach(function (filter, i) {
    if (filter.callback === callback) {
      Hooks.filters[tag].splice(i, 1);
    }
  });
};
/**
 * Calls actions that are stored in Hooks.actions for a specific tag or nothing
 * if there are no actions to call.
 *
 * @param tag A registered tag in Hook.actions
 * @options Optional JavaScript object to pass to the callbacks
 */


Hooks.do_action = function (tag, options) {
  var actions = [];

  if (typeof Hooks.actions[tag] !== "undefined" && Hooks.actions[tag].length > 0) {
    Hooks.actions[tag].forEach(function (hook) {
      actions[hook.priority] = actions[hook.priority] || [];
      actions[hook.priority].push(hook.callback);
    });
    actions.forEach(function (hooks) {
      hooks.forEach(function (callback) {
        callback(options);
      });
    });
  }
};
/**
 * Calls filters that are stored in Hooks.filters for a specific tag or return
 * original value if no filters exist.
 *
 * @param tag A registered tag in Hook.filters
 * @options Optional JavaScript object to pass to the callbacks
 */


Hooks.apply_filters = function (tag, value, options) {
  var filters = [];

  if (typeof Hooks.filters[tag] !== "undefined" && Hooks.filters[tag].length > 0) {
    Hooks.filters[tag].forEach(function (hook) {
      filters[hook.priority] = filters[hook.priority] || [];
      filters[hook.priority].push(hook.callback);
    });
    filters.forEach(function (hooks) {
      hooks.forEach(function (callback) {
        value = callback(value, options);
      });
    });
  }

  return value;
};

/* harmony default export */ __webpack_exports__["default"] = (Hooks);

/***/ }),

/***/ "./js/Redirect.js":
/*!************************!*\
  !*** ./js/Redirect.js ***!
  \************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "parse_url": function() { return /* binding */ parse_url; },
/* harmony export */   "redirect": function() { return /* binding */ redirect; },
/* harmony export */   "redirectRest": function() { return /* binding */ redirectRest; },
/* harmony export */   "redirectToControl": function() { return /* binding */ redirectToControl; }
/* harmony export */ });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _AjaxRequest__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AjaxRequest */ "./js/AjaxRequest.js");


function parse_url(str) {
  if (-1 === str.indexOf("?")) {
    return {
      url: str,
      params: {}
    };
  }

  var query = str.split("?"),
      params = query[1].split("&") || [],
      url = query[0];
  var results = {}; //

  results.url = url;
  results.params = {};
  params.forEach(function (value) {
    var item = value.split("=");
    results.params[item[0]] = item[1];
  });
  return results;
}
function redirect(url, params, method) {
  var _document$body;

  var form = document.createElement('form'),
      parse = parse_url(url); //

  form.action = parse.url;
  form.method = method.toUpperCase() === "POST" ? "POST" : "GET";
  form.style.display = "none";

  for (var key in lodash__WEBPACK_IMPORTED_MODULE_0___default().extend(parse.params, params)) {
    var input = document.createElement("input");
    input.type = "hidden";
    input.name = key;
    input.value = parse.params[key];
    form.append(input);
  }

  (_document$body = document.body) === null || _document$body === void 0 ? void 0 : _document$body.appendChild(form);
  form.submit();
}
function redirectRest(endpoint, params, method) {
  redirect(_AjaxRequest__WEBPACK_IMPORTED_MODULE_1__.RequestOptions.url(endpoint), lodash__WEBPACK_IMPORTED_MODULE_0___default().extend(params, _AjaxRequest__WEBPACK_IMPORTED_MODULE_1__.RequestOptions.params()), method);
}
function redirectToControl(controlType, params, method) {
  redirectRest(_AjaxRequest__WEBPACK_IMPORTED_MODULE_1__.config.endPoint, {
    type: controlType,
    params: JSON.stringify(params)
  }, method);
}

/***/ }),

/***/ "./js/UI.js":
/*!******************!*\
  !*** ./js/UI.js ***!
  \******************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ __webpack_exports__["default"] = ({
  is_blocked: function is_blocked($element) {
    if ($element instanceof Element) {
      $element = jquery__WEBPACK_IMPORTED_MODULE_0___default()($element);
    }

    return !!$element.data('better-studio-el-blocked');
  },
  block: function block($element) {
    if ($element instanceof Element) {
      $element = jquery__WEBPACK_IMPORTED_MODULE_0___default()($element);
    }

    if (this.is_blocked($element)) {
      return;
    }

    $element.data('better-studio-el-blocked', true);
    $element.prepend('<div class="better-studio-overlay" style="z-index:10;display:none;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0;display:none;position: absolute;background:rgba(255,255,255,0.4)"></div>');
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(".better-studio-overlay", $element).fadeIn();
  },
  unblock: function unblock($element) {
    if ($element instanceof Element) {
      $element = jquery__WEBPACK_IMPORTED_MODULE_0___default()($element);
    }

    if (!this.is_blocked($element)) {
      return;
    }

    $element.data('better-studio-el-blocked', false);
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(".better-studio-overlay", $element).fadeOut(function () {
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).remove();
    });
  },
  // Panel loader
  // status: loading, succeed, error
  panel_loader: function panel_loader(status) {
    var message = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "";
    var $bf_loading = jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-loading');

    if ($bf_loading.length === 0) {
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(document.body).append('<div class="bf-loading">\n    <div class="loader">\n        <div class="loader-icon in-loading-icon "><i class="dashicons dashicons-update"></i></div>\n        <div class="loader-icon loaded-icon"><i class="dashicons dashicons-yes"></i></div>\n        <div class="loader-icon not-loaded-icon"><i class="dashicons dashicons-no-alt"></i></div>\n        <div class="message">An Error Occurred!</div>\n    </div>\n</div>').append('<style>\n    .bf-loading {\n        position: fixed;\n        top: 0;\n        left: 0;\n        width: 100%;\n        height: 100%;\n        background-color: #636363;\n        background-color: rgba(0, 0, 0, 0.41);\n        display: none;\n        z-index: 99999;\n    }\n\n    .bf-loading .loader {\n        width: 300px;\n        height: 180px;\n        position: absolute;\n        top: 50%;\n        left: 50%;\n        margin-top: -90px;\n        margin-left: -150px;\n        text-align: center;\n    }\n\n    .bf-loading.not-loaded,\n    .bf-loading.loaded,\n    .bf-loading.in-loading {\n        display: block;\n    }\n\n    .bf-loading.in-loading .loader {\n        color: white;\n    }\n\n    .bf-loading.loaded .loader {\n        color: #27c55a;\n    }\n\n    .bf-loading.not-loaded .loader {\n        color: #ff0000;\n    }\n\n    .bf-loading .loader .loader-icon {\n        font-size: 30px;\n        -webkit-transition: all 0.2s ease;\n        -moz-transition: all 0.2s ease;\n        -ms-transition: all 0.2s ease;\n        -o-transition: all 0.2s ease;\n        transition: all .2s ease;\n        opacity: 0;\n        border-radius: 10px;\n        background-color: #333;\n        background-color: rgba(51, 51, 51, 0.86);\n        width: 60px;\n        height: 60px;\n        line-height: 60px;\n        margin-top: 20px;\n        display: none;\n        position: absolute;\n        left: 50%;\n        margin-left: -30px;\n    }\n\n    .bf-loading .loader .loader-icon .dashicons,\n    .bf-loading .loader .loader-icon .dashicons-before:before {\n        font-size: 55px;\n        line-height: 60px;\n        width: 60px;\n        height: 60px;\n        text-align: center;\n    }\n\n    .bf-loading.in-loading .loader .loader-icon.in-loading-icon,\n    .bf-loading.in-loading.loader .loader-icon.in-loading-icon {\n        opacity: 1;\n        display: inline-block;\n    }\n\n    .bf-loading.in-loading .loader .loader-icon.in-loading-icon .dashicons,\n    .bf-loading.in-loading .loader .loader-icon.in-loading-icon .dashicons-before:before {\n        -webkit-animation: spin 1.15s linear infinite;\n        -moz-animation: spin 1.15s linear infinite;\n        animation: spin 1.15s linear infinite;\n        font-size: 30px;\n    }\n\n    .bf-loading.loaded .loader .loader-icon.loaded-icon {\n        opacity: 1;\n        display: inline-block;\n        font-size: 50px;\n    }\n\n    .bf-loading.loaded .loader .loader-icon.loaded .dashicons,\n    .bf-loading.loaded .loader .loader-icon.loaded .dashicons-before:before {\n        width: 57px;\n    }\n\n    .bf-loading.not-loaded .loader .loader-icon.not-loaded-icon {\n        opacity: 1;\n        display: inline-block;\n    }\n\n    .bf-loading.not-loaded .loader .loader-icon.not-loaded-icon .dashicons,\n    .bf-loading.not-loaded .loader .loader-icon.not-loaded-icon .dashicons-before:before {\n        font-size: 50px;\n        line-height: 62px;\n    }\n\n    .bf-loading .loader .message {\n        display: none;\n        color: #ff0000;\n        font-size: 12px;\n        line-height: 24px;\n        min-width: 100px;\n        max-width: 300px;\n        left: auto;\n        right: auto;\n        text-align: center;\n        background-color: #333;\n        background-color: rgba(51, 51, 51, 0.86);\n        border-radius: 5px;\n        padding: 4px 20px;\n        margin-top: 90px;\n    }\n\n    .bf-loading.with-message .loader .message {\n        display: inline-block;\n    }\n\n    .bf-loading.loaded .loader .message {\n        color: #27c55a;\n    }\n\n    .bf-loading.in-loading .loader .message {\n        color: #fff;\n    }\n\n    @-moz-keyframes spin {\n        100% {\n            -moz-transform: rotate(360deg);\n        }\n    }\n\n    @-webkit-keyframes spin {\n        100% {\n            -webkit-transform: rotate(360deg);\n        }\n    }\n\n    @keyframes spin {\n        100% {\n            -webkit-transform: rotate(360deg);\n            transform: rotate(360deg);\n        }\n    }\n</style>');
      $bf_loading = jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-loading');
    }

    if (status === 'loading') {
      $bf_loading.removeClass().addClass('bf-loading in-loading');

      if (message !== '') {
        $bf_loading.find('.message').html(message);
        $bf_loading.addClass('with-message');
      }
    } else if (status === 'error') {
      $bf_loading.removeClass().addClass('bf-loading not-loaded');

      if (message !== '') {
        $bf_loading.find('.message').html(message);
        $bf_loading.addClass('with-message');
      }

      setTimeout(function () {
        $bf_loading.removeClass('not-loaded');
        $bf_loading.find('.message').html('');
        $bf_loading.removeClass('with-message');
      }, 1500);
    } else if (status === 'succeed') {
      $bf_loading.removeClass().addClass('bf-loading loaded');

      if (message !== '') {
        $bf_loading.find('.message').html(message);
        $bf_loading.addClass('with-message');
      }

      setTimeout(function () {
        $bf_loading.removeClass('loaded');
        $bf_loading.find('.message').html('');
        $bf_loading.removeClass('with-message');
      }, 1000);
    } else if (status === 'hide') {
      setTimeout(function () {
        $bf_loading.removeClass(' in-loading');
        $bf_loading.find('.message').html('');
        $bf_loading.removeClass('with-message');
      }, 500);
    }
  }
});

/***/ }),

/***/ "./src/AdvanceSelect/script.js":
/*!*************************************!*\
  !*** ./src/AdvanceSelect/script.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _Features_script__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../Features/script */ "./src/Features/script.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "listElement", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "onClickCallback", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'advance_select';
    }
  }, {
    key: "init",
    value: function init(element) {
      this.context = element;
      var listElement = this.context.querySelector(".bf-advanced-select-group");

      if (!listElement) {
        return false;
      }

      this.listElement = listElement;
      this.onClickCallback = this.onClick.bind(this);
      this.listElement.addEventListener("click", this.onClickCallback);
      return true;
    }
  }, {
    key: "onClick",
    value: function onClick(event) {
      if (event.target.tagName === "UL") {
        return;
      }

      var element = event.target.tagName !== 'li' ? event.target.closest('li') : event.target; // is item disabled?

      if (element.classList.contains('disable')) {
        return;
      } // is pro?


      if (element.classList.contains('pro-feature')) {
        (0,_Features_script__WEBPACK_IMPORTED_MODULE_8__.ProFeatureModal)(element);

        if (!(0,_Features_script__WEBPACK_IMPORTED_MODULE_8__.option)(element, 'selectable', false)) {
          return;
        }
      } // is none select allowed


      if (this.activeItemsLength() !== 1 || !element.classList.contains('active') || this.allowDeselect()) {
        element.classList.toggle('active');
      }

      if (!this.isMultiple()) {
        this.siblings(element).map(function (element) {
          return element.classList.remove('active');
        });
      }

      this.save();
    }
  }, {
    key: "isMultiple",
    value: function isMultiple() {
      return this.context.querySelector(".bf-advanced-select").classList.contains('multiple');
    }
  }, {
    key: "activeItemsLength",
    value: function activeItemsLength() {
      return this.listElement.querySelectorAll("li.active").length;
    }
  }, {
    key: "allowDeselect",
    value: function allowDeselect() {
      return this.context.querySelector(".bf-advanced-select").classList.contains('allow_deselect');
    }
  }, {
    key: "siblings",
    value: function siblings(element) {
      return Array.from(element.parentElement.children).filter(function (el) {
        return el !== element;
      });
    }
  }, {
    key: "save",
    value: function save() {
      this.valueSet(this.collectActiveItems());
    }
  }, {
    key: "collectActiveItems",
    value: function collectActiveItems() {
      return Array.from(this.context.querySelectorAll("li.active")).map(function (li) {
        return li.dataset.value;
      });
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      var input = this.context.querySelector('.value');

      if (Array.isArray(value)) {
        value = value.join(',');
      } else if (typeof value !== "string") {
        return false;
      }

      input.value = value;
      input.dispatchEvent(new Event('change', {
        bubbles: true
      }));
      this.onChange(input.value);
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return this.context.querySelector('.value').val();
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var _this$listElement;

      (_this$listElement = this.listElement) === null || _this$listElement === void 0 ? void 0 : _this$listElement.removeEventListener("click", this.onClickCallback);
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/AjaxAction/old.js":
/*!*******************************!*\
  !*** ./src/AjaxAction/old.js ***!
  \*******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var _js_UI__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../js/UI */ "./js/UI.js");
/* harmony import */ var _js_AjaxRequest__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../js/AjaxRequest */ "./js/AjaxRequest.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_2__);



/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var $context = jquery__WEBPACK_IMPORTED_MODULE_2___default()(context);
  $context.on('click', '.bf-ajax_action-field-container .bf-action-button', function (e) {
    var $this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this);
    e.preventDefault();
    _js_UI__WEBPACK_IMPORTED_MODULE_0__["default"].panel_loader('loading');

    var _confirm_msg = $this.data('confirm');

    if (typeof _confirm_msg != "undefined") if (!confirm(_confirm_msg)) {
      _js_UI__WEBPACK_IMPORTED_MODULE_0__["default"].panel_loader('hide');
      return false;
    }
    var data = {
      callback: $this.data('callback'),
      args: $this.data('args'),
      call_token: $this.data('token')
    };
    /**
     * todo: use internal hooks
     */

    jquery__WEBPACK_IMPORTED_MODULE_2___default()(document).trigger('ajax-action-field-data', [data]);
    (0,_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_1__.fetch_data)('ajax_action', data).then(function (data) {
      try {
        if (!data) {
          _js_UI__WEBPACK_IMPORTED_MODULE_0__["default"].panel_loader('error');
          return;
        }

        var event = $this.data('event');
        event && jquery__WEBPACK_IMPORTED_MODULE_2___default()(document).trigger(event, [data, $this]);

        if (data.status === 'succeed') {
          if (typeof data.msg !== 'undefined') {
            _js_UI__WEBPACK_IMPORTED_MODULE_0__["default"].panel_loader('succeed', data.msg);
          } else {
            _js_UI__WEBPACK_IMPORTED_MODULE_0__["default"].panel_loader('succeed');
          }
        } else {
          if (typeof data.msg !== 'undefined') {
            _js_UI__WEBPACK_IMPORTED_MODULE_0__["default"].panel_loader('error', data.msg);
          } else {
            _js_UI__WEBPACK_IMPORTED_MODULE_0__["default"].panel_loader('error');
          }
        }

        data.refresh && location.reload();
      } catch (error) {
        console.error(error);
      }
    })["catch"](function () {
      return _js_UI__WEBPACK_IMPORTED_MODULE_0__["default"].panel_loader('error');
    });
  });
  return {
    $context: $context
  };
}

/***/ }),

/***/ "./src/AjaxAction/script.js":
/*!**********************************!*\
  !*** ./src/AjaxAction/script.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/AjaxAction/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'ajax_action';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off('click', '.bf-ajax_action-field-container .bf-action-button');
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/AjaxSelect/old.js":
/*!*******************************!*\
  !*** ./src/AjaxSelect/old.js ***!
  \*******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var _js_AjaxRequest__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../js/AjaxRequest */ "./js/AjaxRequest.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _js_UI__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../js/UI */ "./js/UI.js");
/**
 * TODO: Refactor
 */




/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var $context = jquery__WEBPACK_IMPORTED_MODULE_1___default()(context);
  var _object_ = {};
  _object_.preloader = $context.find('.bf-search-loader');
  _object_.hidden_field = $context.find('input[type=hidden]');
  _object_.result_box = $context.find('.bf-ajax-suggest-search-results');
  _object_._this = this;
  _object_.$context = $context;
  var bf_ajax_input_timeOut = null;
  var bf_ajax_input_interval = 850;

  var saveValue = function saveValue(value) {
    _object_.hidden_field.val(value);

    _object_.hidden_field.change()[0].dispatchEvent(new Event('change', {
      bubbles: true
    })); // trigger onChange


    onChange(value);
  };

  var generateResultItems = function generateResultItems(data) {
    var result = '';
    jquery__WEBPACK_IMPORTED_MODULE_1___default().each(data, function (index, item) {
      var id, label;

      if (lodash__WEBPACK_IMPORTED_MODULE_2___default().isObject(item)) {
        id = item.id;
        label = item.label;
      } else {
        id = index;
        label = item;
      }

      result += '<li class="ui-state-default" data-id="' + id + '">' + label + ' <span class="bf-icon del-icon del"></span></li>';
    });
    return result;
  };

  var appendHTML = function appendHTML(html) {
    _object_.result_box.append(html);
  };

  $context.on('keyup.ajaxselect', '.bf-ajax-suggest-input', function (e) {
    var _this = jquery__WEBPACK_IMPORTED_MODULE_1___default()(e.target);

    _this.removeResults = function () {
      _object_.result_box.find('li').remove();
    };

    _this.get = function (key, callback) {
      var is_repeater = _this.parent().is('.bf-repeater-controls-option'),
          form_data = {
        field_ID: _object_.hidden_field.attr('name'),
        key: key,
        is_repeater: is_repeater ? 1 : 0,
        callback: _object_.hidden_field.data('callback'),
        token: _object_.hidden_field.data('token'),
        exclude: _object_.hidden_field.val()
      };

      if (is_repeater) {
        form_data.repeater_id = _this.closest('.bf-nonrepeater-section').data('id');
      }

      (0,_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_0__.fetch_data)('ajax_select', form_data).then(function (response) {
        return callback(response.data);
      })["catch"](function () {
        return callback(false);
      });
    };

    clearTimeout(bf_ajax_input_timeOut);
    bf_ajax_input_timeOut = setTimeout(function () {
      _object_.preloader.addClass('loader');

      _this.get(_this[0].value, function (data) {
        _object_.preloader.removeClass('loader');

        if (data === false) {
          alert('Something Wrong Happened!');
          return;
        }

        _object_.$context.find('.bf-ajax-suggest-controls').sortable({
          update: function update(event, ui) {
            var value = '';

            _object_.$context.find('.bf-ajax-suggest-controls').find('li:not(".ui-sortable-placeholder")').each(function () {
              value += jquery__WEBPACK_IMPORTED_MODULE_1___default()(this).data('id') + ',';
            });

            saveValue(value.replace(',,', ',').replace(/^,+/, '').replace(/,+$/, ''));
          }
        });

        if (data == -1) {
          return;
        }

        _this.removeResults(); // Remove Current Results


        appendHTML(generateResultItems(data)); // Append The HTMLs

        _object_.result_box.fadeIn();
      });
    }, bf_ajax_input_interval);
  });
  $context.on('blur.ajaxselect', '.bf-ajax-suggest-input', function () {
    _object_.result_box.fadeOut();
  });
  $context.on('focus.ajaxselect', '.bf-ajax-suggest-input', function () {
    if (_object_.result_box.find('li').size() > 0) _object_.result_box.fadeIn();
  });
  $context.on('click.ajaxselect', '.bf-ajax-suggest-search-results li', function (e) {
    var _this = jquery__WEBPACK_IMPORTED_MODULE_1___default()(e.target);

    _object_.result_box.fadeOut();

    if (_object_.$context.find('.bf-ajax-suggest-controls').find('li[data-id="' + _this.data('id') + '"]').exist()) return true;
    var value = _object_.hidden_field.val() === undefined ? [] : _object_.hidden_field.val().split(',');
    value.push(_this.data('id'));

    _object_.$context.find('.bf-ajax-suggest-controls').append(e.target.outerHTML);

    saveValue(jquery__WEBPACK_IMPORTED_MODULE_1___default().array_unique(value).join(',').replace(',,', ',').replace(/^,+/, '').replace(/,+$/, ''));
    jquery__WEBPACK_IMPORTED_MODULE_1___default()(this).remove();
    return false;
  });
  $context.on('click.ajaxselect', '.bf-ajax-suggest-controls li .del', function (e) {
    if (confirm('Are You Sure?')) {
      var _new,
          _this = jquery__WEBPACK_IMPORTED_MODULE_1___default()(e.target).parent(),
          _array,
          ID = _this.data('id');

      _this.remove();

      _array = _object_.hidden_field.val().split(',');
      _new = jquery__WEBPACK_IMPORTED_MODULE_1___default().grep(_array, function (value) {
        return value != ID;
      });
      saveValue(_new.join(',').replace(',,', ',').replace(/^,+/, '').replace(/,+$/, ''));
    }
  });
  (jquery__WEBPACK_IMPORTED_MODULE_1___default().fn.sortable) && $context.find('.bf-ajax-suggest-controls').sortable({
    update: function update(event, ui) {
      var value = '';

      _object_.$context.find('.bf-ajax-suggest-controls').find('li:not(".ui-sortable-placeholder")').each(function () {
        value += jquery__WEBPACK_IMPORTED_MODULE_1___default()(this).data('id') + ',';
      });

      saveValue(value.replace(',,', ',').replace(/^,+/, '').replace(/,+$/, ''));
    }
  }); // fetch callback values from server if needed

  var currentValue = _object_.hidden_field.val();

  var $display = $context.find('.bf-ajax-suggest-controls');

  if (!lodash__WEBPACK_IMPORTED_MODULE_2___default().isEmpty(currentValue) && $display.find('li:first').length === 0) {
    _js_UI__WEBPACK_IMPORTED_MODULE_3__["default"].block($context);

    var params = _object_.hidden_field.data();

    params.value = currentValue;
    (0,_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_0__.load_secure_props)("ajax_select", params, "ajax_select:".concat(currentValue)).then(function (res) {
      _js_UI__WEBPACK_IMPORTED_MODULE_3__["default"].unblock($context);
      (res === null || res === void 0 ? void 0 : res.values) && $display.append(generateResultItems(res.values));
    });
  }

  return _object_;
}

/***/ }),

/***/ "./src/AjaxSelect/script.js":
/*!**********************************!*\
  !*** ./src/AjaxSelect/script.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/AjaxSelect/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

 // TODO : Moved shit implementation from better-framework. it needs refactor with some test.



var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'ajax_select';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.impl.hidden_field.val(value);
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return this.impl.hidden_field.val();
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off('keyup.ajaxselect', '.bf-ajax-suggest-input');
      this.impl.$context.off('blur.ajaxselect', '.bf-ajax-suggest-input');
      this.impl.$context.off('focus.ajaxselect', '.bf-ajax-suggest-input');
      this.impl.$context.off('click.ajaxselect', '.bf-ajax-suggest-search-results li');
      this.impl.$context.off('click.ajaxselect', '.bf-ajax-suggest-controls li .del');
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/BackgroundImage/old.js":
/*!************************************!*\
  !*** ./src/BackgroundImage/old.js ***!
  \************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var $context = jquery__WEBPACK_IMPORTED_MODULE_0___default()(context);

  var valueGet = function valueGet() {
    return {
      type: jquery__WEBPACK_IMPORTED_MODULE_0___default()(".bf-background-image-uploader-select", $context).val(),
      img: jquery__WEBPACK_IMPORTED_MODULE_0___default()(".bf-background-image-input", $context).val()
    };
  };

  $context.on('click', '.bf-background-image-upload-btn', function () {
    var _this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this);

    var media_title = _this.attr('data-mediaTitle');

    var media_button = _this.attr('data-mediaButton'); // prepare uploader


    var custom_uploader;

    if (custom_uploader) {
      custom_uploader.open();
      return;
    }

    custom_uploader = wp.media.frames.file_frame = wp.media({
      title: media_title,
      button: {
        text: media_button
      },
      multiple: false,
      library: {
        type: 'image'
      }
    }); // when select pressed in uploader popup

    custom_uploader.on('select', function () {
      var attachment = custom_uploader.state().get('selection').first().toJSON();

      _this.siblings('.bf-background-image-preview').find("img").attr("src", attachment.url);

      var $input = _this.siblings('.bf-background-image-input');

      $input.val(attachment.url).change();
      $input[0].dispatchEvent(new Event('change', {
        bubbles: true
      }));

      _this.siblings('.bf-background-image-preview').show(100);

      _this.siblings('.bf-background-image-uploader-select-container').removeClass('hidden').show(100);

      _this.siblings('.bf-background-image-remove-btn').show(100);

      var value = valueGet();
      value.img = attachment.url;
      onChange(value);
    }); // open media popup

    custom_uploader.open();
    return false;
  });
  $context.on('click', '.bf-background-image-remove-btn', function () {
    var _this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this),
        $input = _this.siblings('.bf-background-image-input');

    $input.val('').change();
    $input[0].dispatchEvent(new Event('change', {
      bubbles: true
    })); // hide remove button, select and preview

    _this.hide(100);

    _this.siblings('.bf-background-image-uploader-select-container').addClass('hidden').hide(100);

    _this.siblings('.bf-background-image-preview').hide(100);

    var value = valueGet();
    value.img = '';
    onChange(value);
  });
  $context.on('change', '.bf-background-image-uploader-select', function () {
    var value = valueGet();
    value['type'] = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).val();
    onChange(value);
  });
  return {
    $context: $context,
    $img: $context.find('.bf-background-image-input'),
    $type: $context.find('.bf-background-image-uploader-select')
  };
}

/***/ }),

/***/ "./src/BackgroundImage/script.js":
/*!***************************************!*\
  !*** ./src/BackgroundImage/script.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./old */ "./src/BackgroundImage/old.js");









function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_5__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'background_image';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_9__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.impl.$type.val((0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(value) === "object" ? value['type'] : "");
      this.impl.$img.val((0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(value) === "object" ? value['img'] : "");
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return {
        type: this.impl.$type.val(),
        img: this.impl.$img.val()
      };
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off('click', '.bf-background-image-upload-btn');
      this.impl.$context.off('click', '.bf-background-image-remove-btn');
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'object';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_8__.ControlBase);



/***/ }),

/***/ "./src/Checkbox/script.js":
/*!********************************!*\
  !*** ./src/Checkbox/script.js ***!
  \********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }



var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default(props) {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    _this = _super.call(this, props);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    _this.refreshValues = _this.refreshValues.bind((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this));
    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'checkbox';
    }
  }, {
    key: "init",
    value: function init(element) {
      this.context = element;
      this.bindEvents();
      return true;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      this.context.addEventListener('change', this.refreshValues);
    }
  }, {
    key: "checkboxes",
    value: function checkboxes() {
      return this.context.querySelectorAll('[type="checkbox"]');
    }
  }, {
    key: "refreshValues",
    value: function refreshValues() {
      var values = {};
      this.checkboxes().forEach(function (checkbox) {
        if (!checkbox.checked) {
          return;
        }

        var key = checkbox.dataset.key;

        if (key) {
          values[key] = checkbox.value;
        }
      });
      this.onChange(values);
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return [];
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var _this$context;

      (_this$context = this.context) === null || _this$context === void 0 ? void 0 : _this$context.removeEventListener('change', this.refreshValues);
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'object';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Code/script.js":
/*!****************************!*\
  !*** ./src/Code/script.js ***!
  \****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }



var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default(props) {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    _this = _super.call(this, props);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    _this.onTextareaChanged = _this.onTextareaChanged.bind((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this));
    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'code';
    }
  }, {
    key: "init",
    value: function init(element) {
      this.context = element;
      this.bindEvents();
      return true;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var textarea = this.textarea();
      textarea && textarea.addEventListener("change", this.onTextareaChanged);
    }
  }, {
    key: "onTextareaChanged",
    value: function onTextareaChanged(event) {
      this.onChange(event.target.value);
    }
  }, {
    key: "textarea",
    value: function textarea() {
      return this.context.querySelector('.bf-code-editor');
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      var textarea = this.textarea();

      if (!textarea) {
        return false;
      }

      textarea.value = value;
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      var textarea = this.textarea();
      return textarea && textarea.value;
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var textarea = this.textarea();
      textarea && textarea.removeEventListener("change", this.onTextareaChanged);
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Color/old.js":
/*!**************************!*\
  !*** ./src/Color/old.js ***!
  \**************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var clickable = ".wp-picker-container .wp-color-result";
  var $context = jquery__WEBPACK_IMPORTED_MODULE_0___default()(context);

  var fixWidget = lodash__WEBPACK_IMPORTED_MODULE_1___default().once(function () {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).closest('.widget-inside').find('.widget-control-save').prop('disabled', false);
  });

  $context.on('click.color', clickable, function () {
    var $parent = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).parent(),
        $wrapper = $parent.closest('.bs-color-picker-wrapper');
    $wrapper.find('.bs-color-picker-value').wpColorPicker({
      change: function change(event) {
        /// FIX: color save issue on widgets page
        fixWidget.call(this);
        onChange(event.target.value);
      }
    }); // init the color picker

    $parent.remove(); // remove placeholder

    setTimeout(function () {
      $wrapper.find(clickable).click(); // open color picker
    });
  });
  return {
    $context: $context
  };
}

/***/ }),

/***/ "./src/Color/script.js":
/*!*****************************!*\
  !*** ./src/Color/script.js ***!
  \*****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/Color/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'color';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      return false;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return this.impl.$context.find('.bs-color-picker-value').val();
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off('click.color');
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Custom/script.js":
/*!******************************!*\
  !*** ./src/Custom/script.js ***!
  \******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _js_AjaxRequest__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../js/AjaxRequest */ "./js/AjaxRequest.js");
/* harmony import */ var _js_UI__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../../js/UI */ "./js/UI.js");
/* harmony import */ var _js_Hooks__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../js/Hooks */ "./js/Hooks.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_11__);








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }







var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'custom';
    }
  }, {
    key: "init",
    value: function init(context) {
      this.context = context;
      this.deferRender();
      return true;
    }
  }, {
    key: "deferRender",
    value: function deferRender() {
      var _this2 = this;

      var deferElement = this.deferElement();

      if (!deferElement) {
        return false;
      }

      _js_UI__WEBPACK_IMPORTED_MODULE_9__["default"].block(this.context);
      (0,_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_8__.fetch_data)("custom", lodash__WEBPACK_IMPORTED_MODULE_11___default().extend(deferElement.dataset, {
        _value: this.valueGet()
      })).then(function (response) {
        try {
          _js_UI__WEBPACK_IMPORTED_MODULE_9__["default"].unblock(_this2.context);

          if (response) {
            deferElement.innerHTML = (response === null || response === void 0 ? void 0 : response.raw) || "";
            _js_Hooks__WEBPACK_IMPORTED_MODULE_10__["default"].do_action('custom/deferred/loaded', _this2.deferElement());
          }
        } catch (error) {
          console.error(error);
        }
      })["catch"](function () {
        _js_UI__WEBPACK_IMPORTED_MODULE_9__["default"].unblock(_this2.context);
        console.error("Error fetch custom html", deferElement);
      });
      return true;
    }
  }, {
    key: "deferElement",
    value: function deferElement() {
      if (!this.context) {
        return null;
      }

      return this.context.querySelector(".bf-custom-field-view");
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      var input = this.valueInput();

      if (!input) {
        return false;
      }

      input.value = value;
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      var input = this.valueInput();
      return input && input.value;
    }
  }, {
    key: "valueInput",
    value: function valueInput() {
      return this.context.querySelector("input.bf-custom-field-values");
    }
  }, {
    key: "destroy",
    value: function destroy() {}
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Editor/old.js":
/*!***************************!*\
  !*** ./src/Editor/old.js ***!
  \***************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  jquery__WEBPACK_IMPORTED_MODULE_1___default()('.bf-editor-wrapper', context).each(function () {
    var $wrapper = jquery__WEBPACK_IMPORTED_MODULE_1___default()(this),
        $editor = $wrapper.find('.bf-editor'),
        $textarea = $wrapper.find('.bf-editor-field'),
        have_ace = (typeof ace === "undefined" ? "undefined" : (0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(ace)) === "object";

    if (have_ace) {
      $textarea.hide();
      $editor.css('min-height', '100px');
      var lang = $editor.data('lang'),
          max_lines = $editor.data('max-lines'),
          min_lines = $editor.data('min-lines'),
          theme = $editor.data('theme'),
          editor = ace.edit($editor[0]),
          session = editor.getSession();
      editor.setOptions({
        maxLines: max_lines,
        minLines: min_lines,
        mode: "ace/mode/" + lang
      });
      if (theme) editor.setTheme("ace/theme/" + theme);
      session.setUseWorker(false);
      editor.getSession().setValue($textarea.val());
      session.on('change', function (e, EditSession) {
        var value = editor.getSession().getValue();
        $textarea.val(value).trigger('bf-changed');
        $textarea[0].dispatchEvent(new Event('change', {
          bubbles: true
        }));
        onChange(value);
      });
    } else {
      $editor.remove();
      $textarea.show();
    }
  });
}

/***/ }),

/***/ "./src/Editor/script.js":
/*!******************************!*\
  !*** ./src/Editor/script.js ***!
  \******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/Editor/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'editor';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.context = element;
      (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      var textarea = this.context.querySelector('textarea');

      if (!textarea) {
        return false;
      }

      textarea.value = value;
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      var textarea = this.context.querySelector('textarea');
      return textarea && textarea.value;
    }
  }, {
    key: "destroy",
    value: function destroy() {}
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Export/script.js":
/*!******************************!*\
  !*** ./src/Export/script.js ***!
  \******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _js_Redirect__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../js/Redirect */ "./js/Redirect.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default(props) {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    _this = _super.call(this, props);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    _this.onClick = _this.onClick.bind((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this));
    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'export';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this$context$querySe;

      this.context = element;
      (_this$context$querySe = this.context.querySelector("button")) === null || _this$context$querySe === void 0 ? void 0 : _this$context$querySe.addEventListener("click", this.onClick);
      return true;
    }
  }, {
    key: "onClick",
    value: function onClick(event) {
      var _event$target;

      var panel_id = (_event$target = event.target) === null || _event$target === void 0 ? void 0 : _event$target.dataset.panelId;
      (0,_js_Redirect__WEBPACK_IMPORTED_MODULE_8__.redirectToControl)(this.controlType(), {
        panel_id: panel_id
      }, "POST");
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var _this$context$querySe2;

      (_this$context$querySe2 = this.context.querySelector("button")) === null || _this$context$querySe2 === void 0 ? void 0 : _this$context$querySe2.removeEventListener("click", this.onClick);
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Features/script.js":
/*!********************************!*\
  !*** ./src/Features/script.js ***!
  \********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ProFeature": function() { return /* binding */ ProFeature; },
/* harmony export */   "ProFeatureModal": function() { return /* binding */ ProFeatureModal; },
/* harmony export */   "option": function() { return /* binding */ option; },
/* harmony export */   "options": function() { return /* binding */ options; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_autop__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/autop */ "@wordpress/autop");
/* harmony import */ var _wordpress_autop__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_autop__WEBPACK_IMPORTED_MODULE_2__);




function getConfig(id) {
  var element = document.getElementById(id);

  if (!element) {
    return;
  }

  if ("application/json" === element.type) {
    return JSON.parse(element.innerHTML);
  }

  return element.innerHTML;
}

function randomInRange(min, max) {
  return Math.random() * (max - min) + min;
}

function elementCenterX(element) {
  var clientX = element.getBoundingClientRect().left;
  return clientX + element.clientWidth / 2;
}

function getModalID(element) {
  var _element$dataset;

  return (_element$dataset = element.dataset) === null || _element$dataset === void 0 ? void 0 : _element$dataset.modalId;
}

function extractYoutubeVideoID(video_url) {
  if (lodash__WEBPACK_IMPORTED_MODULE_1___default().isEmpty(video_url)) {
    return;
  }

  var match = video_url.toString().match(/\:\/\/w*\.?youtube\.com\/*.*?v=([^&]+)/);
  return match && match[1];
}

function templateParams(element) {
  var _element$dataset2;

  var defaultConfig = getConfig("cnf-bs-pro-feature-".concat(getModalID(element))) || {};
  var templateObject = lodash__WEBPACK_IMPORTED_MODULE_1___default().extend(defaultConfig.template || {}, // modal config
  JSON.parse(((_element$dataset2 = element.dataset) === null || _element$dataset2 === void 0 ? void 0 : _element$dataset2.template) || '{}') // override items
  );
  templateObject.video_id = extractYoutubeVideoID(templateObject.video_url);
  return lodash__WEBPACK_IMPORTED_MODULE_1___default().mapValues(templateObject, function (string, key) {
    var result = string ? Mustache.render(string, element.dataset || {}) : '';

    if (key.match(/desc/i)) {
      return (0,_wordpress_autop__WEBPACK_IMPORTED_MODULE_2__.autop)(result);
    }

    return result;
  });
}

function options(element, _default) {
  var _element$dataset3;

  if (!(element !== null && element !== void 0 && (_element$dataset3 = element.dataset) !== null && _element$dataset3 !== void 0 && _element$dataset3.options)) {
    return {};
  }

  try {
    return JSON.parse(element.dataset.options);
  } catch (e) {
    return {};
  }
}
function option(element, option_key, _default) {
  var allOptions = options(element);
  return allOptions[option_key] || _default;
}
function ProFeature(context) {
  jquery__WEBPACK_IMPORTED_MODULE_0___default()(context).on('click', ".bs-pro-feature", function (event) {
    event.stopPropagation();
    var element = event.target.classList.contains('bs-pro-feature') ? event.target : event.target.closest('.bs-pro-feature');
    ProFeatureModal(element);
  });
}
var _init = false;
function ProFeatureModal(element) {
  if (!_init) {
    jquery__WEBPACK_IMPORTED_MODULE_0___default().bs_modal_template('bs-pro-feature', getConfig('tmpl-bs-pro-feature'));
    jquery__WEBPACK_IMPORTED_MODULE_0___default().bs_modal_template('bs-pro-feature-after', getConfig('tmpl-bs-pro-feature-after'));
    _init = true;
  }

  var onModalInserted = function onModalInserted(modal) {
    // init
    var $modalWrapper = modal.$modal.parent();
    var canvas = document.createElement('canvas'); //

    canvas.classList.add('interaction');
    $modalWrapper[0] && $modalWrapper[0].appendChild(canvas);

    var modalIconUnlock = function modalIconUnlock() {
      $modalWrapper.find('.lock').hide();
      $modalWrapper.find('.unlock').show();
    };

    var modalIconLock = function modalIconLock() {
      $modalWrapper.find('.lock').show();
      $modalWrapper.find('.unlock').hide();
    };

    var onButtonHovered = function onButtonHovered(event) {
      var origin = {
        x: elementCenterX(event.target) / window.innerWidth
      };
      var purchaseConfetti = confetti.create(canvas, {
        resize: true,
        useWorker: true
      });
      purchaseConfetti({
        angle: 90,
        spread: 30,
        particleCount: randomInRange(80, 100),
        origin: origin
      });
      setTimeout(function () {
        purchaseConfetti({
          angle: 120,
          spread: 25,
          particleCount: randomInRange(80, 100),
          origin: origin
        });
      }, randomInRange(75, 110));
      setTimeout(function () {
        purchaseConfetti({
          angle: 61,
          spread: 25,
          particleCount: randomInRange(80, 100),
          origin: origin
        });
      }, randomInRange(75, 110));
      modalIconUnlock();
    };

    var onButtonMouseOut = function onButtonMouseOut() {
      modalIconLock();
    };

    var onButtonClicked = function onButtonClicked() {
      modal.change_skin({
        template: 'bs-pro-feature-after'
      });
    };

    modal.$modal.on('mouseover', '.button-primary', onButtonHovered);
    modal.$modal.on('click', '.button-primary', onButtonClicked);
    modal.$modal.on('mouseout', '.button-primary', onButtonMouseOut);
  };

  jquery__WEBPACK_IMPORTED_MODULE_0___default().bs_modal({
    close_button: true,
    modalId: "bs-pro-feature-modal",
    modalClass: "bf-fields-style",
    styles: {
      container: 'max-width:555px'
    },
    template: 'bs-pro-feature',
    content: templateParams(element),
    events: {
      after_append_html: onModalInserted
    }
  });
}

/***/ }),

/***/ "./src/IconSelect/old.js":
/*!*******************************!*\
  !*** ./src/IconSelect/old.js ***!
  \*******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var _js_AjaxRequest__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../js/AjaxRequest */ "./js/AjaxRequest.js");
/* harmony import */ var _js_UI__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../js/UI */ "./js/UI.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);



 // res : http://stackoverflow.com/questions/1766299/make-search-input-to-filter-through-list-jquery
// custom css expression for a case-insensitive contains()

(jquery__WEBPACK_IMPORTED_MODULE_2___default().expr[":"].Contains) = function (a, i, m) {
  return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
}; // let globalSetup = false;


/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var $document = jquery__WEBPACK_IMPORTED_MODULE_2___default()(document),
      $context = jquery__WEBPACK_IMPORTED_MODULE_2___default()(context);
  var Better_Framework_Modals = {
    init: function init() {
      $context.on('click.init-bf-modal', '.bf-icon-modal-handler', this.loadTemplate.bind(this));
    },
    loadTemplate: function loadTemplate(e) {
      e.stopPropagation();
      var self = this;

      var init = function init() {
        $context.off('click.init-bf-modal');
        self.globalSetup();
        setTimeout(function () {
          jquery__WEBPACK_IMPORTED_MODULE_2___default()(e.currentTarget).click();
        });
      };

      if (!this.templateExists()) {
        _js_UI__WEBPACK_IMPORTED_MODULE_1__["default"].panel_loader('loading');
        (0,_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_0__.fetch_data)("icon_select", {
          action: "template"
        }).then(function (res) {
          try {
            if (res.template) {
              _js_UI__WEBPACK_IMPORTED_MODULE_1__["default"].panel_loader('hide');
              $document.find('body').append(res.template);
              init();
            } else {
              _js_UI__WEBPACK_IMPORTED_MODULE_1__["default"].panel_loader('error', res.error || "Error loading icons");
            }
          } catch (error) {
            console.error(error);
          }
        })["catch"](function () {
          _js_UI__WEBPACK_IMPORTED_MODULE_1__["default"].panel_loader('error');
        });
      } else {
        init();
      }
    },
    templateExists: function templateExists() {
      return !!document.getElementById('better-icon-modal');
    },
    globalSetup: function globalSetup() {
      // if (globalSetup) {
      //
      //     return;
      // }
      var Better_Icon_Modal = jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal').on('opening', function (a, b, c, d) {
        var $this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this),
            zIndex = 1.5e5;
        $this.closest('.remodal-wrapper').css('z-index', zIndex + 1);
        jquery__WEBPACK_IMPORTED_MODULE_2___default()(".remodal-overlay").css('z-index', zIndex);
      }).remodal({
        hashTracking: false,
        closeOnEscape: true
      });
      $context.on('click', '.bf-icon-modal-handler', function (e) {
        e.preventDefault();

        if (!Better_Icon_Modal) {
          console.error('icons modal not found!');
          return;
        }

        var $field_container = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this),
            $input = $field_container.find('input.icon-input'),
            $modal = jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal'),
            $search_input = $modal.find('input.better-icons-search-input'); // Summarize data

        var selected = $input.val();

        if (typeof selected === "string") {
          try {
            selected = JSON.parse(selected);
          } catch (e) {
            selected = {};
          }
        }

        $modal.find('.icons-list .icon-select-option[data-value="' + selected.icon + '"]').addClass('selected');
        Better_Icon_Modal.$handler = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this);
        icons_modal_reset_all_filters();
        Better_Icon_Modal.open();
        $search_input.focus();
      });
      $document.on('closing', '.better-modal.icon-modal', function (e) {
        // No icon selected
        if (jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .icons-list .icon-select-option.selected').length == 0) {
          return;
        }

        var $field_container = Better_Icon_Modal.$handler,
            $selected_container = $field_container.find('.selected-option'),
            $input = $field_container.find('input.icon-input'),
            $selected_icon = jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .icons-list .icon-select-option.selected'); // Summarize data

        var selected = {
          icon: $selected_icon.data('value'),
          label: $selected_icon.data('label'),
          code: $selected_icon.data('font-code'),
          width: '',
          height: '',
          type: ''
        };

        if ($selected_icon.hasClass('custom-icon')) {
          selected.label = 'custom ';
          selected.width = jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .icon-fields input[name="icon-width"]').val();
          selected.height = jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .custom-icon-fields .icon-fields input[name="icon-height"]').val();
          selected.type = $selected_icon.data('type');
          selected.icon = $selected_icon.data('custom-icon');
        } else {
          selected.type = $selected_icon.data('type');
        } // Update view data


        if ($selected_icon.find(".bf-icon-svg").length > 0) {
          // is icon SVG?
          selected.icon_tag = $selected_icon.html();
          $selected_container.html(selected.icon_tag);
        } else if (selected.icon !== '') {
          if (selected.type === 'custom-icon') {
            $selected_container.html('<i class="bf-icon bf-custom-icon "><img src="' + selected.icon + '"></i> ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Custom icon', 'better-studio'));
          } else {
            $selected_container.html('<i class="bf-icon fa ' + selected.icon + '"></i>' + selected.label);
          }
        } else {
          $selected_container.html(selected.label);
        } // Update field data


        $input.attr('label', selected.label);
        var input;
        input = $input.val(JSON.stringify(selected)).change()[0];
        input && input.dispatchEvent(new Event('change', {
          bubbles: true
        }));
        onChange(selected);
        custom_icon_hide();
        jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).find('.icon-select-option.selected').removeClass('selected');
      });
      $document.on('click', '.better-modal.icon-modal .icons-list .icon-select-option', function () {
        jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal  .icons-list').find('.icon-select-option.selected').removeClass('selected');

        if (jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).hasClass('custom-icon')) {
          var $this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this),
              $modal = jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal');
          $modal.find('.custom-icon-fields .icon-preview').attr('src', $this.data('custom-icon')).css({
            'max-width': $this.data('width') + 'px',
            'max-height': 'auto'
          });
          $modal.find('.icon-fields input[name="icon-width"]').val($this.data('width'));
          $modal.find('.icon-fields input[name="icon-height"]').val('');
          jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).toggleClass('selected');
          custom_icon_show();
        } else {
          jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).toggleClass('selected');
          Better_Icon_Modal.close();
        }
      });
      $document.on('click', '.better-modal.icon-modal .icons-list .icon-select-option .delete-icon', function (e) {
        e.stopPropagation();
        (0,_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_0__.fetch_data)("icon_select", {
          action: "remove",
          icon_id: jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).closest('.icon-select-option').data('id')
        });
        jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).closest('.icon-select-option').remove();

        if (jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .icons-list.custom-icons-list .icon-select-option').length == 0) {
          jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .no-custom-icon').removeClass('hidden');
        } else {
          jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .no-custom-icon').addClass('hidden');
        }
      });
      $document.on('click', '.better-modal.icon-modal .upload-custom-icon-container .section-footer .button', function () {
        Better_Icon_Modal.close();
      });
      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .icons-container').mCustomScrollbar({
        theme: 'dark',
        live: true,
        scrollInertia: 2000
      }); // Category Filter

      $document.on('click', '.better-icons-category-list .icon-category', function () {
        var $this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this),
            $modal = jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal'),
            $options_list = $modal.find('.icons-list.font-icons'),
            $search_input = $modal.find('input.better-icons-search-input');

        if ($this.hasClass('selected')) {
          return;
        } // clear search input


        $search_input.val('').parent().removeClass('show-clean').find('.clean').addClass('fa-search').removeClass('fa-times-circle');
        $modal.find('.better-icons-category-list li.selected').removeClass('selected');
        $this.addClass('selected');

        if ($this.attr('id') === 'cat-all') {
          $options_list.find('li').show();
        } else {
          $options_list.find('li').each(function () {
            if (jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).hasClass('default-option')) return true;

            var _cats = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).data('categories').split(' ');

            if (_cats.indexOf($this.attr('id')) < 0) {
              jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).hide();
            } else {
              jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).show();
            }
          });
        }

        return false;
      }); // Search

      $document.on('keyup', '#better-icon-modal .better-icons-search-input', function () {
        if (jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).val() != '') {
          jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).parent().addClass('show-clean').find('.clean').removeClass('fa-search').addClass('fa-times-circle');
        } else {
          jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).parent().removeClass('show-clean').find('.clean').addClass('fa-search').removeClass('fa-times-circle');
        }

        icons_modal_reset_cats_filter();
        icons_modal_text_filter(jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).val());
        return false;
      });
      $document.on('click', '#better-icon-modal .better-icons-search .clean', function () {
        var $modal = jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal'),
            $search_input = $modal.find('input.better-icons-search-input');
        icons_modal_text_filter('');
        $search_input.val('').parent().removeClass('show-clean').find('.clean').addClass('fa-search').removeClass('fa-times-circle');
      });
      $document.on('click', '.better-modal.icon-modal .upload-custom-icon', function () {
        var _this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this);

        var custom_uploader;

        var media_title = _this.data('media-title');

        var media_button_text = _this.data('button-text');

        if (custom_uploader) {
          custom_uploader.open();
          return;
        }

        custom_uploader = wp.media.frames.file_frame = wp.media({
          title: media_title,
          button: {
            text: media_button_text
          },
          multiple: false //,
          //library: { type : 'image'}

        });
        custom_uploader.on('select', function () {
          var attachment = custom_uploader.state().get('selection').first().toJSON();
          var icon = {
            'type': 'custom-icon',
            'icon': attachment.url,
            'width': attachment.width,
            'height': attachment.height
          };
          custom_icon_show_loading();
          var $modal = jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal');
          $modal.find('.better-icons-category-list li.selected').removeClass('selected');
          $modal.find('.custom-icon-fields .icon-preview').attr('src', icon.icon).css({
            'max-width': icon.width + 'px',
            'max-height': 'auto'
          });
          $modal.find('.icon-fields input[name="icon-width"]').val(icon.width);
          $modal.find('.icon-fields input[name="icon-height"]').val('');
          $modal.find('.icons-list.custom-icons-list').append('<li data-id="icon-" class="icon-select-option custom-icon selected" data-custom-icon="' + icon.icon + '" data-width="' + icon.width + '" data-height="' + icon.height + '" data-type="custom-icon"> \
                <i class="bf-custom-icon"><img src="' + icon.icon + '"></i><span class="bf-icon delete-icon"></span></li>');
          custom_icon_show();
          (0,_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_0__.fetch_data)("icon_select", {
            action: "add",
            icon: icon
          }).then(function () {});
        });
        custom_uploader.open();

        if (jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .icons-list.custom-icons-list .icon-select-option').length == 0) {
          jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .no-custom-icon').removeClass('hidden');
        } else {
          jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .no-custom-icon').addClass('hidden');
        }

        return false;
      });
      $document.on('click', '.better-modal.icon-modal .upload-custom-icon-container .close-custom-icon', function () {
        custom_icon_hide();
      });
      $document.on('keyup', '.better-modal.icon-modal .upload-custom-icon-container .custom-icon-fields .icon-fields input[name="icon-width"]', function () {
        jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .custom-icon-fields .icon-preview').css({
          'max-width': jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).val() + 'px'
        });
      });
      $document.on('keyup', '.better-modal.icon-modal .upload-custom-icon-container .custom-icon-fields .icon-fields input[name="icon-height"]', function () {
        jquery__WEBPACK_IMPORTED_MODULE_2___default()('.better-modal.icon-modal .custom-icon-fields .icon-preview').css({
          'max-height': jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).val() + 'px'
        });
      });

      function custom_icon_show_loading() {
        jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal .upload-custom-icon-container').addClass('show show-loading');
      }

      function custom_icon_hide_loading() {
        jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal .upload-custom-icon-container').addClass('show').removeClass('show-loading');
      }

      function custom_icon_hide() {
        jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal .upload-custom-icon-container').removeClass('show show-loading');
        jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal .better-icons-search').removeClass('hidden');
      }

      function custom_icon_show() {
        jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal .upload-custom-icon-container').addClass('show').removeClass('show-loading');
        jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal .better-icons-search').addClass('hidden');
      } // Used for clearing all filters


      function icons_modal_reset_all_filters() {
        var $modal = jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal'),
            $search_input = $modal.find('input.better-icons-search-input');
        $search_input.val('').parent().removeClass('show-clean').find('.clean').addClass('fa-search').removeClass('fa-times-circle');
        icons_modal_text_filter('');
        icons_modal_reset_cats_filter();
      } // Used for clearing just category filter


      function icons_modal_reset_cats_filter() {
        var $modal = jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal'),
            $options_list = $modal.find('.icons-list');
        $options_list.find('.icon-select-option').show();
        $modal.find('.better-icons-category-list li').removeClass('selected');
        $modal.find('.better-icons-category-list li#cat-all').addClass('selected');
      } // filters element with one text


      function icons_modal_text_filter($search_text) {
        var $modal = jquery__WEBPACK_IMPORTED_MODULE_2___default()('#better-icon-modal'),
            $options_list = $modal.find('.icons-list');

        if ($search_text) {
          $options_list.find(".label:not(:Contains(" + $search_text + "))").parent().hide();
          $options_list.find(".label:Contains(" + $search_text + ")").parent().show();
        } else {
          $options_list.find("li").show();
        }
      } // globalSetup = true;

    }
  };
  Better_Framework_Modals.init();
  return {
    $context: $context
  };
}

/***/ }),

/***/ "./src/IconSelect/script.js":
/*!**********************************!*\
  !*** ./src/IconSelect/script.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/IconSelect/old.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_9__);








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }





var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'icon_select';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      return false;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return {};
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off('click.init-bf-modal', '.bf-icon-modal-handler');
      var $document = jquery__WEBPACK_IMPORTED_MODULE_9___default()(document);
      $document.off('closing', '.better-modal.icon-modal');
      $document.off('click', '.better-modal.icon-modal .icons-list .icon-select-option');
      $document.off('click', '.better-modal.icon-modal .icons-list .icon-select-option');
      $document.off('click', '.better-modal.icon-modal .icons-list .icon-select-option .delete-icon');
      $document.off('click', '.better-modal.icon-modal .upload-custom-icon-container .section-footer .button');
      $document.off('click', '.better-icons-category-list .icon-category');
      $document.off('keyup', '#better-icon-modal .better-icons-search-input');
      $document.off('click', '#better-icon-modal .better-icons-search .clean');
      $document.off('click', '.better-modal.icon-modal .upload-custom-icon');
      $document.off('click', '.better-modal.icon-modal .upload-custom-icon-container .close-custom-icon');
      $document.off('keyup', '.better-modal.icon-modal .upload-custom-icon-container .custom-icon-fields .icon-fields input[name="icon-width"]');
      $document.off('keyup', '.better-modal.icon-modal .upload-custom-icon-container .custom-icon-fields .icon-fields input[name="icon-height"]');
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'object';
    }
  }, {
    key: "dynamicValuesIndexes",
    value: function dynamicValuesIndexes() {
      return ["icon_tag"];
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/ImageRadio/script.js":
/*!**********************************!*\
  !*** ./src/ImageRadio/script.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_8__);








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "$context", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'image_radio';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.$context = jquery__WEBPACK_IMPORTED_MODULE_8___default()(element);
      jquery__WEBPACK_IMPORTED_MODULE_8___default()(".bf-image-radio-option", this.$context).on('click.image_radio', function (event) {
        // Prevent Browser Default Behavior
        event.preventDefault();
        var $this = jquery__WEBPACK_IMPORTED_MODULE_8___default()(event.currentTarget),
            value = $this.data('id');

        _this2.$context.find('input[type="hidden"]').val(value);

        $this.addClass('checked').siblings('.bf-image-radio-option').removeClass('checked');

        _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.$context.find('input[type="hidden"]').val(value);
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return this.$context.find('input[type="hidden"]').val();
    }
  }, {
    key: "destroy",
    value: function destroy() {
      jquery__WEBPACK_IMPORTED_MODULE_8___default()(".bf-image-radio-option", this.$context).off("click.image_radio");
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/ImageSelect/old.js":
/*!********************************!*\
  !*** ./src/ImageSelect/old.js ***!
  \********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  // Open Close Select Options Box
  var $context = jquery__WEBPACK_IMPORTED_MODULE_0___default()(context);
  $context.on('click.image-select', '.better-select-image', function (e) {
    var $_target = jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target);

    if ($_target.hasClass('selected-option') || $_target.hasClass('select-options')) {
      // close All Other open boxes
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).toggleClass('bf-opened');
      return;
    }
  }); // Close Everywhere clicked

  $context.on('click.image_select', function (e) {
    if (e.target["class"] !== 'better-select-image' && jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).parents('.better-select-image').length === 0) {
      jquery__WEBPACK_IMPORTED_MODULE_0___default()('.better-select-image').each(function () {
        if (jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).hasClass('bf-opened')) {
          jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).removeClass('bf-opened');
        }
      });
    }
  }); // Select when clicked

  $context.on('click.image_select', '.better-select-image .image-select-option', function (e) {
    var $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this);
    var $parent = $this.closest('.better-select-image');
    var $input = $parent.find('input[type=hidden]');
    var $selected_label = $parent.find('.selected-option');

    if ($this.hasClass('selected')) {
      e.preventDefault();
      $parent.find('.select-options').toggleClass('bf-opened');
    } else {
      $input.attr('value', $this.data('value')).trigger('change');
      $parent.find('.image-select-option.selected').removeClass('selected');
      $this.addClass('selected');
      $selected_label.html($this.data('label'));
      $parent.toggleClass('bf-opened');
      $input[0].dispatchEvent(new Event('change', {
        bubbles: true
      }));
      onChange($this.data('value'));
    }
  });
  return {
    $context: $context
  };
}

/***/ }),

/***/ "./src/ImageSelect/script.js":
/*!***********************************!*\
  !*** ./src/ImageSelect/script.js ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/ImageSelect/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'image_select';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.impl.$context.find('input[type=hidden]').val(value);
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return this.impl.$context.find('input[type=hidden]').val();
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off('click.image-select', '.better-select-image .image-select-option');
      this.impl.$context.off('click.image-select', '.better-select-image');
      this.impl.$context.off('click.image-select');
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Import/script.js":
/*!******************************!*\
  !*** ./src/Import/script.js ***!
  \******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _js_UI__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../js/UI */ "./js/UI.js");
/* harmony import */ var _js_AjaxRequest__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../../js/AjaxRequest */ "./js/AjaxRequest.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }







var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default(props) {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    _this = _super.call(this, props);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "data", void 0);

    _this.onClick = _this.onClick.bind((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this));
    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'import';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this$context$querySe,
          _this2 = this;

      this.context = element;
      (_this$context$querySe = this.context.querySelector(".import-upload-btn")) === null || _this$context$querySe === void 0 ? void 0 : _this$context$querySe.addEventListener("click", this.onClick);
      jquery__WEBPACK_IMPORTED_MODULE_9___default()('.import-file-input').fileupload({
        limitMultiFileUploads: 1,
        url: _js_AjaxRequest__WEBPACK_IMPORTED_MODULE_11__.RequestOptions.url(_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_11__.config.endPoint),
        autoUpload: false,
        replaceFileInput: false,
        formData: this.formData(),
        add: function add(event, data) {
          _this2.data = data;
        },
        start: function start() {
          _js_UI__WEBPACK_IMPORTED_MODULE_10__["default"].panel_loader('loading');
        },
        done: function done(e, data) {
          var result = data.result || {};

          if (result.success) {
            _js_UI__WEBPACK_IMPORTED_MODULE_10__["default"].panel_loader('succeed', result.message || "");
          } else {
            _js_UI__WEBPACK_IMPORTED_MODULE_10__["default"].panel_loader('error', result.message || "");
          }

          if (result.refresh) {
            _this2.reload_location(1500);
          }
        },
        error: function error() {
          _js_UI__WEBPACK_IMPORTED_MODULE_10__["default"].panel_loader('error');
        },
        drop: function drop() {
          return false;
        }
      });
      return true;
    }
  }, {
    key: "reload_location",
    value: function reload_location(delay) {
      _js_UI__WEBPACK_IMPORTED_MODULE_10__["default"].panel_loader('hide');
      setTimeout(function () {
        window.location.reload();
      }, delay || 0);
    }
  }, {
    key: "formData",
    value: function formData() {
      return lodash__WEBPACK_IMPORTED_MODULE_8___default().extend(_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_11__.RequestOptions.params(), {
        type: this.controlType(),
        params: this.elementParams()
      });
    }
  }, {
    key: "elementParams",
    value: function elementParams() {
      var element = this.context.querySelector("input[type=file]");
      return JSON.stringify(lodash__WEBPACK_IMPORTED_MODULE_8___default().extend({}, element === null || element === void 0 ? void 0 : element.dataset));
    }
  }, {
    key: "onClick",
    value: function onClick() {
      this.data && this.data.submit();
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var _this$context$querySe2;

      (_this$context$querySe2 = this.context.querySelector("button")) === null || _this$context$querySe2 === void 0 ? void 0 : _this$context$querySe2.removeEventListener("click", this.onClick);
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/MediaImage/old.js":
/*!*******************************!*\
  !*** ./src/MediaImage/old.js ***!
  \*******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var $context = jquery__WEBPACK_IMPORTED_MODULE_0___default()(context);
  $context.on('click.media_image', ".bf-media-image-upload-btn", function (e) {
    var _this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this),
        $_input = _this.siblings(':input');

    var custom_uploader;

    var media_title = _this.data('media-title');

    var media_button_text = _this.data('button-text');

    custom_uploader = wp.media.frames.file_frame = wp.media({
      title: media_title,
      button: {
        text: media_button_text
      },
      multiple: false
    });
    custom_uploader.on('select', function () {
      $_input.removeClass('bf-invalid-value');
      var attachment = custom_uploader.state().get('selection').first().toJSON();

      if (_this.data('data-type') === "id") {
        $_input.val(attachment.id).trigger('change');
      } else {
        $_input.val(attachment.url).trigger('change');
      }

      $_input.change();
      $_input[0].dispatchEvent(new Event('change', {
        bubbles: true
      }));
      var preview = '';

      if (typeof _this.data('size') != "undefined") {
        var var_name = _this.data('size');

        if (attachment.sizes && typeof attachment.sizes[var_name] != "undefined") {
          preview = attachment.sizes[var_name].url;
        } else {
          preview = attachment.url;
        }
      } else {
        preview = attachment.url;
      }

      _this.siblings('.bf-media-image-remove-btn').show();

      _this.siblings('.bf-media-image-preview').find('img').attr('src', preview);

      _this.siblings('.bf-media-image-preview').show(); // Global change event


      _this.trigger('bf-media-image-changed', {
        'type': _this.data('data-type') || 'src',
        'name': $_input.attr('name'),
        'attachment': attachment
      }); // field Global change event


      _this.trigger('bf-media-image-changed:' + $_input.attr('name'), {
        'type': _this.data('data-type') || 'src',
        'name': $_input.attr('name'),
        'attachment': attachment
      });

      onChange($_input.val());
    });
    custom_uploader.open();
    return false;
  });
  $context.off('keyup.media_image', '.bf-media-image-input').on('keyup', '.bf-media-image-input', function (e) {
    var _this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this);

    var link_regex = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

    if (!link_regex.test(_this.val())) {
      _this.addClass('bf-invalid-value');

      _this.siblings('.bf-media-image-remove-btn').hide();

      _this.siblings('.bf-media-image-preview').find('img').attr('src', '');

      _this.siblings('.bf-media-image-preview').hide();

      return false;
    } else {
      _this.removeClass('bf-invalid-value');
    }

    _this.siblings('.bf-media-image-remove-btn').show();

    _this.siblings('.bf-media-image-preview').find('img').attr('src', _this.val());

    _this.siblings('.bf-media-image-preview').show(); // Global change event


    _this.trigger('bf-media-image-changed', {
      'type': 'src',
      'name': _this.attr('name'),
      'attachment': _this.val()
    }); // field Global change event


    _this.trigger('bf-media-image-changed:' + _this.attr('name'), {
      'type': 'src',
      'name': _this.attr('name'),
      'attachment': _this.val()
    });
  });
  $context.on('click.media_image', '.bf-media-image-remove-btn', function () {
    var _this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this);

    var $input = _this.siblings('.bf-media-image-input').val("").change();

    $input[0].dispatchEvent(new Event('change', {
      bubbles: true
    })); // hide remove button, select and preview

    _this.hide();

    _this.siblings('.bf-media-image-preview').hide();

    onChange("");
  });
  return {
    $context: $context
  };
}

/***/ }),

/***/ "./src/MediaImage/script.js":
/*!**********************************!*\
  !*** ./src/MediaImage/script.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/MediaImage/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'media_image';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.impl.$context.find('.bf-media-image-input').val(value);
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return this.impl.$context.find('.bf-media-image-input').val();
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off("click.media_image", ".bf-media-image-upload-btn");
      this.impl.$context.off("keyup.media_image", ".bf-media-image-input");
      this.impl.$context.off("click.media_image", ".bf-media-image-remove-btn");
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Media/old.js":
/*!**************************!*\
  !*** ./src/Media/old.js ***!
  \**************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var $context = jquery__WEBPACK_IMPORTED_MODULE_0___default()(context);
  $context.on('click.media', '.bf-media-upload-btn', function () {
    var _this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this),
        custom_uploader,
        options = _this.data('mediasettings') || {};

    if (custom_uploader) {
      custom_uploader.open();
      return;
    }

    var library = {
      title: _this.data('mediatitle'),
      //library:   wp.media.query({ type:  ['font/woff'] }),
      multiple: false,
      date: false
    };

    if (options.type) {
      library.library = wp.media.query({
        type: options.type
      });
    }

    custom_uploader = wp.media.frames.file_frame = wp.media({
      button: {
        text: _this.data('buttontext')
      },
      states: [new wp.media.controller.Library(library)]
    });
    custom_uploader.on('select', function () {
      var attachment = custom_uploader.state().get('selection').first().toJSON();

      if (options.type && jquery__WEBPACK_IMPORTED_MODULE_0___default().inArray(attachment.mime, options.type) === -1) {
        return false;
      }

      _this.siblings(':input').val(attachment.url);

      onChange(attachment.url);
      custom_uploader.state().get('selection').each(function (i, o) {});
    });
    custom_uploader.open();
    return false;
  });
  return {
    $context: $context
  };
}

/***/ }),

/***/ "./src/Media/script.js":
/*!*****************************!*\
  !*** ./src/Media/script.js ***!
  \*****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/Media/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'media';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.impl.$context.find(":input").val(value);
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return this.impl.$context.find(":input").val();
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off("click.media", ".bf-media-upload-btn");
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Radio/script.js":
/*!*****************************!*\
  !*** ./src/Radio/script.js ***!
  \*****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }



var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default(props) {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    _this = _super.call(this, props);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    _this.onRadioChanged = _this.onRadioChanged.bind((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this));
    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'radio';
    }
  }, {
    key: "init",
    value: function init(element) {
      this.context = element;
      this.bindEvents();
      return true;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      this.context.addEventListener('change', this.onRadioChanged);
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.context.querySelectorAll('input[type="radio"]').forEach(function (element) {
        element.checked = element.value === value;
      });
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      var radio = this.context.querySelector("input:checked");
      return radio && radio.value;
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var _this$context;

      (_this$context = this.context) === null || _this$context === void 0 ? void 0 : _this$context.removeEventListener('change', this.onRadioChanged);
    }
  }, {
    key: "onRadioChanged",
    value: function onRadioChanged() {
      this.onChange(this.valueGet());
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Repeater/old.js":
/*!*****************************!*\
  !*** ./src/Repeater/old.js ***!
  \*****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _js_Hooks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../js/Hooks */ "./js/Hooks.js");


/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var $context = jquery__WEBPACK_IMPORTED_MODULE_0___default()(context); // Add jQuery UI Sortable to Repeater Items

  $context.find('.bf-repeater-items-container').sortable({
    revert: true,
    cursor: 'move',
    delay: 150,
    handle: ".bf-repeater-item-title",
    start: function start(event, ui) {
      ui.item.addClass('drag-start');
    },
    beforeStop: function beforeStop(event, ui) {
      ui.item.removeClass('drag-start');
    }
  }); // Remove Repeater Item

  $context.on('click', '.bf-remove-repeater-item-btn', function (e) {
    var $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this);

    if ($this.hasClass('no-event')) {
      return;
    }

    var $section = $this.closest('.bf-section');

    if (jquery__WEBPACK_IMPORTED_MODULE_0___default()(".bf-repeater-items-container>.bf-repeater-item", $section).length === 1) {
      alert("Can not remove last item!");
      return;
    }

    if (confirm('Are you sure?')) {
      /**
       * Append hidden input before remove last item
       */
      if (jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-repeater-item:visible', $section).length === 1) {
        var inputName = jquery__WEBPACK_IMPORTED_MODULE_0___default()(':input:first', $section).attr('name').toString().match(/([^\[]+)/)[0];
        var $placeholder = jquery__WEBPACK_IMPORTED_MODULE_0___default()('<input>', {
          'class': 'placeholder-input',
          'name': inputName + '[]',
          'type': 'hidden'
        });
        $section.append($placeholder);
      }

      jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).closest('.bf-repeater-item').slideUp(function () {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).remove();
        _js_Hooks__WEBPACK_IMPORTED_MODULE_1__["default"].do_action('repeater/item/removed', $section[0]);
      });
    }

    e.preventDefault();
  }); // Collapse

  $context.on('click', '.handle-repeater-item', function () {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).toggleClass('bf-closed').closest('.bf-repeater-item').find('.repeater-item-container').slideToggle(400);
  }); // Clone Repeater Item by click

  $context.on('click', '.bf-clone-repeater-item', function (e) {
    e.preventDefault();
    var $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this);

    if ($this.hasClass('no-event')) {
      return;
    }

    var $repeater_items_container = $this.siblings('.bf-repeater-items-container'),
        _html = $context.find('.repeater-item-tmpl').html(),
        count = $repeater_items_container.find('>*').size(); // Retrieve script tags


    if (!_html) {
      return;
    }

    _html = _html.replace(/{{iteration}}/g, count);
    $repeater_items_container.append(_html); // Event for when new item added

    $this.closest('.bf-section').trigger('repeater_item_added', [$repeater_items_container]);
    _js_Hooks__WEBPACK_IMPORTED_MODULE_1__["default"].do_action('repeater/item/added', $repeater_items_container.find('.bf-repeater-item:last')[0]); // Remove temporary placeholder input

    $repeater_items_container.closest('.bf-section').find('input.placeholder-input').remove();
  });
  return {
    $context: $context
  };
}

/***/ }),

/***/ "./src/Repeater/script.js":
/*!********************************!*\
  !*** ./src/Repeater/script.js ***!
  \********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/Repeater/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'repeater';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return [];
    }
  }, {
    key: "onChange",
    value: function onChange(value) {
      this.props.onChange(value);
      return true;
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off('click', '.bf-remove-repeater-item-btn');
      this.impl.$context.off('click', '.handle-repeater-item');
      this.impl.$context.off('click', '.bf-clone-repeater-item');
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'array';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/SelectPopup/old.js":
/*!********************************!*\
  !*** ./src/SelectPopup/old.js ***!
  \********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var $context = jquery__WEBPACK_IMPORTED_MODULE_0___default()(context);

  function getData() {
    var data;

    try {
      data = JSON.parse($context.find('.select-popup-data').text());
    } catch (e) {
      return false;
    }

    if (typeof data === "undefined") {
      return false;
    }

    var obj,
        heading = $context.find('.select-popup-field').data('heading');
    var modal_loc = {
      header: '%%name%%',
      search: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Search...', 'better-studio'),
      btn_label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Choose', 'better-studio'),
      btn_label_active: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Current', 'better-studio'),
      filter_cat_title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Category', 'better-studio'),
      categories: [],
      filter_type_title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Type', 'better-studio'),
      all_l10n: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('All', 'better-studio'),
      types: []
    };

    if (data.texts) {
      [['modal_button', 'btn_label'], ['modal_current', 'btn_label_active'], ['modal_title', 'header']].forEach(function (replacement) {
        var optKey = replacement[0],
            modalKey = replacement[1];

        if (data.texts[optKey]) {
          modal_loc[modalKey] = data.texts[optKey];
        }
      });
    }

    if (modal_loc.header.indexOf('%%name%%') !== -1) {
      modal_loc.header = modal_loc.header.replace('%%name%%', heading);
    }

    var modal2_loc = {
      header: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Do you want to change %%name%%?', 'better-studio'),
      button_ok: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Yes, Change', 'better-studio'),
      button_cancel: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Cancel', 'better-studio'),
      caption: '%s'
    };

    if (data.confirm_texts) {
      modal2_loc = jquery__WEBPACK_IMPORTED_MODULE_0___default().extend(modal2_loc, data.confirm_texts);
    }

    if (modal2_loc.header.indexOf('%%name%%') !== -1) {
      modal2_loc.header = modal2_loc.header.replace('%%name%%', heading);
    }

    if (data.column_class) {
      modal_loc.content_class = data.column_class;
    }

    var items = [],
        cats = {},
        types = {};
    var opts = data.options;

    for (var id in opts) {
      obj = opts[id];
      obj.id = id;

      if (typeof obj.cat !== "undefined") {
        obj.cat.forEach(function (cat) {
          cats[cat] = modal_loc.categories[cat] || cat;
        });
      }

      if (typeof obj.type !== "undefined") {
        obj.type.forEach(function (type) {
          types[type] = modal_loc.types[type] || type;
        });
      }

      var badges = [];

      if (obj.badges) {
        obj.badges.forEach(function (badge) {
          var badgeObj = {};
          badgeObj.badge = badge;
          badges.push(badgeObj);
        });
      }

      obj.badges = badges;
      items.push(obj);
    } //  [ Modal data, General data, Main Modal Localization, Confirm Modal Localization ]


    return [{
      items: items,
      cats: cats,
      types: types
    }, data, modal_loc, modal2_loc];
  }

  $context.on('click', '.select-popup-field', function (e) {
    e.preventDefault();
    var initialZIndex = 1.3e5,
        $select = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this),
        activeItem = $select.find('input.select-value').val(),
        data = getData.call(this); // FIX nested bs-modal z-index issue

    var $parentModal = $select.closest('.bs-modal');

    if ($parentModal.length) {
      initialZIndex = parseInt($parentModal.css('z-index'));
    }

    if (data[0]) {
      jquery__WEBPACK_IMPORTED_MODULE_0___default().bs_selector_modal({
        bsModal: {
          destroyHtml: true,
          show: true
        },
        id: 'better-select-popup-modal',
        modalClass: 'pds-modal',
        itemInnerHtml: '<div class="bf-item-container">\n    \n    <figure>\n        <img src="{{img}}"\n             alt="{{label}}"\n             class="bs-style-thumbnail" data-current-image="{{current_img}}">\n         <div class="bf-item-badges">\n\n         {{#badges}}\n        <div class="bf-item-badge">\n            {{badge}}\n        </div>\n         {{/badges}}\n         </div>\n    </figure>\n\n    <footer class="bf-item-footer bf-clearfix">\n        <span class="bf-item-title">\n            {{label}}\n        </span>\n\n        <div class="bf-item-buttons">\n            <span class="bf-toggle-item-status">\n                <a href="#" target="_blank"\n                   class="button bf-btn-secondary bf-btn-dark">{{btn_label}}</a>\n            </span>\n        </div>\n    </footer>\n</div>',
        content: data[2],
        items: data[0].items,
        categories: data[0].cats,
        types: data[0].types,
        itemsGroupSize: 9,
        fuse: {
          keys: ['label']
        },
        events: {
          scrollIntoView: function scrollIntoView(elements) {
            var modal = this;
            elements.forEach(function (el) {
              modal.$(el).find('.bf-item-container').addClass('bs-animate bs-fadeInUp');
            });
          },
          after_append_html: function after_append_html() {
            var modal = this;

            function getBody() {
              var out = '';

              if (data[3].list_items) {
                out += '<div class="pdsm-notice-list"><ul class="styled">';
                data[3].list_items.forEach(function (lbl) {
                  out += '<li>' + lbl + '</li>';
                });
                out += '</ul></div>';
              }

              if (data[3].notice) {
                out += '<div class="pdsm-notice">';
                out += data[3].notice;
                out += '</div>';
              }

              return out;
            }

            function setItemAsActive($item) {
              $item.addClass('active');
              var $btn = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".bf-toggle-item-status a", $item);
              $btn.attr('class', 'bf-btn-primary disabled').html(data[2].btn_label_active);
              $btn.parent().removeClass('bf-toggle-item-status');
              $btn.on('click', function (e) {
                e.preventDefault();
              });
            }

            function setValue(id, label) {
              var $input = $select.find('input.select-value');
              $input.val(id).change();
              $select.find('.active-item-label').html(label);
              $input[0].dispatchEvent(new Event('change', {
                bubbles: true
              }));
              onChange(id);
            }

            function setImage(src) {
              $select.find('.select-popup-selected-image img').attr('src', src);
            }

            setItemAsActive(modal.selectItem(activeItem));
            modal.bsModal.$modal.on('click', '.bssm-item:not(.disabled)', function (e) {
              var $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this);

              if ($this.hasClass('active')) {
                modal.bsModal.close_modal();
                return;
              }

              var $selectedItem = $this.closest('.bssm-item'),
                  selectedItemId = $selectedItem.data('item-id'),
                  $selectedItemImg = $selectedItem.find('figure img'),
                  selectedItemTitle = $selectedItem.find('.bf-item-title').text();
              var selectedItemImg = $selectedItemImg.data('current-image') || $selectedItemImg.attr('src');
              e.preventDefault();

              if (!data[1].confirm_changes) {
                var e = jquery__WEBPACK_IMPORTED_MODULE_0___default().Event('select-popup-select');
                $select.trigger(e, [this, modal.bsModal]);

                if (!e.isDefaultPrevented()) {
                  modal.bsModal.close_modal();
                  setValue(selectedItemId, selectedItemTitle);
                  setImage(selectedItemImg);
                }

                $select.trigger('select-popup-selectd', [this, modal.bsModal]);
                return;
              }

              jquery__WEBPACK_IMPORTED_MODULE_0___default().bs_modal({
                modalId: 'better-select-popup-confirm-modal',
                modalClass: 'pds-confirm-modal',
                content: jquery__WEBPACK_IMPORTED_MODULE_0___default().extend({
                  image_align: jquery__WEBPACK_IMPORTED_MODULE_0___default()('body').hasClass('rtl') ? 'left' : 'right',
                  image_style: 'margin-left:10px;width:240px',
                  image_src: $selectedItem.find('.bs-style-thumbnail').attr('src'),
                  image_caption: data[3].caption.replace('%s', $selectedItem.find('.bf-item-title').text()),
                  body: getBody()
                }, data[3]),
                buttons: {
                  confirm: {
                    label: data[3].button_ok,
                    type: 'primary',
                    clicked: function clicked() {
                      setValue(selectedItemId, selectedItemTitle);
                      setImage(selectedItemImg);
                      var e = jquery__WEBPACK_IMPORTED_MODULE_0___default().Event('select-popup-confirm');
                      $select.trigger(e, [this, modal.bsModal]);

                      if (e.isDefaultPrevented()) {
                        return;
                      }

                      var delay = Math.floor(this.options.animations.delay / 2);
                      this.close_modal();
                      setTimeout(function () {
                        modal.bsModal.close_modal();
                      }, delay);
                    }
                  },
                  close_modal: {
                    btn_classes: 'bs-modal-button-aside',
                    label: data[3].button_cancel,
                    action: 'close',
                    type: 'secondary',
                    focus: true
                  }
                },
                template: 'single_image',
                styles: {
                  container: 'width: 615px;max-width:100%'
                },
                animations: {
                  body: 'bs-animate bs-fadeInLeft'
                }
              });
            }).on('click', '.bf-toggle-item-status a.disabled', function () {
              return false;
            });
            $select.trigger('select-popup-loaded', [data, modal]);
          }
        }
      }, {
        initialZIndex: initialZIndex
      });
    }
  });
  return {
    $context: $context
  };
}

/***/ }),

/***/ "./src/SelectPopup/script.js":
/*!***********************************!*\
  !*** ./src/SelectPopup/script.js ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/SelectPopup/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'select_popup';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.impl.$context.find('input.select-value').val(value);
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return this.impl.$context.find('input.select-value').val();
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off('click', '.select-popup-field');
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Select/script.js":
/*!******************************!*\
  !*** ./src/Select/script.js ***!
  \******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }



var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default(props) {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    _this = _super.call(this, props);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    _this.onInputChanged = _this.onInputChanged.bind((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this));
    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'select';
    }
  }, {
    key: "init",
    value: function init(element) {
      this.context = element;
      this.bindEvents();
      return true;
    }
  }, {
    key: "select",
    value: function select() {
      return this.context.querySelector("select");
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var select = this.select();
      select && select.addEventListener('change', this.onInputChanged);
    }
  }, {
    key: "onInputChanged",
    value: function onInputChanged() {
      var values = this.valueGet();
      this.valueSet(values);
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.onChange(value);
      return false;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      var input = this.select();

      if (!input) {
        return [];
      }

      if (!input.multiple) {
        return [input.value];
      }

      var values = [];
      Array.from(input.selectedOptions).forEach(function (option) {
        values.push(option.value);
      });
      return values;
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'array';
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var input = this.select();
      input && input.removeEventListener('change', this.onInputChanged);
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Slider/old.js":
/*!***************************!*\
  !*** ./src/Slider/old.js ***!
  \***************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var $selector = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".bf-slider-slider", context);
  $selector.each(function () {
    var $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this);
    var min = $this.data('min');
    var max = $this.data('max');
    var step = $this.data('step');
    var animate = $this.data('animation') === 'enable';

    var _dimension = ' ' + $this.data('dimension');

    var value = $this.data('val');
    $this.slider({
      range: 'min',
      animate: animate,
      value: value,
      step: step,
      min: min,
      max: max,
      slide: function slide(event, ui) {
        $this.find(".ui-slider-handle").html('<span>' + ui.value + _dimension + '</span>');
        $this.siblings('.bf-slider-input').val(ui.value).change();
        onChange(ui.value);
      },
      create: function create(event, ui) {
        $this.find(".ui-slider-handle").html('<span>' + value + _dimension + '</span>');
      }
    });
    $this.removeClass('not-prepared');
  });
  return {
    $selector: $selector
  };
}

/***/ }),

/***/ "./src/Slider/script.js":
/*!******************************!*\
  !*** ./src/Slider/script.js ***!
  \******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/Slider/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'slider';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.impl.$selector.val(value);
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return this.impl.$selector.val();
    }
  }, {
    key: "destroy",
    value: function destroy() {}
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/SorterCheckbox/script.js":
/*!**************************************!*\
  !*** ./src/SorterCheckbox/script.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_8__);








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default(props) {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    _this = _super.call(this, props);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    _this.onValueChange = _this.onValueChange.bind((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this));
    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'sorter_checkbox';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.context = element;
      jquery__WEBPACK_IMPORTED_MODULE_8___default()(".bf-sorter-list", element).sortable({
        placeholder: "placeholder-item",
        cancel: "li.disable-item",
        update: function update(event) {
          var $this = jquery__WEBPACK_IMPORTED_MODULE_8___default()(event.target);

          if (typeof $this.attr('checked') != "undefined") {
            $this.closest('li').addClass('checked-item');
          } else {
            $this.closest('li').removeClass('checked-item');
          }

          _this2.refresh();
        }
      });
      jquery__WEBPACK_IMPORTED_MODULE_8___default()(":checkbox", element).on('change', this.refresh.bind(this));
      this.bindEvents();
      return true;
    }
  }, {
    key: "onValueChange",
    value: function onValueChange() {
      var values = this.valueGet();
      this.onChange(values);
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.context.querySelector("input[type=hidden]").value = JSON.stringify(value);
      this.onChange(value);
      return true;
    }
  }, {
    key: "refresh",
    value: function refresh() {
      this.valueSet(this.valueGet());
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      var values = [];

      if (!this.context) {
        return values;
      }

      this.context.querySelectorAll('input[type="checkbox"]').forEach(function (input) {
        // input.parentElement.classList[input?.checked ? "remove": "add"]("disable-item");
        if (!input.disabled) {
          var _input$parentElement, _input$parentElement$;

          values.push({
            label: (_input$parentElement = input.parentElement) === null || _input$parentElement === void 0 ? void 0 : (_input$parentElement$ = _input$parentElement.innerText) === null || _input$parentElement$ === void 0 ? void 0 : _input$parentElement$.trim(),
            id: input.dataset.id,
            active: input.checked
          });
        }
      });
      return values;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var listEl = this.context.querySelector(".bf-sorter-list");
      listEl && listEl.addEventListener('change', this.onValueChange);
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var listEl = this.context.querySelector(".bf-sorter-list");
      listEl && listEl.removeEventListener('change', this.onValueChange);
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'array';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Sorter/old.js":
/*!***************************!*\
  !*** ./src/Sorter/old.js ***!
  \***************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  jquery__WEBPACK_IMPORTED_MODULE_0___default()(".bf-sorter-list", context).sortable({
    placeholder: "placeholder-item",
    cancel: "li.disable-item",
    update: function update() {
      var $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this);

      if (typeof $this.attr('checked') != "undefined") {
        $this.closest('li').addClass('checked-item');
      } else {
        $this.closest('li').removeClass('checked-item');
      }

      var values = [];
      jquery__WEBPACK_IMPORTED_MODULE_0___default()("li", context).each(function (index, element) {
        values.push({
          label: element.innerText,
          id: element.dataset.id
        });
      });
      onChange(values);
    }
  });
}

/***/ }),

/***/ "./src/Sorter/script.js":
/*!******************************!*\
  !*** ./src/Sorter/script.js ***!
  \******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/Sorter/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "element", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'sorter';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.element = element;
      (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.valueSet(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      var input = this.element.querySelector("input[type=hidden]");
      input.value = JSON.stringify(value);
      this.onChange(value);
      input.dispatchEvent(new Event('change', {
        bubbles: true
      }));
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      try {
        return JSON.parse(this.element.querySelector("input[type=hidden]").value);
      } catch (e) {}

      return [];
    }
  }, {
    key: "destroy",
    value: function destroy() {}
  }, {
    key: "dataType",
    value: function dataType() {
      return 'array';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/SwitchControl/old.js":
/*!**********************************!*\
  !*** ./src/SwitchControl/old.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var $context = jquery__WEBPACK_IMPORTED_MODULE_0___default()(context);

  if ($context.data('bf-switch-init')) {
    return;
  }

  $context.on('click.switch', ".cb-enable", function () {
    var parent = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).parents('.bf-switch');
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.cb-disable', parent).removeClass('selected');
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).addClass('selected');
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.checkbox', parent).attr('value', 1).trigger('change')[0].dispatchEvent(new Event('change', {
      bubbles: true
    }));
    onChange(1);
  });
  $context.on('click.switch', ".cb-disable", function () {
    var parent = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).parents('.bf-switch');
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.cb-enable', parent).removeClass('selected');
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).addClass('selected');
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.checkbox', parent).attr('value', 0).trigger('change')[0].dispatchEvent(new Event('change', {
      bubbles: true
    }));
    onChange(0);
  });
  $context.data('bf-switch-init', true);
  return {
    $context: $context
  };
}

/***/ }),

/***/ "./src/SwitchControl/script.js":
/*!*************************************!*\
  !*** ./src/SwitchControl/script.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/SwitchControl/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'switch';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.valueSet(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.impl.$context.find('.checkbox').val(String(value));
      this.onChange(String(value));
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return this.impl.$context.find('.checkbox').val();
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off('click.switch', '.cb-enable');
      this.impl.$context.off('click.switch', '.cb-disable');
    }
  }, {
    key: "on",
    value: function on() {
      this.impl.$context.find('a.cb-enable').click();
    }
  }, {
    key: "off",
    value: function off() {
      this.impl.$context.find('a.cb-disable').click();
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/TermSelect/old.js":
/*!*******************************!*\
  !*** ./src/TermSelect/old.js ***!
  \*******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _js_AjaxRequest__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../js/AjaxRequest */ "./js/AjaxRequest.js");
/* harmony import */ var _js_UI__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../js/UI */ "./js/UI.js");
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }






/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var config = {
    autoCheckParent: false // Check parent term if all children was checked

  };
  var $context = jquery__WEBPACK_IMPORTED_MODULE_0___default()(context);

  function getTermIdByInputName(inputName) {
    return inputName.toString().match(/\[(\d+)\]$/i)[1];
  }
  /**
   * Display Primary link only when more than one root term are active
   */


  function canCreatePrimaryLink($container) {
    var $checked = $container.parent().find('.bf-field-term-select-wrapper>ul>li>.bf-checkbox-multi-state[data-current-state="active"]');
    return $checked.length >= 2;
  }

  function toggleShowPrimaryClass($el, addClass) {
    $el[addClass ? 'addClass' : 'removeClass']('bf-field-term-show-primary-label');
  }

  function createPrimaryLink($labelContainer, termID) {
    if (!termID) {
      var $input = $labelContainer.prevAll('.bf-checkbox-multi-state').find('.bf-checkbox-status');

      if (!$labelContainer.length) {
        return false;
      }

      termID = getTermIdByInputName($input.attr('name'));
    }

    if (!termID) {
      return false;
    }

    var $primaryEL = $labelContainer.find(".bf-make-term-primary");

    if (!$primaryEL.length || $primaryEL.attr('class') !== 'bf-make-term-primary') {
      if ($primaryEL.length) {
        $primaryEL.remove();
      }

      var $el = jquery__WEBPACK_IMPORTED_MODULE_0___default()('<em/>', {
        'class': 'bf-make-term-primary'
      }).html('<a href="#" data-term-id="' + termID + '">' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Make Primary', 'better-studio') + '</a>');
      $el.appendTo($labelContainer);
      return true;
    }

    return false;
  }

  function initCheckboxes($context) {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-checkbox-multi-state', $context).each(function () {
      var $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this),
          state = $this.data('current-state'),
          $currentMark = jquery__WEBPACK_IMPORTED_MODULE_0___default()('[data-state="' + state + '"]', $this);
      $currentMark.css('display', 'inline-block');
      jquery__WEBPACK_IMPORTED_MODULE_0___default()('[data-state]:not(.disabled)', $this).not($currentMark).css('display', 'none');
    });
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-checkbox-multi-state', $context).each(function () {
      var $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this),
          state = $this.data('current-state');
      jquery__WEBPACK_IMPORTED_MODULE_0___default()('[data-state=' + state + ']', $this).css('display', 'inline-block');
      /**
       * Initial 'Collect ID' Flag
       */

      var $childrenUL = $this.nextAll('.children');

      if ($childrenUL.length) {
        if (isAllChildrenActive($childrenUL)) {
          updateCollectIdFlag($childrenUL, 'dont-collect');
          $childrenUL.addClass('bf-checkbox-dual-state');
          updateCheckboxStatesList($childrenUL, ['active', 'deactivate']);
        }
      }
    });
  }

  function updateCheckboxStatesList($context, validStatus) {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('[data-state]', $context).each(function () {
      var $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this),
          state = $this.data('state'),
          isEnable = jquery__WEBPACK_IMPORTED_MODULE_0___default().inArray(state, validStatus) > -1;
      $this[isEnable ? 'removeClass' : 'addClass']('disabled');
    });
  }

  function changeCheckboxState(status, $checkboxEL, $context) {
    $context = $context || $checkboxEL.parent();
    $checkboxEL.attr('data-current-state', status).data('current-state', status).find('.bf-checkbox-status').val(status);
    $checkboxEL.trigger('bf-checkbox-change', []);
    initCheckboxes($context);
    return true;
  }

  function changeChildrenState($childUlWrapper, status) {
    if (!status) {
      return false;
    }

    if ($childUlWrapper.length) {
      return changeCheckboxState(status, $childUlWrapper.find('.bf-checkbox-multi-state'), $childUlWrapper);
    }

    return false;
  }

  function updateCollectIdFlag($context, flag) {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-checkbox-multi-state', $context)[flag == 'collect' ? "removeClass" : 'addClass']('bf-checkbox-skip-collect-active-term-id');
  }

  function canCollectTermAsDeactivated(checkboxStatusEL) {
    return checkboxStatusEL.parentElement.dataset.currentState === 'deactivate';
  }

  function canCollectTermAsActivated(checkboxStatusEL, $checkboxContainer) {
    return checkboxStatusEL.parentElement.dataset.currentState === 'active' && function ($container) {
      return !$container.hasClass('bf-checkbox-skip-collect-active-term-id');
    }($checkboxContainer || jquery__WEBPACK_IMPORTED_MODULE_0___default()(checkboxStatusEL).closest('.bf-checkbox-multi-state'));
  }

  function isTermPrimary($labelContainer) {
    return $labelContainer.find('.bf-make-term-primary').hasClass('bf-is-term-primary');
  }

  function isAllChildrenActive($childrenUL) {
    return $childrenUL.length && !jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-checkbox-multi-state[data-current-state!="active"]', $childrenUL).length;
  }

  function scrollToFirstActiveCheckbox($context) {
    var topMargin = 40,
        $catsContainer = jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-field-term-select-wrapper', $context);

    if ($catsContainer.length) {
      var $firstChecked = jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-checkbox-multi-state[data-current-state="active"]:first', $catsContainer);

      if ($firstChecked.length) {
        var firstCheckPos = $firstChecked.offset().top - $catsContainer.offset().top - topMargin;
        $catsContainer.animate({
          scrollTop: "+=" + firstCheckPos
        }, 300);
      }
    }
  }

  function findPrimaryTermId(selected_items) {
    if (!lodash__WEBPACK_IMPORTED_MODULE_1___default().isString(selected_items)) {
      return;
    }

    var _iterator = _createForOfIteratorHelper(selected_items.split(',')),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var term_id = _step.value;

        if (term_id.startsWith('+')) {
          return term_id.substr(1);
        }
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  }

  function makePrimary(term_id) {
    $context.find(".cat-item-".concat(term_id, " .bf-make-term-primary a")).click();
  }

  $context.on('bf-checkbox-change.term_select', ".bf-checkbox-multi-state", lodash__WEBPACK_IMPORTED_MODULE_1___default().debounce(function (e, state, calledFrom) {
    var $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this),
        $container = $this.closest('.bf-field-term-select-wrapper'),
        termsIdList = [];
    var $clickedCheckBox = $this.closest('.bf-checkbox-multi-state'),
        $childrenUL = $clickedCheckBox.nextAll('.children'),
        childStateChanged = calledFrom === 'auto-check-children' || changeChildrenState($childrenUL, state === 'deactivate' ? 'none' : state);
    /**
     * Check parent term if all children terms was checked
     */

    if (config.autoCheckParent && state === 'active' && calledFrom !== 'auto-check-children') {
      var $parentChildren = $clickedCheckBox.closest('ul.children');

      if (isAllChildrenActive($parentChildren)) {
        var $EL = $parentChildren.prevAll('.bf-checkbox-multi-state');
        changeCheckboxState('active', $EL);
        $EL.trigger('bf-checkbox-change', [state, 'auto-check-children']);
        return;
      }
    }

    if (childStateChanged && state === 'active') {
      $childrenUL.addClass('bf-checkbox-dual-state');
      $childrenUL.find('ul.children').addClass('bf-checkbox-dual-state');
      updateCheckboxStatesList($childrenUL, ['active', 'deactivate']);
      updateCollectIdFlag($childrenUL, 'dont-collect');
    } else if (state !== 'active') {
      updateCheckboxStatesList($childrenUL.closest('ul.children').removeClass('bf-checkbox-dual-state'), ['none', 'active', 'deactivate']);
      updateCollectIdFlag($childrenUL, 'collect');
    }

    var canCreateLink = canCreatePrimaryLink($container);
    toggleShowPrimaryClass($container, canCreateLink);
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-checkbox-status', $container).each(function () {
      var termID = getTermIdByInputName(this.name),
          $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this),
          status = 'none',
          $container = $this.closest('.bf-checkbox-multi-state'),
          isTermRoot = $this.closest('ul.children', $container).length === 0,
          $label = $container.next('.label');

      if (canCollectTermAsDeactivated(this)) {
        termsIdList.push("-" + termID);
        status = 'deactivate';
      } else if (canCollectTermAsActivated(this, $container)) {
        status = 'active';

        if (isTermPrimary($label)) {
          termsIdList.unshift("+" + termID);
        } else {
          termsIdList.push(termID);
        }
      }

      $label.attr('data-status', status).data('status', status);
      /**
       * Append Make Primary label if needed
       */

      if (canCreateLink && isTermRoot && !$container.hasClass('bf-checkbox-primary-term')) {
        if (this.value === 'active') {
          createPrimaryLink($label, termID);
        } else {//$label.find(".bf-make-term-primary").remove();
        }
      }
      /**
       * Append Excluded label if needed
       */


      if (this.value === 'deactivate') {
        if (!$label.find('.bf-excluded-term').length) {
          var $el = jquery__WEBPACK_IMPORTED_MODULE_0___default()('<em/>', {
            'class': 'bf-excluded-term'
          }).text((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Excluded', 'better-studio'));
          $el.appendTo($label);
        }
      } else {
        $label.find('.bf-excluded-term').remove();
      }
    }).promise().done(function () {
      var newValue = termsIdList.join(',');
      var $input = $container.nextAll('.bf-term-select-value');

      if ($input.length) {
        $input.val(newValue).change()[0].dispatchEvent(new Event('change', {
          bubbles: true
        }));
      }

      onChange(newValue);
    });
  }, 40));
  /**
   * Handle Make Primary Action
   */

  $context.on('click.term_select', '.bf-make-term-primary a', function (e) {
    e.preventDefault();
    var $this = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this),
        termID = $this.data('term-id'),
        $container = $this.closest('.bf-field-term-select-wrapper'),
        $inputEL = $container.siblings('.bf-term-select-value');
    /**
     * Regenerate terms id and mark primary term with a + sign
     */

    var termsIdList = ["+" + termID],
        canCreateLink = canCreatePrimaryLink($container);
    toggleShowPrimaryClass($container, canCreateLink);
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-checkbox-status', $container).each(function () {
      var _termID = getTermIdByInputName(this.name);

      if (_termID == termID) {
        return;
      }

      if (canCollectTermAsDeactivated(this)) {
        termsIdList.push("-" + _termID);
      } else if (canCollectTermAsActivated(this)) {
        termsIdList.push(_termID);
      }
    }).promise().done(function () {
      var newValue = termsIdList.join(',');
      $inputEL.val(newValue).change();
      onChange(newValue);
    });

    if (canCreateLink) {
      jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-is-term-primary', $container).each(function () {
        createPrimaryLink(jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).parent());
      });
    }

    var $label = $this.parent();
    $label.addClass('bf-is-term-primary').html('Primary');
    /**
     * Mark term as primary
     */

    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-checkbox-multi-state', $container).removeClass('bf-checkbox-primary-term');
    $label.closest('.label').prevAll('.bf-checkbox-multi-state').addClass('bf-checkbox-primary-term');
  });
  initCheckboxes($context);
  /**
   * Load Deferred Items.
   */

  var $input = $context.find(".bf-term-select-value");
  var $deferred = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".bf-field-term-select-deferred", $context);
  _js_UI__WEBPACK_IMPORTED_MODULE_4__["default"].block($context);
  var taxonomy = $deferred.data('taxonomy');
  (0,_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_3__.load_data)("term_select", {
    taxonomy: taxonomy
  }, "term_select.".concat(taxonomy)).then(function (response) {
    try {
      _js_UI__WEBPACK_IMPORTED_MODULE_4__["default"].unblock($context);

      if (response.raw) {
        var _BetterStudio, _BetterStudio$Libs;

        var selected = $input.val();
        $deferred.html('<ul>' + response.raw + '</ul>').removeClass('bf-field-term-select-deferred');
        (_BetterStudio = BetterStudio) === null || _BetterStudio === void 0 ? void 0 : (_BetterStudio$Libs = _BetterStudio.Libs) === null || _BetterStudio$Libs === void 0 ? void 0 : _BetterStudio$Libs.checkboxMultiState($context, selected);
        initCheckboxes($context);
        makePrimary(findPrimaryTermId(selected));
      }
    } catch (e) {
      console.error(e);
    }
  })["catch"](function (error) {
    console.group("catch");
    console.error(error);
    console.groupEnd();
    _js_UI__WEBPACK_IMPORTED_MODULE_4__["default"].unblock($context);
    $deferred.html("ERROR fetch data.");
  });
  var $container = jquery__WEBPACK_IMPORTED_MODULE_0___default()('.bf-field-term-select-wrapper', $context);
  toggleShowPrimaryClass($container, canCreatePrimaryLink($container));
  scrollToFirstActiveCheckbox($context);
  return {
    $context: $context,
    $input: $input
  };
}

/***/ }),

/***/ "./src/TermSelect/script.js":
/*!**********************************!*\
  !*** ./src/TermSelect/script.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/TermSelect/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'term_select';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.impl.$input.val(value);
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return this.impl.$input.val();
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off('bf-checkbox-change.term_select', ".bf-checkbox-multi-state");
      this.impl.$context.off('click.term_select', '.bf-make-term-primary a');
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Text/script.js":
/*!****************************!*\
  !*** ./src/Text/script.js ***!
  \****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }



var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default(props) {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    _this = _super.call(this, props);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    _this.onInputChanged = _this.onInputChanged.bind((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this));
    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'text';
    }
  }, {
    key: "init",
    value: function init(element) {
      this.context = element;
      this.bindEvents();
      return true;
    }
  }, {
    key: "input",
    value: function input() {
      return this.context.querySelector("input[type='text']");
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var input = this.input();
      input && input.addEventListener('change', this.onInputChanged);
    }
  }, {
    key: "onInputChanged",
    value: function onInputChanged(event) {
      this.valueSet(event.target.value);
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.onChange(value);
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      var input = this.input();
      return input ? input.value : "";
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var input = this.input();
      input && input.removeEventListener('change', this.onInputChanged);
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Textarea/script.js":
/*!********************************!*\
  !*** ./src/Textarea/script.js ***!
  \********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }



var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default(props) {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    _this = _super.call(this, props);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "context", void 0);

    _this.onTextareaChanged = _this.onTextareaChanged.bind((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this));
    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'textarea';
    }
  }, {
    key: "init",
    value: function init(element) {
      this.context = element;
      this.bindEvents();
      return true;
    }
  }, {
    key: "textarea",
    value: function textarea() {
      return this.context.querySelector("textarea");
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var element = this.textarea();
      element && element.addEventListener('change', this.onTextareaChanged);
    }
  }, {
    key: "onTextareaChanged",
    value: function onTextareaChanged(event) {
      this.valueSet(event.target.value);
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      this.onChange(value);
      return true;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      var element = this.textarea();
      return element ? element.value : "";
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var input = this.textarea();
      input && input.removeEventListener('change', this.onTextareaChanged);
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'string';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/Typography/old.js":
/*!*******************************!*\
  !*** ./src/Typography/old.js ***!
  \*******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var _js_AjaxRequest__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../js/AjaxRequest */ "./js/AjaxRequest.js");
/* harmony import */ var _js_UI__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../js/UI */ "./js/UI.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _Color_script__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Color/script */ "./src/Color/script.js");
/* harmony import */ var _SwitchControl_script__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../SwitchControl/script */ "./src/SwitchControl/script.js");






/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(context, onChange) {
  var $context = jquery__WEBPACK_IMPORTED_MODULE_2___default()(context);
  var better_fonts_manager_loc = {};
  var Better_Fonts_Manager = {
    init: function init() {
      this.setup_color();
      this.setup_switch();
      this.load_fonts();
      this.setup_panel_fonts_manager(); //Setup General fields

      this.setup_field_typography();
      this.setup_field_font_selector();
      $context.find(".bf-section[data-id='font_stacks']").on('keyup', 'input[name$="[id]"]', function () {
        var $this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this),
            $wrapper = $this.closest('.bf-repeater-item');
        $wrapper.find('.bf-repeater-item-title .handle-repeater-title-label').text('Font Stack: ' + $this.val());
      }).find('input[name$="[id]"]').keyup();
    },
    load_google_fonts: {},
    load_theme_fonts: {},
    load_custom_fonts: {},
    load_google_ea_fonts: {},
    getFontSelectorFontsList: function getFontSelectorFontsList() {
      var _better_fonts_manager;

      var font;
      var results = [],
          lbl = (_better_fonts_manager = better_fonts_manager_loc) === null || _better_fonts_manager === void 0 ? void 0 : _better_fonts_manager.labels;

      for (var type in (_better_fonts_manager2 = better_fonts_manager_loc) === null || _better_fonts_manager2 === void 0 ? void 0 : _better_fonts_manager2.fonts) {
        var _better_fonts_manager2;

        for (var fontID in (_better_fonts_manager3 = better_fonts_manager_loc) === null || _better_fonts_manager3 === void 0 ? void 0 : _better_fonts_manager3.fonts[type]) {
          var _better_fonts_manager3, _better_fonts_manager4;

          font = (_better_fonts_manager4 = better_fonts_manager_loc) === null || _better_fonts_manager4 === void 0 ? void 0 : _better_fonts_manager4.fonts[type][fontID];
          var styleCount = font.variants ? (0,lodash__WEBPACK_IMPORTED_MODULE_3__.size)(font.variants) : 1,
              cat = font.category ? font.category : 'serif';
          results.push({
            type_label: lbl.types[type] ? lbl.types[type] : type,
            type: type.replace('fonts', 'font'),
            cat: cat,
            name: font.name ? font.name : fontID,
            styles: lbl.style.replace('%s', styleCount),
            id: fontID
          });
        }
      }

      return results;
    },
    loadFonts: function loadFonts(type, fontFamilies, fontloading, fontactive) {
      var _better_fonts_manager5, _better_fonts_manager6;

      switch (type) {
        case 'google_font':
          WebFont.load({
            google: {
              families: fontFamilies,
              text: (_better_fonts_manager5 = better_fonts_manager_loc) === null || _better_fonts_manager5 === void 0 ? void 0 : (_better_fonts_manager6 = _better_fonts_manager5.labels) === null || _better_fonts_manager6 === void 0 ? void 0 : _better_fonts_manager6.preview_text
            },
            fontloading: fontloading,
            fontactive: fontactive,
            fontinactive: fontactive,
            classes: false
          });
          break;

        case 'google_ea_font':
        case 'custom_font':
        case 'theme_font':
          var qVar = type.replace(/\-+/g, '_') + "_id";
          fontFamilies.forEach(function (fontfamily) {
            var _better_fonts_manager7;

            WebFont.load({
              custom: {
                families: fontfamily,
                urls: [((_better_fonts_manager7 = better_fonts_manager_loc) === null || _better_fonts_manager7 === void 0 ? void 0 : _better_fonts_manager7.admin_fonts_css_url) + '&' + qVar + "=" + fontfamily]
              },
              fontloading: fontloading,
              fontactive: fontactive,
              fontinactive: fontactive,
              classes: false
            });
          });
          break;

        default:
          return false;
          break;
      }

      return true;
    },

    /**
     * Initialize font selector modal
     */
    setup_field_font_selector: function setup_field_font_selector() {
      var self = this,
          autoCloseDelay = 150;
      var fonts = self.getFontSelectorFontsList(),
          $fontSelectorEl;

      function openCustomFontModal(mainModal, selectorModal) {
        var _better_fonts_manager9, _better_fonts_manager10;

        var wpMediaFrameClosed = false;

        function catchWpMediaFrameStatus(modalObj) {
          modalObj.$modal.find('.bf-media-upload-btn').on('click', function () {
            var _this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this),
                custom_uploader,
                options = _this.data('mediasettings') || {};

            if (custom_uploader) {
              custom_uploader.open();
              return;
            }

            var library = {
              title: _this.data('mediatitle'),
              //library:   wp.media.query({ type:  ['font/woff'] }),
              multiple: false,
              date: false
            };

            if (options.type) {
              library.library = wp.media.query({
                type: options.type
              });
            }

            custom_uploader = wp.media({
              button: {
                text: _this.data('buttontext')
              },
              states: [new wp.media.controller.Library(library)]
            });
            custom_uploader.on('select', function () {});
            custom_uploader.open();
            custom_uploader.on('close', function () {
              wpMediaFrameClosed = true;
            });
            custom_uploader.on('select', function () {
              var url = custom_uploader.state().get('selection').first().toJSON().url;
              jquery__WEBPACK_IMPORTED_MODULE_2___default()('.input', _this.parent()).val(url).trigger('keyup');
              onChange(url);
            });
          });
        }

        function overlayClickCloseModal(modalObj) {
          modalObj.find(".bs-modal-overlay").on('click', function () {
            modalObj.close_modal();
          });
        }

        function isWpMediaFrameClosed() {
          return wpMediaFrameClosed;
        }

        function handleAddCustomFontBtn(addFontModal) {
          function generateSingleFontHtml() {
            var _better_fonts_manager8;

            var name = jquery__WEBPACK_IMPORTED_MODULE_2___default()('.font-name', addFontModal.$modal).val();
            var o = selectorModal.options,
                l = (_better_fonts_manager8 = better_fonts_manager_loc) === null || _better_fonts_manager8 === void 0 ? void 0 : _better_fonts_manager8.labels;
            var replacement = {
              type: 'custom_font',
              cat: 'serif',
              id: name,
              name: name,
              styles: 1,
              preview_text: l.preview_text,
              type_label: l.filter_types.custom_font
            };
            return Mustache.render(o.itemBeforeHtml + o.itemInnerHtml + o.itemAfterHtml, replacement);
          }

          var singleFontItem = generateSingleFontHtml(name);
          var data = {};
          jquery__WEBPACK_IMPORTED_MODULE_2___default()('.form', addFontModal.$modal).serializeArray().forEach(function (item) {
            data[item.name] = item.value;
          });
          (0,_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_0__.fetch_data)("typography", {
            action: 'add-font',
            data: data
          }).then(function (response) {
            try {
              var fontsList = jquery__WEBPACK_IMPORTED_MODULE_2___default()('.bssm-list', selectorModal.bsModal.$modal);

              if (response.new_font_id) {
                fontsList.find('.bf-font-family').val(response.new_font_id);
                onChange(response.new_font_id);
              } // Append new custom font to main modal


              if (singleFontItem) {
                fontsList.prepend(singleFontItem);
                selectorModal.markAsSelected(fontsList.find(':first'));
                selectorModal.updateItemsList();
              }

              addFontModal.close_modal();
              setTimeout(function () {
                mainModal.close_modal();
              }, autoCloseDelay + addFontModal.options.animations.delay);
            } catch (error) {
              console.error(error);
            }
          }); // Display loading..

          addFontModal.change_skin({
            skin: 'loading',
            template: 'default',
            animations: {
              body: 'bs-animate bs-fadeInLeft'
            }
          });
        }

        function handleCustomFontInteractiveFields(modalObj) {
          var _isPrimaryBtnActive = false,
              $modal = modalObj.$modal;

          function togglePrimaryBtnDisable(inputValue) {
            if (inputValue && !_isPrimaryBtnActive) {
              $modal.find('.add-font-btn').removeClass('disabled');
              _isPrimaryBtnActive = true;
            } else if (!inputValue && _isPrimaryBtnActive) {
              if (!$modal.find('.input-section:not(.empty)').length) {
                $modal.find('.add-font-btn').addClass('disabled');
                _isPrimaryBtnActive = false;
              }
            }
          }
          /**
           * Auto focus on 'Font Name' field
           */


          $modal.find('input.font-name').focus();
          /**
           * Trigger upload buttons active/deactivate style
           */

          $modal.find(':input').on('keyup', function () {
            var $this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this),
                value = jquery__WEBPACK_IMPORTED_MODULE_2___default().trim($this.val());
            $this.parent()[value === '' ? 'addClass' : 'removeClass']('empty');

            if ($this.hasClass('font-url-input')) {
              togglePrimaryBtnDisable(value);
            }
          });
        }

        jquery__WEBPACK_IMPORTED_MODULE_2___default().bs_modal_template('upload-font', '<div class="bs-modal-default bf-fields-style bs-font-selector-modal bs-font-upload-modal" {{#inline_style}} style="{{inline_style}}"\n     {{/inline_style}}>\n{{#close_button}}\n<a href="#" class="bs-close-modal">\n</a>\n{{/close_button}}\n<div class="bs-modal-header-wrapper bs-modal-clearfix">\n    <h2 class="bs-modal-header">\n        {{header}}\n    </h2>\n</div>\n\n<div class="bs-modal-body">\n\n    <form class="form">\n\n        <div class="bf-section-container">\n            <label for="custom-font-name">{{font_name}}</label>\n            <input type="text" class="font-name input" name="font-name" value="" id="custom-font-name" autofocus>\n\n        </div>\n\n        <div class="bf-choose-fonts">\n            <div class="bf-section-container font-woff">\n                <label for="custom-font-woff">{{font_woff}}</label>\n                <div class="input-section empty">\n\n                    <input type="text" value="" id="custom-font-woff" name="woff" class="input font-url-input">\n                    <a class="bf-media-upload-btn bssm-button button button-primary button-small" \n                       data-mediatitle="{{upload_woff}}"\n                       data-mediasettings=\'{"type":["font/woff","font/woff2"]}\'\n                       data-buttontext="{{upload_woff}}">\n                        {{upload_woff}}\n                    </a>\n                </div>\n            </div>\n\n\t        <div class="bf-section-container font-woff2">\n\t\t        <label for="custom-font-woff2">{{font_woff2}}</label>\n\t\t        <div class="input-section empty">\n\n\t\t\t        <input type="text" value="" id="custom-font-woff2" name="woff2" class="input font-url-input">\n\t\t\t        <a class="bf-media-upload-btn bssm-button  button button-primary button-small"\n\t\t\t           data-mediatitle="{{upload_woff2}}"\n\t\t\t           data-mediasettings=\'{"type":["font/woff","font/woff2"]}\'\n\t\t\t           data-buttontext="{{upload_woff}}">\n\t\t\t\t        {{upload_woff}}\n\t\t\t        </a>\n\t\t        </div>\n\t        </div>\n\t        \n            <div class="bf-section-container font-ttf">\n                <label for="custom-font-ttf">{{font_ttf}}</label>\n                <div class="input-section empty">\n\n                    <input type="text" value="" id="custom-font-ttf" name="ttf" class="input font-url-input">\n                    <a class="bf-media-upload-btn bssm-button  button button-primary button-small" \n                       data-mediatitle="{{upload_ttf}}"\n                       data-mediasettings=\'{"type":["font/ttf"]}\'\n                       data-buttontext="{{upload_ttf}}">\n                        {{upload_ttf}}\n                    </a>\n                </div>\n            </div>\n\n\n            <div class="bf-section-container font-svg">\n                <label for="custom-font-svg">{{font_svg}}</label>\n                <div class="input-section empty">\n\n                    <input type="text" value="" id="custom-font-svg" name="svg" class="input font-url-input">\n                    <a class="bf-media-upload-btn bssm-button  button button-primary button-small" \n                       data-mediatitle="{{upload_svg}}"\n                       data-mediasettings=\'{"type":["font/svg"]}\'\n                       data-buttontext="{{upload_svg}}">\n                        {{upload_svg}}\n                    </a>\n                </div>\n            </div>\n\n\n            <div class="bf-section-container font-eot">\n                <label for="custom-font-eot">{{font_eot}}</label>\n\n                <div class="input-section empty">\n\n                    <input type="text" value="" id="custom-font-eot" name="eot" class="input font-url-input">\n                    <a class="bf-media-upload-btn bssm-button  button button-primary button-small" \n                       data-mediatitle="{{upload_eot}}"\n                       data-mediasettings=\'{"type":["font/eot"]}\'\n                       data-buttontext="{{upload_eot}}">\n                        {{upload_eot}}\n                    </a>\n                </div>\n\n            </div>\n\n\t        <div class="bf-section-container font-otf">\n\t\t        <label for="custom-font-otf">{{font_otf}}</label>\n\n\t\t        <div class="input-section empty">\n\n\t\t\t        <input type="text" value="" id="custom-font-otf" name="otf" class="input font-url-input">\n\t\t\t        <a class="bf-media-upload-btn bssm-button  button button-primary button-small"\n\t\t\t           data-mediatitle="{{upload_otf}}"\n\t\t\t           data-mediasettings=\'{"type":["font/otf"]}\'\n\t\t\t           data-buttontext="{{upload_otf}}">\n\t\t\t\t        {{upload_otf}}\n\t\t\t        </a>\n\t\t        </div>\n\n\t        </div>\n        </div>\n\n        {{{bs_buttons}}}\n    </form>\n</div>');
        jquery__WEBPACK_IMPORTED_MODULE_2___default().bs_modal({
          styles: {
            container: 'max-width:555px'
          },
          template: 'upload-font',
          content: jquery__WEBPACK_IMPORTED_MODULE_2___default().extend(mainModal.options.content, {
            header: 'Upload Custom Font',
            icon: 'fa-upload'
          }),
          buttons: {
            add_font: {
              label: (_better_fonts_manager9 = better_fonts_manager_loc) === null || _better_fonts_manager9 === void 0 ? void 0 : (_better_fonts_manager10 = _better_fonts_manager9.labels) === null || _better_fonts_manager10 === void 0 ? void 0 : _better_fonts_manager10.add_font,
              type: 'primary',
              btn_classes: 'bssm-button add-font-btn disabled',
              clicked: function clicked() {
                /**
                 * Handle Add Custom Font Button
                 */
                handleAddCustomFontBtn(this);
              }
            }
          },
          events: {
            after_append_html: function after_append_html() {
              handleCustomFontInteractiveFields(this);
              catchWpMediaFrameStatus(this);
              overlayClickCloseModal(this);
            },
            handle_keyup: function handle_keyup(e, obj, _continue) {
              /**
               * Enter:
               *  e.which = 27
               */
              if (_continue && e.which === 27 && isWpMediaFrameClosed()) {
                wpMediaFrameClosed = false;
                return false;
              }

              return _continue;
            }
          }
        });
      }

      function moveFontUp($fontEL) {
        $fontEL.show();
        $fontEL.prependTo($fontEL.parent());
      }

      function scrollToTop($wrapper) {
        $wrapper.animate({
          scrollTop: 0
        }, 0);
      }

      var fontLoading = function fontLoading(ff) {
        jquery__WEBPACK_IMPORTED_MODULE_2___default()('.bssm-preview[data-font-family="' + ff + '"]', $context).css('font-size', '0');
      },
          fontLoaded = function fontLoaded(ff) {
        jquery__WEBPACK_IMPORTED_MODULE_2___default()('.bssm-preview[data-font-family="' + ff + '"]', $context).delay(40).queue(function (n) {
          jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).css('font-size', '22px').addClass('bs-animate bs-fadeInUp');
          n();
        });
      };

      function fetchFonts(elements) {
        var loadFonts = [],
            modal = this;
        elements.forEach(function (el) {
          var $font = modal.$(el);
          var id = $font.data('item-id'),
              type = $font.data('item-type');
          loadFonts[type] = loadFonts[type] || [];
          loadFonts[type].push(id);
          $font.find('.bssm-preview').data('font-family', id).attr('data-font-family', id).css('font-family', id);
        });

        for (var fontType in loadFonts) {
          self.loadFonts(fontType, loadFonts[fontType], fontLoading, fontLoaded);
        }
      }

      var modalObject;
      $context.on("click.typography", ".bf-font-selector", function () {
        var _better_fonts_manager11, _better_fonts_manager12, _better_fonts_manager13, _better_fonts_manager14, _better_fonts_manager15, _better_fonts_manager16, _better_fonts_manager17, _better_fonts_manager18;

        $fontSelectorEl = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this);
        var loc = jquery__WEBPACK_IMPORTED_MODULE_2___default().extend({
          header: ((_better_fonts_manager11 = better_fonts_manager_loc) === null || _better_fonts_manager11 === void 0 ? void 0 : (_better_fonts_manager12 = _better_fonts_manager11.labels) === null || _better_fonts_manager12 === void 0 ? void 0 : _better_fonts_manager12.choose_font) + ' {{btn}}'
        }, better_fonts_manager_loc.labels);
        modalObject = modalObject || jquery__WEBPACK_IMPORTED_MODULE_2___default().bs_selector_modal({
          id: 'font-selector-modal',
          modalClass: 'bssm-style-1',
          itemInnerHtml: '<div class="bssm-preview">\n    {{preview_text}}\n</div>\n<div class="bssm-info">\n    <span class="bssm-name">{{name}}</span>\n    <span class="bssm-type">{{type_label}}</span>\n    <span class="bssm-styles">{{styles}}</span>\n</div>',
          content: loc,
          items: fonts,
          itemsGroupSize: 9,
          categories: (_better_fonts_manager13 = better_fonts_manager_loc) === null || _better_fonts_manager13 === void 0 ? void 0 : (_better_fonts_manager14 = _better_fonts_manager13.labels) === null || _better_fonts_manager14 === void 0 ? void 0 : _better_fonts_manager14.filter_cats,
          types: (_better_fonts_manager15 = better_fonts_manager_loc) === null || _better_fonts_manager15 === void 0 ? void 0 : (_better_fonts_manager16 = _better_fonts_manager15.labels) === null || _better_fonts_manager16 === void 0 ? void 0 : _better_fonts_manager16.filter_types,
          inactivateTypes: ['google_ea_font'],
          events: {
            scrollIntoView: fetchFonts,
            after_append_html: function after_append_html() {// $context = this.bsModal.$modal;
            },
            item_select: function item_select(selected, itemId) {
              var modal = this;
              setTimeout(function () {
                modal.bsModal.close_modal();
              }, autoCloseDelay);
            },
            modal_closed: function modal_closed() {
              var selected = this.getSelectedItem();

              if (!selected) {
                return;
              }

              var selectedElement = selected[0],
                  selectedId = selected[1];
              moveFontUp(selectedElement);
              $fontSelectorEl.parent().find('.bf-font-family').val(selectedId);
              onChange(selectedId);
              $fontSelectorEl.text(selectedId);
              self.refresh_typography('family');
            },
            modal_show: function modal_show() {
              var selected = $fontSelectorEl.parent().find('.bf-font-family').val();
              var $selectedFont = this.selectItem(selected);

              if ($selectedFont.length) {
                this.markAsSelected($selectedFont);
                moveFontUp($selectedFont);
                scrollToTop($selectedFont.parent());
                fetchFonts.call(this, [$selectedFont[0]]);
              }
            }
          }
        }, {
          buttons: {
            upload_font: {
              label: (_better_fonts_manager17 = better_fonts_manager_loc) === null || _better_fonts_manager17 === void 0 ? void 0 : (_better_fonts_manager18 = _better_fonts_manager17.labels) === null || _better_fonts_manager18 === void 0 ? void 0 : _better_fonts_manager18.upload_font,
              type: 'primary',
              icon: 'upload',
              btn_classes: 'bssm-button',
              clicked: function clicked() {
                openCustomFontModal(this, modalObject);
              }
            }
          },
          events: {
            prepare_html: function prepare_html(el, options, templateName) {
              this.options.content.header = this.options.content.header.replace('{{btn}}', this.generate_buttons());
            }
          },
          initialZIndex: 15000
        });
        modalObject.show();
      });
    },

    /**
     * Setup Fonts Manager Panel
     ******************************************/
    setup_panel_fonts_manager: function setup_panel_fonts_manager() {
      // change all default fields font id
      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.bf-section[data-id=custom_fonts] .bf-repeater-item', $context).each(function (i) {
        var $this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this);
        var text = $this.find('.better-custom-fonts-id input').val();
        text = text.replace('%i%', i + 1);
        $this.find('.better-custom-fonts-id input').val(text);
      }); // change new fonts id

      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.bf-section[data-id=custom_fonts]', $context).on('repeater_item_added', function () {
        var $this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this);
        var count = $this.find('.bf-repeater-items-container').find('>*').size();
        var text = $this.find('.bf-repeater-item:last-child .better-custom-fonts-id input').val();
        text = text.replace('%i%', count);
        $this.find('.bf-repeater-item:last-child .better-custom-fonts-id input').val(text);
      });
    },
    setup_color: function setup_color() {
      var $colorWrapper = jquery__WEBPACK_IMPORTED_MODULE_2___default()(".bs-color-picker-wrapper", $context);

      if ($colorWrapper.length) {
        var color = new _Color_script__WEBPACK_IMPORTED_MODULE_4__["default"]();
        color.init($colorWrapper);
      }
    },
    setup_switch: function setup_switch() {
      var $colorWrapper = jquery__WEBPACK_IMPORTED_MODULE_2___default()(".bf-switch", $context);

      if ($colorWrapper.length) {
        var sw = new _SwitchControl_script__WEBPACK_IMPORTED_MODULE_5__["default"]();
        sw.init($colorWrapper);
      }
    },
    load_fonts: function load_fonts() {
      if (Object.keys(Better_Fonts_Manager.load_google_fonts).length > 0) {
        WebFont.load({
          google: {
            families: Object.keys(Better_Fonts_Manager.load_google_fonts).map(function (key) {
              return Better_Fonts_Manager.load_google_fonts[key];
            })
          }
        });
      }

      if (Object.keys(Better_Fonts_Manager.load_custom_fonts).length > 0) {
        jquery__WEBPACK_IMPORTED_MODULE_2___default().each(Better_Fonts_Manager.load_custom_fonts, function (key, value) {
          WebFont.load({
            custom: value
          });
        });
      }

      if (Object.keys(Better_Fonts_Manager.load_google_ea_fonts).length > 0) {
        jquery__WEBPACK_IMPORTED_MODULE_2___default().each(Better_Fonts_Manager.load_google_ea_fonts, function (key, value) {
          WebFont.load({
            custom: value
          });
        });
      }

      if (Object.keys(Better_Fonts_Manager.load_theme_fonts).length > 0) {
        jquery__WEBPACK_IMPORTED_MODULE_2___default().each(Better_Fonts_Manager.load_theme_fonts, function (key, value) {
          WebFont.load({
            custom: value
          });
        });
      }
    },

    /**
     * Setup Typography Field
     ******************************************/
    setup_field_typography: function setup_field_typography() {
      // Init preview in page load
      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.bs-control-typography', $context).each(function () {
        Better_Fonts_Manager.refresh_typography('first-time');
      }); // Prepare active field in page load

      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.bs-control-typography .typo-enable-container input[type=hidden]', $context).each(function () {
        var $this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this);
        $this.closest(".bs-control-typography").addClass('have-enable-field');

        if ($this.attr("value") === '1') {
          $this.closest(".bs-control-typography").addClass('enable-field');
        } else {
          $this.closest(".bs-control-typography").addClass('disable-field');
        }
      }); // Active field on change

      jquery__WEBPACK_IMPORTED_MODULE_2___default()(".bs-control-typography .typo-enable-container .cb-enable", $context).click(function () {
        jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).closest(".bs-control-typography").addClass('enable-field').removeClass('disable-field');
      });
      jquery__WEBPACK_IMPORTED_MODULE_2___default()(".bs-control-typography .typo-enable-container .cb-disable", $context).click(function () {
        jquery__WEBPACK_IMPORTED_MODULE_2___default()(this).closest(".bs-control-typography").addClass('disable-field').removeClass('enable-field');
      }); // When Font Variant Changes

      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.font-variants', $context).on('change', function (evt, params) {
        Better_Fonts_Manager.refresh_typography('variant');
      }); // When Font Size Changes

      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.font-size', $context).on('change', function (evt, params) {
        Better_Fonts_Manager.refresh_typography('size');
      }); // When Line Height Changes

      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.line-height', $context).on('change', function (evt, params) {
        Better_Fonts_Manager.refresh_typography('height');
      }); // When Letter Spacing Changes

      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.letter-spacing', $context).on('change', function (evt, params) {
        Better_Fonts_Manager.refresh_typography('letter-spacing');
      }); // When Align Changes

      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.text-align-container select', $context).on('change', function (evt, params) {
        Better_Fonts_Manager.refresh_typography('align');
      }); // When Color Changes

      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.text-color-container .bf-color-picker', $context).on('change', function (evt, params) {
        Better_Fonts_Manager.refresh_typography('color');
      }); // When Transform Changes

      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.text-transform', $context).on('change', function (evt, params) {
        Better_Fonts_Manager.refresh_typography('transform');
      }); // Preview Tab

      jquery__WEBPACK_IMPORTED_MODULE_2___default()('.typography-preview .preview-tab .tab', $context).on('click', function () {
        var $this = jquery__WEBPACK_IMPORTED_MODULE_2___default()(this);

        if ($this.hasClass('current')) {
          return false;
        }

        $this.closest('.preview-tab').find('.current').removeClass('current');
        $this.closest('.typography-preview').find('.preview-text.current').removeClass('current');
        $this.addClass('current');
        $this.closest('.typography-preview').find('.preview-text.' + $this.data('tab')).addClass('current');
      });
    },
    // Used for refreshing typography preview
    refresh_typography_field: function refresh_typography_field(type, _css, first_time) {
      switch (type) {
        case 'size':
          _css.fontSize = $context.find('.font-size').val() + 'px';
          break;

        case 'height':
          if ($context.find('.line-height').val() != '') _css.lineHeight = $context.find('.line-height').val() + 'px';else delete _css.lineHeight;
          break;

        case 'letter-spacing':
          if ($context.find('.letter-spacing').val() != '') _css.letterSpacing = $context.find('.letter-spacing').val();else delete _css.letterSpacing;
          break;

        case 'align':
          _css.textAlign = $context.find('.text-align-container select option:selected').val();
          break;

        case 'color':
          _css.color = $context.find('.text-color-container .bf-color-picker').val();
          break;

        case 'transform':
          _css.textTransform = $context.find('.text-transform').val();
          break;

        case 'family':
          _css.fontFamily = "'" + this.getSelectedFontId() + "', sans-serif";
          break;

        case 'variant':
          var variant = $context.find('.font-variants option:selected').val();
          if (typeof variant == 'undefined') break;

          if (variant.match(/([a-zA-Z].*)/i) != null) {
            var style = variant.match(/([a-zA-Z].*)/i);
            if (style[0] == 'regular') _css.fontStyle = 'normal';else _css.fontStyle = style[0];
          } else {
            delete _css.fontStyle;
          }

          if (variant.match(/\d*(\s*)/i) != null) {
            var size = variant.match(/\d*(\s*)/i);
            _css.fontWeight = size[0];
          } else {
            delete _css.fontWeight;
          }

          break;

        case 'load-font':
          var selected_font_id = this.getSelectedFontId(),
              selected_font = Better_Fonts_Manager.get_font(selected_font_id),
              selected_variant = $context.find('.font-variants option:selected').val();

          switch (selected_font.type) {
            case 'google-font':
              if (first_time == 'first-time') {
                Better_Fonts_Manager.load_google_fonts[selected_font_id + ':' + selected_variant] = selected_font_id + ':' + selected_variant;
              } else {
                WebFont.load({
                  google: {
                    families: [selected_font_id + ':' + selected_variant]
                  }
                });
              }

              _css.fontFamily = "'" + selected_font_id + "', sans-serif";
              break;

            case 'google-ea-font':
              _css.fontFamily = "'" + selected_font_id + "', sans-serif";

              if (first_time == 'first-time') {
                var _better_fonts_manager19;

                Better_Fonts_Manager.load_google_ea_fonts[selected_font_id] = {
                  families: [selected_font_id],
                  urls: [((_better_fonts_manager19 = better_fonts_manager_loc) === null || _better_fonts_manager19 === void 0 ? void 0 : _better_fonts_manager19.admin_fonts_css_url) + '&' + 'google_ea_font_id=' + selected_font_id]
                };
              } else {
                if (typeof Better_Fonts_Manager.load_google_ea_fonts[selected_font_id] == "undefined") {
                  var _better_fonts_manager20, _better_fonts_manager21;

                  WebFont.load({
                    custom: {
                      families: [selected_font_id],
                      urls: [((_better_fonts_manager20 = better_fonts_manager_loc) === null || _better_fonts_manager20 === void 0 ? void 0 : _better_fonts_manager20.admin_fonts_css_url) + '&' + 'google_ea_font_id=' + selected_font_id]
                    }
                  });
                  Better_Fonts_Manager.load_google_ea_fonts[selected_font_id] = {
                    families: [selected_font_id],
                    urls: [((_better_fonts_manager21 = better_fonts_manager_loc) === null || _better_fonts_manager21 === void 0 ? void 0 : _better_fonts_manager21.admin_fonts_css_url) + '&' + 'google_ea_font_id=' + selected_font_id]
                  };
                }
              }

              break;

            case 'custom-font':
              _css.fontFamily = "'" + selected_font_id + "', sans-serif";

              if (first_time == 'first-time') {
                var _better_fonts_manager22;

                Better_Fonts_Manager.load_custom_fonts[selected_font_id] = {
                  families: [selected_font_id],
                  urls: [((_better_fonts_manager22 = better_fonts_manager_loc) === null || _better_fonts_manager22 === void 0 ? void 0 : _better_fonts_manager22.admin_fonts_css_url) + '&' + 'custom_font_id=' + selected_font_id]
                };
              } else {
                if (typeof Better_Fonts_Manager.load_custom_fonts[selected_font_id] == "undefined") {
                  var _better_fonts_manager23, _better_fonts_manager24;

                  WebFont.load({
                    custom: {
                      families: [selected_font_id],
                      urls: [((_better_fonts_manager23 = better_fonts_manager_loc) === null || _better_fonts_manager23 === void 0 ? void 0 : _better_fonts_manager23.admin_fonts_css_url) + '&' + 'custom_font_id=' + selected_font_id]
                    }
                  });
                  Better_Fonts_Manager.load_custom_fonts[selected_font_id] = {
                    families: [selected_font_id],
                    urls: [((_better_fonts_manager24 = better_fonts_manager_loc) === null || _better_fonts_manager24 === void 0 ? void 0 : _better_fonts_manager24.admin_fonts_css_url) + '&' + 'custom_font_id=' + selected_font_id]
                  };
                }
              }

              break;

            case 'theme-font':
              _css.fontFamily = "'" + selected_font_id + "', sans-serif";

              if (first_time == 'first-time') {
                var _better_fonts_manager25;

                Better_Fonts_Manager.load_theme_fonts[selected_font_id] = {
                  families: [selected_font_id],
                  urls: [((_better_fonts_manager25 = better_fonts_manager_loc) === null || _better_fonts_manager25 === void 0 ? void 0 : _better_fonts_manager25.admin_fonts_css_url) + '&' + 'theme_font_id=' + selected_font_id]
                };
              } else {
                if (typeof Better_Fonts_Manager.load_theme_fonts[selected_font_id] == "undefined") {
                  var _better_fonts_manager26, _better_fonts_manager27;

                  WebFont.load({
                    custom: {
                      families: [selected_font_id],
                      urls: [((_better_fonts_manager26 = better_fonts_manager_loc) === null || _better_fonts_manager26 === void 0 ? void 0 : _better_fonts_manager26.admin_fonts_css_url) + '&' + 'theme_font_id=' + selected_font_id]
                    }
                  });
                  Better_Fonts_Manager.load_theme_fonts[selected_font_id] = {
                    families: [selected_font_id],
                    urls: [((_better_fonts_manager27 = better_fonts_manager_loc) === null || _better_fonts_manager27 === void 0 ? void 0 : _better_fonts_manager27.admin_fonts_css_url) + '&' + 'theme_font_id=' + selected_font_id]
                  };
                }
              }

              break;

            case 'font-stack':
              _css.fontFamily = "'" + selected_font_id + "', sans-serif";
              break;
          }

      }

      return _css;
    },
    getSelectedFontId: function getSelectedFontId() {
      return jquery__WEBPACK_IMPORTED_MODULE_2___default()('.bf-font-family', $context).val();
    },
    // Used for refreshing all styles of typography field
    refresh_typography: function refresh_typography(type) {
      type = typeof type !== 'undefined' ? type : 'all';
      var $preview = $context.find('.typography-preview .preview-text');

      var _css = $preview.css(["fontSize", "lineHeight", "textAlign", "fontFamily", "fontStyle", "fontWeight", "textTransform", "color", "letterSpacing"]) || {};

      switch (type) {
        case 'size':
          _css = Better_Fonts_Manager.refresh_typography_field('size', _css);
          break;

        case 'height':
          _css = Better_Fonts_Manager.refresh_typography_field('height', _css);
          break;

        case 'letter-spacing':
          _css = Better_Fonts_Manager.refresh_typography_field('letter-spacing', _css);
          break;

        case 'transform':
          _css = Better_Fonts_Manager.refresh_typography_field('transform', _css);
          break;

        case 'align':
          _css = Better_Fonts_Manager.refresh_typography_field('align', _css);
          break;

        case 'color':
          _css = Better_Fonts_Manager.refresh_typography_field('color', _css);
          break;

        case 'variant':
          _css = Better_Fonts_Manager.refresh_typography_field('variant', _css);
          var selected_font_id = this.getSelectedFontId(),
              selected_font = Better_Fonts_Manager.get_font(selected_font_id),
              selected_variant = $context.find('.font-variants option:selected').val();
          if (selected_variant == 'regular') selected_variant = '';else selected_variant = ':' + selected_variant;

          switch (selected_font.type) {
            case 'google-font':
              // load new font
              WebFont.load({
                google: {
                  families: [selected_font_id + selected_variant]
                }
              });
              _css.fontFamily = "'" + selected_font_id + "', sans-serif";
              break;

            case 'theme-font':
            case 'custom-font':
            case 'font-stack':
              _css.fontFamily = "'" + selected_font_id + "', sans-serif";
              break;
          }

          break;

        case 'family':
          var selected_font_id = this.getSelectedFontId(),
              selected_font = Better_Fonts_Manager.get_font(selected_font_id),
              selected_font_variants = Better_Fonts_Manager.get_font_variants(selected_font),
              selected_font_subsets = Better_Fonts_Manager.get_font_subsets(selected_font); // load and adds variants

          $context.find('.font-variants option').remove();
          var selected = false,
              _selected = ''; // generate variant options

          jquery__WEBPACK_IMPORTED_MODULE_2___default().each(selected_font_variants, function (index, element) {
            if (element['id'] == '400' || element['id'] == 'regular') {
              selected = element['id'];
            }

            $context.find('.font-variants').append('<option value="' + element['id'] + '" ' + (element['id'] == selected ? ' selected' : '') + '>' + element['name'] + '</option>');
          }); // select first if 400 is not available in font variants

          if (selected == false) $context.find('.font-variants option:first-child').attr('selected', 'selected'); // load and adds subsets

          $context.find('.font-subsets option').remove(); // generate subset options

          jquery__WEBPACK_IMPORTED_MODULE_2___default().each(selected_font_subsets, function (index, element) {
            // select latin as default subset
            if (element['id'] == 'latin' || element['id'] == 'unknown') {
              $context.find('.font-subsets').append('<option value="' + element['id'] + '" selected>' + element['name'] + '</option>');
            } else {
              $context.find('.font-subsets').append('<option value="' + element['id'] + '">' + element['name'] + '</option>');
            }
          });
          _css = Better_Fonts_Manager.refresh_typography_field('load-font', _css);
          _css = Better_Fonts_Manager.refresh_typography_field('variant', _css);
          _css = Better_Fonts_Manager.refresh_typography_field('family', _css);
          break;

        case 'first-time':
          $context.find('.load-preview-texts').remove();
          _css = Better_Fonts_Manager.refresh_typography_field('load-font', _css, 'first-time');
          _css = Better_Fonts_Manager.refresh_typography_field('family', _css);
          _css = Better_Fonts_Manager.refresh_typography_field('size', _css);
          _css = Better_Fonts_Manager.refresh_typography_field('height', _css);
          _css = Better_Fonts_Manager.refresh_typography_field('letter-spacing', _css);
          _css = Better_Fonts_Manager.refresh_typography_field('transform', _css);
          _css = Better_Fonts_Manager.refresh_typography_field('align', _css);
          _css = Better_Fonts_Manager.refresh_typography_field('variant', _css);
          $context.find('.typography-preview').css('display', 'block');
      }

      delete _css.lineHeight;
      $preview.attr('style', '');
      $preview.css(_css);
    },
    // Used for getting font information's
    get_font: function get_font(font_id) {
      var _better_fonts_manager28, _better_fonts_manager29, _better_fonts_manager32, _better_fonts_manager33, _better_fonts_manager36, _better_fonts_manager37, _better_fonts_manager40, _better_fonts_manager41, _better_fonts_manager44, _better_fonts_manager45;

      // Custom Fonts
      if (typeof ((_better_fonts_manager28 = better_fonts_manager_loc) === null || _better_fonts_manager28 === void 0 ? void 0 : (_better_fonts_manager29 = _better_fonts_manager28.fonts) === null || _better_fonts_manager29 === void 0 ? void 0 : _better_fonts_manager29.theme_fonts[font_id]) != "undefined") {
        var _better_fonts_manager30, _better_fonts_manager31;

        return (_better_fonts_manager30 = better_fonts_manager_loc) === null || _better_fonts_manager30 === void 0 ? void 0 : (_better_fonts_manager31 = _better_fonts_manager30.fonts) === null || _better_fonts_manager31 === void 0 ? void 0 : _better_fonts_manager31.theme_fonts[font_id];
      } // Custom Fonts
      else if (typeof ((_better_fonts_manager32 = better_fonts_manager_loc) === null || _better_fonts_manager32 === void 0 ? void 0 : (_better_fonts_manager33 = _better_fonts_manager32.fonts) === null || _better_fonts_manager33 === void 0 ? void 0 : _better_fonts_manager33.font_stacks[font_id]) != "undefined") {
        var _better_fonts_manager34, _better_fonts_manager35;

        return (_better_fonts_manager34 = better_fonts_manager_loc) === null || _better_fonts_manager34 === void 0 ? void 0 : (_better_fonts_manager35 = _better_fonts_manager34.fonts) === null || _better_fonts_manager35 === void 0 ? void 0 : _better_fonts_manager35.font_stacks[font_id];
      } // Font Stacks
      else if (typeof ((_better_fonts_manager36 = better_fonts_manager_loc) === null || _better_fonts_manager36 === void 0 ? void 0 : (_better_fonts_manager37 = _better_fonts_manager36.fonts) === null || _better_fonts_manager37 === void 0 ? void 0 : _better_fonts_manager37.custom_fonts[font_id]) != "undefined") {
        var _better_fonts_manager38, _better_fonts_manager39;

        return (_better_fonts_manager38 = better_fonts_manager_loc) === null || _better_fonts_manager38 === void 0 ? void 0 : (_better_fonts_manager39 = _better_fonts_manager38.fonts) === null || _better_fonts_manager39 === void 0 ? void 0 : _better_fonts_manager39.custom_fonts[font_id];
      } // Google Fonts
      else if (typeof ((_better_fonts_manager40 = better_fonts_manager_loc) === null || _better_fonts_manager40 === void 0 ? void 0 : (_better_fonts_manager41 = _better_fonts_manager40.fonts) === null || _better_fonts_manager41 === void 0 ? void 0 : _better_fonts_manager41.google_fonts[font_id]) != "undefined") {
        var _better_fonts_manager42, _better_fonts_manager43;

        return (_better_fonts_manager42 = better_fonts_manager_loc) === null || _better_fonts_manager42 === void 0 ? void 0 : (_better_fonts_manager43 = _better_fonts_manager42.fonts) === null || _better_fonts_manager43 === void 0 ? void 0 : _better_fonts_manager43.google_fonts[font_id];
      } // Google EA Fonts
      else if (typeof ((_better_fonts_manager44 = better_fonts_manager_loc) === null || _better_fonts_manager44 === void 0 ? void 0 : (_better_fonts_manager45 = _better_fonts_manager44.fonts) === null || _better_fonts_manager45 === void 0 ? void 0 : _better_fonts_manager45.google_ea_fonts[font_id]) != "undefined") {
        var _better_fonts_manager46, _better_fonts_manager47;

        return (_better_fonts_manager46 = better_fonts_manager_loc) === null || _better_fonts_manager46 === void 0 ? void 0 : (_better_fonts_manager47 = _better_fonts_manager46.fonts) === null || _better_fonts_manager47 === void 0 ? void 0 : _better_fonts_manager47.google_ea_fonts[font_id];
      }

      return false;
    },
    // full list of default variants array
    get_default_variants: function get_default_variants() {
      var _better_fonts_manager48, _better_fonts_manager49, _better_fonts_manager50, _better_fonts_manager51, _better_fonts_manager52, _better_fonts_manager53, _better_fonts_manager54, _better_fonts_manager55, _better_fonts_manager56, _better_fonts_manager57, _better_fonts_manager58, _better_fonts_manager59, _better_fonts_manager60, _better_fonts_manager61, _better_fonts_manager62, _better_fonts_manager63, _better_fonts_manager64, _better_fonts_manager65, _better_fonts_manager66, _better_fonts_manager67, _better_fonts_manager68, _better_fonts_manager69, _better_fonts_manager70, _better_fonts_manager71;

      return [{
        "id": "100",
        "name": (_better_fonts_manager48 = better_fonts_manager_loc) === null || _better_fonts_manager48 === void 0 ? void 0 : (_better_fonts_manager49 = _better_fonts_manager48.texts) === null || _better_fonts_manager49 === void 0 ? void 0 : _better_fonts_manager49.variant_100
      }, {
        "id": "300",
        "name": (_better_fonts_manager50 = better_fonts_manager_loc) === null || _better_fonts_manager50 === void 0 ? void 0 : (_better_fonts_manager51 = _better_fonts_manager50.texts) === null || _better_fonts_manager51 === void 0 ? void 0 : _better_fonts_manager51.variant_300
      }, {
        "id": "400",
        "name": (_better_fonts_manager52 = better_fonts_manager_loc) === null || _better_fonts_manager52 === void 0 ? void 0 : (_better_fonts_manager53 = _better_fonts_manager52.texts) === null || _better_fonts_manager53 === void 0 ? void 0 : _better_fonts_manager53.variant_400
      }, {
        "id": "500",
        "name": (_better_fonts_manager54 = better_fonts_manager_loc) === null || _better_fonts_manager54 === void 0 ? void 0 : (_better_fonts_manager55 = _better_fonts_manager54.texts) === null || _better_fonts_manager55 === void 0 ? void 0 : _better_fonts_manager55.variant_500
      }, {
        "id": "700",
        "name": (_better_fonts_manager56 = better_fonts_manager_loc) === null || _better_fonts_manager56 === void 0 ? void 0 : (_better_fonts_manager57 = _better_fonts_manager56.texts) === null || _better_fonts_manager57 === void 0 ? void 0 : _better_fonts_manager57.variant_700
      }, {
        "id": "900",
        "name": (_better_fonts_manager58 = better_fonts_manager_loc) === null || _better_fonts_manager58 === void 0 ? void 0 : (_better_fonts_manager59 = _better_fonts_manager58.texts) === null || _better_fonts_manager59 === void 0 ? void 0 : _better_fonts_manager59.variant_900
      }, {
        "id": "100italic",
        "name": (_better_fonts_manager60 = better_fonts_manager_loc) === null || _better_fonts_manager60 === void 0 ? void 0 : (_better_fonts_manager61 = _better_fonts_manager60.texts) === null || _better_fonts_manager61 === void 0 ? void 0 : _better_fonts_manager61.variant_100italic
      }, {
        "id": "300italic",
        "name": (_better_fonts_manager62 = better_fonts_manager_loc) === null || _better_fonts_manager62 === void 0 ? void 0 : (_better_fonts_manager63 = _better_fonts_manager62.texts) === null || _better_fonts_manager63 === void 0 ? void 0 : _better_fonts_manager63.variant_300italic
      }, {
        "id": "400italic",
        "name": (_better_fonts_manager64 = better_fonts_manager_loc) === null || _better_fonts_manager64 === void 0 ? void 0 : (_better_fonts_manager65 = _better_fonts_manager64.texts) === null || _better_fonts_manager65 === void 0 ? void 0 : _better_fonts_manager65.variant_400italic
      }, {
        "id": "500italic",
        "name": (_better_fonts_manager66 = better_fonts_manager_loc) === null || _better_fonts_manager66 === void 0 ? void 0 : (_better_fonts_manager67 = _better_fonts_manager66.texts) === null || _better_fonts_manager67 === void 0 ? void 0 : _better_fonts_manager67.variant_500italic
      }, {
        "id": "700italic",
        "name": (_better_fonts_manager68 = better_fonts_manager_loc) === null || _better_fonts_manager68 === void 0 ? void 0 : (_better_fonts_manager69 = _better_fonts_manager68.texts) === null || _better_fonts_manager69 === void 0 ? void 0 : _better_fonts_manager69.variant_700italic
      }, {
        "id": "900italic",
        "name": (_better_fonts_manager70 = better_fonts_manager_loc) === null || _better_fonts_manager70 === void 0 ? void 0 : (_better_fonts_manager71 = _better_fonts_manager70.texts) === null || _better_fonts_manager71 === void 0 ? void 0 : _better_fonts_manager71.variant_900italic
      }];
    },
    // Used for font variants
    get_font_variants: function get_font_variants(font) {
      // load font if font name is input
      if (typeof font == 'string') {
        font = Better_Fonts_Manager.get_font(font);

        if (font == false) {
          return Better_Fonts_Manager.get_default_variants();
        }
      }

      switch (font.type) {
        case 'google-ea-font':
        case 'google-font':
          return font.variants;
          break;

        case 'theme-font':
        case 'font-stack':
        case 'custom-font':
          return Better_Fonts_Manager.get_default_variants();
          break;
      }

      return false;
    },
    // Used for font variants
    get_font_subsets: function get_font_subsets(font) {
      var _better_fonts_manager74, _better_fonts_manager75;

      // load font if font name is input
      if (typeof font == 'string') {
        font = Better_Fonts_Manager.get_font(font);

        if (font == false) {
          var _better_fonts_manager72, _better_fonts_manager73;

          return [{
            "id": "unknown",
            "name": (_better_fonts_manager72 = better_fonts_manager_loc) === null || _better_fonts_manager72 === void 0 ? void 0 : (_better_fonts_manager73 = _better_fonts_manager72.texts) === null || _better_fonts_manager73 === void 0 ? void 0 : _better_fonts_manager73.subset_unknown
          }];
        }
      }

      switch (font.type) {
        case 'google-font':
          return font.subsets;
          break;

        case 'google-ea-font':
        case 'theme-font':
        case 'font-stack':
        case 'custom-font':
          return [{
            "id": "unknown",
            "name": (_better_fonts_manager74 = better_fonts_manager_loc) === null || _better_fonts_manager74 === void 0 ? void 0 : (_better_fonts_manager75 = _better_fonts_manager74.texts) === null || _better_fonts_manager75 === void 0 ? void 0 : _better_fonts_manager75.subset_unknown
          }];
          break;
      }

      return false;
    }
  };
  _js_UI__WEBPACK_IMPORTED_MODULE_1__["default"].block($context);
  (0,_js_AjaxRequest__WEBPACK_IMPORTED_MODULE_0__.load_data)("typography", {
    action: "loc"
  }, "typography.loc").then(function (response) {
    try {
      _js_UI__WEBPACK_IMPORTED_MODULE_1__["default"].unblock($context);
      better_fonts_manager_loc = response || {};
      Better_Fonts_Manager.init();
    } catch (error) {
      console.error(error);
    }
  });
  return {
    $context: $context
  };
}

/***/ }),

/***/ "./src/Typography/script.js":
/*!**********************************!*\
  !*** ./src/Typography/script.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../ControlBase */ "./ControlBase.js");
/* harmony import */ var _old__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./old */ "./src/Typography/old.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_ControlBase) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _ControlBase);

  var _super = _createSuper(_default);

  function _default() {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__["default"])((0,_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_2__["default"])(_this), "impl", void 0);

    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "controlType",
    value: function controlType() {
      return 'typography';
    }
  }, {
    key: "init",
    value: function init(element) {
      var _this2 = this;

      this.impl = (0,_old__WEBPACK_IMPORTED_MODULE_8__["default"])(element, function (value) {
        return _this2.onChange(value);
      });
      return true;
    }
  }, {
    key: "valueSet",
    value: function valueSet(value) {
      return false;
    }
  }, {
    key: "valueGet",
    value: function valueGet() {
      return {};
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.impl.$context.off("click.typography", ".bf-font-selector");
    }
  }, {
    key: "dataType",
    value: function dataType() {
      return 'object';
    }
  }]);

  return _default;
}(_ControlBase__WEBPACK_IMPORTED_MODULE_7__.ControlBase);



/***/ }),

/***/ "./src/functions.js":
/*!**************************!*\
  !*** ./src/functions.js ***!
  \**************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Init": function() { return /* binding */ Init; }
/* harmony export */ });
/* harmony import */ var _Features_script__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Features/script */ "./src/Features/script.js");

function Init(context) {
  context = context || document.body;
  (0,_Features_script__WEBPACK_IMPORTED_MODULE_0__.ProFeature)(context);
}

/***/ }),

/***/ "./src/types.js":
/*!**********************!*\
  !*** ./src/types.js ***!
  \**********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (['hr', 'text', 'info', 'code', 'media', 'color', 'radio', 'button', 'editor', 'custom', 'import', 'export', 'select', 'slider', 'switch', 'sorter', 'heading', 'repeater', 'textarea', 'checkbox', 'wp_editor', 'typography', 'ajax_action', 'ajax_select', 'icon_select', 'image_radio', 'media_image', 'term_select', 'image_upload', 'image_select', 'select_popup', 'image_preview', 'advance_select', 'sorter_checkbox', 'background_image']);

/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/***/ (function(module) {

module.exports = window["jQuery"];

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/***/ (function(module) {

module.exports = window["lodash"];

/***/ }),

/***/ "@babel/runtime/regenerator":
/*!*************************************!*\
  !*** external "regeneratorRuntime" ***!
  \*************************************/
/***/ (function(module) {

module.exports = window["regeneratorRuntime"];

/***/ }),

/***/ "@wordpress/autop":
/*!*******************************!*\
  !*** external ["wp","autop"] ***!
  \*******************************/
/***/ (function(module) {

module.exports = window["wp"]["autop"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js ***!
  \**************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _assertThisInitialized; }
/* harmony export */ });
function _assertThisInitialized(self) {
  if (self === void 0) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }

  return self;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _asyncToGenerator; }
/* harmony export */ });
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) {
  try {
    var info = gen[key](arg);
    var value = info.value;
  } catch (error) {
    reject(error);
    return;
  }

  if (info.done) {
    resolve(value);
  } else {
    Promise.resolve(value).then(_next, _throw);
  }
}

function _asyncToGenerator(fn) {
  return function () {
    var self = this,
        args = arguments;
    return new Promise(function (resolve, reject) {
      var gen = fn.apply(self, args);

      function _next(value) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value);
      }

      function _throw(err) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err);
      }

      _next(undefined);
    });
  };
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/classCallCheck.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _classCallCheck; }
/* harmony export */ });
function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/createClass.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/createClass.js ***!
  \****************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _createClass; }
/* harmony export */ });
function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  Object.defineProperty(Constructor, "prototype", {
    writable: false
  });
  return Constructor;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/defineProperty.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _defineProperty; }
/* harmony export */ });
function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _getPrototypeOf; }
/* harmony export */ });
function _getPrototypeOf(o) {
  _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
    return o.__proto__ || Object.getPrototypeOf(o);
  };
  return _getPrototypeOf(o);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/inherits.js":
/*!*************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/inherits.js ***!
  \*************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _inherits; }
/* harmony export */ });
/* harmony import */ var _setPrototypeOf_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./setPrototypeOf.js */ "./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js");

function _inherits(subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function");
  }

  subClass.prototype = Object.create(superClass && superClass.prototype, {
    constructor: {
      value: subClass,
      writable: true,
      configurable: true
    }
  });
  Object.defineProperty(subClass, "prototype", {
    writable: false
  });
  if (superClass) (0,_setPrototypeOf_js__WEBPACK_IMPORTED_MODULE_0__["default"])(subClass, superClass);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js ***!
  \******************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _possibleConstructorReturn; }
/* harmony export */ });
/* harmony import */ var _typeof_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./typeof.js */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _assertThisInitialized_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./assertThisInitialized.js */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");


function _possibleConstructorReturn(self, call) {
  if (call && ((0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(call) === "object" || typeof call === "function")) {
    return call;
  } else if (call !== void 0) {
    throw new TypeError("Derived constructors may only return object or undefined");
  }

  return (0,_assertThisInitialized_js__WEBPACK_IMPORTED_MODULE_1__["default"])(self);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _setPrototypeOf; }
/* harmony export */ });
function _setPrototypeOf(o, p) {
  _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
    o.__proto__ = p;
    return o;
  };

  return _setPrototypeOf(o, p);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/typeof.js":
/*!***********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/typeof.js ***!
  \***********************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _typeof; }
/* harmony export */ });
function _typeof(obj) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) {
    return typeof obj;
  } : function (obj) {
    return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
  }, _typeof(obj);
}

/***/ })

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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!*************************!*\
  !*** ./src/controls.js ***!
  \*************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ControlBase": function() { return /* reexport safe */ _ControlBase__WEBPACK_IMPORTED_MODULE_30__.ControlBase; },
/* harmony export */   "Hooks": function() { return /* reexport safe */ _js_Hooks__WEBPACK_IMPORTED_MODULE_28__["default"]; },
/* harmony export */   "Init": function() { return /* reexport safe */ _functions__WEBPACK_IMPORTED_MODULE_31__.Init; },
/* harmony export */   "Instances": function() { return /* binding */ Instances; },
/* harmony export */   "Types": function() { return /* reexport safe */ _types__WEBPACK_IMPORTED_MODULE_29__["default"]; }
/* harmony export */ });
/* harmony import */ var _Code_script__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Code/script */ "./src/Code/script.js");
/* harmony import */ var _Color_script__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Color/script */ "./src/Color/script.js");
/* harmony import */ var _Text_script__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Text/script */ "./src/Text/script.js");
/* harmony import */ var _Media_script__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Media/script */ "./src/Media/script.js");
/* harmony import */ var _Select_script__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Select/script */ "./src/Select/script.js");
/* harmony import */ var _Slider_script__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./Slider/script */ "./src/Slider/script.js");
/* harmony import */ var _Export_script__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./Export/script */ "./src/Export/script.js");
/* harmony import */ var _Sorter_script__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./Sorter/script */ "./src/Sorter/script.js");
/* harmony import */ var _Custom_script__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./Custom/script */ "./src/Custom/script.js");
/* harmony import */ var _Radio_script__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./Radio/script */ "./src/Radio/script.js");
/* harmony import */ var _Editor_script__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./Editor/script */ "./src/Editor/script.js");
/* harmony import */ var _Textarea_script__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./Textarea/script */ "./src/Textarea/script.js");
/* harmony import */ var _Repeater_script__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./Repeater/script */ "./src/Repeater/script.js");
/* harmony import */ var _Typography_script__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./Typography/script */ "./src/Typography/script.js");
/* harmony import */ var _AjaxSelect_script__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./AjaxSelect/script */ "./src/AjaxSelect/script.js");
/* harmony import */ var _ImageRadio_script__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./ImageRadio/script */ "./src/ImageRadio/script.js");
/* harmony import */ var _TermSelect_script__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./TermSelect/script */ "./src/TermSelect/script.js");
/* harmony import */ var _AjaxAction_script__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./AjaxAction/script */ "./src/AjaxAction/script.js");
/* harmony import */ var _IconSelect_script__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./IconSelect/script */ "./src/IconSelect/script.js");
/* harmony import */ var _MediaImage_script__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./MediaImage/script */ "./src/MediaImage/script.js");
/* harmony import */ var _ImageSelect_script__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./ImageSelect/script */ "./src/ImageSelect/script.js");
/* harmony import */ var _SelectPopup_script__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ./SelectPopup/script */ "./src/SelectPopup/script.js");
/* harmony import */ var _AdvanceSelect_script__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ./AdvanceSelect/script */ "./src/AdvanceSelect/script.js");
/* harmony import */ var _Import_script__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ./Import/script */ "./src/Import/script.js");
/* harmony import */ var _SwitchControl_script__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! ./SwitchControl/script */ "./src/SwitchControl/script.js");
/* harmony import */ var _SorterCheckbox_script__WEBPACK_IMPORTED_MODULE_25__ = __webpack_require__(/*! ./SorterCheckbox/script */ "./src/SorterCheckbox/script.js");
/* harmony import */ var _BackgroundImage_script__WEBPACK_IMPORTED_MODULE_26__ = __webpack_require__(/*! ./BackgroundImage/script */ "./src/BackgroundImage/script.js");
/* harmony import */ var _Checkbox_script__WEBPACK_IMPORTED_MODULE_27__ = __webpack_require__(/*! ./Checkbox/script */ "./src/Checkbox/script.js");
/* harmony import */ var _js_Hooks__WEBPACK_IMPORTED_MODULE_28__ = __webpack_require__(/*! ../js/Hooks */ "./js/Hooks.js");
/* harmony import */ var _types__WEBPACK_IMPORTED_MODULE_29__ = __webpack_require__(/*! ./types */ "./src/types.js");
/* harmony import */ var _ControlBase__WEBPACK_IMPORTED_MODULE_30__ = __webpack_require__(/*! ../ControlBase */ "./ControlBase.js");
/* harmony import */ var _functions__WEBPACK_IMPORTED_MODULE_31__ = __webpack_require__(/*! ./functions */ "./src/functions.js");



























 //

 //




var Instances = {
  code: _Code_script__WEBPACK_IMPORTED_MODULE_0__["default"],
  media: _Media_script__WEBPACK_IMPORTED_MODULE_3__["default"],
  color: _Color_script__WEBPACK_IMPORTED_MODULE_1__["default"],
  text: _Text_script__WEBPACK_IMPORTED_MODULE_2__["default"],
  radio: _Radio_script__WEBPACK_IMPORTED_MODULE_9__["default"],
  select: _Select_script__WEBPACK_IMPORTED_MODULE_4__["default"],
  slider: _Slider_script__WEBPACK_IMPORTED_MODULE_5__["default"],
  sorter: _Sorter_script__WEBPACK_IMPORTED_MODULE_7__["default"],
  custom: _Custom_script__WEBPACK_IMPORTED_MODULE_8__["default"],
  editor: _Editor_script__WEBPACK_IMPORTED_MODULE_10__["default"],
  checkbox: _Checkbox_script__WEBPACK_IMPORTED_MODULE_27__["default"],
  textarea: _Textarea_script__WEBPACK_IMPORTED_MODULE_11__["default"],
  repeater: _Repeater_script__WEBPACK_IMPORTED_MODULE_12__["default"],
  typography: _Typography_script__WEBPACK_IMPORTED_MODULE_13__["default"],
  ajax_select: _AjaxSelect_script__WEBPACK_IMPORTED_MODULE_14__["default"],
  image_radio: _ImageRadio_script__WEBPACK_IMPORTED_MODULE_15__["default"],
  term_select: _TermSelect_script__WEBPACK_IMPORTED_MODULE_16__["default"],
  ajax_action: _AjaxAction_script__WEBPACK_IMPORTED_MODULE_17__["default"],
  media_image: _MediaImage_script__WEBPACK_IMPORTED_MODULE_19__["default"],
  icon_select: _IconSelect_script__WEBPACK_IMPORTED_MODULE_18__["default"],
  image_select: _ImageSelect_script__WEBPACK_IMPORTED_MODULE_20__["default"],
  select_popup: _SelectPopup_script__WEBPACK_IMPORTED_MODULE_21__["default"],
  advance_select: _AdvanceSelect_script__WEBPACK_IMPORTED_MODULE_22__["default"],
  background_image: _BackgroundImage_script__WEBPACK_IMPORTED_MODULE_26__["default"],
  sorter_checkbox: _SorterCheckbox_script__WEBPACK_IMPORTED_MODULE_25__["default"],
  "switch": _SwitchControl_script__WEBPACK_IMPORTED_MODULE_24__["default"],
  "export": _Export_script__WEBPACK_IMPORTED_MODULE_6__["default"],
  "import": _Import_script__WEBPACK_IMPORTED_MODULE_23__["default"]
};

}();
(BetterStudio = typeof BetterStudio === "undefined" ? {} : BetterStudio).Controls = __webpack_exports__;
/******/ })()
;
//# sourceMappingURL=controls-script.js.map