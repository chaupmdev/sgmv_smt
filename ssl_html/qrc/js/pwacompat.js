(function () {
  function v(a, b) {
    a = "__pwacompat_" + a;
    void 0 !== b && (w[a] = b);
    return w[a]
  }

  function F() {
    var a = document.head.querySelector('link[rel="manifest"]'),
      b = a ? a.href : "";
    if (!b) throw 'can\'t find <link rel="manifest" href=".." />\'';
    var e = O([b, window.location]),
      g = v("manifest");
    if (g) try {
      var r = JSON.parse(g);
      G(r, e)
    } catch (t) {
      console.warn("PWACompat error", t)
    } else {
      var n = new XMLHttpRequest;
      n.open("GET", b);
      n.withCredentials = "use-credentials" === a.getAttribute("crossorigin");
      n.onload = function () {
        try {
          var t = JSON.parse(n.responseText);
          v("manifest", n.responseText);
          G(t, e)
        } catch (x) {
          console.warn("PWACompat error", x)
        }
      };
      n.send(null)
    }
  }

  function O(a) {
    for (var b = {}, e = 0; e < a.length; b = {
        c: b.c
      }, ++e) {
      b.c = a[e];
      try {
        return new URL("", b.c),
          function (g) {
            return function (r) {
              return (new URL(r || "", g.c)).toString()
            }
          }(b)
      } catch (g) {}
    }
    return function (g) {
      return g || ""
    }
  }

  function C(a, b) {
    a = document.createElement(a);
    for (var e in b) a.setAttribute(e, b[e]);
    document.head.appendChild(a);
    return a
  }

  function k(a, b) {
    b && (!0 === b && (b = "yes"), C("meta", {
      name: a,
      content: b
    }))
  }

  function G(a, b) {
    function e(f, c, l, h) {
      var d = window.devicePixelRatio,
        m = D({
          width: f * d,
          height: c * d
        });
      m.scale(d, d);
      m.fillStyle = y;
      m.fillRect(0, 0, f, c);
      m.translate(f / 2, (c - 32) / 2);
      h && (c = h.width / d, d = h.height / d, 128 < d && (c /= d / 128, d = 128), 48 <= c && 48 <= d && (m.drawImage(h, c / -2, d / -2, c, d), m.translate(0, d / 2 + 32)));
      m.fillStyle = P ? "white" : "black";
      m.font = "24px HelveticaNeue-CondensedBold";
      h = a.name || a.short_name || document.title;
      d = m.measureText(h).width;
      if (d < .8 * f) m.fillText(h, d / -2, 0);
      else
        for (h = h.split(/\s+/g), d = 1; d <= h.length; ++d) {
          c = h.slice(0, d).join(" ");
          var H = m.measureText(c).width;
          if (d === h.length || H > .6 * f) m.fillText(c, H / -2, 0), m.translate(0, 24 * 1.2), h.splice(0, d), d = 0
        }
      return function () {
        var I = m.canvas.toDataURL();
        g(l, I);
        return I
      }
    }

    function g(f, c) {
      var l = document.createElement("link");
      l.setAttribute("rel", "apple-touch-startup-image");
      l.setAttribute("media", "(orientation: " + f + ")");
      l.setAttribute("href", c);
      document.head.appendChild(l)
    }

    function r(f, c) {
      var l = window.screen,
        h = e(l.availWidth, l.availHeight, "portrait", f),
        d = e(l.availHeight, l.availWidth, "landscape", f);
      window.setTimeout(function () {
        u.p = h();
        window.setTimeout(function () {
          u.l = d();
          c()
        }, 10)
      }, 10)
    }

    function n(f) {
      function c() {
        --l || f()
      }
      var l = z.length + 1;
      c();
      z.forEach(function (h) {
        var d = new Image;
        d.crossOrigin = "anonymous";
        d.onerror = c;
        d.onload = function () {
          d.onload = null;
          h.href = J(d, y, !0);
          u.i[d.src] = h.href;
          c()
        };
        d.src = h.href
      })
    }

    function t() {
      v("iOS", JSON.stringify(u))
    }

    function x() {
      var f = z.shift();
      if (f) {
        var c = new Image;
        c.crossOrigin = "anonymous";
        c.onerror = function () {
          return void x()
        };
        c.onload = function () {
          c.onload = null;
          r(c, function () {
            var l = a.background_color && J(c, y);
            l ? (f.href = l, u.i[c.src] = l, n(t)) : t()
          })
        };
        c.src = f.href
      } else r(null, t)
    }
    var p = a.icons || [];
    p.sort(function (f, c) {
      return parseInt(c.sizes, 10) - parseInt(f.sizes, 10)
    });
    var z = p.map(function (f) {
        var c = {
          rel: "icon",
          href: b(f.src),
          sizes: f.sizes
        };
        C("link", c);
        if (A && !(120 > parseInt(f.sizes, 10))) return c.rel = "apple-touch-icon", C("link", c)
      }).filter(Boolean),
      q = document.head.querySelector('meta[name="viewport"]'),
      Q = !!(q && q.content || "").match(/\bviewport-fit\s*=\s*cover\b/),
      B = a.display;
    q = -1 !== R.indexOf(B);
    k("mobile-web-app-capable", q);
    S(a.theme_color || "black", Q);
    T && (k("application-name", a.short_name), k("msapplication-tooltip", a.description), k("msapplication-starturl", b(a.start_url || ".")), k("msapplication-navbutton-color", a.theme_color), (p = p[0]) && k("msapplication-TileImage", b(p.src)), k("msapplication-TileColor", a.background_color));
    document.head.querySelector('[name="theme-color"]') || k("theme-color", a.theme_color);
    if (A) {
      var y = a.background_color || "#f8f9fa",
        P = K(y);
      (B = U(a.related_applications)) && k("apple-itunes-app", "app-id=" + B);
      k("apple-mobile-web-app-capable", q);
      k("apple-mobile-web-app-title", a.short_name || a.name);
      if (q = v("iOS")) try {
        var E = JSON.parse(q);
        g("portrait", E.p);
        g("landscape", E.l);
        z.forEach(function (f) {
          var c = E.i[f.href];
          c && (f.href = c)
        });
        return
      } catch (f) {}
      var u = {
        i: {}
      };
      x()
    } else p = {
      por: "portrait",
      lan: "landscape"
    }[String(a.orientation || "").substr(0, 3)] || "", k("x5-orientation", p), k("screen-orientation", p), "fullscreen" === B ? (k("x5-fullscreen", "true"), k("full-screen", "yes")) : q && (k("x5-page-mode", "app"), k("browsermode", "application"))
  }

  function U(a) {
    var b;
    (a || []).filter(function (e) {
      return "itunes" === e.platform
    }).forEach(function (e) {
      e.id ? b = e.id : (e = e.url.match(/id(\d+)/)) && (b = e[1])
    });
    return b
  }

  function S(a, b) {
    if (A || V) {
      var e = K(a);
      if (A) k("apple-mobile-web-app-status-bar-style", b ? "black-translucent" : e ? "black" : "default");
      else {
        a: {
          try {
            var g = Windows.UI.ViewManagement.ApplicationView.getForCurrentView().titleBar;
            break a
          } catch (r) {}
          g = void 0
        }
        if (b = g) b.foregroundColor = L(e ? "black" : "white"),
        b.backgroundColor = L(a)
      }
    }
  }

  function L(a) {
    a = M(a);
    return {
      r: a[0],
      g: a[1],
      b: a[2],
      a: a[3]
    }
  }

  function M(a) {
    var b = D();
    b.fillStyle = a;
    b.fillRect(0, 0, 1, 1);
    return b.getImageData(0, 0, 1, 1).data || []
  }

  function K(a) {
    a = M(a).map(function (b) {
      b /= 255;
      return .03928 > b ? b / 12.92 : Math.pow((b + .055) / 1.055, 2.4)
    });
    return 3 < Math.abs(1.05 / (.2126 * a[0] + .7152 * a[1] + .0722 * a[2] + .05))
  }

  function J(a, b, e) {
    e = void 0 === e ? !1 : e;
    var g = D(a);
    g.drawImage(a, 0, 0);
    if (e || 255 !== g.getImageData(0, 0, 1, 1).data[3]) return g.globalCompositeOperation = "destination-over", g.fillStyle = b, g.fillRect(0, 0, a.width, a.height), g.canvas.toDataURL()
  }

  function D(a) {
    a = void 0 === a ? {
      width: 1,
      height: 1
    } : a;
    var b = a.height,
      e = document.createElement("canvas");
    e.width = a.width;
    e.height = b;
    return e.getContext("2d")
  }
  if ("onload" in XMLHttpRequest.prototype && !navigator.f) {
    var R = ["standalone", "fullscreen", "minimal-ui"],
      N = navigator.userAgent || "",
      A = navigator.vendor && -1 !== navigator.vendor.indexOf("Apple") && -1 !== N.indexOf("Mobile/"),
      T = !!N.match(/(MSIE |Edge\/|Trident\/)/),
      V = "undefined" !== typeof Windows;
    try {
      var w = window.sessionStorage
    } catch (a) {}
    w = w || {};
    "complete" === document.readyState ? F() : window.addEventListener("load", F)
  }
})();