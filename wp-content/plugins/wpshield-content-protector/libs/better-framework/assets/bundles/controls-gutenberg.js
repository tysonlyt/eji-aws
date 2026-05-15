var BetterStudio;
/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/AdvanceSelect/templates/gutenberg.js":
/*!**************************************************!*\
  !*** ./src/AdvanceSelect/templates/gutenberg.js ***!
  \**************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ AdvanceSelect; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../Icon */ "./src/Icon.js");
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../utils */ "./src/utils.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_5__);






function AdvanceSelect(props) {
  var _props$value;

  var values = Array.isArray(props.value) ? props.value : ((_props$value = props.value) === null || _props$value === void 0 ? void 0 : _props$value.split(',')) || [];
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()("bf-advanced-select", props.container_class)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("ul", {
    className: "bf-advanced-select-group"
  }, Object.entries(props.options || {}).map(function (_ref) {
    var _option$icon, _option$badge, _option$badge2, _option$badge3, _option$badge3$icon, _option$badge4;

    var _ref2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref, 2),
        option_id = _ref2[0],
        option = _ref2[1];

    var active = values.indexOf(option_id) !== -1;
    var disable = option.disable || false;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("li", {
      key: option_id,
      className: classnames__WEBPACK_IMPORTED_MODULE_2___default()(option.classes, {
        active: active,
        disable: disable
      }),
      "data-value": option_id,
      style: (0,_utils__WEBPACK_IMPORTED_MODULE_4__.cssObject)(option.inline_styles)
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
      icon: (_option$icon = option.icon) === null || _option$icon === void 0 ? void 0 : _option$icon.icon,
      className: "icon"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", {
      className: "label",
      dangerouslySetInnerHTML: {
        __html: option.label
      }
    }), !lodash__WEBPACK_IMPORTED_MODULE_5___default().isEmpty(option.badge) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", {
      className: classnames__WEBPACK_IMPORTED_MODULE_2___default()("badge", (_option$badge = option.badge) === null || _option$badge === void 0 ? void 0 : _option$badge.classes),
      style: (0,_utils__WEBPACK_IMPORTED_MODULE_4__.cssObject)((_option$badge2 = option.badge) === null || _option$badge2 === void 0 ? void 0 : _option$badge2.inline_styles)
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
      icon: (_option$badge3 = option.badge) === null || _option$badge3 === void 0 ? void 0 : (_option$badge3$icon = _option$badge3.icon) === null || _option$badge3$icon === void 0 ? void 0 : _option$badge3$icon.icon,
      className: "icon"
    }), (_option$badge4 = option.badge) === null || _option$badge4 === void 0 ? void 0 : _option$badge4.label));
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("input", {
    type: "hidden",
    name: props.input_name,
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()(props.input_class, "value"),
    value: props.value
  }));
}

/***/ }),

/***/ "./src/AjaxAction/templates/gutenberg.js":
/*!***********************************************!*\
  !*** ./src/AjaxAction/templates/gutenberg.js ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ BF_Ajax_action; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);


function BF_Ajax_action(props) {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("bf-ajax_action-field-container", props.container_class)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("bf-action-button button bf-main-button", props['button-class']),
    "data-callback": props.callback,
    "data-token": props.token,
    "data-event": props['js-event'],
    "data-confirm": props.confirm,
    dangerouslySetInnerHTML: {
      __html: props['button-name']
    }
  }));
}

/***/ }),

/***/ "./src/AjaxSelect/templates/gutenberg.js":
/*!***********************************************!*\
  !*** ./src/AjaxSelect/templates/gutenberg.js ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _Icon__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../Icon */ "./src/Icon.js");







function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }





var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "shouldComponentUpdate",
    value: function shouldComponentUpdate() {
      return false;
    }
  }, {
    key: "render",
    value: function render() {
      var props = this.props;
      var LiValues = props.values ? props.values.map(function (v) {
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("li", {
          "data-id": v.id
        }, "#", v.label, "#", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("span", {
          className: "bf-icon del del-icon"
        }));
      }) : [];
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("bf-ajax_select-field-container", props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "bf-ajax_select-input-container"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "text",
        className: "bf-ajax-suggest-input",
        placeholder: props.placeholder
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("span", {
        className: "bf-search-loader"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)(_Icon__WEBPACK_IMPORTED_MODULE_7__["default"], {
        icon: "fa-search"
      }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "hidden",
        name: props.input_name,
        value: props.value,
        className: props.input_class,
        "data-callback": props.callback,
        "data-token": props.token
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("ul", {
        className: "bf-ajax-suggest-search-results"
      }, " "), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("ul", {
        className: "bf-ajax-suggest-controls"
      }, LiValues));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Component);



/***/ }),

/***/ "./src/BackgroundImage/templates/gutenberg.js":
/*!****************************************************!*\
  !*** ./src/BackgroundImage/templates/gutenberg.js ***!
  \****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _Icon__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../Icon */ "./src/Icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__);







function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }





/**
 * todo: add support for type param
 */

var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "shouldComponentUpdate",
    value: function shouldComponentUpdate() {
      return false;
    }
  }, {
    key: "render",
    value: function render() {
      var props = this.props;
      var imageUrl = '';

      if (props.value && props.value.img) {
        imageUrl = props.value.img;
      }

      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("bf-background-image-field", props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("a", {
        href: "#",
        className: "button button-primary bf-main-button bf-background-image-upload-btn",
        "data-mediatitle": props.mediaTitle,
        buttontext: props.buttonText
      }, " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)(_Icon__WEBPACK_IMPORTED_MODULE_7__["default"], {
        icon: "fa-upload"
      }), " ", props.uploadLabel || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__.__)('Upload', 'better-studio')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("a", {
        href: "#",
        className: "button bf-background-image-remove-btn"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)(_Icon__WEBPACK_IMPORTED_MODULE_7__["default"], {
        icon: "fa-remove"
      }), " ", props.removeLabel || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__.__)('Remove', 'better-studio')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "hidden",
        className: "bf-background-image-input " + props.input_class,
        value: imageUrl
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "bf-background-image-preview"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("img", {
        src: imageUrl
      })));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Component);



/***/ }),

/***/ "./src/Button/templates/gutenberg.js":
/*!*******************************************!*\
  !*** ./src/Button/templates/gutenberg.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ BF_Button; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);


function BF_Button(props) {
  var classesName = classnames__WEBPACK_IMPORTED_MODULE_1___default()("button", "button-primary", "bf-main-button", props['class-name']); // const customAttrs = (props.customAttrs||[]).map((attr)=>{
  //
  // 	return `${attr.key}="${attr.value}"`;
  //
  // }).join(" ");

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("bf-button-field-container", props.container_class)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: classesName,
    dangerouslySetInnerHTML: {
      __html: props.name
    }
  }));
}

/***/ }),

/***/ "./src/Checkbox/templates/gutenberg.js":
/*!*********************************************!*\
  !*** ./src/Checkbox/templates/gutenberg.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_8__);








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }





var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, [{
    key: "castObject",
    value: function castObject(something) {
      if (Array.isArray(something)) {
        // array to object
        return Object.assign({}, something);
      }

      return lodash__WEBPACK_IMPORTED_MODULE_8___default().isObject(something) ? something : {};
    }
  }, {
    key: "render",
    value: function render() {
      var props = this.props,
          id = props.id || ("id-" + Math.random()).replace('0.', '');
      var options = this.castObject(props.options);
      var values = lodash__WEBPACK_IMPORTED_MODULE_8___default().isObject(props.value) || Array.isArray(props.value) ? props.value : {};
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("bs-control-checkbox", props.container_class)
      }, Object.entries(options).map(function (_ref) {
        var _ref2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref, 2),
            key = _ref2[0],
            label = _ref2[1];

        var option_id = id + "-" + key;
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
          className: "bs-control-checkbox-option",
          key: key
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("input", {
          type: "checkbox",
          "data-key": key,
          id: option_id,
          className: props.input_name,
          value: key,
          defaultChecked: !lodash__WEBPACK_IMPORTED_MODULE_8___default().isEmpty(values[key])
        }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("label", {
          htmlFor: option_id
        }, label));
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.Component);



/***/ }),

/***/ "./src/Code/templates/gutenberg.js":
/*!*****************************************!*\
  !*** ./src/Code/templates/gutenberg.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ BF_Code; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_2__);



function BF_Code(params) {
  var language_attr;

  switch (params.language) {
    case 'javascript':
    case 'json':
    case 'js':
      language_attr = 'text/javascript';
      break;

    case 'php':
      language_attr = 'application/x-httpd-php';
      break;

    case 'css':
      language_attr = 'text/css';
      break;

    case 'sql':
      language_attr = 'text/x-sql';
      break;

    case 'xml':
    case 'html':
    default:
      language_attr = 'text/html';
      break;
  }

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("bs-control-code", params.container_class)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("textarea", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("bf-code-editor", params.input_class),
    "data-lang": language_attr,
    defaultValue: params.value,
    "data-line-numbers": !lodash__WEBPACK_IMPORTED_MODULE_2___default().isEmpty(params.line_numbers) ? "enable" : "disable",
    "data-auto-close-brackets": !lodash__WEBPACK_IMPORTED_MODULE_2___default().isEmpty(params.auto_close_brackets) ? "enable" : "disable",
    "data-auto-close-tags": !lodash__WEBPACK_IMPORTED_MODULE_2___default().isEmpty(params.auto_close_tags) ? "enable" : "disable",
    placeholder: params.placeholder
  }));
}

/***/ }),

/***/ "./src/Custom/templates/gutenberg.js":
/*!*******************************************!*\
  !*** ./src/Custom/templates/gutenberg.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);







function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "onChange",
    value: function onChange(event) {
      this.props.onChange && this.props.onChange(event.target.value);
    }
  }, {
    key: "render",
    value: function render() {
      var props = this.props;
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("bf-custom-field-wrapper", props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("form", {
        className: "bf-custom-field-view",
        "data-token": props.token,
        "data-callback": props.callback,
        "data-callback-args": JSON.stringify(props.callback_args)
      }, "Loading..."), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "hidden",
        className: "bf-custom-field-values",
        value: props.value,
        onChange: this.onChange.bind(this)
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Component);



/***/ }),

/***/ "./src/Editor/templates/gutenberg.js":
/*!*******************************************!*\
  !*** ./src/Editor/templates/gutenberg.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);







function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "shouldComponentUpdate",
    value: function shouldComponentUpdate() {
      return false;
    }
  }, {
    key: "render",
    value: function render() {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("bf-editor-wrapper", this.props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("pre", {
        className: "bf-editor",
        "data-lang": this.props.lang || "text",
        "data-max-lines": this.props["max-lines"] || 15,
        "data-min-lines": this.props["min-lines"] || 10
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("textarea", {
        className: "bf-editor-field",
        defaultValue: this.props.value
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Component);



/***/ }),

/***/ "./src/HR/gutenberg.js":
/*!*****************************!*\
  !*** ./src/HR/gutenberg.js ***!
  \*****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ BF_Hr; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

function BF_Hr() {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("hr", {
    className: "bf-hr"
  });
}

/***/ }),

/***/ "./src/Heading/templates/gutenberg.js":
/*!********************************************!*\
  !*** ./src/Heading/templates/gutenberg.js ***!
  \********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ BF_Heading; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);


function BF_Heading(props) {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("bf-section-container", "bf-clearfix", props.container_class)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("bf-section-heading", "bf-clearfix", props.layout || "style-1'")
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "bf-section-heading-title bf-clearfix"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", null, props.name || props.title)), props.desc && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "bf-section-heading-desc bf-clearfix",
    dangerouslySetInnerHTML: {
      __html: props.desc
    }
  }, " ")));
}

/***/ }),

/***/ "./src/Icon.js":
/*!*********************!*\
  !*** ./src/Icon.js ***!
  \*********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ Icon; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);



function Icon(props) {
  var _BetterStudio, _BetterStudio$Libs;

  if (!props.icon || !((_BetterStudio = BetterStudio) !== null && _BetterStudio !== void 0 && (_BetterStudio$Libs = _BetterStudio.Libs) !== null && _BetterStudio$Libs !== void 0 && _BetterStudio$Libs.icon_loader)) {
    return '';
  }

  var _useState = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)('?'),
      _useState2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState, 2),
      icon = _useState2[0],
      updateIcon = _useState2[1];

  try {
    BetterStudio.Libs.icon_loader(props, '', props).then(updateIcon);
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", {
      dangerouslySetInnerHTML: {
        __html: icon
      },
      className: props.className || ""
    });
  } catch (e) {
    // console.error(e);
    return 'E?';
  }
}

/***/ }),

/***/ "./src/IconSelect/templates/gutenberg.js":
/*!***********************************************!*\
  !*** ./src/IconSelect/templates/gutenberg.js ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);







function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "iconType",
    value: function iconType(icon) {
      if (!icon) {
        return '';
      }

      if (icon.substr(0, 3) == 'fa-') {
        return 'fontawesome';
      } // BetterStudio Font Icon
      else if (icon.substr(0, 5) == 'bsfi-' || icon.substr(0, 5) == 'bsai-') {
        return 'bs-icons';
      } // Dashicon
      else if (icon.substr(0, 10) == 'dashicons-') {
        return 'Dashicon';
      }

      return 'custom-icon';
    }
  }, {
    key: "iconTag",
    value: function iconTag(icon) {
      if (!icon) {
        return '';
      }

      icon = icon.toString();

      if (icon.substr(0, 3) == 'fa-') {
        return '<i class="bf-icon fa ' + icon + '"></i>';
      } // BetterStudio Font Icon
      else if (icon.substr(0, 5) == 'bsfi-') {
        return '<i class="bf-icon ' + icon + '"></i>';
      } // Dashicon
      else if (icon.substr(0, 10) == 'dashicons-') {
        return '<i class="bf-icon dashicons dashicons-' + icon + '"></i>';
      } // Better Studio Admin Icon
      else if (icon.substr(0, 5) == 'bsai-') {
        return '<i class="bf-icon ' + icon + '"></i>';
      } // Custom Icon -> as URL


      if (icon) return '<i class="bf-icon bf-custom-icon bf-custom-icon-url"><img src="' + icon + '"></i>';
      return '';
    }
  }, {
    key: "render",
    value: function render() {
      var props = this.props,
          value = props.value,
          label = (value === null || value === void 0 ? void 0 : value.label) || "";
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("bf-icon-modal-handler", props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "select-options"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("span", {
        className: "selected-option",
        dangerouslySetInnerHTML: {
          __html: props.icon_tag ? props.icon_tag + label : this.iconTag((value === null || value === void 0 ? void 0 : value.icon) || (value === null || value === void 0 ? void 0 : value.id)) + label
        }
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "hidden",
        className: "icon-input",
        defaultValue: value === null || value === void 0 ? void 0 : value.icon
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "hidden",
        className: "icon-input-font-code",
        defaultValue: value === null || value === void 0 ? void 0 : value.font_code
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "hidden",
        className: "icon-input-type",
        defaultValue: value === null || value === void 0 ? void 0 : value.type
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "hidden",
        className: "icon-input-height",
        defaultValue: value === null || value === void 0 ? void 0 : value.height
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "hidden",
        className: "icon-input-width",
        defaultValue: value === null || value === void 0 ? void 0 : value.width
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Component);



/***/ }),

/***/ "./src/ImagePreview/templates/gutenberg.js":
/*!*************************************************!*\
  !*** ./src/ImagePreview/templates/gutenberg.js ***!
  \*************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ BF_Image_Preview; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_3__);




function BF_Image_Preview(props) {
  if (lodash__WEBPACK_IMPORTED_MODULE_3___default().isEmpty(props.value)) {
    var value = props.std || {};
  } else {
    var value = props.value || {};
  }

  if (typeof value === "string") {
    value = [value];
  }

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()("bs-control-image-preview", props.container_class)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    className: "info-value",
    style: {
      textAlign: props.align
    }
  }, Object.entries(value).map(function (_ref) {
    var _ref2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref, 2),
        key = _ref2[0],
        image = _ref2[1];

    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("img", {
      src: image,
      className: 'image-' + key,
      key: key
    });
  })));
}

/***/ }),

/***/ "./src/ImageRadio/templates/gutenberg.js":
/*!***********************************************!*\
  !*** ./src/ImageRadio/templates/gutenberg.js ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, [{
    key: "render",
    value: function render() {
      var props = this.props;
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("bs-control-image-radio", props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("input", {
        type: "hidden",
        value: props.value,
        className: "image-radio-value"
      }), Object.entries(props.options || {}).map(function (_ref) {
        var _ref2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref, 2),
            key = _ref2[0],
            item = _ref2[1];

        var ID = item.id || key;
        var checked = props.value === key ? "checked" : "";
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
          className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("bf-image-radio-option", checked),
          key: ID,
          "data-id": ID
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("label", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("img", {
          src: item.img,
          alt: item.label,
          className: item["class"]
        }), item.label && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("p", {
          className: "item-label"
        }, item.label)));
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.Component);



/***/ }),

/***/ "./src/ImageSelect/templates/gutenberg.js":
/*!************************************************!*\
  !*** ./src/ImageSelect/templates/gutenberg.js ***!
  \************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__);








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }





var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, [{
    key: "render",
    value: function render() {
      var _this$props = this.props,
          value = _this$props.value,
          options = _this$props.options,
          default_text = _this$props.default_text,
          input_class = _this$props.input_class;
      var current = {
        key: "",
        label: default_text || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__.__)('chose one...', 'better-studio'),
        img: ""
      };

      if (!lodash.isEmpty(value)) {
        if (options && lodash.isObject(options[value])) {
          current = options[value];
          current.key = value;
        }
      }

      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("better-select-image", this.props.input_name, this.props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
        className: "select-options"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("span", {
        className: "selected-option"
      }, current.label), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
        className: "better-select-image-options"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("ul", {
        className: 'options-list bf-clearfix' + this.props["list_style"] || 0
      }, options && Object.entries(options).map(function (_ref) {
        var _ref2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref, 2),
            key = _ref2[0],
            option = _ref2[1];

        var currentClass = current.key === key ? 'selected' : '';
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("li", {
          "data-value": key,
          "data-label": option.label,
          className: 'image-select-option ' + currentClass,
          key: key
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("img", {
          src: option.img,
          alt: option.label
        }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("p", null, option.label));
      })))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("input", {
        type: "hidden",
        value: current.key,
        className: input_class
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.Component);



/***/ }),

/***/ "./src/Info/templates/gutenberg.js":
/*!*****************************************!*\
  !*** ./src/Info/templates/gutenberg.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ BF_Info; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);


function BF_Info(props) {
  var icons = {
    help: 'fa fa-support',
    info: 'fa fa-info ',
    warning: 'fa fa-warning',
    danger: 'fa fa-exclamation',
    _default: 'fa fa-info'
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("bf-info-control", props['info-type'] || "info", props.state || "open", props.container_class, "bf-clearfix")
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "bf-section-info bf-clearfix " + props.level + " " + props.state
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "bf-info-control-title bf-clearfix"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
    className: icons[props.level] || icons._default
  }), props.name)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "bf-info-control-text bf-clearfix",
    dangerouslySetInnerHTML: {
      __html: props.value || props.std || ""
    }
  })));
}

/***/ }),

/***/ "./src/MediaImage/templates/gutenberg.js":
/*!***********************************************!*\
  !*** ./src/MediaImage/templates/gutenberg.js ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _Icon__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../Icon */ "./src/Icon.js");







function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }






var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "shouldComponentUpdate",
    value: function shouldComponentUpdate() {
      return false;
    }
  }, {
    key: "render",
    value: function render() {
      var props = this.props;
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("bs-control-media-image", props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: props.show_input ? "text" : "hidden",
        name: props.input_name,
        value: props.value,
        placeholder: props.input_placeholder || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('Image external link...', 'better-studio'),
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("bf-media-image-input", "ltr", props.input_class)
      }), !props.hide_buttons && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("a", {
        href: "#",
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("button", "button-primary", "bf-main-button", "bf-media-image-upload-btn", props.upload_button_class),
        "data-data-type": props['data-type'] || "src",
        "data-media-title": props.media_title || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('Upload', 'better-studio'),
        "data-button-text": props.media_button || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('Upload', 'better-studio'),
        "data-size": props["preview-size"] || "thumbnail"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)(_Icon__WEBPACK_IMPORTED_MODULE_8__["default"], {
        icon: "fa-upload"
      }), " ", props.upload_label), !props.hide_buttons && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("a", {
        href: "#",
        className: "button bf-media-image-remove-btn",
        style: {
          display: props.value ? 'inline' : 'none'
        }
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)(_Icon__WEBPACK_IMPORTED_MODULE_8__["default"], {
        icon: "fa-remove"
      }), " ", props.remove_label), !props.hide_preview && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "bf-media-image-preview",
        style: {
          display: props.value ? 'block' : 'none'
        }
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("img", {
        src: props.preview_image_url || props.value
      })));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Component);



/***/ }),

/***/ "./src/Radio/templates/gutenberg.js":
/*!******************************************!*\
  !*** ./src/Radio/templates/gutenberg.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, [{
    key: "shouldComponentUpdate",
    value: function shouldComponentUpdate() {
      return false;
    }
  }, {
    key: "render",
    value: function render() {
      var props = this.props,
          unique_id = props.id || ("id-" + Math.random()).replace('0.', '');
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("bs-control-radio", props.container_class)
      }, Object.entries(props.options || {}).map(function (_ref) {
        var _ref2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref, 2),
            key = _ref2[0],
            label = _ref2[1];

        var input_id = unique_id + "-" + key;
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
          className: "bf-radio-button-option",
          key: key
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("input", {
          type: "radio",
          name: props.input_name || unique_id,
          id: input_id,
          className: props.input_class,
          defaultValue: key,
          defaultChecked: props.value === key
        }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("label", {
          htmlFor: input_id
        }, label));
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.Component);



/***/ }),

/***/ "./src/Repeater/templates/gutenberg.js":
/*!*********************************************!*\
  !*** ./src/Repeater/templates/gutenberg.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__);








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }






var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default(props) {
    var _this;

    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__["default"])(this, _default);

    _this = _super.call(this, props);
    var items = [];

    if (props.value && Array.isArray(props.value)) {
      items = props.value;
    }

    _this.state = {
      items: items
    };
    return _this;
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, [{
    key: "appendItem",
    value: function appendItem() {
      var defaults = this.defaults(),
          items = this.items();
      var item = lodash__WEBPACK_IMPORTED_MODULE_8___default().clone(defaults[0]);
      item.uniqueKey = this.generateKey();
      items.push(item);
      this.setState({
        items: items
      });
    }
  }, {
    key: "defaults",
    value: function defaults() {
      var _this$props;

      if (Array.isArray(this.props['default'])) {
        return this.props['default'];
      }

      var items = {};
      Object.entries(((_this$props = this.props) === null || _this$props === void 0 ? void 0 : _this$props.options) || {}).forEach(function (_ref) {
        var _ref2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref, 2),
            key = _ref2[0],
            control = _ref2[1];

        if (control.id) {
          items[control.id] = "";
        }
      });
      return [items];
    }
  }, {
    key: "itemChanged",
    value: function itemChanged(value) {
      var items = this.repeater.state.items || {};

      if (!items[this.i]) {
        items[this.i] = {};
      }

      items[this.i] = lodash__WEBPACK_IMPORTED_MODULE_8___default().clone(items[this.i]);
      items[this.i][this.id] = value;
      var newItems = lodash__WEBPACK_IMPORTED_MODULE_8___default().clone(items);
      this.repeater.setState({
        items: lodash__WEBPACK_IMPORTED_MODULE_8___default().cloneDeep(newItems)
      });
      this.repeater.onChange(newItems);
    }
  }, {
    key: "onChange",
    value: function onChange(items) {
      this.props.onChange && this.props.onChange(items || this.items());
    }
  }, {
    key: "prepareChildrenElements",
    value: function prepareChildrenElements(elements, elementsValue, i) {
      var _this2 = this;

      return elements.map(function (element) {
        var id = element.props.id,
            value = elementsValue && elementsValue[id] ? elementsValue[id] : '';
        var key = id + "__" + i;
        var props = {
          value: value,
          key: key,
          i: i,
          id: id,
          onChange: _this2.itemChanged.bind({
            repeater: _this2,
            id: id,
            i: i
          })
        };

        if (element.props.children) {
          props.children = _this2.prepareChildrenElements(Array.isArray(element.props.children) ? element.props.children : [element.props.children], elementsValue, i);
        }

        return React.cloneElement(element, props);
      });
    }
  }, {
    key: "items",
    value: function items() {
      var _this3 = this;

      var items = this.state.items,
          propsValue = Array.isArray(this.props.value) ? this.props.value : [];
      propsValue.forEach(function (value, key) {
        items[key] = Object.assign(items[key] || {}, value || {});

        if (!items[key].uniqueKey) {
          items[key].uniqueKey = _this3.generateKey();
        }
      });
      return items.filter(function (item) {
        return !lodash__WEBPACK_IMPORTED_MODULE_8___default().isEmpty(item);
      });
    }
  }, {
    key: "removeItem",
    value: function removeItem(i) {
      if (lodash__WEBPACK_IMPORTED_MODULE_8___default().size(this.state.items) === 1) {
        alert((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__.__)('Can not remove last item!', 'better-studio'));
        return;
      }

      var items = lodash__WEBPACK_IMPORTED_MODULE_8___default().clone(this.state.items || {});
      delete items[i];
      items = items.filter(function (item) {
        return !lodash__WEBPACK_IMPORTED_MODULE_8___default().isEmpty(item);
      });
      this.setState({
        items: items
      });
      this.onChange(items);
    }
  }, {
    key: "generateKey",
    value: function generateKey() {
      var id = lodash__WEBPACK_IMPORTED_MODULE_8___default().random(9, 9e6);
      return 'item-' + id;
    }
  }, {
    key: "render",
    value: function render() {
      var _this4 = this;

      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("bs-control-repeater", this.props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
        className: "bf-repeater-items-container bf-clearfix"
      }, this.items().map(function (values, i) {
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
          className: "bf-repeater-item",
          key: values.uniqueKey
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
          className: "bf-repeater-item-title ui-sortable-handle"
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("h5", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("span", {
          className: "handle-repeater-title-label"
        }, _this4.props.item_title || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__.__)('Item', 'better-studio')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("span", {
          className: "handle-repeater-item"
        }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("span", {
          className: "bf-remove-repeater-item-btn no-event",
          onClick: function onClick() {
            return _this4.removeItem(i);
          }
        }, _this4.props.delete_label || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__.__)('Delete', 'better-studio')))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
          className: "repeater-item-container bf-clearfix"
        }, _this4.prepareChildrenElements(Array.isArray(_this4.props.children) ? _this4.props.children : [_this4.props.children], values, i)));
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("button", {
        className: "bf-clone-repeater-item button button-primary bf-main-button no-event",
        onClick: this.appendItem.bind(this),
        dangerouslySetInnerHTML: {
          __html: this.props.add_label || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__.__)('Add', 'better-studio')
        }
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.Component);



/***/ }),

/***/ "./src/SelectPopup/templates/gutenberg.js":
/*!************************************************!*\
  !*** ./src/SelectPopup/templates/gutenberg.js ***!
  \************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_8__);







function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }






var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "render",
    value: function render() {
      var props = this.props,
          texts = lodash__WEBPACK_IMPORTED_MODULE_8___default().isObject(props.texts) ? props.texts : {};
      var current = {
        key: "",
        label: "",
        img: ""
      };

      if (!lodash__WEBPACK_IMPORTED_MODULE_8___default().isEmpty(props.value)) {
        if (props.options && lodash__WEBPACK_IMPORTED_MODULE_8___default().isObject(props.options[props.value])) {
          current = props.options[props.value];
          current.key = props.value;
        }
      }

      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("select-popup-field", "bf-clearfix", props.container_class, props.input_name),
        "data-heading": props.name
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "select-popup-selected-image"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("img", {
        src: current.img || current.current_img
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "select-popup-selected-info"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "active-item-text"
      }, texts.box_pre_title || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('Active item', 'better-studio')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "active-item-label"
      }, current.label), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("a", {
        href: "#",
        className: "button button-primary"
      }, texts.box_button || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('Change', 'better-studio'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("script", {
        type: "application/json",
        className: "select-popup-data"
      }, JSON.stringify(props.data2print)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "hidden",
        name: props.input_name,
        value: current.key,
        className: "select-value"
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Component);



/***/ }),

/***/ "./src/Select/templates/gutenberg.js":
/*!*******************************************!*\
  !*** ./src/Select/templates/gutenberg.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_8__);








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }





var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, [{
    key: "render",
    value: function render() {
      var _props$options, _props$options$catego;

      var props = this.props,
          elements = [];
      var values = props.multiple ? props.value.toString().split(',') : props.value;

      if (!props.multiple && Array.isArray(values)) {
        values = values.shift();
      }

      if ((_props$options = props.options) !== null && _props$options !== void 0 && (_props$options$catego = _props$options.category_walker) !== null && _props$options$catego !== void 0 && _props$options$catego.raw) {
        var _props$options2, _props$options2$categ;

        elements.push((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("select", {
          key: "select-raw",
          name: props.input_name || props.id,
          className: props.input_class,
          multiple: props.multiple,
          defaultValue: values,
          dangerouslySetInnerHTML: {
            __html: (_props$options2 = props.options) === null || _props$options2 === void 0 ? void 0 : (_props$options2$categ = _props$options2.category_walker) === null || _props$options2$categ === void 0 ? void 0 : _props$options2$categ.raw
          }
        }));
      } else {
        elements.push((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("select", {
          key: "select-options",
          name: props.input_name || props.id,
          className: props.input_class,
          multiple: props.multiple,
          defaultValue: values
        }, this.optionElements(props.options)));
      }

      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("bf-select-option-container", props.container_class, {
          multiple: !!props.multiple
        })
      }, elements);
    }
  }, {
    key: "optionElements",
    value: function optionElements(options) {
      if (!options) {
        return [];
      }

      var items = [];

      for (var _key in options) {
        if (lodash__WEBPACK_IMPORTED_MODULE_8___default().isObject(options[_key])) {
          continue;
        }

        items.push((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("option", {
          value: _key,
          key: _key
        }, options[_key]));
      }

      for (var key in options) {
        if (!lodash__WEBPACK_IMPORTED_MODULE_8___default().isObject(options[key])) {
          continue;
        }

        var option = lodash__WEBPACK_IMPORTED_MODULE_8___default().isObject(options[key]) ? options[key] : {};

        if (option.options) {
          items.push((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("optgroup", {
            label: option.label,
            key: key
          }, this.optionElements(option.options)));
        } else if (option.label) {
          items.push((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("option", {
            value: key,
            key: key,
            disabled: option.disabled || false
          }, option.label));
        } else if ((0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(option) !== 'object') {
          {
            items.push((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("option", {
              value: key,
              key: key,
              disabled: option.disabled || false
            }, option));
          }
        }
      }

      return items;
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.Component);



/***/ }),

/***/ "./src/Slider/templates/gutenberg.js":
/*!*******************************************!*\
  !*** ./src/Slider/templates/gutenberg.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ BF_Slider; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);


function BF_Slider(props) {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("bs-control-slider", props.container_class)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "bf-slider-slider",
    "data-dimension": props.dimension,
    "data-animation": props.animation ? "enable" : "disable",
    "data-val": props.value || 0,
    "data-min": props.min || 0,
    "data-max": props.max || 100,
    "data-step": props.step || 1
  }, " "), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "hidden",
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("bf-slider-input", props.input_class),
    value: props.value || 0,
    name: props.input_name
  }));
}

/***/ }),

/***/ "./src/SorterCheckbox/templates/gutenberg.js":
/*!***************************************************!*\
  !*** ./src/SorterCheckbox/templates/gutenberg.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../utils */ "./src/utils.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }





var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, [{
    key: "render",
    value: function render() {
      var props = this.props,
          values = props.value || {};

      var li = function li(_ref) {
        var _item$cssClass;

        var _ref2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref, 2),
            key = _ref2[0],
            item = _ref2[1];

        var ID = item.id || key,
            extraStyles = (0,_utils__WEBPACK_IMPORTED_MODULE_8__.cssObject)(item.css || ''),
            extraClass = item['css-class'] || '',
            checked = !!values[ID],
            disabled = ((_item$cssClass = item['css-class']) === null || _item$cssClass === void 0 ? void 0 : _item$cssClass.indexOf('disable-item')) !== -1;
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("li", {
          id: 'bf-sorter-group-item-' + props.id + '-' + ID,
          className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("item-" + props.id, extraClass, {
            "checked-item": checked
          }),
          style: extraStyles,
          key: ID
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("input", {
          value: item.label,
          type: "checkbox",
          name: ID,
          defaultChecked: checked,
          "data-id": ID,
          disabled: disabled,
          className: "sorter-checkbox-value"
        }), item.label);
      };

      var displayedItems = [];
      var options = Object.entries(props.options || {});
      var activeAndSelectedItems = options.filter(function (_ref3) {
        var _item$cssClass2;

        var _ref4 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref3, 2),
            key = _ref4[0],
            item = _ref4[1];

        var ID = item.id || key;
        var visible = values[ID] !== -1 && ((_item$cssClass2 = item['css-class']) === null || _item$cssClass2 === void 0 ? void 0 : _item$cssClass2.indexOf('active-item')) !== -1;

        if (visible) {
          displayedItems.push(ID);
        }

        return visible;
      });
      var activeAndDeSelectedItems = options.filter(function (_ref5) {
        var _item$cssClass3;

        var _ref6 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref5, 2),
            key = _ref6[0],
            item = _ref6[1];

        var ID = item.id || key;
        var visible = values[ID] === -1 && ((_item$cssClass3 = item['css-class']) === null || _item$cssClass3 === void 0 ? void 0 : _item$cssClass3.indexOf('active-item')) !== -1;

        if (visible) {
          displayedItems.push(ID);
        }

        return visible;
      });
      var disabledItems = options.filter(function (_ref7) {
        var _ref8 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref7, 2),
            key = _ref8[0],
            item = _ref8[1];

        var ID = item.id || key;
        return displayedItems.indexOf(ID) === -1;
      });
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("bf-sorter-groups-container", props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("ul", {
        id: "bf-sorter-group-" + props.id,
        className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("bf-sorter-list", "bf-sorter-" + props.id)
      }, activeAndSelectedItems.map(li), activeAndDeSelectedItems.map(li), disabledItems.map(li)));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.Component);



/***/ }),

/***/ "./src/Sorter/templates/gutenberg.js":
/*!*******************************************!*\
  !*** ./src/Sorter/templates/gutenberg.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../../utils */ "./src/utils.js");








function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }






var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, [{
    key: "render",
    value: function render() {
      var props = this.props;
      var values = Array.isArray(props.value) ? props.value : [],
          options = props.options || {};
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("bf-sorter-groups-container", props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("input", {
        name: props.input_name,
        value: JSON.stringify(values),
        className: props.input_class,
        type: "hidden"
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("ul", {
        id: "bf-sorter-group-" + props.id,
        className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("bf-sorter-list", "bf-sorter-" + props.id)
      }, values.map(function (item, key) {
        var ID = item.id || lodash.kebabCase((item === null || item === void 0 ? void 0 : item.label) || item),
            option = options[ID] || item,
            extraStyles = (0,_utils__WEBPACK_IMPORTED_MODULE_9__.cssObject)(option.css || ''),
            label = lodash__WEBPACK_IMPORTED_MODULE_8___default().isObject(option) ? option.label : option,
            extraClass = option['css-class'] || '';
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("li", {
          id: 'bf-sorter-group-item-' + props.id + '-' + ID,
          className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("item-" + props.id, extraClass),
          style: extraStyles,
          key: ID,
          "data-id": ID
        }, label);
      }), values.length === 0 && Object.entries(options).map(function (_ref) {
        var _ref2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref, 2),
            key = _ref2[0],
            item = _ref2[1];

        var extraStyles = (0,_utils__WEBPACK_IMPORTED_MODULE_9__.cssObject)(item.css || ''),
            label = lodash__WEBPACK_IMPORTED_MODULE_8___default().isObject(item) ? item.label : item,
            ID = item.id || lodash.kebabCase(label),
            extraClass = item['css-class'] || '';
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.createElement)("li", {
          id: 'bf-sorter-group-item-' + props.id + '-' + ID,
          className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("item-" + props.id, extraClass),
          style: extraStyles,
          key: ID,
          "data-id": ID
        }, label);
      })));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.Component);



/***/ }),

/***/ "./src/SwitchControl/templates/gutenberg.js":
/*!**************************************************!*\
  !*** ./src/SwitchControl/templates/gutenberg.js ***!
  \**************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__);







function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }





var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "shouldComponentUpdate",
    value: function shouldComponentUpdate() {
      return false;
    }
  }, {
    key: "render",
    value: function render() {
      var intValue = parseInt(this.props.value),
          checked = isNaN(intValue) ? !!this.props.value : !!intValue;
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("bf-switch", this.props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("a", {
        className: "cb-enable" + (checked ? ' selected' : '')
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("span", null, this.props['on-label'] || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('On', 'better-studio'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("a", {
        className: "cb-disable" + (checked ? '' : ' selected')
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("span", null, this.props['off-label'] || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('Off', 'better-studio'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "hidden",
        value: Number(checked),
        className: "checkbox"
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Component);



/***/ }),

/***/ "./src/TermSelect/templates/gutenberg.js":
/*!***********************************************!*\
  !*** ./src/TermSelect/templates/gutenberg.js ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__);







function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }





var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "shouldComponentUpdate",
    value: function shouldComponentUpdate() {
      return false;
    }
  }, {
    key: "render",
    value: function render() {
      var props = this.props,
          labels = this.props.labels || {};
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("bf-term-select-field", props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "bf-field-term-select-wrapper bf-field-term-select-deferred",
        "data-taxonomy": props.taxonomy || "category"
      }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('Loading...', 'better-studio')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "bf-field-term-select-help"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("label", null, labels.help || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('Help: Click on check box to', 'better-studio')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "bf-checkbox-multi-state",
        "data-current-state": "none"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("span", {
        "data-state": "none"
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("label", null, labels.not_selected || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('Not Selected', 'better-studio')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "bf-checkbox-multi-state",
        "data-current-state": "active"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("span", {
        "data-state": "active",
        className: "bf-checkbox-active"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("span", {
        className: "icon",
        "aria-hidden": "true"
      }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("label", null, labels.selected || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('Selected', 'better-studio')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: "bf-checkbox-multi-state",
        "data-current-state": "deactivate"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("span", {
        "data-state": "deactivate",
        className: "bf-checkbox-active"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("i", {
        className: "fa fa-times",
        "aria-hidden": "true"
      }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("label", null, labels.excluded || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__.__)('Excluded', 'better-studio'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "hidden",
        value: props.value,
        name: props.input_name,
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("bf-term-select-value", props.input_class)
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Component);



/***/ }),

/***/ "./src/Text/templates/gutenberg.js":
/*!*****************************************!*\
  !*** ./src/Text/templates/gutenberg.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_7__);







function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }





var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "shouldComponentUpdate",
    value: function shouldComponentUpdate() {
      return false;
    }
  }, {
    key: "render",
    value: function render() {
      var props = this.props,
          havePrefix = !lodash__WEBPACK_IMPORTED_MODULE_7___default().isEmpty(props.prefix),
          haveSuffix = !lodash__WEBPACK_IMPORTED_MODULE_7___default().isEmpty(props.suffix);
      var value = props['special-chars'] ? this.specialCharsDecode(props.value) : props.value;
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()({
          "bf-field-with-prefix": havePrefix,
          "bf-field-with-suffix": haveSuffix,
          "rtl": !lodash__WEBPACK_IMPORTED_MODULE_7___default().isEmpty(props.rtl),
          "ltr": !lodash__WEBPACK_IMPORTED_MODULE_7___default().isEmpty(props.ltr)
        }, props.container_class)
      }, havePrefix && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("span", null, props.prefix), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("input", {
        type: "text",
        name: props.input_name,
        className: props.input_class,
        placeholder: props.placeholder,
        defaultValue: value
      }), haveSuffix && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("span", null, props.suffix));
    }
  }, {
    key: "specialCharsDecode",
    value: function specialCharsDecode(str) {
      if (str == null) return '';
      return String(str).replace('&amp;', '&').replace('&lt;', '<').replace('&gt;', '>').replace('&quot;', '"').replace('&#039;', '\'').replace('&lsqb;', '[').replace('&rsqb;', ']').replace('&Hat;', '^').replace('&sol;', '/').replace('&lpar;', '(').replace('&rpar;', ')').replace('&plus;', '+').replace('&nbsp;', ' ').replace('&copy;', '©');
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Component);



/***/ }),

/***/ "./src/Textarea/templates/gutenberg.js":
/*!*********************************************!*\
  !*** ./src/Textarea/templates/gutenberg.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _default; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);







function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }




var _default = /*#__PURE__*/function (_Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(_default, _Component);

  var _super = _createSuper(_default);

  function _default() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, _default);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(_default, [{
    key: "shouldComponentUpdate",
    value: function shouldComponentUpdate() {
      return false;
    }
  }, {
    key: "render",
    value: function render() {
      var props = this.props;
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("div", {
        className: classnames__WEBPACK_IMPORTED_MODULE_6___default()("bs-control-textarea", props.container_class)
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.createElement)("textarea", {
        name: props.input_name,
        className: props.input_class,
        placeholder: props.placeholder,
        defaultValue: props.value
      }));
    }
  }]);

  return _default;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Component);



/***/ }),

/***/ "./src/utils.js":
/*!**********************!*\
  !*** ./src/utils.js ***!
  \**********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "cssObject": function() { return /* binding */ cssObject; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");


/**
 * @link https://stackoverflow.com/questions/3446170/escape-string-for-use-in-javascript-regex/6969486#6969486
 */
function escapeRegExp(string) {
  return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
}

function extractValue(key, text) {
  var match = text.match(new RegExp("".concat(escapeRegExp(key), "\\s*:\\s*([^;]+)")));
  return match && match[1];
}

function cssObject(cssText) {
  var el = document.createElement('a');
  el.style.cssText = cssText;
  var obj = {};
  Object.entries(el.style).map(function (_ref) {
    var _ref2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref, 2),
        key = _ref2[0],
        value = _ref2[1];

    if (!isNaN(key)) {
      if (value.startsWith('--')) {
        // is css variable
        key = value;
        value = extractValue(key, el.style.cssText);
      }
    }

    if (value && value !== "initial" && isNaN(key)) {
      obj[key] = value;
    }
  });
  return obj;
}

/***/ }),

/***/ "./node_modules/classnames/index.js":
/*!******************************************!*\
  !*** ./node_modules/classnames/index.js ***!
  \******************************************/
/***/ (function(module, exports) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
  Copyright (c) 2018 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames() {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg)) {
				if (arg.length) {
					var inner = classNames.apply(null, arg);
					if (inner) {
						classes.push(inner);
					}
				}
			} else if (argType === 'object') {
				if (arg.toString === Object.prototype.toString) {
					for (var key in arg) {
						if (hasOwn.call(arg, key) && arg[key]) {
							classes.push(key);
						}
					}
				} else {
					classes.push(arg.toString());
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/***/ (function(module) {

"use strict";
module.exports = window["lodash"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayLikeToArray; }
/* harmony export */ });
function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayWithHoles; }
/* harmony export */ });
function _arrayWithHoles(arr) {
  if (Array.isArray(arr)) return arr;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js ***!
  \**************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

/***/ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/classCallCheck.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

"use strict";
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

/***/ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

"use strict";
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

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js ***!
  \*************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _iterableToArrayLimit; }
/* harmony export */ });
function _iterableToArrayLimit(arr, i) {
  var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"];

  if (_i == null) return;
  var _arr = [];
  var _n = true;
  var _d = false;

  var _s, _e;

  try {
    for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) {
      _arr.push(_s.value);

      if (i && _arr.length === i) break;
    }
  } catch (err) {
    _d = true;
    _e = err;
  } finally {
    try {
      if (!_n && _i["return"] != null) _i["return"]();
    } finally {
      if (_d) throw _e;
    }
  }

  return _arr;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _nonIterableRest; }
/* harmony export */ });
function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js ***!
  \******************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

"use strict";
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

/***/ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/slicedToArray.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _slicedToArray; }
/* harmony export */ });
/* harmony import */ var _arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithHoles.js */ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js");
/* harmony import */ var _iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArrayLimit.js */ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js");
/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js");
/* harmony import */ var _nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableRest.js */ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js");




function _slicedToArray(arr, i) {
  return (0,_arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__["default"])(arr) || (0,_iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__["default"])(arr, i) || (0,_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__["default"])(arr, i) || (0,_nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__["default"])();
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/typeof.js":
/*!***********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/typeof.js ***!
  \***********************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js":
/*!*******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js ***!
  \*******************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _unsupportedIterableToArray; }
/* harmony export */ });
/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js");

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(o, minLen);
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
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
/*!**************************!*\
  !*** ./src/gutenberg.js ***!
  \**************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Controls": function() { return /* binding */ Controls; }
/* harmony export */ });
/* harmony import */ var _HR_gutenberg__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./HR/gutenberg */ "./src/HR/gutenberg.js");
/* harmony import */ var _Button_templates_gutenberg__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Button/templates/gutenberg */ "./src/Button/templates/gutenberg.js");
/* harmony import */ var _Code_templates_gutenberg__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Code/templates/gutenberg */ "./src/Code/templates/gutenberg.js");
/* harmony import */ var _Info_templates_gutenberg__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Info/templates/gutenberg */ "./src/Info/templates/gutenberg.js");
/* harmony import */ var _Text_templates_gutenberg__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Text/templates/gutenberg */ "./src/Text/templates/gutenberg.js");
/* harmony import */ var _Radio_templates_gutenberg__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./Radio/templates/gutenberg */ "./src/Radio/templates/gutenberg.js");
/* harmony import */ var _Select_templates_gutenberg__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./Select/templates/gutenberg */ "./src/Select/templates/gutenberg.js");
/* harmony import */ var _Custom_templates_gutenberg__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./Custom/templates/gutenberg */ "./src/Custom/templates/gutenberg.js");
/* harmony import */ var _Editor_templates_gutenberg__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./Editor/templates/gutenberg */ "./src/Editor/templates/gutenberg.js");
/* harmony import */ var _Checkbox_templates_gutenberg__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./Checkbox/templates/gutenberg */ "./src/Checkbox/templates/gutenberg.js");
/* harmony import */ var _Slider_templates_gutenberg__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./Slider/templates/gutenberg */ "./src/Slider/templates/gutenberg.js");
/* harmony import */ var _Sorter_templates_gutenberg__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./Sorter/templates/gutenberg */ "./src/Sorter/templates/gutenberg.js");
/* harmony import */ var _Heading_templates_gutenberg__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./Heading/templates/gutenberg */ "./src/Heading/templates/gutenberg.js");
/* harmony import */ var _Textarea_templates_gutenberg__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./Textarea/templates/gutenberg */ "./src/Textarea/templates/gutenberg.js");
/* harmony import */ var _Repeater_templates_gutenberg__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./Repeater/templates/gutenberg */ "./src/Repeater/templates/gutenberg.js");
/* harmony import */ var _AjaxAction_templates_gutenberg__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./AjaxAction/templates/gutenberg */ "./src/AjaxAction/templates/gutenberg.js");
/* harmony import */ var _AjaxSelect_templates_gutenberg__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./AjaxSelect/templates/gutenberg */ "./src/AjaxSelect/templates/gutenberg.js");
/* harmony import */ var _IconSelect_templates_gutenberg__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./IconSelect/templates/gutenberg */ "./src/IconSelect/templates/gutenberg.js");
/* harmony import */ var _ImageRadio_templates_gutenberg__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./ImageRadio/templates/gutenberg */ "./src/ImageRadio/templates/gutenberg.js");
/* harmony import */ var _MediaImage_templates_gutenberg__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./MediaImage/templates/gutenberg */ "./src/MediaImage/templates/gutenberg.js");
/* harmony import */ var _TermSelect_templates_gutenberg__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./TermSelect/templates/gutenberg */ "./src/TermSelect/templates/gutenberg.js");
/* harmony import */ var _ImageSelect_templates_gutenberg__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ./ImageSelect/templates/gutenberg */ "./src/ImageSelect/templates/gutenberg.js");
/* harmony import */ var _SelectPopup_templates_gutenberg__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ./SelectPopup/templates/gutenberg */ "./src/SelectPopup/templates/gutenberg.js");
/* harmony import */ var _ImagePreview_templates_gutenberg__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ./ImagePreview/templates/gutenberg */ "./src/ImagePreview/templates/gutenberg.js");
/* harmony import */ var _AdvanceSelect_templates_gutenberg__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! ./AdvanceSelect/templates/gutenberg */ "./src/AdvanceSelect/templates/gutenberg.js");
/* harmony import */ var _SwitchControl_templates_gutenberg__WEBPACK_IMPORTED_MODULE_25__ = __webpack_require__(/*! ./SwitchControl/templates/gutenberg */ "./src/SwitchControl/templates/gutenberg.js");
/* harmony import */ var _SorterCheckbox_templates_gutenberg__WEBPACK_IMPORTED_MODULE_26__ = __webpack_require__(/*! ./SorterCheckbox/templates/gutenberg */ "./src/SorterCheckbox/templates/gutenberg.js");
/* harmony import */ var _BackgroundImage_templates_gutenberg__WEBPACK_IMPORTED_MODULE_27__ = __webpack_require__(/*! ./BackgroundImage/templates/gutenberg */ "./src/BackgroundImage/templates/gutenberg.js");




























var Controls = {
  hr: _HR_gutenberg__WEBPACK_IMPORTED_MODULE_0__["default"],
  code: _Code_templates_gutenberg__WEBPACK_IMPORTED_MODULE_2__["default"],
  info: _Info_templates_gutenberg__WEBPACK_IMPORTED_MODULE_3__["default"],
  text: _Text_templates_gutenberg__WEBPACK_IMPORTED_MODULE_4__["default"],
  radio: _Radio_templates_gutenberg__WEBPACK_IMPORTED_MODULE_5__["default"],
  select: _Select_templates_gutenberg__WEBPACK_IMPORTED_MODULE_6__["default"],
  button: _Button_templates_gutenberg__WEBPACK_IMPORTED_MODULE_1__["default"],
  custom: _Custom_templates_gutenberg__WEBPACK_IMPORTED_MODULE_7__["default"],
  editor: _Editor_templates_gutenberg__WEBPACK_IMPORTED_MODULE_8__["default"],
  slider: _Slider_templates_gutenberg__WEBPACK_IMPORTED_MODULE_10__["default"],
  sorter: _Sorter_templates_gutenberg__WEBPACK_IMPORTED_MODULE_11__["default"],
  heading: _Heading_templates_gutenberg__WEBPACK_IMPORTED_MODULE_12__["default"],
  checkbox: _Checkbox_templates_gutenberg__WEBPACK_IMPORTED_MODULE_9__["default"],
  textarea: _Textarea_templates_gutenberg__WEBPACK_IMPORTED_MODULE_13__["default"],
  repeater: _Repeater_templates_gutenberg__WEBPACK_IMPORTED_MODULE_14__["default"],
  ajax_action: _AjaxAction_templates_gutenberg__WEBPACK_IMPORTED_MODULE_15__["default"],
  ajax_select: _AjaxSelect_templates_gutenberg__WEBPACK_IMPORTED_MODULE_16__["default"],
  icon_select: _IconSelect_templates_gutenberg__WEBPACK_IMPORTED_MODULE_17__["default"],
  image_radio: _ImageRadio_templates_gutenberg__WEBPACK_IMPORTED_MODULE_18__["default"],
  media_image: _MediaImage_templates_gutenberg__WEBPACK_IMPORTED_MODULE_19__["default"],
  term_select: _TermSelect_templates_gutenberg__WEBPACK_IMPORTED_MODULE_20__["default"],
  image_select: _ImageSelect_templates_gutenberg__WEBPACK_IMPORTED_MODULE_21__["default"],
  select_popup: _SelectPopup_templates_gutenberg__WEBPACK_IMPORTED_MODULE_22__["default"],
  image_preview: _ImagePreview_templates_gutenberg__WEBPACK_IMPORTED_MODULE_23__["default"],
  advance_select: _AdvanceSelect_templates_gutenberg__WEBPACK_IMPORTED_MODULE_24__["default"],
  background_image: _BackgroundImage_templates_gutenberg__WEBPACK_IMPORTED_MODULE_27__["default"],
  "switch": _SwitchControl_templates_gutenberg__WEBPACK_IMPORTED_MODULE_25__["default"],
  sorter_checkbox: _SorterCheckbox_templates_gutenberg__WEBPACK_IMPORTED_MODULE_26__["default"]
};

}();
(BetterStudio = typeof BetterStudio === "undefined" ? {} : BetterStudio).Block = __webpack_exports__;
/******/ })()
;
//# sourceMappingURL=controls-gutenberg.js.map