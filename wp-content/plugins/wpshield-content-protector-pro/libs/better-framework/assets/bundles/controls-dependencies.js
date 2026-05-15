!function(){var t={n:function(e){var a=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(a,{a:a}),a},d:function(e,a){for(var n in a)t.o(a,n)&&!t.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:a[n]})},o:function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},r:function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})}},e={};!function(){"use strict";t.r(e),t.d(e,{checkboxMultiState:function(){return o}});var a=window.jQuery,n=t.n(a),i=window.lodash,r=t.n(i);function o(t,e){function a(t,e){t.attr("data-current-state",e).data("current-state",e)}var i;t.on("click",".bf-checkbox-multi-state:not(.disabled)",(function(t){t.preventDefault();var e=n()(this),i=n()("[data-state]:not(.disabled):first",e),r=e.data("current-state"),o=e.find("[data-state="+r+"]");function c(){var t=o.next("[data-state]:not(.disabled)");return t.length||(t=i),t}o.length||(o=i);var d=c(),s=d.data("state");o.css("display","none"),d.css("display","inline-block"),a(e,s),e.trigger("bf-checkbox-change",[s].concat(Array.prototype.slice.call(arguments,1)))})),t.on("click",".bf-radio-field:not(.disabled)",(function(t){t.preventDefault(),t.stopPropagation();var e=n()(this);if("active"!==e.data("current-state")){var i=n()(this).closest(".bf-radio-group"),r=i.find('[data-current-state="active"]');a(r,"deactivate"),r.children("span").hide(),a(e,"active"),e.children("span").show(),i.trigger("bf-radio-change",[e.data("name")].concat(Array.prototype.slice.call(arguments,1)))}})).on("click",".bf-radio-toggle",(function(){n()(".bf-radio-field:not(.disabled)",n()(this).parent()).click()})),n()(".bf-checkbox-multi-state",t).each((function(){var t=n()(this),e=t.data("current-state");n()("[data-state="+e+"]",t).css("display","inline-block")})),(i=[],r().isEmpty(e)||!r().isString(e)||e.split(",").forEach((function(t){if(t&&"0"!==t){var e="active";t.startsWith("-")&&(t=t.substr(1),e="deactivate"),i.push({term_id:Number(t),status:e})}})),i).forEach((function(e){var i=e.term_id,r=e.status;!function(t,e){a(t.find(".bf-checkbox-multi-state"),e),t.find(".bf-checkbox-icon").each((function(){var t=n()(this);t.css("display",t.data("state")===e?"inline-block":"none")}))}(t.find(".cat-item-".concat(i)),r)}))}}();var a=(BetterStudio="undefined"==typeof BetterStudio?{}:BetterStudio).Libs=BetterStudio.Libs||{};for(var n in e)a[n]=e[n];e.__esModule&&Object.defineProperty(a,"__esModule",{value:!0})}();
// canvas-confetti v1.5.1 built on 2022-02-08T22:20:40.944Z
!(function (window, module) {
// source content
(function main(global, module, isWorker, workerSize) {
  var canUseWorker = !!(
    global.Worker &&
    global.Blob &&
    global.Promise &&
    global.OffscreenCanvas &&
    global.OffscreenCanvasRenderingContext2D &&
    global.HTMLCanvasElement &&
    global.HTMLCanvasElement.prototype.transferControlToOffscreen &&
    global.URL &&
    global.URL.createObjectURL);

  function noop() {}

  // create a promise if it exists, otherwise, just
  // call the function directly
  function promise(func) {
    var ModulePromise = module.exports.Promise;
    var Prom = ModulePromise !== void 0 ? ModulePromise : global.Promise;

    if (typeof Prom === 'function') {
      return new Prom(func);
    }

    func(noop, noop);

    return null;
  }

  var raf = (function () {
    var TIME = Math.floor(1000 / 60);
    var frame, cancel;
    var frames = {};
    var lastFrameTime = 0;

    if (typeof requestAnimationFrame === 'function' && typeof cancelAnimationFrame === 'function') {
      frame = function (cb) {
        var id = Math.random();

        frames[id] = requestAnimationFrame(function onFrame(time) {
          if (lastFrameTime === time || lastFrameTime + TIME - 1 < time) {
            lastFrameTime = time;
            delete frames[id];

            cb();
          } else {
            frames[id] = requestAnimationFrame(onFrame);
          }
        });

        return id;
      };
      cancel = function (id) {
        if (frames[id]) {
          cancelAnimationFrame(frames[id]);
        }
      };
    } else {
      frame = function (cb) {
        return setTimeout(cb, TIME);
      };
      cancel = function (timer) {
        return clearTimeout(timer);
      };
    }

    return { frame: frame, cancel: cancel };
  }());

  var getWorker = (function () {
    var worker;
    var prom;
    var resolves = {};

    function decorate(worker) {
      function execute(options, callback) {
        worker.postMessage({ options: options || {}, callback: callback });
      }
      worker.init = function initWorker(canvas) {
        var offscreen = canvas.transferControlToOffscreen();
        worker.postMessage({ canvas: offscreen }, [offscreen]);
      };

      worker.fire = function fireWorker(options, size, done) {
        if (prom) {
          execute(options, null);
          return prom;
        }

        var id = Math.random().toString(36).slice(2);

        prom = promise(function (resolve) {
          function workerDone(msg) {
            if (msg.data.callback !== id) {
              return;
            }

            delete resolves[id];
            worker.removeEventListener('message', workerDone);

            prom = null;
            done();
            resolve();
          }

          worker.addEventListener('message', workerDone);
          execute(options, id);

          resolves[id] = workerDone.bind(null, { data: { callback: id }});
        });

        return prom;
      };

      worker.reset = function resetWorker() {
        worker.postMessage({ reset: true });

        for (var id in resolves) {
          resolves[id]();
          delete resolves[id];
        }
      };
    }

    return function () {
      if (worker) {
        return worker;
      }

      if (!isWorker && canUseWorker) {
        var code = [
          'var CONFETTI, SIZE = {}, module = {};',
          '(' + main.toString() + ')(this, module, true, SIZE);',
          'onmessage = function(msg) {',
          '  if (msg.data.options) {',
          '    CONFETTI(msg.data.options).then(function () {',
          '      if (msg.data.callback) {',
          '        postMessage({ callback: msg.data.callback });',
          '      }',
          '    });',
          '  } else if (msg.data.reset) {',
          '    CONFETTI.reset();',
          '  } else if (msg.data.resize) {',
          '    SIZE.width = msg.data.resize.width;',
          '    SIZE.height = msg.data.resize.height;',
          '  } else if (msg.data.canvas) {',
          '    SIZE.width = msg.data.canvas.width;',
          '    SIZE.height = msg.data.canvas.height;',
          '    CONFETTI = module.exports.create(msg.data.canvas);',
          '  }',
          '}',
        ].join('\n');
        try {
          worker = new Worker(URL.createObjectURL(new Blob([code])));
        } catch (e) {
          // eslint-disable-next-line no-console
          typeof console !== undefined && typeof console.warn === 'function' ? console.warn('🎊 Could not load worker', e) : null;

          return null;
        }

        decorate(worker);
      }

      return worker;
    };
  })();

  var defaults = {
    particleCount: 50,
    angle: 90,
    spread: 45,
    startVelocity: 45,
    decay: 0.9,
    gravity: 1,
    drift: 0,
    ticks: 200,
    x: 0.5,
    y: 0.5,
    shapes: ['square', 'circle'],
    zIndex: 100,
    colors: [
      '#26ccff',
      '#a25afd',
      '#ff5e7e',
      '#88ff5a',
      '#fcff42',
      '#ffa62d',
      '#ff36ff'
    ],
    // probably should be true, but back-compat
    disableForReducedMotion: false,
    scalar: 1
  };

  function convert(val, transform) {
    return transform ? transform(val) : val;
  }

  function isOk(val) {
    return !(val === null || val === undefined);
  }

  function prop(options, name, transform) {
    return convert(
      options && isOk(options[name]) ? options[name] : defaults[name],
      transform
    );
  }

  function onlyPositiveInt(number){
    return number < 0 ? 0 : Math.floor(number);
  }

  function randomInt(min, max) {
    // [min, max)
    return Math.floor(Math.random() * (max - min)) + min;
  }

  function toDecimal(str) {
    return parseInt(str, 16);
  }

  function colorsToRgb(colors) {
    return colors.map(hexToRgb);
  }

  function hexToRgb(str) {
    var val = String(str).replace(/[^0-9a-f]/gi, '');

    if (val.length < 6) {
        val = val[0]+val[0]+val[1]+val[1]+val[2]+val[2];
    }

    return {
      r: toDecimal(val.substring(0,2)),
      g: toDecimal(val.substring(2,4)),
      b: toDecimal(val.substring(4,6))
    };
  }

  function getOrigin(options) {
    var origin = prop(options, 'origin', Object);
    origin.x = prop(origin, 'x', Number);
    origin.y = prop(origin, 'y', Number);

    return origin;
  }

  function setCanvasWindowSize(canvas) {
    canvas.width = document.documentElement.clientWidth;
    canvas.height = document.documentElement.clientHeight;
  }

  function setCanvasRectSize(canvas) {
    var rect = canvas.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = rect.height;
  }

  function getCanvas(zIndex) {
    var canvas = document.createElement('canvas');

    canvas.style.position = 'fixed';
    canvas.style.top = '0px';
    canvas.style.left = '0px';
    canvas.style.pointerEvents = 'none';
    canvas.style.zIndex = zIndex;

    return canvas;
  }

  function ellipse(context, x, y, radiusX, radiusY, rotation, startAngle, endAngle, antiClockwise) {
    context.save();
    context.translate(x, y);
    context.rotate(rotation);
    context.scale(radiusX, radiusY);
    context.arc(0, 0, 1, startAngle, endAngle, antiClockwise);
    context.restore();
  }

  function randomPhysics(opts) {
    var radAngle = opts.angle * (Math.PI / 180);
    var radSpread = opts.spread * (Math.PI / 180);

    return {
      x: opts.x,
      y: opts.y,
      wobble: Math.random() * 10,
      wobbleSpeed: Math.min(0.11, Math.random() * 0.1 + 0.05),
      velocity: (opts.startVelocity * 0.5) + (Math.random() * opts.startVelocity),
      angle2D: -radAngle + ((0.5 * radSpread) - (Math.random() * radSpread)),
      tiltAngle: (Math.random() * (0.75 - 0.25) + 0.25) * Math.PI,
      color: opts.color,
      shape: opts.shape,
      tick: 0,
      totalTicks: opts.ticks,
      decay: opts.decay,
      drift: opts.drift,
      random: Math.random() + 2,
      tiltSin: 0,
      tiltCos: 0,
      wobbleX: 0,
      wobbleY: 0,
      gravity: opts.gravity * 3,
      ovalScalar: 0.6,
      scalar: opts.scalar
    };
  }

  function updateFetti(context, fetti) {
    fetti.x += Math.cos(fetti.angle2D) * fetti.velocity + fetti.drift;
    fetti.y += Math.sin(fetti.angle2D) * fetti.velocity + fetti.gravity;
    fetti.wobble += fetti.wobbleSpeed;
    fetti.velocity *= fetti.decay;
    fetti.tiltAngle += 0.1;
    fetti.tiltSin = Math.sin(fetti.tiltAngle);
    fetti.tiltCos = Math.cos(fetti.tiltAngle);
    fetti.random = Math.random() + 2;
    fetti.wobbleX = fetti.x + ((10 * fetti.scalar) * Math.cos(fetti.wobble));
    fetti.wobbleY = fetti.y + ((10 * fetti.scalar) * Math.sin(fetti.wobble));

    var progress = (fetti.tick++) / fetti.totalTicks;

    var x1 = fetti.x + (fetti.random * fetti.tiltCos);
    var y1 = fetti.y + (fetti.random * fetti.tiltSin);
    var x2 = fetti.wobbleX + (fetti.random * fetti.tiltCos);
    var y2 = fetti.wobbleY + (fetti.random * fetti.tiltSin);

    context.fillStyle = 'rgba(' + fetti.color.r + ', ' + fetti.color.g + ', ' + fetti.color.b + ', ' + (1 - progress) + ')';
    context.beginPath();

    if (fetti.shape === 'circle') {
      context.ellipse ?
        context.ellipse(fetti.x, fetti.y, Math.abs(x2 - x1) * fetti.ovalScalar, Math.abs(y2 - y1) * fetti.ovalScalar, Math.PI / 10 * fetti.wobble, 0, 2 * Math.PI) :
        ellipse(context, fetti.x, fetti.y, Math.abs(x2 - x1) * fetti.ovalScalar, Math.abs(y2 - y1) * fetti.ovalScalar, Math.PI / 10 * fetti.wobble, 0, 2 * Math.PI);
    } else {
      context.moveTo(Math.floor(fetti.x), Math.floor(fetti.y));
      context.lineTo(Math.floor(fetti.wobbleX), Math.floor(y1));
      context.lineTo(Math.floor(x2), Math.floor(y2));
      context.lineTo(Math.floor(x1), Math.floor(fetti.wobbleY));
    }

    context.closePath();
    context.fill();

    return fetti.tick < fetti.totalTicks;
  }

  function animate(canvas, fettis, resizer, size, done) {
    var animatingFettis = fettis.slice();
    var context = canvas.getContext('2d');
    var animationFrame;
    var destroy;

    var prom = promise(function (resolve) {
      function onDone() {
        animationFrame = destroy = null;

        context.clearRect(0, 0, size.width, size.height);

        done();
        resolve();
      }

      function update() {
        if (isWorker && !(size.width === workerSize.width && size.height === workerSize.height)) {
          size.width = canvas.width = workerSize.width;
          size.height = canvas.height = workerSize.height;
        }

        if (!size.width && !size.height) {
          resizer(canvas);
          size.width = canvas.width;
          size.height = canvas.height;
        }

        context.clearRect(0, 0, size.width, size.height);

        animatingFettis = animatingFettis.filter(function (fetti) {
          return updateFetti(context, fetti);
        });

        if (animatingFettis.length) {
          animationFrame = raf.frame(update);
        } else {
          onDone();
        }
      }

      animationFrame = raf.frame(update);
      destroy = onDone;
    });

    return {
      addFettis: function (fettis) {
        animatingFettis = animatingFettis.concat(fettis);

        return prom;
      },
      canvas: canvas,
      promise: prom,
      reset: function () {
        if (animationFrame) {
          raf.cancel(animationFrame);
        }

        if (destroy) {
          destroy();
        }
      }
    };
  }

  function confettiCannon(canvas, globalOpts) {
    var isLibCanvas = !canvas;
    var allowResize = !!prop(globalOpts || {}, 'resize');
    var globalDisableForReducedMotion = prop(globalOpts, 'disableForReducedMotion', Boolean);
    var shouldUseWorker = canUseWorker && !!prop(globalOpts || {}, 'useWorker');
    var worker = shouldUseWorker ? getWorker() : null;
    var resizer = isLibCanvas ? setCanvasWindowSize : setCanvasRectSize;
    var initialized = (canvas && worker) ? !!canvas.__confetti_initialized : false;
    var preferLessMotion = typeof matchMedia === 'function' && matchMedia('(prefers-reduced-motion)').matches;
    var animationObj;

    function fireLocal(options, size, done) {
      var particleCount = prop(options, 'particleCount', onlyPositiveInt);
      var angle = prop(options, 'angle', Number);
      var spread = prop(options, 'spread', Number);
      var startVelocity = prop(options, 'startVelocity', Number);
      var decay = prop(options, 'decay', Number);
      var gravity = prop(options, 'gravity', Number);
      var drift = prop(options, 'drift', Number);
      var colors = prop(options, 'colors', colorsToRgb);
      var ticks = prop(options, 'ticks', Number);
      var shapes = prop(options, 'shapes');
      var scalar = prop(options, 'scalar');
      var origin = getOrigin(options);

      var temp = particleCount;
      var fettis = [];

      var startX = canvas.width * origin.x;
      var startY = canvas.height * origin.y;

      while (temp--) {
        fettis.push(
          randomPhysics({
            x: startX,
            y: startY,
            angle: angle,
            spread: spread,
            startVelocity: startVelocity,
            color: colors[temp % colors.length],
            shape: shapes[randomInt(0, shapes.length)],
            ticks: ticks,
            decay: decay,
            gravity: gravity,
            drift: drift,
            scalar: scalar
          })
        );
      }

      // if we have a previous canvas already animating,
      // add to it
      if (animationObj) {
        return animationObj.addFettis(fettis);
      }

      animationObj = animate(canvas, fettis, resizer, size , done);

      return animationObj.promise;
    }

    function fire(options) {
      var disableForReducedMotion = globalDisableForReducedMotion || prop(options, 'disableForReducedMotion', Boolean);
      var zIndex = prop(options, 'zIndex', Number);

      if (disableForReducedMotion && preferLessMotion) {
        return promise(function (resolve) {
          resolve();
        });
      }

      if (isLibCanvas && animationObj) {
        // use existing canvas from in-progress animation
        canvas = animationObj.canvas;
      } else if (isLibCanvas && !canvas) {
        // create and initialize a new canvas
        canvas = getCanvas(zIndex);
        document.body.appendChild(canvas);
      }

      if (allowResize && !initialized) {
        // initialize the size of a user-supplied canvas
        resizer(canvas);
      }

      var size = {
        width: canvas.width,
        height: canvas.height
      };

      if (worker && !initialized) {
        worker.init(canvas);
      }

      initialized = true;

      if (worker) {
        canvas.__confetti_initialized = true;
      }

      function onResize() {
        if (worker) {
          // TODO this really shouldn't be immediate, because it is expensive
          var obj = {
            getBoundingClientRect: function () {
              if (!isLibCanvas) {
                return canvas.getBoundingClientRect();
              }
            }
          };

          resizer(obj);

          worker.postMessage({
            resize: {
              width: obj.width,
              height: obj.height
            }
          });
          return;
        }

        // don't actually query the size here, since this
        // can execute frequently and rapidly
        size.width = size.height = null;
      }

      function done() {
        animationObj = null;

        if (allowResize) {
          global.removeEventListener('resize', onResize);
        }

        if (isLibCanvas && canvas) {
          document.body.removeChild(canvas);
          canvas = null;
          initialized = false;
        }
      }

      if (allowResize) {
        global.addEventListener('resize', onResize, false);
      }

      if (worker) {
        return worker.fire(options, size, done);
      }

      return fireLocal(options, size, done);
    }

    fire.reset = function () {
      if (worker) {
        worker.reset();
      }

      if (animationObj) {
        animationObj.reset();
      }
    };

    return fire;
  }

  // Make default export lazy to defer worker creation until called.
  var defaultFire;
  function getDefaultFire() {
    if (!defaultFire) {
      defaultFire = confettiCannon(null, { useWorker: true, resize: true });
    }
    return defaultFire;
  }

  module.exports = function() {
    return getDefaultFire().apply(this, arguments);
  };
  module.exports.reset = function() {
    getDefaultFire().reset();
  };
  module.exports.create = confettiCannon;
}((function () {
  if (typeof window !== 'undefined') {
    return window;
  }

  if (typeof self !== 'undefined') {
    return self;
  }

  return this || {};
})(), module, false));

// end source content

  window.confetti = module.exports;
}(window, {}));

/* == jquery mousewheel plugin == Version: 3.1.13, License: MIT License (MIT) */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a:a(jQuery)}(function(a){function b(b){var g=b||window.event,h=i.call(arguments,1),j=0,l=0,m=0,n=0,o=0,p=0;if(b=a.event.fix(g),b.type="mousewheel","detail"in g&&(m=-1*g.detail),"wheelDelta"in g&&(m=g.wheelDelta),"wheelDeltaY"in g&&(m=g.wheelDeltaY),"wheelDeltaX"in g&&(l=-1*g.wheelDeltaX),"axis"in g&&g.axis===g.HORIZONTAL_AXIS&&(l=-1*m,m=0),j=0===m?l:m,"deltaY"in g&&(m=-1*g.deltaY,j=m),"deltaX"in g&&(l=g.deltaX,0===m&&(j=-1*l)),0!==m||0!==l){if(1===g.deltaMode){var q=a.data(this,"mousewheel-line-height");j*=q,m*=q,l*=q}else if(2===g.deltaMode){var r=a.data(this,"mousewheel-page-height");j*=r,m*=r,l*=r}if(n=Math.max(Math.abs(m),Math.abs(l)),(!f||f>n)&&(f=n,d(g,n)&&(f/=40)),d(g,n)&&(j/=40,l/=40,m/=40),j=Math[j>=1?"floor":"ceil"](j/f),l=Math[l>=1?"floor":"ceil"](l/f),m=Math[m>=1?"floor":"ceil"](m/f),k.settings.normalizeOffset&&this.getBoundingClientRect){var s=this.getBoundingClientRect();o=b.clientX-s.left,p=b.clientY-s.top}return b.deltaX=l,b.deltaY=m,b.deltaFactor=f,b.offsetX=o,b.offsetY=p,b.deltaMode=0,h.unshift(b,j,l,m),e&&clearTimeout(e),e=setTimeout(c,200),(a.event.dispatch||a.event.handle).apply(this,h)}}function c(){f=null}function d(a,b){return k.settings.adjustOldDeltas&&"mousewheel"===a.type&&b%120===0}var e,f,g=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],h="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],i=Array.prototype.slice;if(a.event.fixHooks)for(var j=g.length;j;)a.event.fixHooks[g[--j]]=a.event.mouseHooks;var k=a.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var c=h.length;c;)this.addEventListener(h[--c],b,!1);else this.onmousewheel=b;a.data(this,"mousewheel-line-height",k.getLineHeight(this)),a.data(this,"mousewheel-page-height",k.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var c=h.length;c;)this.removeEventListener(h[--c],b,!1);else this.onmousewheel=null;a.removeData(this,"mousewheel-line-height"),a.removeData(this,"mousewheel-page-height")},getLineHeight:function(b){var c=a(b),d=c["offsetParent"in a.fn?"offsetParent":"parent"]();return d.length||(d=a("body")),parseInt(d.css("fontSize"),10)||parseInt(c.css("fontSize"),10)||16},getPageHeight:function(b){return a(b).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})});!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a:a(jQuery)}(function(a){function b(b){var g=b||window.event,h=i.call(arguments,1),j=0,l=0,m=0,n=0,o=0,p=0;if(b=a.event.fix(g),b.type="mousewheel","detail"in g&&(m=-1*g.detail),"wheelDelta"in g&&(m=g.wheelDelta),"wheelDeltaY"in g&&(m=g.wheelDeltaY),"wheelDeltaX"in g&&(l=-1*g.wheelDeltaX),"axis"in g&&g.axis===g.HORIZONTAL_AXIS&&(l=-1*m,m=0),j=0===m?l:m,"deltaY"in g&&(m=-1*g.deltaY,j=m),"deltaX"in g&&(l=g.deltaX,0===m&&(j=-1*l)),0!==m||0!==l){if(1===g.deltaMode){var q=a.data(this,"mousewheel-line-height");j*=q,m*=q,l*=q}else if(2===g.deltaMode){var r=a.data(this,"mousewheel-page-height");j*=r,m*=r,l*=r}if(n=Math.max(Math.abs(m),Math.abs(l)),(!f||f>n)&&(f=n,d(g,n)&&(f/=40)),d(g,n)&&(j/=40,l/=40,m/=40),j=Math[j>=1?"floor":"ceil"](j/f),l=Math[l>=1?"floor":"ceil"](l/f),m=Math[m>=1?"floor":"ceil"](m/f),k.settings.normalizeOffset&&this.getBoundingClientRect){var s=this.getBoundingClientRect();o=b.clientX-s.left,p=b.clientY-s.top}return b.deltaX=l,b.deltaY=m,b.deltaFactor=f,b.offsetX=o,b.offsetY=p,b.deltaMode=0,h.unshift(b,j,l,m),e&&clearTimeout(e),e=setTimeout(c,200),(a.event.dispatch||a.event.handle).apply(this,h)}}function c(){f=null}function d(a,b){return k.settings.adjustOldDeltas&&"mousewheel"===a.type&&b%120===0}var e,f,g=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],h="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],i=Array.prototype.slice;if(a.event.fixHooks)for(var j=g.length;j;)a.event.fixHooks[g[--j]]=a.event.mouseHooks;var k=a.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var c=h.length;c;)this.addEventListener(h[--c],b,!1);else this.onmousewheel=b;a.data(this,"mousewheel-line-height",k.getLineHeight(this)),a.data(this,"mousewheel-page-height",k.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var c=h.length;c;)this.removeEventListener(h[--c],b,!1);else this.onmousewheel=null;a.removeData(this,"mousewheel-line-height"),a.removeData(this,"mousewheel-page-height")},getLineHeight:function(b){var c=a(b),d=c["offsetParent"in a.fn?"offsetParent":"parent"]();return d.length||(d=a("body")),parseInt(d.css("fontSize"),10)||parseInt(c.css("fontSize"),10)||16},getPageHeight:function(b){return a(b).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})});

/* == malihu jquery custom scrollbar plugin == Version: 3.1.5, License: MIT License (MIT) */
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"undefined"!=typeof module&&module.exports?module.exports=e:e(jQuery,window,document)}(function(e){!function(t){var o="function"==typeof define&&define.amd,a="undefined"!=typeof module&&module.exports,n="https:"==document.location.protocol?"https:":"http:",i="cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js";o||(a?require("jquery-mousewheel")(e):e.event.special.mousewheel||e("head").append(decodeURI("%3Cscript src="+n+"//"+i+"%3E%3C/script%3E"))),t()}(function(){var t,o="mCustomScrollbar",a="mCS",n=".mCustomScrollbar",i={setTop:0,setLeft:0,axis:"y",scrollbarPosition:"inside",scrollInertia:950,autoDraggerLength:!0,alwaysShowScrollbar:0,snapOffset:0,mouseWheel:{enable:!0,scrollAmount:"auto",axis:"y",deltaFactor:"auto",disableOver:["select","option","keygen","datalist","textarea"]},scrollButtons:{scrollType:"stepless",scrollAmount:"auto"},keyboard:{enable:!0,scrollType:"stepless",scrollAmount:"auto"},contentTouchScroll:25,documentTouchScroll:!0,advanced:{autoScrollOnFocus:"input,textarea,select,button,datalist,keygen,a[tabindex],area,object,[contenteditable='true']",updateOnContentResize:!0,updateOnImageLoad:"auto",autoUpdateTimeout:60},theme:"light",callbacks:{onTotalScrollOffset:0,onTotalScrollBackOffset:0,alwaysTriggerOffsets:!0}},r=0,l={},s=window.attachEvent&&!window.addEventListener?1:0,c=!1,d=["mCSB_dragger_onDrag","mCSB_scrollTools_onDrag","mCS_img_loaded","mCS_disabled","mCS_destroyed","mCS_no_scrollbar","mCS-autoHide","mCS-dir-rtl","mCS_no_scrollbar_y","mCS_no_scrollbar_x","mCS_y_hidden","mCS_x_hidden","mCSB_draggerContainer","mCSB_buttonUp","mCSB_buttonDown","mCSB_buttonLeft","mCSB_buttonRight"],u={init:function(t){var t=e.extend(!0,{},i,t),o=f.call(this);if(t.live){var s=t.liveSelector||this.selector||n,c=e(s);if("off"===t.live)return void m(s);l[s]=setTimeout(function(){c.mCustomScrollbar(t),"once"===t.live&&c.length&&m(s)},500)}else m(s);return t.setWidth=t.set_width?t.set_width:t.setWidth,t.setHeight=t.set_height?t.set_height:t.setHeight,t.axis=t.horizontalScroll?"x":p(t.axis),t.scrollInertia=t.scrollInertia>0&&t.scrollInertia<17?17:t.scrollInertia,"object"!=typeof t.mouseWheel&&1==t.mouseWheel&&(t.mouseWheel={enable:!0,scrollAmount:"auto",axis:"y",preventDefault:!1,deltaFactor:"auto",normalizeDelta:!1,invert:!1}),t.mouseWheel.scrollAmount=t.mouseWheelPixels?t.mouseWheelPixels:t.mouseWheel.scrollAmount,t.mouseWheel.normalizeDelta=t.advanced.normalizeMouseWheelDelta?t.advanced.normalizeMouseWheelDelta:t.mouseWheel.normalizeDelta,t.scrollButtons.scrollType=g(t.scrollButtons.scrollType),h(t),e(o).each(function(){var o=e(this);if(!o.data(a)){o.data(a,{idx:++r,opt:t,scrollRatio:{y:null,x:null},overflowed:null,contentReset:{y:null,x:null},bindEvents:!1,tweenRunning:!1,sequential:{},langDir:o.css("direction"),cbOffsets:null,trigger:null,poll:{size:{o:0,n:0},img:{o:0,n:0},change:{o:0,n:0}}});var n=o.data(a),i=n.opt,l=o.data("mcs-axis"),s=o.data("mcs-scrollbar-position"),c=o.data("mcs-theme");l&&(i.axis=l),s&&(i.scrollbarPosition=s),c&&(i.theme=c,h(i)),v.call(this),n&&i.callbacks.onCreate&&"function"==typeof i.callbacks.onCreate&&i.callbacks.onCreate.call(this),e("#mCSB_"+n.idx+"_container img:not(."+d[2]+")").addClass(d[2]),u.update.call(null,o)}})},update:function(t,o){var n=t||f.call(this);return e(n).each(function(){var t=e(this);if(t.data(a)){var n=t.data(a),i=n.opt,r=e("#mCSB_"+n.idx+"_container"),l=e("#mCSB_"+n.idx),s=[e("#mCSB_"+n.idx+"_dragger_vertical"),e("#mCSB_"+n.idx+"_dragger_horizontal")];if(!r.length)return;n.tweenRunning&&Q(t),o&&n&&i.callbacks.onBeforeUpdate&&"function"==typeof i.callbacks.onBeforeUpdate&&i.callbacks.onBeforeUpdate.call(this),t.hasClass(d[3])&&t.removeClass(d[3]),t.hasClass(d[4])&&t.removeClass(d[4]),l.css("max-height","none"),l.height()!==t.height()&&l.css("max-height",t.height()),_.call(this),"y"===i.axis||i.advanced.autoExpandHorizontalScroll||r.css("width",x(r)),n.overflowed=y.call(this),M.call(this),i.autoDraggerLength&&S.call(this),b.call(this),T.call(this);var c=[Math.abs(r[0].offsetTop),Math.abs(r[0].offsetLeft)];"x"!==i.axis&&(n.overflowed[0]?s[0].height()>s[0].parent().height()?B.call(this):(G(t,c[0].toString(),{dir:"y",dur:0,overwrite:"none"}),n.contentReset.y=null):(B.call(this),"y"===i.axis?k.call(this):"yx"===i.axis&&n.overflowed[1]&&G(t,c[1].toString(),{dir:"x",dur:0,overwrite:"none"}))),"y"!==i.axis&&(n.overflowed[1]?s[1].width()>s[1].parent().width()?B.call(this):(G(t,c[1].toString(),{dir:"x",dur:0,overwrite:"none"}),n.contentReset.x=null):(B.call(this),"x"===i.axis?k.call(this):"yx"===i.axis&&n.overflowed[0]&&G(t,c[0].toString(),{dir:"y",dur:0,overwrite:"none"}))),o&&n&&(2===o&&i.callbacks.onImageLoad&&"function"==typeof i.callbacks.onImageLoad?i.callbacks.onImageLoad.call(this):3===o&&i.callbacks.onSelectorChange&&"function"==typeof i.callbacks.onSelectorChange?i.callbacks.onSelectorChange.call(this):i.callbacks.onUpdate&&"function"==typeof i.callbacks.onUpdate&&i.callbacks.onUpdate.call(this)),N.call(this)}})},scrollTo:function(t,o){if("undefined"!=typeof t&&null!=t){var n=f.call(this);return e(n).each(function(){var n=e(this);if(n.data(a)){var i=n.data(a),r=i.opt,l={trigger:"external",scrollInertia:r.scrollInertia,scrollEasing:"mcsEaseInOut",moveDragger:!1,timeout:60,callbacks:!0,onStart:!0,onUpdate:!0,onComplete:!0},s=e.extend(!0,{},l,o),c=Y.call(this,t),d=s.scrollInertia>0&&s.scrollInertia<17?17:s.scrollInertia;c[0]=X.call(this,c[0],"y"),c[1]=X.call(this,c[1],"x"),s.moveDragger&&(c[0]*=i.scrollRatio.y,c[1]*=i.scrollRatio.x),s.dur=ne()?0:d,setTimeout(function(){null!==c[0]&&"undefined"!=typeof c[0]&&"x"!==r.axis&&i.overflowed[0]&&(s.dir="y",s.overwrite="all",G(n,c[0].toString(),s)),null!==c[1]&&"undefined"!=typeof c[1]&&"y"!==r.axis&&i.overflowed[1]&&(s.dir="x",s.overwrite="none",G(n,c[1].toString(),s))},s.timeout)}})}},stop:function(){var t=f.call(this);return e(t).each(function(){var t=e(this);t.data(a)&&Q(t)})},disable:function(t){var o=f.call(this);return e(o).each(function(){var o=e(this);if(o.data(a)){o.data(a);N.call(this,"remove"),k.call(this),t&&B.call(this),M.call(this,!0),o.addClass(d[3])}})},destroy:function(){var t=f.call(this);return e(t).each(function(){var n=e(this);if(n.data(a)){var i=n.data(a),r=i.opt,l=e("#mCSB_"+i.idx),s=e("#mCSB_"+i.idx+"_container"),c=e(".mCSB_"+i.idx+"_scrollbar");r.live&&m(r.liveSelector||e(t).selector),N.call(this,"remove"),k.call(this),B.call(this),n.removeData(a),$(this,"mcs"),c.remove(),s.find("img."+d[2]).removeClass(d[2]),l.replaceWith(s.contents()),n.removeClass(o+" _"+a+"_"+i.idx+" "+d[6]+" "+d[7]+" "+d[5]+" "+d[3]).addClass(d[4])}})}},f=function(){return"object"!=typeof e(this)||e(this).length<1?n:this},h=function(t){var o=["rounded","rounded-dark","rounded-dots","rounded-dots-dark"],a=["rounded-dots","rounded-dots-dark","3d","3d-dark","3d-thick","3d-thick-dark","inset","inset-dark","inset-2","inset-2-dark","inset-3","inset-3-dark"],n=["minimal","minimal-dark"],i=["minimal","minimal-dark"],r=["minimal","minimal-dark"];t.autoDraggerLength=e.inArray(t.theme,o)>-1?!1:t.autoDraggerLength,t.autoExpandScrollbar=e.inArray(t.theme,a)>-1?!1:t.autoExpandScrollbar,t.scrollButtons.enable=e.inArray(t.theme,n)>-1?!1:t.scrollButtons.enable,t.autoHideScrollbar=e.inArray(t.theme,i)>-1?!0:t.autoHideScrollbar,t.scrollbarPosition=e.inArray(t.theme,r)>-1?"outside":t.scrollbarPosition},m=function(e){l[e]&&(clearTimeout(l[e]),$(l,e))},p=function(e){return"yx"===e||"xy"===e||"auto"===e?"yx":"x"===e||"horizontal"===e?"x":"y"},g=function(e){return"stepped"===e||"pixels"===e||"step"===e||"click"===e?"stepped":"stepless"},v=function(){var t=e(this),n=t.data(a),i=n.opt,r=i.autoExpandScrollbar?" "+d[1]+"_expand":"",l=["<div id='mCSB_"+n.idx+"_scrollbar_vertical' class='mCSB_scrollTools mCSB_"+n.idx+"_scrollbar mCS-"+i.theme+" mCSB_scrollTools_vertical"+r+"'><div class='"+d[12]+"'><div id='mCSB_"+n.idx+"_dragger_vertical' class='mCSB_dragger' style='position:absolute;'><div class='mCSB_dragger_bar' /></div><div class='mCSB_draggerRail' /></div></div>","<div id='mCSB_"+n.idx+"_scrollbar_horizontal' class='mCSB_scrollTools mCSB_"+n.idx+"_scrollbar mCS-"+i.theme+" mCSB_scrollTools_horizontal"+r+"'><div class='"+d[12]+"'><div id='mCSB_"+n.idx+"_dragger_horizontal' class='mCSB_dragger' style='position:absolute;'><div class='mCSB_dragger_bar' /></div><div class='mCSB_draggerRail' /></div></div>"],s="yx"===i.axis?"mCSB_vertical_horizontal":"x"===i.axis?"mCSB_horizontal":"mCSB_vertical",c="yx"===i.axis?l[0]+l[1]:"x"===i.axis?l[1]:l[0],u="yx"===i.axis?"<div id='mCSB_"+n.idx+"_container_wrapper' class='mCSB_container_wrapper' />":"",f=i.autoHideScrollbar?" "+d[6]:"",h="x"!==i.axis&&"rtl"===n.langDir?" "+d[7]:"";i.setWidth&&t.css("width",i.setWidth),i.setHeight&&t.css("height",i.setHeight),i.setLeft="y"!==i.axis&&"rtl"===n.langDir?"989999px":i.setLeft,t.addClass(o+" _"+a+"_"+n.idx+f+h).wrapInner("<div id='mCSB_"+n.idx+"' class='mCustomScrollBox mCS-"+i.theme+" "+s+"'><div id='mCSB_"+n.idx+"_container' class='mCSB_container' style='position:relative; top:"+i.setTop+"; left:"+i.setLeft+";' dir='"+n.langDir+"' /></div>");var m=e("#mCSB_"+n.idx),p=e("#mCSB_"+n.idx+"_container");"y"===i.axis||i.advanced.autoExpandHorizontalScroll||p.css("width",x(p)),"outside"===i.scrollbarPosition?("static"===t.css("position")&&t.css("position","relative"),t.css("overflow","visible"),m.addClass("mCSB_outside").after(c)):(m.addClass("mCSB_inside").append(c),p.wrap(u)),w.call(this);var g=[e("#mCSB_"+n.idx+"_dragger_vertical"),e("#mCSB_"+n.idx+"_dragger_horizontal")];g[0].css("min-height",g[0].height()),g[1].css("min-width",g[1].width())},x=function(t){var o=[t[0].scrollWidth,Math.max.apply(Math,t.children().map(function(){return e(this).outerWidth(!0)}).get())],a=t.parent().width();return o[0]>a?o[0]:o[1]>a?o[1]:"100%"},_=function(){var t=e(this),o=t.data(a),n=o.opt,i=e("#mCSB_"+o.idx+"_container");if(n.advanced.autoExpandHorizontalScroll&&"y"!==n.axis){i.css({width:"auto","min-width":0,"overflow-x":"scroll"});var r=Math.ceil(i[0].scrollWidth);3===n.advanced.autoExpandHorizontalScroll||2!==n.advanced.autoExpandHorizontalScroll&&r>i.parent().width()?i.css({width:r,"min-width":"100%","overflow-x":"inherit"}):i.css({"overflow-x":"inherit",position:"absolute"}).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({width:Math.ceil(i[0].getBoundingClientRect().right+.4)-Math.floor(i[0].getBoundingClientRect().left),"min-width":"100%",position:"relative"}).unwrap()}},w=function(){var t=e(this),o=t.data(a),n=o.opt,i=e(".mCSB_"+o.idx+"_scrollbar:first"),r=oe(n.scrollButtons.tabindex)?"tabindex='"+n.scrollButtons.tabindex+"'":"",l=["<a href='#' class='"+d[13]+"' "+r+" />","<a href='#' class='"+d[14]+"' "+r+" />","<a href='#' class='"+d[15]+"' "+r+" />","<a href='#' class='"+d[16]+"' "+r+" />"],s=["x"===n.axis?l[2]:l[0],"x"===n.axis?l[3]:l[1],l[2],l[3]];n.scrollButtons.enable&&i.prepend(s[0]).append(s[1]).next(".mCSB_scrollTools").prepend(s[2]).append(s[3])},S=function(){var t=e(this),o=t.data(a),n=e("#mCSB_"+o.idx),i=e("#mCSB_"+o.idx+"_container"),r=[e("#mCSB_"+o.idx+"_dragger_vertical"),e("#mCSB_"+o.idx+"_dragger_horizontal")],l=[n.height()/i.outerHeight(!1),n.width()/i.outerWidth(!1)],c=[parseInt(r[0].css("min-height")),Math.round(l[0]*r[0].parent().height()),parseInt(r[1].css("min-width")),Math.round(l[1]*r[1].parent().width())],d=s&&c[1]<c[0]?c[0]:c[1],u=s&&c[3]<c[2]?c[2]:c[3];r[0].css({height:d,"max-height":r[0].parent().height()-10}).find(".mCSB_dragger_bar").css({"line-height":c[0]+"px"}),r[1].css({width:u,"max-width":r[1].parent().width()-10})},b=function(){var t=e(this),o=t.data(a),n=e("#mCSB_"+o.idx),i=e("#mCSB_"+o.idx+"_container"),r=[e("#mCSB_"+o.idx+"_dragger_vertical"),e("#mCSB_"+o.idx+"_dragger_horizontal")],l=[i.outerHeight(!1)-n.height(),i.outerWidth(!1)-n.width()],s=[l[0]/(r[0].parent().height()-r[0].height()),l[1]/(r[1].parent().width()-r[1].width())];o.scrollRatio={y:s[0],x:s[1]}},C=function(e,t,o){var a=o?d[0]+"_expanded":"",n=e.closest(".mCSB_scrollTools");"active"===t?(e.toggleClass(d[0]+" "+a),n.toggleClass(d[1]),e[0]._draggable=e[0]._draggable?0:1):e[0]._draggable||("hide"===t?(e.removeClass(d[0]),n.removeClass(d[1])):(e.addClass(d[0]),n.addClass(d[1])))},y=function(){var t=e(this),o=t.data(a),n=e("#mCSB_"+o.idx),i=e("#mCSB_"+o.idx+"_container"),r=null==o.overflowed?i.height():i.outerHeight(!1),l=null==o.overflowed?i.width():i.outerWidth(!1),s=i[0].scrollHeight,c=i[0].scrollWidth;return s>r&&(r=s),c>l&&(l=c),[r>n.height(),l>n.width()]},B=function(){var t=e(this),o=t.data(a),n=o.opt,i=e("#mCSB_"+o.idx),r=e("#mCSB_"+o.idx+"_container"),l=[e("#mCSB_"+o.idx+"_dragger_vertical"),e("#mCSB_"+o.idx+"_dragger_horizontal")];if(Q(t),("x"!==n.axis&&!o.overflowed[0]||"y"===n.axis&&o.overflowed[0])&&(l[0].add(r).css("top",0),G(t,"_resetY")),"y"!==n.axis&&!o.overflowed[1]||"x"===n.axis&&o.overflowed[1]){var s=dx=0;"rtl"===o.langDir&&(s=i.width()-r.outerWidth(!1),dx=Math.abs(s/o.scrollRatio.x)),r.css("left",s),l[1].css("left",dx),G(t,"_resetX")}},T=function(){function t(){r=setTimeout(function(){e.event.special.mousewheel?(clearTimeout(r),W.call(o[0])):t()},100)}var o=e(this),n=o.data(a),i=n.opt;if(!n.bindEvents){if(I.call(this),i.contentTouchScroll&&D.call(this),E.call(this),i.mouseWheel.enable){var r;t()}P.call(this),U.call(this),i.advanced.autoScrollOnFocus&&H.call(this),i.scrollButtons.enable&&F.call(this),i.keyboard.enable&&q.call(this),n.bindEvents=!0}},k=function(){var t=e(this),o=t.data(a),n=o.opt,i=a+"_"+o.idx,r=".mCSB_"+o.idx+"_scrollbar",l=e("#mCSB_"+o.idx+",#mCSB_"+o.idx+"_container,#mCSB_"+o.idx+"_container_wrapper,"+r+" ."+d[12]+",#mCSB_"+o.idx+"_dragger_vertical,#mCSB_"+o.idx+"_dragger_horizontal,"+r+">a"),s=e("#mCSB_"+o.idx+"_container");n.advanced.releaseDraggableSelectors&&l.add(e(n.advanced.releaseDraggableSelectors)),n.advanced.extraDraggableSelectors&&l.add(e(n.advanced.extraDraggableSelectors)),o.bindEvents&&(e(document).add(e(!A()||top.document)).unbind("."+i),l.each(function(){e(this).unbind("."+i)}),clearTimeout(t[0]._focusTimeout),$(t[0],"_focusTimeout"),clearTimeout(o.sequential.step),$(o.sequential,"step"),clearTimeout(s[0].onCompleteTimeout),$(s[0],"onCompleteTimeout"),o.bindEvents=!1)},M=function(t){var o=e(this),n=o.data(a),i=n.opt,r=e("#mCSB_"+n.idx+"_container_wrapper"),l=r.length?r:e("#mCSB_"+n.idx+"_container"),s=[e("#mCSB_"+n.idx+"_scrollbar_vertical"),e("#mCSB_"+n.idx+"_scrollbar_horizontal")],c=[s[0].find(".mCSB_dragger"),s[1].find(".mCSB_dragger")];"x"!==i.axis&&(n.overflowed[0]&&!t?(s[0].add(c[0]).add(s[0].children("a")).css("display","block"),l.removeClass(d[8]+" "+d[10])):(i.alwaysShowScrollbar?(2!==i.alwaysShowScrollbar&&c[0].css("display","none"),l.removeClass(d[10])):(s[0].css("display","none"),l.addClass(d[10])),l.addClass(d[8]))),"y"!==i.axis&&(n.overflowed[1]&&!t?(s[1].add(c[1]).add(s[1].children("a")).css("display","block"),l.removeClass(d[9]+" "+d[11])):(i.alwaysShowScrollbar?(2!==i.alwaysShowScrollbar&&c[1].css("display","none"),l.removeClass(d[11])):(s[1].css("display","none"),l.addClass(d[11])),l.addClass(d[9]))),n.overflowed[0]||n.overflowed[1]?o.removeClass(d[5]):o.addClass(d[5])},O=function(t){var o=t.type,a=t.target.ownerDocument!==document&&null!==frameElement?[e(frameElement).offset().top,e(frameElement).offset().left]:null,n=A()&&t.target.ownerDocument!==top.document&&null!==frameElement?[e(t.view.frameElement).offset().top,e(t.view.frameElement).offset().left]:[0,0];switch(o){case"pointerdown":case"MSPointerDown":case"pointermove":case"MSPointerMove":case"pointerup":case"MSPointerUp":return a?[t.originalEvent.pageY-a[0]+n[0],t.originalEvent.pageX-a[1]+n[1],!1]:[t.originalEvent.pageY,t.originalEvent.pageX,!1];case"touchstart":case"touchmove":case"touchend":var i=t.originalEvent.touches[0]||t.originalEvent.changedTouches[0],r=t.originalEvent.touches.length||t.originalEvent.changedTouches.length;return t.target.ownerDocument!==document?[i.screenY,i.screenX,r>1]:[i.pageY,i.pageX,r>1];default:return a?[t.pageY-a[0]+n[0],t.pageX-a[1]+n[1],!1]:[t.pageY,t.pageX,!1]}},I=function(){function t(e,t,a,n){if(h[0].idleTimer=d.scrollInertia<233?250:0,o.attr("id")===f[1])var i="x",s=(o[0].offsetLeft-t+n)*l.scrollRatio.x;else var i="y",s=(o[0].offsetTop-e+a)*l.scrollRatio.y;G(r,s.toString(),{dir:i,drag:!0})}var o,n,i,r=e(this),l=r.data(a),d=l.opt,u=a+"_"+l.idx,f=["mCSB_"+l.idx+"_dragger_vertical","mCSB_"+l.idx+"_dragger_horizontal"],h=e("#mCSB_"+l.idx+"_container"),m=e("#"+f[0]+",#"+f[1]),p=d.advanced.releaseDraggableSelectors?m.add(e(d.advanced.releaseDraggableSelectors)):m,g=d.advanced.extraDraggableSelectors?e(!A()||top.document).add(e(d.advanced.extraDraggableSelectors)):e(!A()||top.document);m.bind("contextmenu."+u,function(e){e.preventDefault()}).bind("mousedown."+u+" touchstart."+u+" pointerdown."+u+" MSPointerDown."+u,function(t){if(t.stopImmediatePropagation(),t.preventDefault(),ee(t)){c=!0,s&&(document.onselectstart=function(){return!1}),L.call(h,!1),Q(r),o=e(this);var a=o.offset(),l=O(t)[0]-a.top,u=O(t)[1]-a.left,f=o.height()+a.top,m=o.width()+a.left;f>l&&l>0&&m>u&&u>0&&(n=l,i=u),C(o,"active",d.autoExpandScrollbar)}}).bind("touchmove."+u,function(e){e.stopImmediatePropagation(),e.preventDefault();var a=o.offset(),r=O(e)[0]-a.top,l=O(e)[1]-a.left;t(n,i,r,l)}),e(document).add(g).bind("mousemove."+u+" pointermove."+u+" MSPointerMove."+u,function(e){if(o){var a=o.offset(),r=O(e)[0]-a.top,l=O(e)[1]-a.left;if(n===r&&i===l)return;t(n,i,r,l)}}).add(p).bind("mouseup."+u+" touchend."+u+" pointerup."+u+" MSPointerUp."+u,function(){o&&(C(o,"active",d.autoExpandScrollbar),o=null),c=!1,s&&(document.onselectstart=null),L.call(h,!0)})},D=function(){function o(e){if(!te(e)||c||O(e)[2])return void(t=0);t=1,b=0,C=0,d=1,y.removeClass("mCS_touch_action");var o=I.offset();u=O(e)[0]-o.top,f=O(e)[1]-o.left,z=[O(e)[0],O(e)[1]]}function n(e){if(te(e)&&!c&&!O(e)[2]&&(T.documentTouchScroll||e.preventDefault(),e.stopImmediatePropagation(),(!C||b)&&d)){g=K();var t=M.offset(),o=O(e)[0]-t.top,a=O(e)[1]-t.left,n="mcsLinearOut";if(E.push(o),W.push(a),z[2]=Math.abs(O(e)[0]-z[0]),z[3]=Math.abs(O(e)[1]-z[1]),B.overflowed[0])var i=D[0].parent().height()-D[0].height(),r=u-o>0&&o-u>-(i*B.scrollRatio.y)&&(2*z[3]<z[2]||"yx"===T.axis);if(B.overflowed[1])var l=D[1].parent().width()-D[1].width(),h=f-a>0&&a-f>-(l*B.scrollRatio.x)&&(2*z[2]<z[3]||"yx"===T.axis);r||h?(U||e.preventDefault(),b=1):(C=1,y.addClass("mCS_touch_action")),U&&e.preventDefault(),w="yx"===T.axis?[u-o,f-a]:"x"===T.axis?[null,f-a]:[u-o,null],I[0].idleTimer=250,B.overflowed[0]&&s(w[0],R,n,"y","all",!0),B.overflowed[1]&&s(w[1],R,n,"x",L,!0)}}function i(e){if(!te(e)||c||O(e)[2])return void(t=0);t=1,e.stopImmediatePropagation(),Q(y),p=K();var o=M.offset();h=O(e)[0]-o.top,m=O(e)[1]-o.left,E=[],W=[]}function r(e){if(te(e)&&!c&&!O(e)[2]){d=0,e.stopImmediatePropagation(),b=0,C=0,v=K();var t=M.offset(),o=O(e)[0]-t.top,a=O(e)[1]-t.left;if(!(v-g>30)){_=1e3/(v-p);var n="mcsEaseOut",i=2.5>_,r=i?[E[E.length-2],W[W.length-2]]:[0,0];x=i?[o-r[0],a-r[1]]:[o-h,a-m];var u=[Math.abs(x[0]),Math.abs(x[1])];_=i?[Math.abs(x[0]/4),Math.abs(x[1]/4)]:[_,_];var f=[Math.abs(I[0].offsetTop)-x[0]*l(u[0]/_[0],_[0]),Math.abs(I[0].offsetLeft)-x[1]*l(u[1]/_[1],_[1])];w="yx"===T.axis?[f[0],f[1]]:"x"===T.axis?[null,f[1]]:[f[0],null],S=[4*u[0]+T.scrollInertia,4*u[1]+T.scrollInertia];var y=parseInt(T.contentTouchScroll)||0;w[0]=u[0]>y?w[0]:0,w[1]=u[1]>y?w[1]:0,B.overflowed[0]&&s(w[0],S[0],n,"y",L,!1),B.overflowed[1]&&s(w[1],S[1],n,"x",L,!1)}}}function l(e,t){var o=[1.5*t,2*t,t/1.5,t/2];return e>90?t>4?o[0]:o[3]:e>60?t>3?o[3]:o[2]:e>30?t>8?o[1]:t>6?o[0]:t>4?t:o[2]:t>8?t:o[3]}function s(e,t,o,a,n,i){e&&G(y,e.toString(),{dur:t,scrollEasing:o,dir:a,overwrite:n,drag:i})}var d,u,f,h,m,p,g,v,x,_,w,S,b,C,y=e(this),B=y.data(a),T=B.opt,k=a+"_"+B.idx,M=e("#mCSB_"+B.idx),I=e("#mCSB_"+B.idx+"_container"),D=[e("#mCSB_"+B.idx+"_dragger_vertical"),e("#mCSB_"+B.idx+"_dragger_horizontal")],E=[],W=[],R=0,L="yx"===T.axis?"none":"all",z=[],P=I.find("iframe"),H=["touchstart."+k+" pointerdown."+k+" MSPointerDown."+k,"touchmove."+k+" pointermove."+k+" MSPointerMove."+k,"touchend."+k+" pointerup."+k+" MSPointerUp."+k],U=void 0!==document.body.style.touchAction&&""!==document.body.style.touchAction;I.bind(H[0],function(e){o(e)}).bind(H[1],function(e){n(e)}),M.bind(H[0],function(e){i(e)}).bind(H[2],function(e){r(e)}),P.length&&P.each(function(){e(this).bind("load",function(){A(this)&&e(this.contentDocument||this.contentWindow.document).bind(H[0],function(e){o(e),i(e)}).bind(H[1],function(e){n(e)}).bind(H[2],function(e){r(e)})})})},E=function(){function o(){return window.getSelection?window.getSelection().toString():document.selection&&"Control"!=document.selection.type?document.selection.createRange().text:0}function n(e,t,o){d.type=o&&i?"stepped":"stepless",d.scrollAmount=10,j(r,e,t,"mcsLinearOut",o?60:null)}var i,r=e(this),l=r.data(a),s=l.opt,d=l.sequential,u=a+"_"+l.idx,f=e("#mCSB_"+l.idx+"_container"),h=f.parent();f.bind("mousedown."+u,function(){t||i||(i=1,c=!0)}).add(document).bind("mousemove."+u,function(e){if(!t&&i&&o()){var a=f.offset(),r=O(e)[0]-a.top+f[0].offsetTop,c=O(e)[1]-a.left+f[0].offsetLeft;r>0&&r<h.height()&&c>0&&c<h.width()?d.step&&n("off",null,"stepped"):("x"!==s.axis&&l.overflowed[0]&&(0>r?n("on",38):r>h.height()&&n("on",40)),"y"!==s.axis&&l.overflowed[1]&&(0>c?n("on",37):c>h.width()&&n("on",39)))}}).bind("mouseup."+u+" dragend."+u,function(){t||(i&&(i=0,n("off",null)),c=!1)})},W=function(){function t(t,a){if(Q(o),!z(o,t.target)){var r="auto"!==i.mouseWheel.deltaFactor?parseInt(i.mouseWheel.deltaFactor):s&&t.deltaFactor<100?100:t.deltaFactor||100,d=i.scrollInertia;if("x"===i.axis||"x"===i.mouseWheel.axis)var u="x",f=[Math.round(r*n.scrollRatio.x),parseInt(i.mouseWheel.scrollAmount)],h="auto"!==i.mouseWheel.scrollAmount?f[1]:f[0]>=l.width()?.9*l.width():f[0],m=Math.abs(e("#mCSB_"+n.idx+"_container")[0].offsetLeft),p=c[1][0].offsetLeft,g=c[1].parent().width()-c[1].width(),v="y"===i.mouseWheel.axis?t.deltaY||a:t.deltaX;else var u="y",f=[Math.round(r*n.scrollRatio.y),parseInt(i.mouseWheel.scrollAmount)],h="auto"!==i.mouseWheel.scrollAmount?f[1]:f[0]>=l.height()?.9*l.height():f[0],m=Math.abs(e("#mCSB_"+n.idx+"_container")[0].offsetTop),p=c[0][0].offsetTop,g=c[0].parent().height()-c[0].height(),v=t.deltaY||a;"y"===u&&!n.overflowed[0]||"x"===u&&!n.overflowed[1]||((i.mouseWheel.invert||t.webkitDirectionInvertedFromDevice)&&(v=-v),i.mouseWheel.normalizeDelta&&(v=0>v?-1:1),(v>0&&0!==p||0>v&&p!==g||i.mouseWheel.preventDefault)&&(t.stopImmediatePropagation(),t.preventDefault()),t.deltaFactor<5&&!i.mouseWheel.normalizeDelta&&(h=t.deltaFactor,d=17),G(o,(m-v*h).toString(),{dir:u,dur:d}))}}if(e(this).data(a)){var o=e(this),n=o.data(a),i=n.opt,r=a+"_"+n.idx,l=e("#mCSB_"+n.idx),c=[e("#mCSB_"+n.idx+"_dragger_vertical"),e("#mCSB_"+n.idx+"_dragger_horizontal")],d=e("#mCSB_"+n.idx+"_container").find("iframe");d.length&&d.each(function(){e(this).bind("load",function(){A(this)&&e(this.contentDocument||this.contentWindow.document).bind("mousewheel."+r,function(e,o){t(e,o)})})}),l.bind("mousewheel."+r,function(e,o){t(e,o)})}},R=new Object,A=function(t){var o=!1,a=!1,n=null;if(void 0===t?a="#empty":void 0!==e(t).attr("id")&&(a=e(t).attr("id")),a!==!1&&void 0!==R[a])return R[a];if(t){try{var i=t.contentDocument||t.contentWindow.document;n=i.body.innerHTML}catch(r){}o=null!==n}else{try{var i=top.document;n=i.body.innerHTML}catch(r){}o=null!==n}return a!==!1&&(R[a]=o),o},L=function(e){var t=this.find("iframe");if(t.length){var o=e?"auto":"none";t.css("pointer-events",o)}},z=function(t,o){var n=o.nodeName.toLowerCase(),i=t.data(a).opt.mouseWheel.disableOver,r=["select","textarea"];return e.inArray(n,i)>-1&&!(e.inArray(n,r)>-1&&!e(o).is(":focus"))},P=function(){var t,o=e(this),n=o.data(a),i=a+"_"+n.idx,r=e("#mCSB_"+n.idx+"_container"),l=r.parent(),s=e(".mCSB_"+n.idx+"_scrollbar ."+d[12]);s.bind("mousedown."+i+" touchstart."+i+" pointerdown."+i+" MSPointerDown."+i,function(o){c=!0,e(o.target).hasClass("mCSB_dragger")||(t=1)}).bind("touchend."+i+" pointerup."+i+" MSPointerUp."+i,function(){c=!1}).bind("click."+i,function(a){if(t&&(t=0,e(a.target).hasClass(d[12])||e(a.target).hasClass("mCSB_draggerRail"))){Q(o);var i=e(this),s=i.find(".mCSB_dragger");if(i.parent(".mCSB_scrollTools_horizontal").length>0){if(!n.overflowed[1])return;var c="x",u=a.pageX>s.offset().left?-1:1,f=Math.abs(r[0].offsetLeft)-u*(.9*l.width())}else{if(!n.overflowed[0])return;var c="y",u=a.pageY>s.offset().top?-1:1,f=Math.abs(r[0].offsetTop)-u*(.9*l.height())}G(o,f.toString(),{dir:c,scrollEasing:"mcsEaseInOut"})}})},H=function(){var t=e(this),o=t.data(a),n=o.opt,i=a+"_"+o.idx,r=e("#mCSB_"+o.idx+"_container"),l=r.parent();r.bind("focusin."+i,function(){var o=e(document.activeElement),a=r.find(".mCustomScrollBox").length,i=0;o.is(n.advanced.autoScrollOnFocus)&&(Q(t),clearTimeout(t[0]._focusTimeout),t[0]._focusTimer=a?(i+17)*a:0,t[0]._focusTimeout=setTimeout(function(){var e=[ae(o)[0],ae(o)[1]],a=[r[0].offsetTop,r[0].offsetLeft],s=[a[0]+e[0]>=0&&a[0]+e[0]<l.height()-o.outerHeight(!1),a[1]+e[1]>=0&&a[0]+e[1]<l.width()-o.outerWidth(!1)],c="yx"!==n.axis||s[0]||s[1]?"all":"none";"x"===n.axis||s[0]||G(t,e[0].toString(),{dir:"y",scrollEasing:"mcsEaseInOut",overwrite:c,dur:i}),"y"===n.axis||s[1]||G(t,e[1].toString(),{dir:"x",scrollEasing:"mcsEaseInOut",overwrite:c,dur:i})},t[0]._focusTimer))})},U=function(){var t=e(this),o=t.data(a),n=a+"_"+o.idx,i=e("#mCSB_"+o.idx+"_container").parent();i.bind("scroll."+n,function(){0===i.scrollTop()&&0===i.scrollLeft()||e(".mCSB_"+o.idx+"_scrollbar").css("visibility","hidden")})},F=function(){var t=e(this),o=t.data(a),n=o.opt,i=o.sequential,r=a+"_"+o.idx,l=".mCSB_"+o.idx+"_scrollbar",s=e(l+">a");s.bind("contextmenu."+r,function(e){e.preventDefault()}).bind("mousedown."+r+" touchstart."+r+" pointerdown."+r+" MSPointerDown."+r+" mouseup."+r+" touchend."+r+" pointerup."+r+" MSPointerUp."+r+" mouseout."+r+" pointerout."+r+" MSPointerOut."+r+" click."+r,function(a){function r(e,o){i.scrollAmount=n.scrollButtons.scrollAmount,j(t,e,o)}if(a.preventDefault(),ee(a)){var l=e(this).attr("class");switch(i.type=n.scrollButtons.scrollType,a.type){case"mousedown":case"touchstart":case"pointerdown":case"MSPointerDown":if("stepped"===i.type)return;c=!0,o.tweenRunning=!1,r("on",l);break;case"mouseup":case"touchend":case"pointerup":case"MSPointerUp":case"mouseout":case"pointerout":case"MSPointerOut":if("stepped"===i.type)return;c=!1,i.dir&&r("off",l);break;case"click":if("stepped"!==i.type||o.tweenRunning)return;r("on",l)}}})},q=function(){function t(t){function a(e,t){r.type=i.keyboard.scrollType,r.scrollAmount=i.keyboard.scrollAmount,"stepped"===r.type&&n.tweenRunning||j(o,e,t)}switch(t.type){case"blur":n.tweenRunning&&r.dir&&a("off",null);break;case"keydown":case"keyup":var l=t.keyCode?t.keyCode:t.which,s="on";if("x"!==i.axis&&(38===l||40===l)||"y"!==i.axis&&(37===l||39===l)){if((38===l||40===l)&&!n.overflowed[0]||(37===l||39===l)&&!n.overflowed[1])return;"keyup"===t.type&&(s="off"),e(document.activeElement).is(u)||(t.preventDefault(),t.stopImmediatePropagation(),a(s,l))}else if(33===l||34===l){if((n.overflowed[0]||n.overflowed[1])&&(t.preventDefault(),t.stopImmediatePropagation()),"keyup"===t.type){Q(o);var f=34===l?-1:1;if("x"===i.axis||"yx"===i.axis&&n.overflowed[1]&&!n.overflowed[0])var h="x",m=Math.abs(c[0].offsetLeft)-f*(.9*d.width());else var h="y",m=Math.abs(c[0].offsetTop)-f*(.9*d.height());G(o,m.toString(),{dir:h,scrollEasing:"mcsEaseInOut"})}}else if((35===l||36===l)&&!e(document.activeElement).is(u)&&((n.overflowed[0]||n.overflowed[1])&&(t.preventDefault(),t.stopImmediatePropagation()),"keyup"===t.type)){if("x"===i.axis||"yx"===i.axis&&n.overflowed[1]&&!n.overflowed[0])var h="x",m=35===l?Math.abs(d.width()-c.outerWidth(!1)):0;else var h="y",m=35===l?Math.abs(d.height()-c.outerHeight(!1)):0;G(o,m.toString(),{dir:h,scrollEasing:"mcsEaseInOut"})}}}var o=e(this),n=o.data(a),i=n.opt,r=n.sequential,l=a+"_"+n.idx,s=e("#mCSB_"+n.idx),c=e("#mCSB_"+n.idx+"_container"),d=c.parent(),u="input,textarea,select,datalist,keygen,[contenteditable='true']",f=c.find("iframe"),h=["blur."+l+" keydown."+l+" keyup."+l];f.length&&f.each(function(){e(this).bind("load",function(){A(this)&&e(this.contentDocument||this.contentWindow.document).bind(h[0],function(e){t(e)})})}),s.attr("tabindex","0").bind(h[0],function(e){t(e)})},j=function(t,o,n,i,r){function l(e){u.snapAmount&&(f.scrollAmount=u.snapAmount instanceof Array?"x"===f.dir[0]?u.snapAmount[1]:u.snapAmount[0]:u.snapAmount);var o="stepped"!==f.type,a=r?r:e?o?p/1.5:g:1e3/60,n=e?o?7.5:40:2.5,s=[Math.abs(h[0].offsetTop),Math.abs(h[0].offsetLeft)],d=[c.scrollRatio.y>10?10:c.scrollRatio.y,c.scrollRatio.x>10?10:c.scrollRatio.x],m="x"===f.dir[0]?s[1]+f.dir[1]*(d[1]*n):s[0]+f.dir[1]*(d[0]*n),v="x"===f.dir[0]?s[1]+f.dir[1]*parseInt(f.scrollAmount):s[0]+f.dir[1]*parseInt(f.scrollAmount),x="auto"!==f.scrollAmount?v:m,_=i?i:e?o?"mcsLinearOut":"mcsEaseInOut":"mcsLinear",w=!!e;return e&&17>a&&(x="x"===f.dir[0]?s[1]:s[0]),G(t,x.toString(),{dir:f.dir[0],scrollEasing:_,dur:a,onComplete:w}),e?void(f.dir=!1):(clearTimeout(f.step),void(f.step=setTimeout(function(){l()},a)))}function s(){clearTimeout(f.step),$(f,"step"),Q(t)}var c=t.data(a),u=c.opt,f=c.sequential,h=e("#mCSB_"+c.idx+"_container"),m="stepped"===f.type,p=u.scrollInertia<26?26:u.scrollInertia,g=u.scrollInertia<1?17:u.scrollInertia;switch(o){case"on":if(f.dir=[n===d[16]||n===d[15]||39===n||37===n?"x":"y",n===d[13]||n===d[15]||38===n||37===n?-1:1],Q(t),oe(n)&&"stepped"===f.type)return;l(m);break;case"off":s(),(m||c.tweenRunning&&f.dir)&&l(!0)}},Y=function(t){var o=e(this).data(a).opt,n=[];return"function"==typeof t&&(t=t()),t instanceof Array?n=t.length>1?[t[0],t[1]]:"x"===o.axis?[null,t[0]]:[t[0],null]:(n[0]=t.y?t.y:t.x||"x"===o.axis?null:t,n[1]=t.x?t.x:t.y||"y"===o.axis?null:t),"function"==typeof n[0]&&(n[0]=n[0]()),"function"==typeof n[1]&&(n[1]=n[1]()),n},X=function(t,o){if(null!=t&&"undefined"!=typeof t){var n=e(this),i=n.data(a),r=i.opt,l=e("#mCSB_"+i.idx+"_container"),s=l.parent(),c=typeof t;o||(o="x"===r.axis?"x":"y");var d="x"===o?l.outerWidth(!1)-s.width():l.outerHeight(!1)-s.height(),f="x"===o?l[0].offsetLeft:l[0].offsetTop,h="x"===o?"left":"top";switch(c){case"function":return t();case"object":var m=t.jquery?t:e(t);if(!m.length)return;return"x"===o?ae(m)[1]:ae(m)[0];case"string":case"number":if(oe(t))return Math.abs(t);if(-1!==t.indexOf("%"))return Math.abs(d*parseInt(t)/100);if(-1!==t.indexOf("-="))return Math.abs(f-parseInt(t.split("-=")[1]));if(-1!==t.indexOf("+=")){var p=f+parseInt(t.split("+=")[1]);return p>=0?0:Math.abs(p)}if(-1!==t.indexOf("px")&&oe(t.split("px")[0]))return Math.abs(t.split("px")[0]);if("top"===t||"left"===t)return 0;if("bottom"===t)return Math.abs(s.height()-l.outerHeight(!1));if("right"===t)return Math.abs(s.width()-l.outerWidth(!1));if("first"===t||"last"===t){var m=l.find(":"+t);return"x"===o?ae(m)[1]:ae(m)[0]}return e(t).length?"x"===o?ae(e(t))[1]:ae(e(t))[0]:(l.css(h,t),void u.update.call(null,n[0]))}}},N=function(t){function o(){return clearTimeout(f[0].autoUpdate),0===l.parents("html").length?void(l=null):void(f[0].autoUpdate=setTimeout(function(){return c.advanced.updateOnSelectorChange&&(s.poll.change.n=i(),s.poll.change.n!==s.poll.change.o)?(s.poll.change.o=s.poll.change.n,void r(3)):c.advanced.updateOnContentResize&&(s.poll.size.n=l[0].scrollHeight+l[0].scrollWidth+f[0].offsetHeight+l[0].offsetHeight+l[0].offsetWidth,s.poll.size.n!==s.poll.size.o)?(s.poll.size.o=s.poll.size.n,void r(1)):!c.advanced.updateOnImageLoad||"auto"===c.advanced.updateOnImageLoad&&"y"===c.axis||(s.poll.img.n=f.find("img").length,s.poll.img.n===s.poll.img.o)?void((c.advanced.updateOnSelectorChange||c.advanced.updateOnContentResize||c.advanced.updateOnImageLoad)&&o()):(s.poll.img.o=s.poll.img.n,void f.find("img").each(function(){n(this)}))},c.advanced.autoUpdateTimeout))}function n(t){function o(e,t){return function(){
    return t.apply(e,arguments)}}function a(){this.onload=null,e(t).addClass(d[2]),r(2)}if(e(t).hasClass(d[2]))return void r();var n=new Image;n.onload=o(n,a),n.src=t.src}function i(){c.advanced.updateOnSelectorChange===!0&&(c.advanced.updateOnSelectorChange="*");var e=0,t=f.find(c.advanced.updateOnSelectorChange);return c.advanced.updateOnSelectorChange&&t.length>0&&t.each(function(){e+=this.offsetHeight+this.offsetWidth}),e}function r(e){clearTimeout(f[0].autoUpdate),u.update.call(null,l[0],e)}var l=e(this),s=l.data(a),c=s.opt,f=e("#mCSB_"+s.idx+"_container");return t?(clearTimeout(f[0].autoUpdate),void $(f[0],"autoUpdate")):void o()},V=function(e,t,o){return Math.round(e/t)*t-o},Q=function(t){var o=t.data(a),n=e("#mCSB_"+o.idx+"_container,#mCSB_"+o.idx+"_container_wrapper,#mCSB_"+o.idx+"_dragger_vertical,#mCSB_"+o.idx+"_dragger_horizontal");n.each(function(){Z.call(this)})},G=function(t,o,n){function i(e){return s&&c.callbacks[e]&&"function"==typeof c.callbacks[e]}function r(){return[c.callbacks.alwaysTriggerOffsets||w>=S[0]+y,c.callbacks.alwaysTriggerOffsets||-B>=w]}function l(){var e=[h[0].offsetTop,h[0].offsetLeft],o=[x[0].offsetTop,x[0].offsetLeft],a=[h.outerHeight(!1),h.outerWidth(!1)],i=[f.height(),f.width()];t[0].mcs={content:h,top:e[0],left:e[1],draggerTop:o[0],draggerLeft:o[1],topPct:Math.round(100*Math.abs(e[0])/(Math.abs(a[0])-i[0])),leftPct:Math.round(100*Math.abs(e[1])/(Math.abs(a[1])-i[1])),direction:n.dir}}var s=t.data(a),c=s.opt,d={trigger:"internal",dir:"y",scrollEasing:"mcsEaseOut",drag:!1,dur:c.scrollInertia,overwrite:"all",callbacks:!0,onStart:!0,onUpdate:!0,onComplete:!0},n=e.extend(d,n),u=[n.dur,n.drag?0:n.dur],f=e("#mCSB_"+s.idx),h=e("#mCSB_"+s.idx+"_container"),m=h.parent(),p=c.callbacks.onTotalScrollOffset?Y.call(t,c.callbacks.onTotalScrollOffset):[0,0],g=c.callbacks.onTotalScrollBackOffset?Y.call(t,c.callbacks.onTotalScrollBackOffset):[0,0];if(s.trigger=n.trigger,0===m.scrollTop()&&0===m.scrollLeft()||(e(".mCSB_"+s.idx+"_scrollbar").css("visibility","visible"),m.scrollTop(0).scrollLeft(0)),"_resetY"!==o||s.contentReset.y||(i("onOverflowYNone")&&c.callbacks.onOverflowYNone.call(t[0]),s.contentReset.y=1),"_resetX"!==o||s.contentReset.x||(i("onOverflowXNone")&&c.callbacks.onOverflowXNone.call(t[0]),s.contentReset.x=1),"_resetY"!==o&&"_resetX"!==o){if(!s.contentReset.y&&t[0].mcs||!s.overflowed[0]||(i("onOverflowY")&&c.callbacks.onOverflowY.call(t[0]),s.contentReset.x=null),!s.contentReset.x&&t[0].mcs||!s.overflowed[1]||(i("onOverflowX")&&c.callbacks.onOverflowX.call(t[0]),s.contentReset.x=null),c.snapAmount){var v=c.snapAmount instanceof Array?"x"===n.dir?c.snapAmount[1]:c.snapAmount[0]:c.snapAmount;o=V(o,v,c.snapOffset)}switch(n.dir){case"x":var x=e("#mCSB_"+s.idx+"_dragger_horizontal"),_="left",w=h[0].offsetLeft,S=[f.width()-h.outerWidth(!1),x.parent().width()-x.width()],b=[o,0===o?0:o/s.scrollRatio.x],y=p[1],B=g[1],T=y>0?y/s.scrollRatio.x:0,k=B>0?B/s.scrollRatio.x:0;break;case"y":var x=e("#mCSB_"+s.idx+"_dragger_vertical"),_="top",w=h[0].offsetTop,S=[f.height()-h.outerHeight(!1),x.parent().height()-x.height()],b=[o,0===o?0:o/s.scrollRatio.y],y=p[0],B=g[0],T=y>0?y/s.scrollRatio.y:0,k=B>0?B/s.scrollRatio.y:0}b[1]<0||0===b[0]&&0===b[1]?b=[0,0]:b[1]>=S[1]?b=[S[0],S[1]]:b[0]=-b[0],t[0].mcs||(l(),i("onInit")&&c.callbacks.onInit.call(t[0])),clearTimeout(h[0].onCompleteTimeout),J(x[0],_,Math.round(b[1]),u[1],n.scrollEasing),!s.tweenRunning&&(0===w&&b[0]>=0||w===S[0]&&b[0]<=S[0])||J(h[0],_,Math.round(b[0]),u[0],n.scrollEasing,n.overwrite,{onStart:function(){n.callbacks&&n.onStart&&!s.tweenRunning&&(i("onScrollStart")&&(l(),c.callbacks.onScrollStart.call(t[0])),s.tweenRunning=!0,C(x),s.cbOffsets=r())},onUpdate:function(){n.callbacks&&n.onUpdate&&i("whileScrolling")&&(l(),c.callbacks.whileScrolling.call(t[0]))},onComplete:function(){if(n.callbacks&&n.onComplete){"yx"===c.axis&&clearTimeout(h[0].onCompleteTimeout);var e=h[0].idleTimer||0;h[0].onCompleteTimeout=setTimeout(function(){i("onScroll")&&(l(),c.callbacks.onScroll.call(t[0])),i("onTotalScroll")&&b[1]>=S[1]-T&&s.cbOffsets[0]&&(l(),c.callbacks.onTotalScroll.call(t[0])),i("onTotalScrollBack")&&b[1]<=k&&s.cbOffsets[1]&&(l(),c.callbacks.onTotalScrollBack.call(t[0])),s.tweenRunning=!1,h[0].idleTimer=0,C(x,"hide")},e)}}})}},J=function(e,t,o,a,n,i,r){function l(){S.stop||(x||m.call(),x=K()-v,s(),x>=S.time&&(S.time=x>S.time?x+f-(x-S.time):x+f-1,S.time<x+1&&(S.time=x+1)),S.time<a?S.id=h(l):g.call())}function s(){a>0?(S.currVal=u(S.time,_,b,a,n),w[t]=Math.round(S.currVal)+"px"):w[t]=o+"px",p.call()}function c(){f=1e3/60,S.time=x+f,h=window.requestAnimationFrame?window.requestAnimationFrame:function(e){return s(),setTimeout(e,.01)},S.id=h(l)}function d(){null!=S.id&&(window.requestAnimationFrame?window.cancelAnimationFrame(S.id):clearTimeout(S.id),S.id=null)}function u(e,t,o,a,n){switch(n){case"linear":case"mcsLinear":return o*e/a+t;case"mcsLinearOut":return e/=a,e--,o*Math.sqrt(1-e*e)+t;case"easeInOutSmooth":return e/=a/2,1>e?o/2*e*e+t:(e--,-o/2*(e*(e-2)-1)+t);case"easeInOutStrong":return e/=a/2,1>e?o/2*Math.pow(2,10*(e-1))+t:(e--,o/2*(-Math.pow(2,-10*e)+2)+t);case"easeInOut":case"mcsEaseInOut":return e/=a/2,1>e?o/2*e*e*e+t:(e-=2,o/2*(e*e*e+2)+t);case"easeOutSmooth":return e/=a,e--,-o*(e*e*e*e-1)+t;case"easeOutStrong":return o*(-Math.pow(2,-10*e/a)+1)+t;case"easeOut":case"mcsEaseOut":default:var i=(e/=a)*e,r=i*e;return t+o*(.499999999999997*r*i+-2.5*i*i+5.5*r+-6.5*i+4*e)}}e._mTween||(e._mTween={top:{},left:{}});var f,h,r=r||{},m=r.onStart||function(){},p=r.onUpdate||function(){},g=r.onComplete||function(){},v=K(),x=0,_=e.offsetTop,w=e.style,S=e._mTween[t];"left"===t&&(_=e.offsetLeft);var b=o-_;S.stop=0,"none"!==i&&d(),c()},K=function(){return window.performance&&window.performance.now?window.performance.now():window.performance&&window.performance.webkitNow?window.performance.webkitNow():Date.now?Date.now():(new Date).getTime()},Z=function(){var e=this;e._mTween||(e._mTween={top:{},left:{}});for(var t=["top","left"],o=0;o<t.length;o++){var a=t[o];e._mTween[a].id&&(window.requestAnimationFrame?window.cancelAnimationFrame(e._mTween[a].id):clearTimeout(e._mTween[a].id),e._mTween[a].id=null,e._mTween[a].stop=1)}},$=function(e,t){try{delete e[t]}catch(o){e[t]=null}},ee=function(e){return!(e.which&&1!==e.which)},te=function(e){var t=e.originalEvent.pointerType;return!(t&&"touch"!==t&&2!==t)},oe=function(e){return!isNaN(parseFloat(e))&&isFinite(e)},ae=function(e){var t=e.parents(".mCSB_container");return[e.offset().top-t.offset().top,e.offset().left-t.offset().left]},ne=function(){function e(){var e=["webkit","moz","ms","o"];if("hidden"in document)return"hidden";for(var t=0;t<e.length;t++)if(e[t]+"Hidden"in document)return e[t]+"Hidden";return null}var t=e();return t?document[t]:!1};e.fn[o]=function(t){return u[t]?u[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void e.error("Method "+t+" does not exist"):u.init.apply(this,arguments)},e[o]=function(t){return u[t]?u[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void e.error("Method "+t+" does not exist"):u.init.apply(this,arguments)},e[o].defaults=i,window[o]=!0,e(window).bind("load",function(){e(n)[o](),e.extend(e.expr[":"],{mcsInView:e.expr[":"].mcsInView||function(t){var o,a,n=e(t),i=n.parents(".mCSB_container");if(i.length)return o=i.parent(),a=[i[0].offsetTop,i[0].offsetLeft],a[0]+ae(n)[0]>=0&&a[0]+ae(n)[0]<o.height()-n.outerHeight(!1)&&a[1]+ae(n)[1]>=0&&a[1]+ae(n)[1]<o.width()-n.outerWidth(!1)},mcsInSight:e.expr[":"].mcsInSight||function(t,o,a){var n,i,r,l,s=e(t),c=s.parents(".mCSB_container"),d="exact"===a[3]?[[1,0],[1,0]]:[[.9,.1],[.6,.4]];if(c.length)return n=[s.outerHeight(!1),s.outerWidth(!1)],r=[c[0].offsetTop+ae(s)[0],c[0].offsetLeft+ae(s)[1]],i=[c.parent()[0].offsetHeight,c.parent()[0].offsetWidth],l=[n[0]<i[0]?d[0]:d[1],n[1]<i[1]?d[0]:d[1]],r[0]-i[0]*l[0][0]<0&&r[0]+n[0]-i[0]*l[0][1]>=0&&r[1]-i[1]*l[1][0]<0&&r[1]+n[1]-i[1]*l[1][1]>=0},mcsOverflow:e.expr[":"].mcsOverflow||function(t){var o=e(t).data(a);if(o)return o.overflowed[0]||o.overflowed[1]}})})})});


/**
 * @license
 * Fuse - Lightweight fuzzy-search
 *
 * Copyright (c) 2012-2016 Kirollos Risk <kirollos@gmail.com>.
 * All Rights Reserved. Apache Software License 2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License")
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
!function(t){"use strict";function e(){console.log.apply(console,arguments)}function s(t,e){var s;this.list=t,this.options=e=e||{};for(s in r)r.hasOwnProperty(s)&&("boolean"==typeof r[s]?this.options[s]=s in e?e[s]:r[s]:this.options[s]=e[s]||r[s])}function n(t,e,s){var o,r,h,a,c,p;if(e){if(h=e.indexOf("."),h!==-1?(o=e.slice(0,h),r=e.slice(h+1)):o=e,a=t[o],null!==a&&void 0!==a)if(r||"string"!=typeof a&&"number"!=typeof a)if(i(a))for(c=0,p=a.length;c<p;c++)n(a[c],r,s);else r&&n(a,r,s);else s.push(a)}else s.push(t);return s}function i(t){return"[object Array]"===Object.prototype.toString.call(t)}function o(t,e){e=e||{},this.options=e,this.options.location=e.location||o.defaultOptions.location,this.options.distance="distance"in e?e.distance:o.defaultOptions.distance,this.options.threshold="threshold"in e?e.threshold:o.defaultOptions.threshold,this.options.maxPatternLength=e.maxPatternLength||o.defaultOptions.maxPatternLength,this.pattern=e.caseSensitive?t:t.toLowerCase(),this.patternLen=t.length,this.patternLen<=this.options.maxPatternLength&&(this.matchmask=1<<this.patternLen-1,this.patternAlphabet=this._calculatePatternAlphabet())}var r={id:null,caseSensitive:!1,include:[],shouldSort:!0,searchFn:o,sortFn:function(t,e){return t.score-e.score},getFn:n,keys:[],verbose:!1,tokenize:!1,matchAllTokens:!1,tokenSeparator:/ +/g,minMatchCharLength:1,findAllMatches:!1};s.VERSION="2.6.0",s.prototype.set=function(t){return this.list=t,t},s.prototype.search=function(t){this.options.verbose&&e("\nSearch term:",t,"\n"),this.pattern=t,this.results=[],this.resultMap={},this._keyMap=null,this._prepareSearchers(),this._startSearch(),this._computeScore(),this._sort();var s=this._format();return s},s.prototype._prepareSearchers=function(){var t=this.options,e=this.pattern,s=t.searchFn,n=e.split(t.tokenSeparator),i=0,o=n.length;if(this.options.tokenize)for(this.tokenSearchers=[];i<o;i++)this.tokenSearchers.push(new s(n[i],t));this.fullSeacher=new s(e,t)},s.prototype._startSearch=function(){var t,e,s,n,i=this.options,o=i.getFn,r=this.list,h=r.length,a=this.options.keys,c=a.length,p=null;if("string"==typeof r[0])for(s=0;s<h;s++)this._analyze("",r[s],s,s);else for(this._keyMap={},s=0;s<h;s++)for(p=r[s],n=0;n<c;n++){if(t=a[n],"string"!=typeof t){if(e=1-t.weight||1,this._keyMap[t.name]={weight:e},t.weight<=0||t.weight>1)throw new Error("Key weight has to be > 0 and <= 1");t=t.name}else this._keyMap[t]={weight:1};this._analyze(t,o(p,t,[]),p,s)}},s.prototype._analyze=function(t,s,n,o){var r,h,a,c,p,l,u,f,d,g,m,y,v,k,S,b=this.options,M=!1;if(void 0!==s&&null!==s){h=[];var _=0;if("string"==typeof s){if(r=s.split(b.tokenSeparator),b.verbose&&e("---------\nKey:",t),this.options.tokenize){for(k=0;k<this.tokenSearchers.length;k++){for(f=this.tokenSearchers[k],b.verbose&&e("Pattern:",f.pattern),d=[],y=!1,S=0;S<r.length;S++){g=r[S],m=f.search(g);var L={};m.isMatch?(L[g]=m.score,M=!0,y=!0,h.push(m.score)):(L[g]=1,this.options.matchAllTokens||h.push(1)),d.push(L)}y&&_++,b.verbose&&e("Token scores:",d)}for(c=h[0],l=h.length,k=1;k<l;k++)c+=h[k];c/=l,b.verbose&&e("Token score average:",c)}u=this.fullSeacher.search(s),b.verbose&&e("Full text score:",u.score),p=u.score,void 0!==c&&(p=(p+c)/2),b.verbose&&e("Score average:",p),v=!this.options.tokenize||!this.options.matchAllTokens||_>=this.tokenSearchers.length,b.verbose&&e("Check Matches",v),(M||u.isMatch)&&v&&(a=this.resultMap[o],a?a.output.push({key:t,score:p,matchedIndices:u.matchedIndices}):(this.resultMap[o]={item:n,output:[{key:t,score:p,matchedIndices:u.matchedIndices}]},this.results.push(this.resultMap[o])))}else if(i(s))for(k=0;k<s.length;k++)this._analyze(t,s[k],n,o)}},s.prototype._computeScore=function(){var t,s,n,i,o,r,h,a,c,p=this._keyMap,l=this.results;for(this.options.verbose&&e("\n\nComputing score:\n"),t=0;t<l.length;t++){for(n=0,i=l[t].output,o=i.length,a=1,s=0;s<o;s++)r=i[s].score,h=p?p[i[s].key].weight:1,c=r*h,1!==h?a=Math.min(a,c):(n+=c,i[s].nScore=c);1===a?l[t].score=n/o:l[t].score=a,this.options.verbose&&e(l[t])}},s.prototype._sort=function(){var t=this.options;t.shouldSort&&(t.verbose&&e("\n\nSorting...."),this.results.sort(t.sortFn))},s.prototype._format=function(){var t,s,n,i,o,r=this.options,h=r.getFn,a=[],c=this.results,p=r.include;for(r.verbose&&e("\n\nOutput:\n\n",c),i=r.id?function(t){c[t].item=h(c[t].item,r.id,[])[0]}:function(){},o=function(t){var e,s,n,i,o,r=c[t];if(p.length>0){if(e={item:r.item},p.indexOf("matches")!==-1)for(n=r.output,e.matches=[],s=0;s<n.length;s++)i=n[s],o={indices:i.matchedIndices},i.key&&(o.key=i.key),e.matches.push(o);p.indexOf("score")!==-1&&(e.score=c[t].score)}else e=r.item;return e},s=0,n=c.length;s<n;s++)i(s),t=o(s),a.push(t);return a},o.defaultOptions={location:0,distance:100,threshold:.6,maxPatternLength:32},o.prototype._calculatePatternAlphabet=function(){var t={},e=0;for(e=0;e<this.patternLen;e++)t[this.pattern.charAt(e)]=0;for(e=0;e<this.patternLen;e++)t[this.pattern.charAt(e)]|=1<<this.pattern.length-e-1;return t},o.prototype._bitapScore=function(t,e){var s=t/this.patternLen,n=Math.abs(this.options.location-e);return this.options.distance?s+n/this.options.distance:n?1:s},o.prototype.search=function(t){var e,s,n,i,o,r,h,a,c,p,l,u,f,d,g,m,y,v,k,S,b,M,_,L=this.options;if(t=L.caseSensitive?t:t.toLowerCase(),this.pattern===t)return{isMatch:!0,score:0,matchedIndices:[[0,t.length-1]]};if(this.patternLen>L.maxPatternLength){if(v=t.match(new RegExp(this.pattern.replace(L.tokenSeparator,"|"))),k=!!v)for(b=[],e=0,M=v.length;e<M;e++)_=v[e],b.push([t.indexOf(_),_.length-1]);return{isMatch:k,score:k?.5:1,matchedIndices:b}}for(i=L.findAllMatches,o=L.location,n=t.length,r=L.threshold,h=t.indexOf(this.pattern,o),S=[],e=0;e<n;e++)S[e]=0;for(h!=-1&&(r=Math.min(this._bitapScore(0,h),r),h=t.lastIndexOf(this.pattern,o+this.patternLen),h!=-1&&(r=Math.min(this._bitapScore(0,h),r))),h=-1,m=1,y=[],p=this.patternLen+n,e=0;e<this.patternLen;e++){for(a=0,c=p;a<c;)this._bitapScore(e,o+c)<=r?a=c:p=c,c=Math.floor((p-a)/2+a);for(p=c,l=Math.max(1,o-c+1),u=i?n:Math.min(o+c,n)+this.patternLen,f=Array(u+2),f[u+1]=(1<<e)-1,s=u;s>=l;s--)if(g=this.patternAlphabet[t.charAt(s-1)],g&&(S[s-1]=1),0===e?f[s]=(f[s+1]<<1|1)&g:f[s]=(f[s+1]<<1|1)&g|((d[s+1]|d[s])<<1|1)|d[s+1],f[s]&this.matchmask&&(m=this._bitapScore(e,s-1),m<=r)){if(r=m,h=s-1,y.push(h),!(h>o))break;l=Math.max(1,2*o-h)}if(this._bitapScore(e+1,o)>r)break;d=f}return b=this._getMatchedIndices(S),{isMatch:h>=0,score:0===m?.001:m,matchedIndices:b}},o.prototype._getMatchedIndices=function(t){for(var e,s=[],n=-1,i=-1,o=0,r=t.length;o<r;o++)e=t[o],e&&n===-1?n=o:e||n===-1||(i=o-1,i-n+1>=this.options.minMatchCharLength&&s.push([n,i]),n=-1);return t[o-1]&&o-1-n+1>=this.options.minMatchCharLength&&s.push([n,o-1]),s},"object"==typeof exports?module.exports=s:"function"==typeof define&&define.amd?define(function(){return s}):t.Fuse=s}(this);

!function(){var t={n:function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,{a:n}),n},d:function(e,n){for(var i in n)t.o(n,i)&&!t.o(e,i)&&Object.defineProperty(e,i,{enumerable:!0,get:n[i]})},o:function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},r:function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})}},e={};!function(){"use strict";t.r(e),t.d(e,{IconLoaderClass:function(){return f},icon_loader:function(){return l}});var n,i=window.lodash,r=t.n(i);function o(t){var e=[];if(0===t.length)return"";if("string"!=typeof t[0])throw new TypeError("Url must be a string. Received "+t[0]);t[0].match(/^[^/:]+:\/*$/)&&t.length>1&&(t[0]=t.shift()+t[0]),t[0].match(/^file:\/\/\//)?t[0]=t[0].replace(/^([^/:]+):\/*/,"$1:///"):t[0]=t[0].replace(/^([^/:]+):\/*/,"$1://");for(var n=0;n<t.length;n++){var i=t[n];if("string"!=typeof i)throw new TypeError("Url must be a string. Received "+i);""!==i&&(n>0&&(i=i.replace(/^[\/]+/,"")),i=n<t.length-1?i.replace(/[\/]+$/,""):i.replace(/[\/]+$/,"/"),e.push(i))}var r=e.join("/"),o=(r=r.replace(/\/(\?|&|#[^!])/g,"$1")).split("?");return r=o.shift()+(o.length>0?"?":"")+o.join("&")}function c(t,e){var n=r().omit(function(t){var e=document.createElementNS("http://www.w3.org/2000/svg","svg");e.innerHTML="<symbol ".concat(t,"></symbol>");for(var n=e.children[0],i={},r=0;r<n.attributes.length;r++){var o=n.attributes[r].name;i[o]=n.getAttribute(o)}return i}(t),["width","height"]);return e&&(n.id=e),function(t){var e="";for(var n in t)e+="".concat(n,'="').concat(t[n],'" ');return e.trim()}(n)}function u(t){return u="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},u(t)}function a(t,e){for(var n=0;n<e.length;n++){var i=e[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}function s(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var f=function(){function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),s(this,"icon",void 0),s(this,"config",void 0),s(this,"container",void 0),this.icon=e;var n=document.getElementById("bs-svg-icon-sprite");n||((n=document.createElementNS("http://www.w3.org/2000/svg","svg")).setAttribute("height",0),n.setAttribute("width",0),n.setAttribute("class","hidden"),n.setAttribute("id","bs-svg-icon-sprite"),document.body.append(n)),this.container=n}var e,i,f;return e=t,i=[{key:"the_icon",value:function(t){var e=this,i=function(){return'<span class="bf-icon bf-icon-svg '.concat(e.icon.prefix||""," ").concat(e.icon.id||"",'"><svg class="bf-svg-tag"><use xlink:href="#').concat(e.icon.icon,'"></use></svg></span>')};return this.isLoaded()?Promise.resolve(i()):(n||(n=wp.apiRequest({path:"betterstudio/v1/icon-config",method:"POST"}).promise().then((function(t){return t.families}))),n).then((function(t){return e.config=t,e.icon=e.normalize(e.icon),e.iconContent().then((function(t){return e.isLoaded()||e.keep(t),i()}))}))}},{key:"isLoaded",value:function(){return!(!this.icon.icon||"string"!=typeof this.icon.icon||!this.container.querySelector("#"+this.icon.icon))}},{key:"keep",value:function(t){var e=t.match(/<\s*svg([^\>]+)>([\s\S]+)<\s*\/\s*svg\s*>/i);return!!e&&(this.container.innerHTML+="<symbol ".concat(c(e[1],this.icon.icon),">").concat(e[2],"</symbol>"),!0)}},{key:"iconContent",value:function(){if(!this.icon.prefix||!this.config[this.icon.prefix])throw new Error("invalid icon: "+JSON.stringify(this.icon));var t=this.iconURL();return fetch(t).then((function(e){if(200!==e.status)throw new Error("cannot fetch icon file: "+t);return e.text()}))}},{key:"iconURL",value:function(){return function(){for(var t=arguments.length,e=new Array(t),n=0;n<t;n++)e[n]=arguments[n];return o(Array.from(Array.isArray(e[0])?e[0]:e))}(this.config[this.icon.prefix].base_url,"v"+this.icon.version,this.icon.id+".svg")}},{key:"normalize",value:function(t){return"object"!==u(t)&&(t={icon:t}),r().extend({icon:t,width:"",height:"",type:"",prefix:"",version:"",id:""},t,this.icon_info(t.icon))}},{key:"icon_info",value:function(t){if(r().isEmpty(t))return{};if(function(t){var e;try{e=new URL(t)}catch(t){return!1}return-1!==["http:","https:"].indexOf(e.protocol)}(t))return{type:"custom-url"};var e=t.match(/^([^\-]+)\-(.+)/);if(!e)return{};var n=e[2],i=e[1],o=this.config[i]&&this.config[i].id||i;return{type:o,version:"font-awesome"===o?"4.7":"1",id:n,prefix:i}}}],f=[{key:"config",value:function(){}}],i&&a(e.prototype,i),f&&a(e,f),Object.defineProperty(e,"prototype",{writable:!1}),t}();function l(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};return e&&(n.custom_classes=e),new f(t).the_icon(n)}}();var n=(BetterStudio="undefined"==typeof BetterStudio?{}:BetterStudio).Libs=BetterStudio.Libs||{};for(var i in e)n[i]=e[i];e.__esModule&&Object.defineProperty(n,"__esModule",{value:!0})}();

/*!
 * mustache.js - Logic-less {{mustache}} templates with JavaScript
 * http://github.com/janl/mustache.js
 */

/*global define: false Mustache: true*/

(function defineMustache (global, factory) {
  if (typeof exports === 'object' && exports && typeof exports.nodeName !== 'string') {
    factory(exports); // CommonJS
  } else if (typeof define === 'function' && define.amd) {
    define(['exports'], factory); // AMD
  } else {
    global.Mustache = {};
    factory(global.Mustache); // script, wsh, asp
  }
}(this, function mustacheFactory (mustache) {

  var objectToString = Object.prototype.toString;
  var isArray = Array.isArray || function isArrayPolyfill (object) {
    return objectToString.call(object) === '[object Array]';
  };

  function isFunction (object) {
    return typeof object === 'function';
  }

  /**
   * More correct typeof string handling array
   * which normally returns typeof 'object'
   */
  function typeStr (obj) {
    return isArray(obj) ? 'array' : typeof obj;
  }

  function escapeRegExp (string) {
    return string.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&');
  }

  /**
   * Null safe way of checking whether or not an object,
   * including its prototype, has a given property
   */
  function hasProperty (obj, propName) {
    return obj != null && typeof obj === 'object' && (propName in obj);
  }

  // Workaround for https://issues.apache.org/jira/browse/COUCHDB-577
  // See https://github.com/janl/mustache.js/issues/189
  var regExpTest = RegExp.prototype.test;
  function testRegExp (re, string) {
    return regExpTest.call(re, string);
  }

  var nonSpaceRe = /\S/;
  function isWhitespace (string) {
    return !testRegExp(nonSpaceRe, string);
  }

  var entityMap = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;',
    '/': '&#x2F;',
    '`': '&#x60;',
    '=': '&#x3D;'
  };

  function escapeHtml (string) {
    return String(string).replace(/[&<>"'`=\/]/g, function fromEntityMap (s) {
      return entityMap[s];
    });
  }

  var whiteRe = /\s*/;
  var spaceRe = /\s+/;
  var equalsRe = /\s*=/;
  var curlyRe = /\s*\}/;
  var tagRe = /#|\^|\/|>|\{|&|=|!/;

  /**
   * Breaks up the given `template` string into a tree of tokens. If the `tags`
   * argument is given here it must be an array with two string values: the
   * opening and closing tags used in the template (e.g. [ "<%", "%>" ]). Of
   * course, the default is to use mustaches (i.e. mustache.tags).
   *
   * A token is an array with at least 4 elements. The first element is the
   * mustache symbol that was used inside the tag, e.g. "#" or "&". If the tag
   * did not contain a symbol (i.e. {{myValue}}) this element is "name". For
   * all text that appears outside a symbol this element is "text".
   *
   * The second element of a token is its "value". For mustache tags this is
   * whatever else was inside the tag besides the opening symbol. For text tokens
   * this is the text itself.
   *
   * The third and fourth elements of the token are the start and end indices,
   * respectively, of the token in the original template.
   *
   * Tokens that are the root node of a subtree contain two more elements: 1) an
   * array of tokens in the subtree and 2) the index in the original template at
   * which the closing tag for that section begins.
   */
  function parseTemplate (template, tags) {
    if (!template)
      return [];

    var sections = [];     // Stack to hold section tokens
    var tokens = [];       // Buffer to hold the tokens
    var spaces = [];       // Indices of whitespace tokens on the current line
    var hasTag = false;    // Is there a {{tag}} on the current line?
    var nonSpace = false;  // Is there a non-space char on the current line?

    // Strips all whitespace tokens array for the current line
    // if there was a {{#tag}} on it and otherwise only space.
    function stripSpace () {
      if (hasTag && !nonSpace) {
        while (spaces.length)
          delete tokens[spaces.pop()];
      } else {
        spaces = [];
      }

      hasTag = false;
      nonSpace = false;
    }

    var openingTagRe, closingTagRe, closingCurlyRe;
    function compileTags (tagsToCompile) {
      if (typeof tagsToCompile === 'string')
        tagsToCompile = tagsToCompile.split(spaceRe, 2);

      if (!isArray(tagsToCompile) || tagsToCompile.length !== 2)
        throw new Error('Invalid tags: ' + tagsToCompile);

      openingTagRe = new RegExp(escapeRegExp(tagsToCompile[0]) + '\\s*');
      closingTagRe = new RegExp('\\s*' + escapeRegExp(tagsToCompile[1]));
      closingCurlyRe = new RegExp('\\s*' + escapeRegExp('}' + tagsToCompile[1]));
    }

    compileTags(tags || mustache.tags);

    var scanner = new Scanner(template);

    var start, type, value, chr, token, openSection;
    while (!scanner.eos()) {
      start = scanner.pos;

      // Match any text between tags.
      value = scanner.scanUntil(openingTagRe);

      if (value) {
        for (var i = 0, valueLength = value.length; i < valueLength; ++i) {
          chr = value.charAt(i);

          if (isWhitespace(chr)) {
            spaces.push(tokens.length);
          } else {
            nonSpace = true;
          }

          tokens.push([ 'text', chr, start, start + 1 ]);
          start += 1;

          // Check for whitespace on the current line.
          if (chr === '\n')
            stripSpace();
        }
      }

      // Match the opening tag.
      if (!scanner.scan(openingTagRe))
        break;

      hasTag = true;

      // Get the tag type.
      type = scanner.scan(tagRe) || 'name';
      scanner.scan(whiteRe);

      // Get the tag value.
      if (type === '=') {
        value = scanner.scanUntil(equalsRe);
        scanner.scan(equalsRe);
        scanner.scanUntil(closingTagRe);
      } else if (type === '{') {
        value = scanner.scanUntil(closingCurlyRe);
        scanner.scan(curlyRe);
        scanner.scanUntil(closingTagRe);
        type = '&';
      } else {
        value = scanner.scanUntil(closingTagRe);
      }

      // Match the closing tag.
      if (!scanner.scan(closingTagRe))
        throw new Error('Unclosed tag at ' + scanner.pos);

      token = [ type, value, start, scanner.pos ];
      tokens.push(token);

      if (type === '#' || type === '^') {
        sections.push(token);
      } else if (type === '/') {
        // Check section nesting.
        openSection = sections.pop();

        if (!openSection)
          throw new Error('Unopened section "' + value + '" at ' + start);

        if (openSection[1] !== value)
          throw new Error('Unclosed section "' + openSection[1] + '" at ' + start);
      } else if (type === 'name' || type === '{' || type === '&') {
        nonSpace = true;
      } else if (type === '=') {
        // Set the tags for the next time around.
        compileTags(value);
      }
    }

    // Make sure there are no open sections when we're done.
    openSection = sections.pop();

    if (openSection)
      throw new Error('Unclosed section "' + openSection[1] + '" at ' + scanner.pos);

    return nestTokens(squashTokens(tokens));
  }

  /**
   * Combines the values of consecutive text tokens in the given `tokens` array
   * to a single token.
   */
  function squashTokens (tokens) {
    var squashedTokens = [];

    var token, lastToken;
    for (var i = 0, numTokens = tokens.length; i < numTokens; ++i) {
      token = tokens[i];

      if (token) {
        if (token[0] === 'text' && lastToken && lastToken[0] === 'text') {
          lastToken[1] += token[1];
          lastToken[3] = token[3];
        } else {
          squashedTokens.push(token);
          lastToken = token;
        }
      }
    }

    return squashedTokens;
  }

  /**
   * Forms the given array of `tokens` into a nested tree structure where
   * tokens that represent a section have two additional items: 1) an array of
   * all tokens that appear in that section and 2) the index in the original
   * template that represents the end of that section.
   */
  function nestTokens (tokens) {
    var nestedTokens = [];
    var collector = nestedTokens;
    var sections = [];

    var token, section;
    for (var i = 0, numTokens = tokens.length; i < numTokens; ++i) {
      token = tokens[i];

      switch (token[0]) {
        case '#':
        case '^':
          collector.push(token);
          sections.push(token);
          collector = token[4] = [];
          break;
        case '/':
          section = sections.pop();
          section[5] = token[2];
          collector = sections.length > 0 ? sections[sections.length - 1][4] : nestedTokens;
          break;
        default:
          collector.push(token);
      }
    }

    return nestedTokens;
  }

  /**
   * A simple string scanner that is used by the template parser to find
   * tokens in template strings.
   */
  function Scanner (string) {
    this.string = string;
    this.tail = string;
    this.pos = 0;
  }

  /**
   * Returns `true` if the tail is empty (end of string).
   */
  Scanner.prototype.eos = function eos () {
    return this.tail === '';
  };

  /**
   * Tries to match the given regular expression at the current position.
   * Returns the matched text if it can match, the empty string otherwise.
   */
  Scanner.prototype.scan = function scan (re) {
    var match = this.tail.match(re);

    if (!match || match.index !== 0)
      return '';

    var string = match[0];

    this.tail = this.tail.substring(string.length);
    this.pos += string.length;

    return string;
  };

  /**
   * Skips all text until the given regular expression can be matched. Returns
   * the skipped string, which is the entire tail if no match can be made.
   */
  Scanner.prototype.scanUntil = function scanUntil (re) {
    var index = this.tail.search(re), match;

    switch (index) {
      case -1:
        match = this.tail;
        this.tail = '';
        break;
      case 0:
        match = '';
        break;
      default:
        match = this.tail.substring(0, index);
        this.tail = this.tail.substring(index);
    }

    this.pos += match.length;

    return match;
  };

  /**
   * Represents a rendering context by wrapping a view object and
   * maintaining a reference to the parent context.
   */
  function Context (view, parentContext) {
    this.view = view;
    this.cache = { '.': this.view };
    this.parent = parentContext;
  }

  /**
   * Creates a new context using the given view with this context
   * as the parent.
   */
  Context.prototype.push = function push (view) {
    return new Context(view, this);
  };

  /**
   * Returns the value of the given name in this context, traversing
   * up the context hierarchy if the value is absent in this context's view.
   */
  Context.prototype.lookup = function lookup (name) {
    var cache = this.cache;

    var value;
    if (cache.hasOwnProperty(name)) {
      value = cache[name];
    } else {
      var context = this, names, index, lookupHit = false;

      while (context) {
        if (name.indexOf('.') > 0) {
          value = context.view;
          names = name.split('.');
          index = 0;

          /**
           * Using the dot notion path in `name`, we descend through the
           * nested objects.
           *
           * To be certain that the lookup has been successful, we have to
           * check if the last object in the path actually has the property
           * we are looking for. We store the result in `lookupHit`.
           *
           * This is specially necessary for when the value has been set to
           * `undefined` and we want to avoid looking up parent contexts.
           **/
          while (value != null && index < names.length) {
            if (index === names.length - 1)
              lookupHit = hasProperty(value, names[index]);

            value = value[names[index++]];
          }
        } else {
          value = context.view[name];
          lookupHit = hasProperty(context.view, name);
        }

        if (lookupHit)
          break;

        context = context.parent;
      }

      cache[name] = value;
    }

    if (isFunction(value))
      value = value.call(this.view);

    return value;
  };

  /**
   * A Writer knows how to take a stream of tokens and render them to a
   * string, given a context. It also maintains a cache of templates to
   * avoid the need to parse the same template twice.
   */
  function Writer () {
    this.cache = {};
  }

  /**
   * Clears all cached templates in this writer.
   */
  Writer.prototype.clearCache = function clearCache () {
    this.cache = {};
  };

  /**
   * Parses and caches the given `template` and returns the array of tokens
   * that is generated from the parse.
   */
  Writer.prototype.parse = function parse (template, tags) {
    var cache = this.cache;
    var tokens = cache[template];

    if (tokens == null)
      tokens = cache[template] = parseTemplate(template, tags);

    return tokens;
  };

  /**
   * High-level method that is used to render the given `template` with
   * the given `view`.
   *
   * The optional `partials` argument may be an object that contains the
   * names and templates of partials that are used in the template. It may
   * also be a function that is used to load partial templates on the fly
   * that takes a single argument: the name of the partial.
   */
  Writer.prototype.render = function render (template, view, partials) {
    var tokens = this.parse(template);
    var context = (view instanceof Context) ? view : new Context(view);
    return this.renderTokens(tokens, context, partials, template);
  };

  /**
   * Low-level method that renders the given array of `tokens` using
   * the given `context` and `partials`.
   *
   * Note: The `originalTemplate` is only ever used to extract the portion
   * of the original template that was contained in a higher-order section.
   * If the template doesn't use higher-order sections, this argument may
   * be omitted.
   */
  Writer.prototype.renderTokens = function renderTokens (tokens, context, partials, originalTemplate) {
    var buffer = '';

    var token, symbol, value;
    for (var i = 0, numTokens = tokens.length; i < numTokens; ++i) {
      value = undefined;
      token = tokens[i];
      symbol = token[0];

      if (symbol === '#') value = this.renderSection(token, context, partials, originalTemplate);
      else if (symbol === '^') value = this.renderInverted(token, context, partials, originalTemplate);
      else if (symbol === '>') value = this.renderPartial(token, context, partials, originalTemplate);
      else if (symbol === '&') value = this.unescapedValue(token, context);
      else if (symbol === 'name') value = this.escapedValue(token, context);
      else if (symbol === 'text') value = this.rawValue(token);

      if (value !== undefined)
        buffer += value;
    }

    return buffer;
  };

  Writer.prototype.renderSection = function renderSection (token, context, partials, originalTemplate) {
    var self = this;
    var buffer = '';
    var value = context.lookup(token[1]);

    // This function is used to render an arbitrary template
    // in the current context by higher-order sections.
    function subRender (template) {
      return self.render(template, context, partials);
    }

    if (!value) return;

    if (isArray(value)) {
      for (var j = 0, valueLength = value.length; j < valueLength; ++j) {
        buffer += this.renderTokens(token[4], context.push(value[j]), partials, originalTemplate);
      }
    } else if (typeof value === 'object' || typeof value === 'string' || typeof value === 'number') {
      buffer += this.renderTokens(token[4], context.push(value), partials, originalTemplate);
    } else if (isFunction(value)) {
      if (typeof originalTemplate !== 'string')
        throw new Error('Cannot use higher-order sections without the original template');

      // Extract the portion of the original template that the section contains.
      value = value.call(context.view, originalTemplate.slice(token[3], token[5]), subRender);

      if (value != null)
        buffer += value;
    } else {
      buffer += this.renderTokens(token[4], context, partials, originalTemplate);
    }
    return buffer;
  };

  Writer.prototype.renderInverted = function renderInverted (token, context, partials, originalTemplate) {
    var value = context.lookup(token[1]);

    // Use JavaScript's definition of falsy. Include empty arrays.
    // See https://github.com/janl/mustache.js/issues/186
    if (!value || (isArray(value) && value.length === 0))
      return this.renderTokens(token[4], context, partials, originalTemplate);
  };

  Writer.prototype.renderPartial = function renderPartial (token, context, partials) {
    if (!partials) return;

    var value = isFunction(partials) ? partials(token[1]) : partials[token[1]];
    if (value != null)
      return this.renderTokens(this.parse(value), context, partials, value);
  };

  Writer.prototype.unescapedValue = function unescapedValue (token, context) {
    var value = context.lookup(token[1]);
    if (value != null)
      return value;
  };

  Writer.prototype.escapedValue = function escapedValue (token, context) {
    var value = context.lookup(token[1]);
    if (value != null)
      return mustache.escape(value);
  };

  Writer.prototype.rawValue = function rawValue (token) {
    return token[1];
  };

  mustache.name = 'mustache.js';
  mustache.version = '2.2.1';
  mustache.tags = [ '{{', '}}' ];

  // All high-level mustache.* functions use this writer.
  var defaultWriter = new Writer();

  /**
   * Clears all cached templates in the default writer.
   */
  mustache.clearCache = function clearCache () {
    return defaultWriter.clearCache();
  };

  /**
   * Parses and caches the given template in the default writer and returns the
   * array of tokens it contains. Doing this ahead of time avoids the need to
   * parse templates on the fly as they are rendered.
   */
  mustache.parse = function parse (template, tags) {
    return defaultWriter.parse(template, tags);
  };

  /**
   * Renders the `template` with the given `view` and `partials` using the
   * default writer.
   */
  mustache.render = function render (template, view, partials) {
    if (typeof template !== 'string') {
      throw new TypeError('Invalid template! Template should be a "string" ' +
                          'but "' + typeStr(template) + '" was given as the first ' +
                          'argument for mustache#render(template, view, partials)');
    }

    return defaultWriter.render(template, view, partials);
  };

  // This is here for backwards compatibility with 0.4.x.,
  /*eslint-disable */ // eslint wants camel cased function name
  mustache.to_html = function to_html (template, view, partials, send) {
    /*eslint-enable*/

    var result = mustache.render(template, view, partials);

    if (isFunction(send)) {
      send(result);
    } else {
      return result;
    }
  };

  // Export the escaping function so that the user may override it.
  // See https://github.com/janl/mustache.js/issues/244
  mustache.escape = escapeHtml;

  // Export these mainly for testing, but also for advanced usage.
  mustache.Scanner = Scanner;
  mustache.Context = Context;
  mustache.Writer = Writer;

}));

/*
 *  Remodal - v1.1.1
 *  Responsive, lightweight, fast, synchronized with CSS animations, fully customizable modal window plugin with declarative configuration and hash tracking.
 *  http://vodkabears.github.io/remodal/
 *
 *  Made by Ilya Makarov
 *  Under MIT License
 */
!function(a,b){"function"==typeof define&&define.amd?define(["jquery"],function(c){return b(a,c)}):"object"==typeof exports?b(a,require("jquery")):b(a,a.jQuery||a.Zepto)}(this,function(a,b){"use strict";function c(a){if(w&&"none"===a.css("animation-name")&&"none"===a.css("-webkit-animation-name")&&"none"===a.css("-moz-animation-name")&&"none"===a.css("-o-animation-name")&&"none"===a.css("-ms-animation-name"))return 0;var b,c,d,e,f=a.css("animation-duration")||a.css("-webkit-animation-duration")||a.css("-moz-animation-duration")||a.css("-o-animation-duration")||a.css("-ms-animation-duration")||"0s",g=a.css("animation-delay")||a.css("-webkit-animation-delay")||a.css("-moz-animation-delay")||a.css("-o-animation-delay")||a.css("-ms-animation-delay")||"0s",h=a.css("animation-iteration-count")||a.css("-webkit-animation-iteration-count")||a.css("-moz-animation-iteration-count")||a.css("-o-animation-iteration-count")||a.css("-ms-animation-iteration-count")||"1";for(f=f.split(", "),g=g.split(", "),h=h.split(", "),e=0,c=f.length,b=Number.NEGATIVE_INFINITY;e<c;e++)d=parseFloat(f[e])*parseInt(h[e],10)+parseFloat(g[e]),d>b&&(b=d);return b}function d(){if(b(document).height()<=b(window).height())return 0;var a,c,d=document.createElement("div"),e=document.createElement("div");return d.style.visibility="hidden",d.style.width="100px",document.body.appendChild(d),a=d.offsetWidth,d.style.overflow="scroll",e.style.width="100%",d.appendChild(e),c=e.offsetWidth,d.parentNode.removeChild(d),a-c}function e(){if(!x){var a,c,e=b("html"),f=k("is-locked");e.hasClass(f)||(c=b(document.body),a=parseInt(c.css("padding-right"),10)+d(),c.css("padding-right",a+"px"),e.addClass(f))}}function f(){if(!x){var a,c,e=b("html"),f=k("is-locked");e.hasClass(f)&&(c=b(document.body),a=parseInt(c.css("padding-right"),10)-d(),c.css("padding-right",a+"px"),e.removeClass(f))}}function g(a,b,c,d){var e=k("is",b),f=[k("is",u.CLOSING),k("is",u.OPENING),k("is",u.CLOSED),k("is",u.OPENED)].join(" ");a.$bg.removeClass(f).addClass(e),a.$overlay.removeClass(f).addClass(e),a.$wrapper.removeClass(f).addClass(e),a.$modal.removeClass(f).addClass(e),a.state=b,!c&&a.$modal.trigger({type:b,reason:d},[{reason:d}])}function h(a,d,e){var f=0,g=function(a){a.target===this&&f++},h=function(a){a.target===this&&0===--f&&(b.each(["$bg","$overlay","$wrapper","$modal"],function(a,b){e[b].off(r+" "+s)}),d())};b.each(["$bg","$overlay","$wrapper","$modal"],function(a,b){e[b].on(r,g).on(s,h)}),a(),0===c(e.$bg)&&0===c(e.$overlay)&&0===c(e.$wrapper)&&0===c(e.$modal)&&(b.each(["$bg","$overlay","$wrapper","$modal"],function(a,b){e[b].off(r+" "+s)}),d())}function i(a){a.state!==u.CLOSED&&(b.each(["$bg","$overlay","$wrapper","$modal"],function(b,c){a[c].off(r+" "+s)}),a.$bg.removeClass(a.settings.modifier),a.$overlay.removeClass(a.settings.modifier).hide(),a.$wrapper.hide(),f(),g(a,u.CLOSED,!0))}function j(a){var b,c,d,e,f={};for(a=a.replace(/\s*:\s*/g,":").replace(/\s*,\s*/g,","),b=a.split(","),e=0,c=b.length;e<c;e++)b[e]=b[e].split(":"),d=b[e][1],("string"==typeof d||d instanceof String)&&(d="true"===d||"false"!==d&&d),("string"==typeof d||d instanceof String)&&(d=isNaN(d)?d:+d),f[b[e][0]]=d;return f}function k(){for(var a=q,b=0;b<arguments.length;++b)a+="-"+arguments[b];return a}function l(){var a,c,d=location.hash.replace("#","");if(d){try{c=b('[data-remodal-id="'+d+'"]')}catch(e){}c&&c.length&&(a=b[p].lookup[c.data(p)],a&&a.settings.hashTracking&&a.open())}else n&&n.state===u.OPENED&&n.settings.hashTracking&&n.close()}function m(a,c){var d=b(document.body),e=d,f=this;f.settings=b.extend({},t,c),f.index=b[p].lookup.push(f)-1,f.state=u.CLOSED,f.$overlay=b("."+k("overlay")),null!==f.settings.appendTo&&f.settings.appendTo.length&&(e=b(f.settings.appendTo)),f.$overlay.length||(f.$overlay=b("<div>").addClass(k("overlay")+" "+k("is",u.CLOSED)).hide(),e.append(f.$overlay)),f.$bg=b("."+k("bg")).addClass(k("is",u.CLOSED)),f.$modal=a.addClass(q+" "+k("is-initialized")+" "+f.settings.modifier+" "+k("is",u.CLOSED)).attr("tabindex","-1"),f.$wrapper=b("<div>").addClass(k("wrapper")+" "+f.settings.modifier+" "+k("is",u.CLOSED)).hide().append(f.$modal),e.append(f.$wrapper),f.$wrapper.on("click."+q,'[data-remodal-action="close"]',function(a){a.preventDefault(),f.close()}),f.$wrapper.on("click."+q,'[data-remodal-action="cancel"]',function(a){a.preventDefault(),f.$modal.trigger(v.CANCELLATION),f.settings.closeOnCancel&&f.close(v.CANCELLATION)}),f.$wrapper.on("click."+q,'[data-remodal-action="confirm"]',function(a){a.preventDefault(),f.$modal.trigger(v.CONFIRMATION),f.settings.closeOnConfirm&&f.close(v.CONFIRMATION)}),f.$wrapper.on("click."+q,function(a){var c=b(a.target);c.hasClass(k("wrapper"))&&f.settings.closeOnOutsideClick&&f.close()})}var n,o,p="remodal",q=a.REMODAL_GLOBALS&&a.REMODAL_GLOBALS.NAMESPACE||p,r=b.map(["animationstart","webkitAnimationStart","MSAnimationStart","oAnimationStart"],function(a){return a+"."+q}).join(" "),s=b.map(["animationend","webkitAnimationEnd","MSAnimationEnd","oAnimationEnd"],function(a){return a+"."+q}).join(" "),t=b.extend({hashTracking:!0,closeOnConfirm:!0,closeOnCancel:!0,closeOnEscape:!0,closeOnOutsideClick:!0,modifier:"",appendTo:null},a.REMODAL_GLOBALS&&a.REMODAL_GLOBALS.DEFAULTS),u={CLOSING:"closing",CLOSED:"closed",OPENING:"opening",OPENED:"opened"},v={CONFIRMATION:"confirmation",CANCELLATION:"cancellation"},w=function(){var a=document.createElement("div").style;return void 0!==a.animationName||void 0!==a.WebkitAnimationName||void 0!==a.MozAnimationName||void 0!==a.msAnimationName||void 0!==a.OAnimationName}(),x=/iPad|iPhone|iPod/.test(navigator.platform);m.prototype.open=function(){var a,c=this;c.state!==u.OPENING&&c.state!==u.CLOSING&&(a=c.$modal.attr("data-remodal-id"),a&&c.settings.hashTracking&&(o=b(window).scrollTop(),location.hash=a),n&&n!==c&&i(n),n=c,e(),c.$bg.addClass(c.settings.modifier),c.$overlay.addClass(c.settings.modifier).show(),c.$wrapper.show().scrollTop(0),c.$modal.focus(),h(function(){g(c,u.OPENING)},function(){g(c,u.OPENED)},c))},m.prototype.close=function(a){var c=this;c.state!==u.OPENING&&c.state!==u.CLOSING&&c.state!==u.CLOSED&&(c.settings.hashTracking&&c.$modal.attr("data-remodal-id")===location.hash.substr(1)&&(location.hash="",b(window).scrollTop(o)),h(function(){g(c,u.CLOSING,!1,a)},function(){c.$bg.removeClass(c.settings.modifier),c.$overlay.removeClass(c.settings.modifier).hide(),c.$wrapper.hide(),f(),g(c,u.CLOSED,!1,a)},c))},m.prototype.getState=function(){return this.state},m.prototype.destroy=function(){var a,c=b[p].lookup;i(this),this.$wrapper.remove(),delete c[this.index],a=b.grep(c,function(a){return!!a}).length,0===a&&(this.$overlay.remove(),this.$bg.removeClass(k("is",u.CLOSING)+" "+k("is",u.OPENING)+" "+k("is",u.CLOSED)+" "+k("is",u.OPENED)))},b[p]={lookup:[]},b.fn[p]=function(a){var c,d;return this.each(function(e,f){d=b(f),null==d.data(p)?(c=new m(d,a),d.data(p,c.index),c.settings.hashTracking&&d.attr("data-remodal-id")===location.hash.substr(1)&&c.open()):c=b[p].lookup[d.data(p)]}),c},b(document).ready(function(){b(document).on("click","[data-remodal-target]",function(a){a.preventDefault();var c=a.currentTarget,d=c.getAttribute("data-remodal-target"),e=b('[data-remodal-id="'+d+'"]');b[p].lookup[e.data(p)].open()}),b(document).find("."+q).each(function(a,c){var d=b(c),e=d.data("remodal-options");e?("string"==typeof e||e instanceof String)&&(e=j(e)):e={},d[p](e)}),b(document).on("keydown."+q,function(a){n&&n.settings.closeOnEscape&&n.state===u.OPENED&&27===a.keyCode&&n.close()}),b(window).on("hashchange."+q,l)})});

/**
 * Web Font Loader v1.6.27
 * (c) Adobe Systems, Google.
 * License: Apache 2.0
 * */
(function(){function aa(a,b,c){return a.call.apply(a.bind,arguments)}function ba(a,b,c){if(!a)throw Error();if(2<arguments.length){var d=Array.prototype.slice.call(arguments,2);return function(){var c=Array.prototype.slice.call(arguments);Array.prototype.unshift.apply(c,d);return a.apply(b,c)}}return function(){return a.apply(b,arguments)}}function p(a,b,c){p=Function.prototype.bind&&-1!=Function.prototype.bind.toString().indexOf("native code")?aa:ba;return p.apply(null,arguments)}var q=Date.now||function(){return+new Date};function ca(a,b){this.a=a;this.m=b||a;this.c=this.m.document}var da=!!window.FontFace;function t(a,b,c,d){b=a.c.createElement(b);if(c)for(var e in c)c.hasOwnProperty(e)&&("style"==e?b.style.cssText=c[e]:b.setAttribute(e,c[e]));d&&b.appendChild(a.c.createTextNode(d));return b}function u(a,b,c){a=a.c.getElementsByTagName(b)[0];a||(a=document.documentElement);a.insertBefore(c,a.lastChild)}function v(a){a.parentNode&&a.parentNode.removeChild(a)}
    function w(a,b,c){b=b||[];c=c||[];for(var d=a.className.split(/\s+/),e=0;e<b.length;e+=1){for(var f=!1,g=0;g<d.length;g+=1)if(b[e]===d[g]){f=!0;break}f||d.push(b[e])}b=[];for(e=0;e<d.length;e+=1){f=!1;for(g=0;g<c.length;g+=1)if(d[e]===c[g]){f=!0;break}f||b.push(d[e])}a.className=b.join(" ").replace(/\s+/g," ").replace(/^\s+|\s+$/,"")}function y(a,b){for(var c=a.className.split(/\s+/),d=0,e=c.length;d<e;d++)if(c[d]==b)return!0;return!1}
    function z(a){if("string"===typeof a.f)return a.f;var b=a.m.location.protocol;"about:"==b&&(b=a.a.location.protocol);return"https:"==b?"https:":"http:"}function ea(a){return a.m.location.hostname||a.a.location.hostname}
    function A(a,b,c){function d(){k&&e&&f&&(k(g),k=null)}b=t(a,"link",{rel:"stylesheet",href:b,media:"all"});var e=!1,f=!0,g=null,k=c||null;da?(b.onload=function(){e=!0;d()},b.onerror=function(){e=!0;g=Error("Stylesheet failed to load");d()}):setTimeout(function(){e=!0;d()},0);u(a,"head",b)}
    function B(a,b,c,d){var e=a.c.getElementsByTagName("head")[0];if(e){var f=t(a,"script",{src:b}),g=!1;f.onload=f.onreadystatechange=function(){g||this.readyState&&"loaded"!=this.readyState&&"complete"!=this.readyState||(g=!0,c&&c(null),f.onload=f.onreadystatechange=null,"HEAD"==f.parentNode.tagName&&e.removeChild(f))};e.appendChild(f);setTimeout(function(){g||(g=!0,c&&c(Error("Script load timeout")))},d||5E3);return f}return null};function C(){this.a=0;this.c=null}function D(a){a.a++;return function(){a.a--;E(a)}}function F(a,b){a.c=b;E(a)}function E(a){0==a.a&&a.c&&(a.c(),a.c=null)};function G(a){this.a=a||"-"}G.prototype.c=function(a){for(var b=[],c=0;c<arguments.length;c++)b.push(arguments[c].replace(/[\W_]+/g,"").toLowerCase());return b.join(this.a)};function H(a,b){this.c=a;this.f=4;this.a="n";var c=(b||"n4").match(/^([nio])([1-9])$/i);c&&(this.a=c[1],this.f=parseInt(c[2],10))}function fa(a){return I(a)+" "+(a.f+"00")+" 300px "+J(a.c)}function J(a){var b=[];a=a.split(/,\s*/);for(var c=0;c<a.length;c++){var d=a[c].replace(/['"]/g,"");-1!=d.indexOf(" ")||/^\d/.test(d)?b.push("'"+d+"'"):b.push(d)}return b.join(",")}function K(a){return a.a+a.f}function I(a){var b="normal";"o"===a.a?b="oblique":"i"===a.a&&(b="italic");return b}
    function ga(a){var b=4,c="n",d=null;a&&((d=a.match(/(normal|oblique|italic)/i))&&d[1]&&(c=d[1].substr(0,1).toLowerCase()),(d=a.match(/([1-9]00|normal|bold)/i))&&d[1]&&(/bold/i.test(d[1])?b=7:/[1-9]00/.test(d[1])&&(b=parseInt(d[1].substr(0,1),10))));return c+b};function ha(a,b){this.c=a;this.f=a.m.document.documentElement;this.h=b;this.a=new G("-");this.j=!1!==b.events;this.g=!1!==b.classes}function ia(a){a.g&&w(a.f,[a.a.c("wf","loading")]);L(a,"loading")}function M(a){if(a.g){var b=y(a.f,a.a.c("wf","active")),c=[],d=[a.a.c("wf","loading")];b||c.push(a.a.c("wf","inactive"));w(a.f,c,d)}L(a,"inactive")}function L(a,b,c){if(a.j&&a.h[b])if(c)a.h[b](c.c,K(c));else a.h[b]()};function ja(){this.c={}}function ka(a,b,c){var d=[],e;for(e in b)if(b.hasOwnProperty(e)){var f=a.c[e];f&&d.push(f(b[e],c))}return d};function N(a,b){this.c=a;this.f=b;this.a=t(this.c,"span",{"aria-hidden":"true"},this.f)}function O(a){u(a.c,"body",a.a)}function P(a){return"display:block;position:absolute;top:-9999px;left:-9999px;font-size:300px;width:auto;height:auto;line-height:normal;margin:0;padding:0;font-variant:normal;white-space:nowrap;font-family:"+J(a.c)+";"+("font-style:"+I(a)+";font-weight:"+(a.f+"00")+";")};function Q(a,b,c,d,e,f){this.g=a;this.j=b;this.a=d;this.c=c;this.f=e||3E3;this.h=f||void 0}Q.prototype.start=function(){var a=this.c.m.document,b=this,c=q(),d=new Promise(function(d,e){function k(){q()-c>=b.f?e():a.fonts.load(fa(b.a),b.h).then(function(a){1<=a.length?d():setTimeout(k,25)},function(){e()})}k()}),e=new Promise(function(a,d){setTimeout(d,b.f)});Promise.race([e,d]).then(function(){b.g(b.a)},function(){b.j(b.a)})};function R(a,b,c,d,e,f,g){this.v=a;this.B=b;this.c=c;this.a=d;this.s=g||"BESbswy";this.f={};this.w=e||3E3;this.u=f||null;this.o=this.j=this.h=this.g=null;this.g=new N(this.c,this.s);this.h=new N(this.c,this.s);this.j=new N(this.c,this.s);this.o=new N(this.c,this.s);a=new H(this.a.c+",serif",K(this.a));a=P(a);this.g.a.style.cssText=a;a=new H(this.a.c+",sans-serif",K(this.a));a=P(a);this.h.a.style.cssText=a;a=new H("serif",K(this.a));a=P(a);this.j.a.style.cssText=a;a=new H("sans-serif",K(this.a));a=
        P(a);this.o.a.style.cssText=a;O(this.g);O(this.h);O(this.j);O(this.o)}var S={D:"serif",C:"sans-serif"},T=null;function U(){if(null===T){var a=/AppleWebKit\/([0-9]+)(?:\.([0-9]+))/.exec(window.navigator.userAgent);T=!!a&&(536>parseInt(a[1],10)||536===parseInt(a[1],10)&&11>=parseInt(a[2],10))}return T}R.prototype.start=function(){this.f.serif=this.j.a.offsetWidth;this.f["sans-serif"]=this.o.a.offsetWidth;this.A=q();la(this)};
    function ma(a,b,c){for(var d in S)if(S.hasOwnProperty(d)&&b===a.f[S[d]]&&c===a.f[S[d]])return!0;return!1}function la(a){var b=a.g.a.offsetWidth,c=a.h.a.offsetWidth,d;(d=b===a.f.serif&&c===a.f["sans-serif"])||(d=U()&&ma(a,b,c));d?q()-a.A>=a.w?U()&&ma(a,b,c)&&(null===a.u||a.u.hasOwnProperty(a.a.c))?V(a,a.v):V(a,a.B):na(a):V(a,a.v)}function na(a){setTimeout(p(function(){la(this)},a),50)}function V(a,b){setTimeout(p(function(){v(this.g.a);v(this.h.a);v(this.j.a);v(this.o.a);b(this.a)},a),0)};function W(a,b,c){this.c=a;this.a=b;this.f=0;this.o=this.j=!1;this.s=c}var X=null;W.prototype.g=function(a){var b=this.a;b.g&&w(b.f,[b.a.c("wf",a.c,K(a).toString(),"active")],[b.a.c("wf",a.c,K(a).toString(),"loading"),b.a.c("wf",a.c,K(a).toString(),"inactive")]);L(b,"fontactive",a);this.o=!0;oa(this)};
    W.prototype.h=function(a){var b=this.a;if(b.g){var c=y(b.f,b.a.c("wf",a.c,K(a).toString(),"active")),d=[],e=[b.a.c("wf",a.c,K(a).toString(),"loading")];c||d.push(b.a.c("wf",a.c,K(a).toString(),"inactive"));w(b.f,d,e)}L(b,"fontinactive",a);oa(this)};function oa(a){0==--a.f&&a.j&&(a.o?(a=a.a,a.g&&w(a.f,[a.a.c("wf","active")],[a.a.c("wf","loading"),a.a.c("wf","inactive")]),L(a,"active")):M(a.a))};function pa(a){this.j=a;this.a=new ja;this.h=0;this.f=this.g=!0}pa.prototype.load=function(a){this.c=new ca(this.j,a.context||this.j);this.g=!1!==a.events;this.f=!1!==a.classes;qa(this,new ha(this.c,a),a)};
    function ra(a,b,c,d,e){var f=0==--a.h;(a.f||a.g)&&setTimeout(function(){var a=e||null,k=d||null||{};if(0===c.length&&f)M(b.a);else{b.f+=c.length;f&&(b.j=f);var h,m=[];for(h=0;h<c.length;h++){var l=c[h],n=k[l.c],r=b.a,x=l;r.g&&w(r.f,[r.a.c("wf",x.c,K(x).toString(),"loading")]);L(r,"fontloading",x);r=null;if(null===X)if(window.FontFace){var x=/Gecko.*Firefox\/(\d+)/.exec(window.navigator.userAgent),ya=/OS X.*Version\/10\..*Safari/.exec(window.navigator.userAgent)&&/Apple/.exec(window.navigator.vendor);
        X=x?42<parseInt(x[1],10):ya?!1:!0}else X=!1;X?r=new Q(p(b.g,b),p(b.h,b),b.c,l,b.s,n):r=new R(p(b.g,b),p(b.h,b),b.c,l,b.s,a,n);m.push(r)}for(h=0;h<m.length;h++)m[h].start()}},0)}function qa(a,b,c){var d=[],e=c.timeout;ia(b);var d=ka(a.a,c,a.c),f=new W(a.c,b,e);a.h=d.length;b=0;for(c=d.length;b<c;b++)d[b].load(function(b,d,c){ra(a,f,b,d,c)})};function sa(a,b){this.c=a;this.a=b}function ta(a,b,c){var d=z(a.c);a=(a.a.api||"fast.fonts.net/jsapi").replace(/^.*http(s?):(\/\/)?/,"");return d+"//"+a+"/"+b+".js"+(c?"?v="+c:"")}
    sa.prototype.load=function(a){function b(){if(f["__mti_fntLst"+d]){var c=f["__mti_fntLst"+d](),e=[],h;if(c)for(var m=0;m<c.length;m++){var l=c[m].fontfamily;void 0!=c[m].fontStyle&&void 0!=c[m].fontWeight?(h=c[m].fontStyle+c[m].fontWeight,e.push(new H(l,h))):e.push(new H(l))}a(e)}else setTimeout(function(){b()},50)}var c=this,d=c.a.projectId,e=c.a.version;if(d){var f=c.c.m;B(this.c,ta(c,d,e),function(e){e?a([]):(f["__MonotypeConfiguration__"+d]=function(){return c.a},b())}).id="__MonotypeAPIScript__"+
        d}else a([])};function ua(a,b){this.c=a;this.a=b}ua.prototype.load=function(a){var b,c,d=this.a.urls||[],e=this.a.families||[],f=this.a.testStrings||{},g=new C;b=0;for(c=d.length;b<c;b++)A(this.c,d[b],D(g));var k=[];b=0;for(c=e.length;b<c;b++)if(d=e[b].split(":"),d[1])for(var h=d[1].split(","),m=0;m<h.length;m+=1)k.push(new H(d[0],h[m]));else k.push(new H(d[0]));F(g,function(){a(k,f)})};function va(a,b,c){a?this.c=a:this.c=b+wa;this.a=[];this.f=[];this.g=c||""}var wa="//fonts.googleapis.com/css";function xa(a,b){for(var c=b.length,d=0;d<c;d++){var e=b[d].split(":");3==e.length&&a.f.push(e.pop());var f="";2==e.length&&""!=e[1]&&(f=":");a.a.push(e.join(f))}}
    function za(a){if(0==a.a.length)throw Error("No fonts to load!");if(-1!=a.c.indexOf("kit="))return a.c;for(var b=a.a.length,c=[],d=0;d<b;d++)c.push(a.a[d].replace(/ /g,"+"));b=a.c+"?family="+c.join("%7C");0<a.f.length&&(b+="&subset="+a.f.join(","));0<a.g.length&&(b+="&text="+encodeURIComponent(a.g));return b};function Aa(a){this.f=a;this.a=[];this.c={}}
    var Ba={latin:"BESbswy","latin-ext":"\u00e7\u00f6\u00fc\u011f\u015f",cyrillic:"\u0439\u044f\u0416",greek:"\u03b1\u03b2\u03a3",khmer:"\u1780\u1781\u1782",Hanuman:"\u1780\u1781\u1782"},Ca={thin:"1",extralight:"2","extra-light":"2",ultralight:"2","ultra-light":"2",light:"3",regular:"4",book:"4",medium:"5","semi-bold":"6",semibold:"6","demi-bold":"6",demibold:"6",bold:"7","extra-bold":"8",extrabold:"8","ultra-bold":"8",ultrabold:"8",black:"9",heavy:"9",l:"3",r:"4",b:"7"},Da={i:"i",italic:"i",n:"n",normal:"n"},
        Ea=/^(thin|(?:(?:extra|ultra)-?)?light|regular|book|medium|(?:(?:semi|demi|extra|ultra)-?)?bold|black|heavy|l|r|b|[1-9]00)?(n|i|normal|italic)?$/;
    function Fa(a){for(var b=a.f.length,c=0;c<b;c++){var d=a.f[c].split(":"),e=d[0].replace(/\+/g," "),f=["n4"];if(2<=d.length){var g;var k=d[1];g=[];if(k)for(var k=k.split(","),h=k.length,m=0;m<h;m++){var l;l=k[m];if(l.match(/^[\w-]+$/)){var n=Ea.exec(l.toLowerCase());if(null==n)l="";else{l=n[2];l=null==l||""==l?"n":Da[l];n=n[1];if(null==n||""==n)n="4";else var r=Ca[n],n=r?r:isNaN(n)?"4":n.substr(0,1);l=[l,n].join("")}}else l="";l&&g.push(l)}0<g.length&&(f=g);3==d.length&&(d=d[2],g=[],d=d?d.split(","):
        g,0<d.length&&(d=Ba[d[0]])&&(a.c[e]=d))}a.c[e]||(d=Ba[e])&&(a.c[e]=d);for(d=0;d<f.length;d+=1)a.a.push(new H(e,f[d]))}};function Ga(a,b){this.c=a;this.a=b}var Ha={Arimo:!0,Cousine:!0,Tinos:!0};Ga.prototype.load=function(a){var b=new C,c=this.c,d=new va(this.a.api,z(c),this.a.text),e=this.a.families;xa(d,e);var f=new Aa(e);Fa(f);A(c,za(d),D(b));F(b,function(){a(f.a,f.c,Ha)})};function Ia(a,b){this.c=a;this.a=b}Ia.prototype.load=function(a){var b=this.a.id,c=this.c.m;b?B(this.c,(this.a.api||"https://use.typekit.net")+"/"+b+".js",function(b){if(b)a([]);else if(c.Typekit&&c.Typekit.config&&c.Typekit.config.fn){b=c.Typekit.config.fn;for(var e=[],f=0;f<b.length;f+=2)for(var g=b[f],k=b[f+1],h=0;h<k.length;h++)e.push(new H(g,k[h]));try{c.Typekit.load({events:!1,classes:!1,async:!0})}catch(m){}a(e)}},2E3):a([])};function Ja(a,b){this.c=a;this.f=b;this.a=[]}Ja.prototype.load=function(a){var b=this.f.id,c=this.c.m,d=this;b?(c.__webfontfontdeckmodule__||(c.__webfontfontdeckmodule__={}),c.__webfontfontdeckmodule__[b]=function(b,c){for(var g=0,k=c.fonts.length;g<k;++g){var h=c.fonts[g];d.a.push(new H(h.name,ga("font-weight:"+h.weight+";font-style:"+h.style)))}a(d.a)},B(this.c,z(this.c)+(this.f.api||"//f.fontdeck.com/s/css/js/")+ea(this.c)+"/"+b+".js",function(b){b&&a([])})):a([])};var Y=new pa(window);Y.a.c.custom=function(a,b){return new ua(b,a)};Y.a.c.fontdeck=function(a,b){return new Ja(b,a)};Y.a.c.monotype=function(a,b){return new sa(b,a)};Y.a.c.typekit=function(a,b){return new Ia(b,a)};Y.a.c.google=function(a,b){return new Ga(b,a)};var Z={load:p(Y.load,Y)};"function"===typeof define&&define.amd?define(function(){return Z}):"undefined"!==typeof module&&module.exports?module.exports=Z:(window.WebFont=Z,window.WebFontConfig&&Y.load(window.WebFontConfig));}());


/**!
 * wp-color-picker-alpha
 *
 * Overwrite Automattic Iris for enabled Alpha Channel in wpColorPicker
 * Only run in input and is defined data alpha in true
 *
 * Version: 3.0.2
 * https://github.com/kallookoo/wp-color-picker-alpha
 * Licensed under the GPLv2 license or later.
 */
(function(o,a){var t={version:302};if("wpColorPickerAlpha"in window&&"version"in window.wpColorPickerAlpha){var r=parseInt(window.wpColorPickerAlpha.version,10);if(!isNaN(r)&&r>=t.version)return}if(!Color.fn.hasOwnProperty("to_s")){Color.fn.to_s=function(o){o=o||"hex","hex"===o&&this._alpha<1&&(o="rgba");var a="";return"hex"===o?a=this.toString():this.error||(a=this.toCSS(o).replace(/\(\s+/,"(").replace(/\s+\)/,")")),a},window.wpColorPickerAlpha=t;var i="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAAHnlligAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNpi+P///4EDBxiAGMgCCCAGFB5AADGCRBgYDh48CCRZIJS9vT2QBAggFBkmBiSAogxFBiCAoHogAKIKAlBUYTELAiAmEtABEECk20G6BOmuIl0CIMBQ/IEMkO0myiSSraaaBhZcbkUOs0HuBwDplz5uFJ3Z4gAAAABJRU5ErkJggg==";o.widget("a8c.iris",o.a8c.iris,{alphaOptions:{alphaEnabled:!1},_getColor:function(o){return o===a&&(o=this._color),this.alphaOptions.alphaEnabled?(o=o.to_s(this.alphaOptions.alphaColorType),this.alphaOptions.alphaColorWithSpace||(o=o.replace(/\s+/g,"")),o):o.toString()},_create:function(){try{this.alphaOptions=this.element.wpColorPicker("instance").alphaOptions}catch(o){}o.extend({},this.alphaOptions,{alphaEnabled:!1,alphaCustomWidth:130,alphaReset:!1,alphaColorType:"hex",alphaColorWithSpace:!1}),this._super()},_addInputListeners:function(o){var a=this,t=100,r=function(t){var r=o.val(),i=new Color(r),l=(r=r.replace(/^(#|(rgb|hsl)a?)/,""),a.alphaOptions.alphaColorType);o.removeClass("iris-error"),i.error?""!==r&&o.addClass("iris-error"):"hex"===l&&"keyup"===t.type&&r.match(/^[0-9a-fA-F]{3}$/)||i.toIEOctoHex()!==a._color.toIEOctoHex()&&a._setOption("color",a._getColor(i))};o.on("change",r).on("keyup",a._debounce(r,t)),a.options.hide&&o.one("focus",function(){a.show()})},_initControls:function(){if(this._super(),this.alphaOptions.alphaEnabled){var a=this,t=a.controls.strip.clone(!1,!1),r=t.find(".iris-slider-offset"),i={stripAlpha:t,stripAlphaSlider:r};t.addClass("iris-strip-alpha"),r.addClass("iris-slider-offset-alpha"),t.appendTo(a.picker.find(".iris-picker-inner")),o.each(i,function(o,t){a.controls[o]=t}),a.controls.stripAlphaSlider.slider({orientation:"vertical",min:0,max:100,step:1,value:parseInt(100*a._color._alpha),slide:function(o,t){a.active="strip",a._color._alpha=parseFloat(t.value/100),a._change.apply(a,arguments)}})}},_dimensions:function(o){if(this._super(o),this.alphaOptions.alphaEnabled){var a,t,r,i,l,e=this,s=e.options,n=e.controls,p=n.square,h=e.picker.find(".iris-strip");for(a=Math.round(e.picker.outerWidth(!0)-(s.border?22:0)),t=Math.round(p.outerWidth()),r=Math.round((a-t)/2),i=Math.round(r/2),l=Math.round(t+2*r+2*i);l>a;)r=Math.round(r-2),i=Math.round(i-1),l=Math.round(t+2*r+2*i);p.css("margin","0"),h.width(r).css("margin-left",i+"px")}},_change:function(){var a=this,t=a.active;if(a._super(),a.alphaOptions.alphaEnabled){var r=a.controls,l=parseInt(100*a._color._alpha),e=a._color.toRgb(),s=["rgb("+e.r+","+e.g+","+e.b+") 0%","rgba("+e.r+","+e.g+","+e.b+", 0) 100%"];a.picker.closest(".wp-picker-container").find(".wp-color-result");a.options.color=a._getColor(),r.stripAlpha.css({background:"linear-gradient(to bottom, "+s.join(", ")+"), url("+i+")"}),t&&r.stripAlphaSlider.slider("value",l),a._color.error||a.element.removeClass("iris-error").val(a.options.color),a.picker.find(".iris-palette-container").on("click.palette",".iris-palette",function(){var t=o(this).data("color");a.alphaOptions.alphaReset&&(a._color._alpha=1,t=a._getColor()),a._setOption("color",t)})}},_paintDimension:function(o,a){var t=this,r=!1;t.alphaOptions.alphaEnabled&&"strip"===a&&(r=t._color,t._color=new Color(r.toString()),t.hue=t._color.h()),t._super(o,a),r&&(t._color=r)},_setOption:function(o,a){var t=this;if("color"!==o||!t.alphaOptions.alphaEnabled)return t._super(o,a);a=""+a,newColor=new Color(a).setHSpace(t.options.mode),newColor.error||t._getColor(newColor)===t._getColor()||(t._color=newColor,t.options.color=t._getColor(),t.active="external",t._change())},color:function(o){return!0===o?this._color.clone():o===a?this._getColor():void this.option("color",o)}}),o.widget("wp.wpColorPicker",o.wp.wpColorPicker,{alphaOptions:{alphaEnabled:!1},_getAlphaOptions:function(){var a=this.element,t=a.data("type")||this.options.type,r=a.data("defaultColor")||a.val(),i={alphaEnabled:a.data("alphaEnabled")||!1,alphaCustomWidth:130,alphaReset:!1,alphaColorType:"rgb",alphaColorWithSpace:!1};return i.alphaEnabled&&(i.alphaEnabled=a.is("input")&&"full"===t),i.alphaEnabled?(i.alphaColorWithSpace=r&&r.match(/\s/),o.each(i,function(o,t){var l=a.data(o)||t;switch(o){case"alphaCustomWidth":l=l?parseInt(l,10):0,l=isNaN(l)?t:l;break;case"alphaColorType":l.match(/^(hex|(rgb|hsl)a?)$/)||(l=r&&r.match(/^#/)?"hex":r&&r.match(/^hsla?/)?"hsl":t);break;default:l=!!l}i[o]=l}),i):i},_create:function(){o.support.iris&&(this.alphaOptions=this._getAlphaOptions(),this._super())},_addListeners:function(){if(!this.alphaOptions.alphaEnabled)return this._super();var a=this,t=a.element,r=a.toggler.is("a");this.alphaOptions.defaultWidth=t.width(),this.alphaOptions.alphaCustomWidth&&t.width(parseInt(this.alphaOptions.defaultWidth+this.alphaOptions.alphaCustomWidth,10)),a.toggler.css({position:"relative","background-image":"url("+i+")"}),r?a.toggler.html('<span class="color-alpha" />'):a.toggler.append('<span class="color-alpha" />'),a.colorAlpha=a.toggler.find("span.color-alpha").css({width:"30px",height:"100%",position:"absolute",top:0,"background-color":t.val()}),"ltr"===a.colorAlpha.css("direction")?a.colorAlpha.css({"border-bottom-left-radius":"2px","border-top-left-radius":"2px",left:0}):a.colorAlpha.css({"border-bottom-right-radius":"2px","border-top-right-radius":"2px",right:0}),t.iris({change:function(o,t){a.colorAlpha.css({"background-color":t.color.to_s(a.alphaOptions.alphaColorType)}),"function"==typeof a.options.change&&a.options.change.call(this,o,t)}}),a.wrap.on("click.wpcolorpicker",function(o){o.stopPropagation()}),a.toggler.on("click",function(){a.toggler.hasClass("wp-picker-open")?a.close():a.open()}),t.on("change",function(i){var l=o(this).val();(t.hasClass("iris-error")||""===l||l.match(/^(#|(rgb|hsl)a?)$/))&&(r&&a.toggler.removeAttr("style"),a.colorAlpha.css("background-color",""),"function"==typeof a.options.clear&&a.options.clear.call(this,i))}),a.button.on("click",function(i){o(this).hasClass("wp-picker-default")?t.val(a.options.defaultColor).change():o(this).hasClass("wp-picker-clear")&&(t.val(""),r&&a.toggler.removeAttr("style"),a.colorAlpha.css("background-color",""),"function"==typeof a.options.clear&&a.options.clear.call(this,i),t.trigger("change"))})}})}})(jQuery);

/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: https://betterstudio.com/
 *
 *  \--> BetterStudio, 2017 <--/
 */


(function ($) {
    "use strict";

    var prevModalZIndex = 0,
        activeModalsId  = [];
    var BS_Modal        = function (options) {
        function _return_true() {
            return true;
        }

        this.options = $.extend(true, {
            template: 'default',
            skin: 'skin-1',

            initialZIndex: 0,
            show: true,
            /**
             * {Obejct} Mustache View Object
             * @see {@link https://github.com/janl/mustache.js#usage}
             */
            content: {},
            close_button: true,  //Display Modal Window Close Button?
            button_position: 'right',
            animations: {
                delay: 600,
                open: 'bs-animate bs-fadeInDown',
                close: 'bs-animate bs-fadeOutUp'
            },

            /**
             * List of Buttons to Generate
             * buttons: {
             *   BUTTON_ID : {
             *       type: primary|secondary|normal,
             *       action: close|yes|normal,
             *       clicked: Callback Call After Button Clicked,
             *       label: Button Label,
             *       btn_classes: List of Button Classes as HTML Separated by space,
             *       href: Button Link href Value
             *   }
             * }
             */
            buttons: {},

            events: {
                //Event Fire Before click (on every thing) in Modal Section
                before_click: _return_true,
                //Event Fire After Click (on every thing) in Modal Section
                clicked: _return_true,
                handle_keyup: function (e, obj, _continue) {
                    return _continue;
                }
            },

            styles: {
                modal: '',
                container: ''
            },

            modalId: 'modal-'+Math.floor(Math.random() * 999), // Set Random Modal ID
            modalClass: '',
            destroyHtml: true, // Remove modal html after it closed
            is_vertical_center: true // if set true, make modal window center vertically
        }, options);

        // $modal HTML element object used in some methods
        this.$modal = false;
        // $overlay HTML element object used in some methods
        this.$overlay = false;
        //Timer setTimeout return numbers
        this.timerTimeouts = [];
        //document body jQuery object
        this.$document = false;
        //Modal Unique ID
        this.modalID = false;

        this.visible = false;

        this.init(); //Start Modal!
    };
    BS_Modal.prototype  = {

        /**
         * List of Modal Templates
         *
         * templates = {
         *  templateName: Mustache Template String
         *  ,
         *  ...
         * }
         */
        templates: {
            'default': '\n<div class="bs-modal-default bf-fields-style"  {{#inline_style}} style="{{inline_style}}" {{/inline_style}}>\n    {{#close_button}}\n    <a href="#" class="bs-close-modal">\n    </a>\n    {{/close_button}}\n    <div class="bs-modal-header-wrapper bs-modal-clearfix">\n        <h2 class="bs-modal-header">\n            {{header}}\n        </h2>\n    </div>\n\n    <div class="bs-modal-body">\n        {{{bs_body}}}\n    </div>\n\n    {{#bs_buttons}}\n    <div class="bs-modal-bottom bs-modal-buttons-{{btn_position}} bs-modal-clearfix">\n        {{{bs_buttons}}}\n    </div>\n    {{/bs_buttons}}\n</div>',
            'single_image': '\n<div class="bs-modal-default bf-fields-style" {{#inline_style}} style="{{inline_style}}" {{/inline_style}}>\n    {{#close_button}}\n    <a href="#" class="bs-close-modal">\n        <i class="fa fa-times" aria-hidden="true"></i>\n    </a>\n    {{/close_button}}\n    <div class="bs-modal-header-wrapper bs-modal-clearfix">\n        <h2 class="bs-modal-header">\n            {{#icon}}\n            <i class="fa {{icon}}"></i>\n            {{/icon}}\n\n            {{header}}\n        </h2>\n    </div>\n\n    <div class="bs-modal-body bf-clearfix">\n        \n        <div class="bs-modal-image bf-clearfix" {{#image_align}} style="float:{{image_align}}"{{/image_align}}>\n\n            <img src="{{image_src}}" {{#image_style}} style="{{image_style}}"{{/image_style}}/>\n            \n            {{#image_caption}}\n            <div class="bs-modal-image-caption">\n                {{image_caption}}\n            </div>\n            {{/image_caption}}\n        </div>\n        {{{bs_body}}}\n    </div>\n\n    {{#bs_buttons}}\n    <div class="bs-modal-bottom bs-modal-buttons-left bs-modal-clearfix">\n        {{{bs_buttons}}}\n        \n        {{#checkbox}}\n        <div class="bs-modal-checkbox">\n            <input type="checkbox" name="include_content" class="toggle-content" value="1" checked="checked"> <label class="checkbox-label">{{checkbox_label}}</label>\n        </div>\n        {{/checkbox}}\n    </div>\n    {{/bs_buttons}}\n</div>'
        },

        /**
         * List of Modal Skins
         *
         * Skins = {
         *  skinName: Mustache Template String
         *  ,
         *  ...
         * }
         */
        skins: {
            'skin-1': '<div class="bs-modal-description">\n   {{#title}} <h3 class="bs-modal-title">{{title}}</h3>\n   {{/title}} \n    {{{body}}}\n</div>',
            'loading': ' <div class="bs-modal-loading bs-modal-loading-1">\n    <img src="data:image/false;base64,R0lGODlhIAAIAPEAALy8vHh4eP///wAAACH/C05FVFNDQVBFMi4wAwEAAAAh/hhPcHRpbWl6ZWQgd2l0aCBlemdpZi5jb20AIfkEBQsAAQAsAAAAACAACAAAAiGEb6GBuNyiWxK8Wq/UuHFPdaCSheLxTeSJmiPEWq4KNwUAIfkEBQsAAAAsAAAAAAgACAAAAgaMj6nL7V0AIfkEBQsAAgAsAAAAABQACAAAAhuEb6IS7Y3QYs/FM6u9IOvAdcsHcp4WnlU6fgUAIfkEBQsAAgAsDAAAABQACAAAAhuEb6IS7Y3QYs/FM6u9IOvAdcsHcp4WnlU6fgUAOw==" />\n\n     <div class="bs-modal-loading-heading">\n         <h4>{{loading_heading}}</h4>\n     </div>\n </div>\n',
            'loading-2': ' <div class="bs-modal-loading">\n     <div class="la-line-scale-pulse-out-rapid la-2x">\n         <div></div>\n         <div></div>\n         <div></div>\n         <div></div>\n         <div></div>\n     </div>\n     \n     <div class="bs-modal-loading-heading">\n         <h4>{{loading_heading}}</h4>\n     </div>\n</div>\n',
            success: ' <div class="bs-modal-success">\n     \n     <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>\n     \n     <div class="bs-modal-bs-modal-success-heading">\n         <h4>{{success_heading}}</h4>\n     </div>\n</div>\n'
        },

        /**
         * HTML Structure of Button
         * @see {@link this.generate_buttons}
         */
        button_struct: '<a {{#href}}href="{{href}}"{{/href}} {{#btn_classes}}class="{{btn_classes}}"{{/btn_classes}} id="{{id}}">{{{label}}}</a>',

        /**
         * Display Debug Console Messages
         * @param {*} message
         */
        debug: function (message) {
            console.error(message);
        },


        get_html: function (templateName, skinName, replacement) {

            if (typeof this.templates[ templateName ] === 'undefined') {
                this.debug('invalid template name');

                return false;
            }

            if (typeof this.skins[ skinName ] === 'undefined') {
                this.debug('invalid skin');

                return false;
            }
            var template = Mustache.parse(this.templates[ templateName ]);
            Mustache.parse(this.skins[ skinName ]);

            var body_content            = Mustache.render(this.skins[ skinName ], replacement),
                template_replace_object = {
                    bs_body: body_content,
                    bs_buttons: this.generate_buttons(),
                    close_button: this.options.close_button,
                    inline_style: this.options.styles.modal,
                    btn_position: this.options.button_position
                };

            this.handle_event('prepare_html', this,templateName, skinName, replacement);

            return Mustache.render(this.templates[ templateName ], $.extend(replacement, template_replace_object));
        },

        before_append_html: function (context) {
            this.handle_event('before_append_html', this, context);
        },
        /**
         *  This function will fire after modal HTML changed
         */

        after_append_html: function (context) {
            if (this.options.is_vertical_center) {
                this.make_vertical_center();
            }

            this.$modal.focus();

            this.handle_event('after_append_html', this, context);
        },

        /**
         * Fire after modal closed
         */
        after_close_modal: function () {
            // unbind resize event
            $(window).off('resize.bs-modal');

            activeModalsId.pop();
        },

        has_button: function () {

            return typeof this.options.buttons === 'object';
        },

        /**
         * Generate Modal Body HTML Codes. Prepare Skin HTML Output and Append to {{{bs_body}}} Section of Template.
         *
         * @returns {boolean} true on Success, false on Failure
         */
        append_html: function () {
            this.before_append_html('modal');

            var htmlOutput = this.get_html(this.options.template, this.options.skin, this.options.content);

            if (typeof htmlOutput !== 'string')
                return htmlOutput;

            this.$modal.html(htmlOutput);

            this.after_append_html('modal');

            return true;
        },

        /**
         * Generate Buttons HTML Code
         *
         * @returns {String} Button HTML Codes on Success, Empty String on Failure
         */
        generate_buttons: function () {
            if (!this.has_button())
                return '';

            Mustache.parse(this.button_struct);

            var html_output = '';

            for (var btn_id in this.options.buttons) {

                html_output += "\n";
                html_output += Mustache.render(this.button_struct, this.get_button_replacement_object(btn_id));
            }

            return html_output;
        },

        /**
         * Generate Mustache View Object used to Render Button HTML
         * @see {@link this.button_struct}
         *
         * @param button_id {String} Property Name of Option, buttons Object
         * @see {@link this.options.buttons}
         * @see {@link https://github.com/janl/mustache.js#usage}
         *
         * @returns {Object} Mustache View Object on Success, Empty Object on Failure
         */
        get_button_replacement_object: function (button_id) {
            if (typeof this.options.buttons[ button_id ] !== 'object') {
                this.debug('invalid button id');

                return {};
            }

            var obj = $.extend({focus: false}, this.options.buttons[ button_id ]);

            delete obj.clicked;

            obj.id = button_id;

            if (typeof obj.btn_classes !== 'string') {
                obj.btn_classes = '';
            }
            if ($.inArray(obj.type, [ 'primary', 'secondary' ]) !== -1) {
                obj.btn_classes += ' bs-modal-btn-' + obj.type;
            }

            if (obj.focus) {
                obj.btn_classes += ' bs-modal-btn-focus';
            }

            return obj;
        },

        /**
         * Close & Remove active Modal
         */
        close_modal: function (who_called) {

            this.visible   = false;
            var self       = this,
                who_called = who_called || 'callback';

            self.handle_event('modal_close', this, who_called);

            for (var i = 0; i < this.timerTimeouts.length; i++) {
                clearTimeout(this.timerTimeouts[ i ]);
            }

            self.$modal
                .removeClass(this.options.animations.open)
                .addClass(this.options.animations.close)
                .delay(this.options.animations.delay)
                .queue(function (n) {

                    self.$modal
                        .hide()
                        .removeClass(self.options.animations.close);

                    if (self.options.destroyHtml) {
                        self.$modal.remove();
                    } else {
                        self.$modal.clearQueue();
                    }
                    n();
                });

            self.$overlay.fadeOut(this.options.animations.delay, function () {
                if (self.options.destroyHtml) {
                    self.find().remove();
                }
                self.$document.removeClass('modal-open').removeClass('modal-open-vc');

                // remove keyup event when modal closed
                self.keyup_unbind();

                // handle after click global event
                self.handle_event('modal_closed', this, who_called);
            });

            self.after_close_modal();
        },


        /**
         * unbind keyup event
         */
        keyup_unbind: function () {

            if (this.options.destroyHtml) {
                this.$document.off('keyup.bs-modal-' + this.getModalID());
            }
        },
        /**
         * handle modal events. (EX: before_click event)
         * Fire Registered callback for event
         *
         * @param event {String} event name
         * @param el    {object} Active HTML Element Object
         * @returns     {*}      Return Fired Callback Results
         */
        handle_event: function (event, el) {
            var args = Array.prototype.slice.call(arguments, 2);

            if (typeof this.options.events[ event ] === 'function')
                return this.options.events[ event ].apply(this, [ el, this.options ].concat(args));
        },

        /**
         * Handle Timer Callback - Fire Registered Callback After Specified Delay
         *
         * @param timer_object. Timer Object. @see {@link this.options.timer}
         *   timer_object = {
         *      callback: function to fire.
         *      delay: delay to fire callback
         *  }
         *
         */
        handle_timer: function (timer_object) {
            var self = this;

            this.timerTimeouts.push(
                setTimeout(function () {
                    timer_object.callback.call(self, self.option);
                }, timer_object.delay)
            );

        },

        /**
         * Refresh Modal Content inside modal events
         * @param options {
             *   template:  {String} New Modal Template,
             *   skin:      {String} New Modal Skin,
             *   content:   {Obejct} Mustache View Object,
             *   animations:{Object} Animation Settings
             * }
         * @returns {*}
         */
        change_skin: function (options, deep) {
            deep = typeof deep === 'boolean' ? deep : true;

            var settings = $.extend(deep, {
                template: this.options.template,
                skin: this.options.skin,
                content: this.options.content,
                animations: {
                    open: false,
                    body: false,
                    delay: 20
                },
                buttons: {},
                styles: {},
            }, options);

            /**
             * set new button value
             * @see {@link this.generate_buttons}
             *
             * @type {Object}
             */
            this.options.buttons = settings.buttons;


            /***
             *
             */
            if (settings.styles.container) {
                var replacement = {inline_style: settings.styles.container},
                    wrapper     = Mustache.render('<div class="bs-modal bf-fields-style" {{#inline_style}} style="{{inline_style}}" {{/inline_style}}></div>\n', replacement);

                this.$modal[ 0 ].outerHTML = wrapper;
                this.$modal                = this.find('.bs-modal')
                                                 .css('z-index', prevModalZIndex + 1)
                                                 .show();
                this.afterWrapperHtmlGenerated();
            }

            var htmlOutput = this.get_html(settings.template, settings.skin, settings.content);

            if (typeof htmlOutput !== 'string')
                return htmlOutput;

            this.$modal
                .html(htmlOutput)
                .removeClass(this.options.animations.open)
                .delay(20)
                .queue(function (n) {
                    if (settings.animations.open) {
                        $(this)
                            .addClass(settings.animations.open)
                    }
                    n();
                })
                .removeClass(function (idx, css) {

                    return (css.match(/(^|\s)skin-\S+/g) || []).join(' ');
                })
                .addClass('skin-' + settings.skin)
                .find('.bs-modal-body')
                .addClass(settings.animations.body);

            if (typeof settings.timer === 'object') {
                //start timer after modal open effect finished
                this.handle_timer(settings.timer);
            }

            this.after_append_html('change_skin');
            this.handle_event('modal_loaded', this);
        },

        make_vertical_center: function () {
            var self = this;
            $(window).on('resize.bs-modal', function () {

                if (!self.isModalLast()) {
                    return false;
                }

                var mh = self.$modal.innerHeight(),
                    wh = window.innerHeight;

                if (wh > mh) {
                    var top = Math.ceil((wh - mh) / 2);
                } else {
                    var top = 35; // default top margin
                }

                self.$modal.css('top', top);
            }).trigger('resize.bs-modal');
        },

        getModalID: function () {
            return this.modalID;
        },
        setModalID: function (ID) {
            this.modalID = ID;
        },

        setActiveModalId: function (ID) {
            activeModalsId.push(ID);
        },

        getActiveModalId: function () {
            if (activeModalsId.length) {
                return activeModalsId[ activeModalsId.length - 1 ];
            }

            return 0;
        },

        /**
         * Check is current modal last opened
         * @returns {boolean}
         */
        isModalLast: function () {
            return this.getActiveModalId() === this.getModalID();
        },

        find: function (selector) {
            var $context = this.$document.find('#' + this.getModalID());

            if (selector) {
                return $(selector, $context);
            }

            return $context;
        },

        _fixModalZindex: function () {

        },

        show: function () {
            var self = this;

            if(self.$document.find('.vc_ui-panel.vc_active .vc_ui-panel-window-inner').length) {
                self.$document.addClass('modal-alongside-vc-panel');
            }

            this.$overlay
                .fadeIn(this.options.animations.open, function () {
                    self.$document.addClass('modal-open');
                });

            this.$modal
                .addClass(self.options.animations.open)
                .addClass('skin-' + self.options.skin)
                .show().delay(self.options.animations.delay).queue(function (n) {

                if (typeof self.options.timer === 'object') {
                    //start timer after modal open effect finished
                    self.handle_timer(self.options.timer);
                }

                n();
            });

            this.setActiveModalId(this.getModalID());
            this.visible = true;
            this.handle_event('modal_show', this);
        },
        /**
         * Initial Modal - Generate Modal Html Output and Handle Events
         */
        init: function () {

            this.$document = $(document.body);
            var self       = this;

            this.setModalID(this.options.modalId);
            this.setActiveModalId(this.getModalID());

            var generateModalHtml = this.find().length === 0;

            if (generateModalHtml) { //  append modal html elements if needed
                var replacement = {
                    inline_style: this.options.styles.container,
                    modal_id: this.getModalID(),
                    modal_class: this.options.modalClass
                };
                this.$document
                    .append(Mustache.render('<div id="{{modal_id}}"{{#modal_class}} class="{{modal_class}} bf-fields-style"{{/modal_class}}>\n    <div class="bs-modal-overlay"></div>\n        <div class="bs-modal" {{#inline_style}} style="{{inline_style}}" {{/inline_style}}>\n    </div>\n</div>', replacement));
            }

            // $modal and $overlay used in some methods
            this.$modal   = this.find('.bs-modal');
            this.$overlay = this.find('.bs-modal-overlay');

            /**
             * Fix Modal Overlay Z-index
             */
            if (prevModalZIndex) {

                prevModalZIndex = Math.max(prevModalZIndex, this.options.initialZIndex);
                prevModalZIndex++;

                this.$overlay.css('z-index', prevModalZIndex);
                this.$modal.css('z-index', prevModalZIndex + 1);

            } else if(this.options.initialZIndex) {

                this.$overlay.css('z-index', this.options.initialZIndex);
                this.$modal.css('z-index', this.options.initialZIndex+1);

                prevModalZIndex = this.options.initialZIndex;

            }else{
                prevModalZIndex = parseInt(this.$modal.css('z-index'));
            }


            if (generateModalHtml) {
                self.afterWrapperHtmlGenerated();
            }

            //close modal when user pressed esc key, if close button was enabled
            //and prevent opening another modal when user presses enter key
            if (this.options.close_button) {

                /**
                 * 27   => escape
                 * 13   => enter
                 */
                this.$document.on('keyup.bs-modal-' + this.getModalID(), function (e) {

                    if (!self.handle_event('handle_keyup', e, self.isModalLast())) {

                        return false;
                    }

                    if(e.target.tagName === "TEXTAREA") {
                        return false;
                    }

                    if (self.options.close_button && e.which === 27) {

                        self.close_modal('esc');
                    } else if (e.which === 13) {

                        //call primary button event
                        if (self.has_button()) {
                            var $btn_context  = self.$modal
                                                    .find('.bs-modal-bottom');
                            var $btn_selector = $(".bs-modal-btn-focus", $btn_context);
                            if (!$btn_selector.length) {
                                $btn_selector = $(".bs-modal-btn-primary", $btn_context);
                            }

                            $btn_selector.trigger('click');
                        }

                        self.keyup_unbind();
                    }
                });
            }

            if (this.options.show) {

                self.show();
            }


            if (generateModalHtml) {
                self.append_html();
            }

            this.handle_event('modal_loaded', this);
        },

        afterWrapperHtmlGenerated: function () {
            var self = this;

            //handle click events
            this.$modal.on('click', 'a', function (e) {

                //handle before click global event
                if (self.handle_event('before_click', this)) {

                    var $this = $(this), id = $this.attr('id');

                    //link with bs-close-modal is close button
                    if ($this.hasClass('bs-close-modal')) {

                        e.preventDefault();
                        self.close_modal('btn');

                    } else if (id && typeof self.options.buttons[ id ] === 'object') {
                        var btn = self.options.buttons[ id ];

                        // Handle buttons actions
                        switch (btn.action) {
                            case 'close':

                                self.close_modal('link');
                                break;
                        }

                        if (typeof btn.clicked === 'function') {

                            btn.clicked.call(self);
                        }

                    }

                    // handle after click global event
                    self.handle_event('clicked', this)
                }
            });
        }

    };


    /**
     * Register bs_modal jQuery function
     *
     * @param options options object.
     * @returns {jQuery}
     */
    $.bs_modal = function (options) {

        return new BS_Modal(options);
    };

    $.bs_modal_template = function (name, html) {

        if (html) {

            BS_Modal.prototype.templates[name] = html;
        } else {

            return BS_Modal.prototype.templates[name];
        }
    };

})(jQuery);

/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: https://betterstudio.com/
 *
 *  \--> BetterStudio, 2017 <--/
 */


(function ($) {
    "use strict";


    /**
     * Init modal
     *
     * @param {Object} options
     * @constructor
     */
    var BsSelectorModal = function (options, bsModal) {

        this.options = $.extend(true, {
            itemBeforeHtml: '<li class="bssm-item {{class}}"{{#type}} data-item-type="{{type}}"{{/type}}{{#cat}} data-item-cat="{{cat}}"{{/cat}} data-item-id="{{id}}">',
            itemInnerHtml: '',
            itemAfterHtml: '</li>',

            modalHtml: false,
            id: 'modal-' + Math.ceil(Math.random() * 1e3),

            bsModal: bsModal || {},
            content: {},

            items: {},
            itemsGroupSize: 6,

            fuse: {
                keys: ["name"],
                tokenize: true,
                matchAllTokens: true,
                threshold: 0.3
            },

            searchDelay: 300,
            overlayClickCloseModal: true,
            searchAutoFocus: true,
            selectedItemId: 0,
            modalClass: '',

            categories: {},
            types: {},

            inactivateTypes: [],
            inactivateCats: [],

            noSidebar: false,

            /**
             * @property {callback} events.show_item Fire when a item must show
             * @property {callback} events.hide_item Fire when a item must hide
             *
             * @property {callback} events.modal_content [Filter] Generate bsModal options.content object
             * @property {callback} events.scrollIntoView  Fire when item into scrolled into view
             * @property {callback} events.after_append_html
             * @property {callback} events.item_select
             * @property {callback} events.item_click
             */
            events: {
                show_item: function (el) {
                    el.style.display = 'inline-block';
                },

                hide_item: function (el) {
                    el.style.display = 'none';
                }
            }

        }, options);

        if (!this.options.modalHtml) {

            var categories_template = '';

            if (this._length(this.options.categories)) {
                categories_template = '<div class="bssm-categories">\n    <h4 class="title"><span>{{filter_cat_title}}</span></h4>\n\n    <ul class="bssm-filter-items bf-radio-group">\n\n        {{#count_all}}\n        <li>\n            <div class="bf-radio-field" data-current-state="active" data-name="all"><span style="display: inline-block;"><span class="icon" aria-hidden="true"></span></span></div>\n            <span class="name bf-radio-toggle" data-id="{{key}}">{{all_l10n}}</span><span class="length">({{count_all}})</span>\n        </li>\n        {{/count_all}}\n        \n        {{#filter_cats}}\n        <li>\n            <div class="bf-radio-field" data-current-state="deactivate" data-name="{{key}}"><span><span class="icon" aria-hidden="true"></span></span></div>\n            <span class="name bf-radio-toggle" data-id="{{key}}">{{value}}</span><span class="length">({{count}})</span>\n        </li>\n        {{/filter_cats}}\n\n    </ul>\n</div>\n';
            }

            var types_template = '';

            if (this._length(this.options.types)) {
                types_template = '\n<div class="bssm-types">\n    <h4 class="title"><span>{{filter_type_title}}</span></h4>\n\n    <ul class="bssm-filter-items bf-radio-group">\n\n        {{#count_all}}\n        <li>\n            <div class="bf-radio-field" data-current-state="active" data-name="all"><span style="display: inline-block;"><span class="icon" aria-hidden="true"></span></span></div>\n            <span class="name bf-radio-toggle" data-id="{{key}}">{{all_l10n}}</span><span class="length">({{count_all}})</span>\n        </li>\n        {{/count_all}}\n\n        {{#filter_types}}\n        <li>\n            <div class="bf-radio-field" data-current-state="deactivate" data-name="{{key}}"><span><span class="icon" aria-hidden="true"></span></span></div>\n            <span class="name bf-radio-toggle" data-id="{{key}}">{{value}}</span>{{#count}}<span class="length">({{count}})</span>{{/count}}\n        </li>\n        {{/filter_types}}\n    </ul>\n</div>\t';
            }

            this.options.modalHtml = '<div class="bs-modal-default bf-fields-style bs-selector-modal ' + this.options.modalClass + '" {{#inline_style}} style="{{inline_style}}" {{/inline_style}}>\n{{#close_button}}\n<a href="#" class="bs-close-modal">\n</a>\n{{/close_button}}\n<div class="bs-modal-header-wrapper bs-modal-clearfix">\n    <h2 class="bs-modal-header">\n\n        {{{header}}}\n    </h2>\n</div>\n\n<div class="bs-modal-body">\n    <!--{{{bs_body}}}-->\n\n    <input type="hidden" name="item" class="bssm-input">\n\n    <div class="bssm-content {{content_class}}">\n        <ul class="bssm-list">\n            {{#items}}\n                '
                + this.options.itemBeforeHtml + this.options.itemInnerHtml + this.options.itemAfterHtml +
                '        \n            {{/items}}\n        </ul>\n    </div>\n    <div class="bssm-sidebar">\n        <div class="bssm-search">\n            <input type="text" class="bssm-search-input" value="" placeholder="{{search}}" autofocus>\n            <span class="search-icon"></span>\n        </div>\n\n        <div class="bssm-filter">' + categories_template + types_template +
                ' </div>\n        \n    </div>\n</div>';
        }
        this.bsModal     = false;
        this.$allItems   = false;
        this.$itemsCache = {};

        this._scrollIntoView = [];
        this._visibleItems   = [];
        this.selectedItem    = false;

        this.initialized = false;

        this.fuse = new Fuse(this.options.items, this.options.fuse);

        this.init();
    };

    BsSelectorModal.prototype = {

        /**
         * Initialize Modal
         */
        init: function () {
            var self = this;

            self.initialized = true;

            $.bs_modal_template(this.options.id, this.options.modalHtml);

            var bsModalOptions = {
                modalId: this.options.id,
                buttons: this.options.buttons,
                destroyHtml: false,
                modalClass: this.options.modalClass,
                show: false,
                styles: {
                    container: 'width: 1060px;max-width:100%'
                },
                template: this.options.id,

                content: self._getModalContent(),

                events: {
                    before_append_html: function () {

                    },
                    after_append_html: function () {
                        self.bsModal = this;

                        self.options.noSidebar && self.bsModal.$modal.addClass('no-sidebar');

                        self.updateItemsList();
                        self._bindEvents();

                        self._updateSidebarCheckboxStatus();

                        self.handleEvent('after_append_html', '');

                    },
                    /**
                     * Update Input Value
                     */
                    modal_closed: function () {

                        self._updateSidebarCheckboxStatus('dont-refresh-items');
                        self._showHide(self.$allItems, true);

                        self.handleEvent('modal_closed', '');

                        self.initialized = false;
                    },


                    modal_loaded: function () {

                        if (self.options.searchAutoFocus) {

                            /**
                             * Auto focus on 'search' field
                             */
                            $('input.bssm-search-input', this.$modal).focus();
                        }

                        self.handleEvent('modal_loaded', '');
                    },

                    modal_show: function () {
                        self.handleEvent('modal_show', '');

                        if (self.initialized) {
                            self.scrollIntoView(0, self.options.itemsGroupSize);
                        }
                    }
                }

            };

            $.bs_modal($.extend(true, bsModalOptions, self.options.bsModal));
        },

        show: function () {
            this.bsModal.show();
        },
        updateItemsList: function () {
            this.$allItems = $(".bssm-item", this.bsModal.$modal);
        },

        /**
         *
         * Bind Events
         *
         * @private
         */
        _bindEvents: function () {
            var self = this;

            var $context = this.bsModal.$modal;

            if (typeof BetterStudio === "object" && BetterStudio.Libs && BetterStudio.Libs.checkboxMultiState) {

                BetterStudio.Libs.checkboxMultiState($context);
            }

            /**
             *
             * @param {jQuery|array} _visibleItems
             */
            function updateVisibleItems(_visibleItems) {
                self._visibleItems = _visibleItems;
            }

            updateVisibleItems(self.$allItems);

            var $itemsContainer = $(".bssm-list", $context),
                $itemsList      = $itemsContainer.find('.bssm-item');

            self._cacheItems($itemsList);

            function handleFilters() {

                function getActiveItems(type) {
                    var activeItems  = [],
                        activeFilter = $(".bssm-" + type + " .bf-radio-field[data-current-state='active']", $context).data('name');


                    if (activeFilter) {

                        if (activeFilter === 'all') {

                            var idx = type === 'categories' ? 'filter_cats' : "filter_" + type;

                            if (self.bsModal.options.content[idx]) {
                                self.bsModal.options.content[idx].forEach(function (o) {

                                    activeItems.push(o.key);
                                });
                            }
                        } else {

                            activeItems.push(activeFilter);
                        }
                    }

                    return activeItems;
                }

                var checkList = {};

                if (self._length(self.options.types)) {
                    checkList.itemType = getActiveItems('types');
                }

                if (self._length(self.options.categories)) {
                    checkList.itemCat = getActiveItems('categories');
                }

                return self._filterItems(function () {

                    return (function (el) {
                        var value, _break,
                            itemValue,
                            data = self.$(el).data(); // cat & type props encoded as json already

                        for (var x in checkList) {
                            value     = checkList[x];

                            if( typeof data[x] ===  "undefined" || data[x] === '' ){
                                continue;
                            }

                            itemValue = self.json_decode(data[x]);
                            if (typeof itemValue === "string") {
                                itemValue = [itemValue];
                            }

                            if (typeof value === "string") {
                                value = [value];
                            }

                            var found = false;
                            for (var i = 0; i < itemValue.length; i++) {

                                if ($.inArray(itemValue[i], value) > -1) {

                                    found = true;
                                    break;
                                }
                            }

                            if (!found) {
                                return false;
                            }
                        }

                        return true;
                    })(this);
                });
            }


            function itemsScrollIntoView() {

                var singleItemHeight = $itemsList.outerHeight(true);

                /**
                 * Detect how many items show in each row
                 */
                function getItemsInSingleRow() {
                    var maxWidth   = $itemsContainer.width(),
                        itemsWidth = $itemsContainer.find('.bssm-item').outerWidth(true);

                    return Math.max(1, Math.floor(maxWidth / itemsWidth));
                }

                function getCurrentActiveItemsBlockNumber() {
                    var numberOfRows = self.options.itemsGroupSize / getItemsInSingleRow();

                    return Math.ceil($itemsContainer.scrollTop() / (singleItemHeight * numberOfRows));
                }

                var block = getCurrentActiveItemsBlockNumber();

                if (block) {
                    var currentBlock = (block * self.options.itemsGroupSize ); // - 1;

                    self.scrollIntoView(currentBlock, currentBlock + self.options.itemsGroupSize);
                }

            }

            function handleItemSearch(e, status) {

                /**
                 * Escape: 27
                 * Space: 32
                 */
                if ($.inArray(e.which, [32, 27]) !== -1) {
                    return;
                }
                clearTimeout(_searchTimeout);

                var searchQuery = $.trim(this.value),
                    $parent     = $(this).parent();

                $parent[searchQuery ? 'addClass' : 'removeClass']('active');

                if (status === 'change-icon') {
                    return;
                }

                _searchTimeout = setTimeout(function () {

                    $(".bssm-filter-items .bf-radio-field[data-name='all']", $context).trigger('click', ['search']); // reset filters

                    if (searchQuery) {

                        var visibleItemsIds = [];

                        self.fuse.search(searchQuery).forEach(function (found) {
                            visibleItemsIds.push(found.id);
                        });

                        var found, _visibleItems = [];
                        for (var i = 0; i < self.$allItems.length; i++) {

                            found = visibleItemsIds.indexOf(
                                    self.get_attr(self.$allItems[i], 'itemId')
                                ) !== -1;

                            if (found) {
                                _visibleItems.push(self.$allItems[i]);
                            }

                        }

                        self._showHide(self.$allItems, false);
                        self._showHide(_visibleItems, true);

                        updateVisibleItems(_visibleItems);
                    } else {
                        self.$allItems.show();
                        updateVisibleItems(self.$allItems);
                    }

                }, self.options.searchDelay);
            }


            if (self.eventExists('item_click')) {

                $itemsContainer.on("click",".bssm-item", function () {
                    self.handleEvent('item_click', this);
                });
            }

            $itemsContainer.on("click",".bssm-item", function () {

                var $item = self.$(this);

                self.markAsSelected($item);

                self.handleEvent('item_select', this, $item.data('item-id'));
            });

            var _searchTimeout;


            $('.bssm-search-input', $context).on('keyup', handleItemSearch);
            $('.bssm-search .search-icon', $context).on('click', function () {
                var $input = $('.bssm-search-input', $(this).parent());
                $input.val('');
                $input.trigger('keyup');
            });

            // Event to Handle Filter by type & category

            $(".bssm-sidebar .bf-radio-group", $context).on('bf-radio-change', function (e, status, flag) {
                if (flag !== 'dont-refresh-items') {

                    if (flag !== 'search') {
                        $(".bssm-search-input", $context).val('').trigger('keyup', ['change-icon']);
                    }

                    var $foundItems = handleFilters();

                    self._showHide(self.$allItems, false);
                    self._showHide($foundItems, true);
                    updateVisibleItems($foundItems);
                }
            });


            if (self.eventExists('scrollIntoView')) {

                $itemsContainer.on('scroll', function () {
                    itemsScrollIntoView();
                });
            }

            if (self.options.overlayClickCloseModal) {

                self.bsModal.find(".bs-modal-overlay").on('click', function () {
                    self.bsModal.close_modal();
                });
            }
        },

        /**
         * Trigger 'scroll into view' event of elements from start to end range
         *
         * @param {number} start
         * @param {number} end
         * @private
         */

        scrollIntoView: function (start, end) {
            var self  = this,
                items = self._visibleItems.slice(start, end);

            var results = [], id;

            for (var i = 0; i < items.length; i++) {

                id = self.get_attr(items[i], 'itemId');

                if (self._scrollIntoView.indexOf(id) === -1) {

                    self._scrollIntoView.push(id);
                    results.push(items[i]);
                }
            }

            if (results.length) {
                self.handleEvent('scrollIntoView', results, start, end);
            }

        },


        getSelectedItem: function () {

            return this.selectedItem;
        },

        setSelectedItem: function (element, id) {

            this.selectedItem = [element, id];
        },

        markAsSelected: function ($item) {
            this.$allItems.removeClass('bssm-selected');
            $item.addClass('bssm-selected');

            this.setSelectedItem($item, $item.data('item-id'));
        },

        json_encode: function (data) {
            data = JSON.stringify(data);

            return data.replace(/"/g, '\\"').replace(/'/g, "\\'");

        },

        json_decode: function (data) {
            data = data.replace(/\\"/g, '"').replace(/\\'/g, "'");
            return JSON.parse(data);
        },
        /**
         * Generate BSModal content option bsModal.options.content
         * @returns object
         * @private
         */
        _getModalContent: function () {
            var self = this,
                data;

            var results = self.options.content,
                o       = self.options;

            // Fill items with json encoded cat & type attributes
            results.items = [];

            self.options.items.forEach(function (item) {

                var _item = $.extend({},item);

                if (_item.cat) {
                    _item.cat = self.json_encode(item.cat);
                }

                if (_item.type) {
                    _item.type = self.json_encode(item.type);
                }

                results.items.push(_item);
            });

            var itemsCount_all = self._length(self.options.items);
            var itemsCount = 0;

            // remove no category items from all count
            for (var i = 0; i < itemsCount_all; i++) {

                if ( typeof self.options.items[i] === 'object' &&
                    self.options.items[i]['id'] !== "default") {
                    itemsCount++;
                }
            }

            function appendCountProperty(data, count) {
                var c;

                for (var i = 0; i < data.length; i++) {
                    c = data[i].key;

                    if (typeof count[c] !== 'undefined') {
                        data[i].count = String(count[c]);
                    } else {
                        data[i].count = '0';
                    }
                }

                return data;
            }

            function _countKey(key) {

                var _results = {}, k;

                for (var i = 0; i < itemsCount_all; i++) {

                    if(typeof self.options.items[i] !== 'object') {
                        continue;
                    }

                    k = self.options.items[i][key];

                    if (_results[k])
                        _results[k]++;
                    else
                        _results[k] = 1;

                }

                return _results;
            }

            data                = self._prepareObjectForMustache(o.categories);
            results.filter_cats = appendCountProperty(data, _countKey('cat'));

            data                 = self._prepareObjectForMustache(o.types);
            results.filter_types = appendCountProperty(data, _countKey('type'));

            results.count_all = itemsCount;

            // results.ch = '<div class="bf-checkbox-multi-state" data-current-state="active">\n    <input type="hidden"\n           class="bf-checkbox-status"\n           value="active">\n\n    <span data-state="none"></span>\n    <span data-state="active" class="bf-checkbox-active" style="display: inline-block;">\n        <span class="icon" aria-hidden="true"></span>\n    </span>\n</div>'

            return this.handleEvent('modal_content', results);
        },


        _updateSidebarCheckboxStatus: function (flag) {
            var self = this;

            $('.bssm-sidebar .name[data-id]', self.bsModal.$modal).each(function () {
                var $this         = $(this),
                    $checkbox     = $this.parent().find('.bf-checkbox-multi-state'),
                    currentStatus = $checkbox.data('current-state');

                // Todo: check inactivateCats

                if ($.inArray($this.data('id'), self.options.inactivateTypes) > -1) {

                    if (currentStatus !== 'none') {
                        $checkbox.trigger('click', [flag]);
                    }
                } else {
                    if (currentStatus !== 'active') {
                        $checkbox.trigger('click', [flag]);
                    }
                }
            });
        },

        /**
         * Filter all Items jquery object
         *
         * @param {callback}
         * @returns {jQuery}
         * @private
         */
        _filterItems: function (callback) {

            return this.$allItems.filter(callback);
        },
        /**
         * Handle modal events.
         * Fire Registered callback for event
         *
         * @param  {String} event
         * @param  {*} retVal return value
         * @returns {*} Return Fired Callback Results
         */
        handleEvent: function (event, retVal) {
            var args = Array.prototype.slice.call(arguments, 1);
            if (typeof this.options.events[event] === 'function')
                return this.options.events[event].apply(this, args);

            return retVal;
        },

        /**
         * Check is any function registered for event
         *
         * @param  {String} event
         */
        eventExists: function (event) {
            return typeof this.options.events[event] === 'function';
        },

        /**
         * Get cached jQuery element
         *
         * @param {Element}
         * @returns {jQuery}
         */
        $: function (el) {
            if (el.dataset.itemId) {

                var id = el.dataset.itemId;

                if (!( id in this.$itemsCache )) {

                    this.$itemsCache[el.dataset.itemId] = $(el, this.$modal);
                }

                return this.$itemsCache[el.dataset.itemId];
            }

            return $(el, this.$modal);
        },

        selectItem: function (itemId) {

            if (!( itemId in this.$itemsCache )) {

                this.$itemsCache[itemId] = $('.bssm-item[data-item-id="' + itemId + '"]', this.bsModal.$modal);
            }

            return this.$itemsCache[itemId];
        },

        /**
         * Show/ hide items element
         *
         * @param {Array}  elements array of elements
         * @param {Boolean} show
         * @private
         */

        _showHide: function (elements, show) {

            var event = show ? 'show_item' : 'hide_item';

            for (var i = 0; i < elements.length; i++) {

                if (elements[i]) {
                    this.handleEvent(event, elements[i]);
                }
            }
        },
        /**
         * Get Element Attirubute
         *
         * @param {Element} el
         * @param {String} attr
         * @returns {*}
         */
        get_attr: function (el, attr) {

            if (el.dataset) {
                return el.dataset[attr];
            }

            return this.$(el, this.bsModal.$modal).attr(attr);
        },

        /**
         * Convert Key-Value paired object to array
         *
         * @param obj
         *  {
         *   Key: value
         *  }
         *  to
         *     [
         *      {
         *          key:key,
         *          value:value
         *      }
         *     ]
         *
         * @private
         */
        _prepareObjectForMustache: function (obj) {
            var results = [];
            for (var k in obj) {

                results.push({
                    key: k,
                    value: obj[k]
                });
            }

            return results;
        },

        _cacheItems: function (items) {
            this.$itemsCache = {};

            var $item, id;
            for (var i = 0; i < items.length; i++) {
                $item = $(items[i]);

                id = $item.data('item-id');


                this.$itemsCache[id] = $item;
            }

        },
        _length: function (object) {
            if (!object) {
                return 0;
            }

            if (Object.keys) {
                return Object.keys(object).length;
            }

            var count = 0, i;

            for (i in object) {
                if (object.hasOwnProperty(i)) {
                    count++;
                }
            }

            return count;
        },


    };

    /**
     * Register bs_selector_modal jQuery function
     *
     * @param {Object} options Options
     * @param {Object} bsModalOptions override BsModal options
     *
     * @returns {BsSelectorModal} Selector modal object
     */
    $.bs_selector_modal = function (options, bsModalOptions) {

        return new BsSelectorModal(options, bsModalOptions);
    }
})(jQuery);
