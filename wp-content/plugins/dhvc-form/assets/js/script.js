/*! Smooth Scroll - v1.4.9 - 2013-01-21
 * https://github.com/kswedberg/jquery-smooth-scroll
 * Copyright (c) 2013 Karl Swedberg; Licensed MIT
 *
 * Modified by ThemeCatcher to be compatible with jQuery Tools Scrollable
 * The following code was removed from the original file:
 * 
 *   scrollable: function(dir) {
 *	    var scrl = getScrollable.call(this, {dir: dir});
 *	    return this.pushStack(scrl);
 *  	},
 * 
 */
;
(function(b) {
    function m(b) {
        return b.replace(/(:|\.)/g, "\\$1")
    }
    b.fn.extend({
        firstScrollable: function(e) {
            var c = [],
                a = !1,
                f = e && "left" == e ? "scrollLeft" : "scrollTop";
            this.each(function() {
                if (!(this == document || this == window)) {
                    var d = b(this);
                    0 < d[f]() ? c.push(this) : (d[f](1), (a = 0 < d[f]()) && c.push(this), d[f](0))
                }
            });
            c.length || this.each(function() {
                "BODY" === this.nodeName && (c = [this])
            });
            1 < c.length && (c = [c[0]]);
            return this.pushStack(c)
        },
        smoothScroll: function(e) {
            e = e || {};
            var c = b.extend({}, b.fn.smoothScroll.defaults, e),
                a = b.smoothScroll.filterPath(location.pathname);
            this.unbind("click.smoothscroll").bind("click.smoothscroll", function(e) {
                var d = b(this),
                    g = c.exclude,
                    j = c.excludeWithin,
                    h = 0,
                    k = 0,
                    l = !0,
                    n = {},
                    q = location.hostname === this.hostname || !this.hostname,
                    r = c.scrollTarget || (b.smoothScroll.filterPath(this.pathname) || a) === a,
                    p = m(this.hash);
                if (!c.scrollTarget && (!q || !r || !p)) l = !1;
                else {
                    for (; l && h < g.length;) d.is(m(g[h++])) && (l = !1);
                    for (; l && k < j.length;) d.closest(j[k++]).length && (l = !1)
                }
                l && (e.preventDefault(), b.extend(n, c, {
                    scrollTarget: c.scrollTarget || p,
                    link: this
                }), b.smoothScroll(n))
            });
            return this
        }
    });
    b.smoothScroll = function(e, c) {
        var a, f, d, g;
        g = 0;
        var j = "offset",
            h = "scrollTop",
            k = {};
        d = {};
        "number" === typeof e ? (a = b.fn.smoothScroll.defaults, d = e) : (a = b.extend({}, b.fn.smoothScroll.defaults, e || {}), a.scrollElement && (j = "position", "static" == a.scrollElement.css("position") && a.scrollElement.css("position", "relative")));
        a = b.extend({
            link: null
        }, a);
        h = "left" == a.direction ? "scrollLeft" : h;
        a.scrollElement ? (f = a.scrollElement, g = f[h]()) : f = b("html, body").firstScrollable();
        a.beforeScroll.call(f, a);
        d = "number" === typeof e ? e : c || b(a.scrollTarget)[j]() && b(a.scrollTarget)[j]()[a.direction] || 0;
        k[h] = d + g + a.offset;
        g = a.speed;
        "auto" === g && (g = k[h] || f.scrollTop(), g /= a.autoCoefficent);
        d = {
            duration: g,
            easing: a.easing,
            complete: function() {
                a.afterScroll.call(a.link, a)
            }
        };
        a.step && (d.step = a.step);
        f.length ? f.stop().animate(k, d) : a.afterScroll.call(a.link, a)
    };
    b.smoothScroll.version = "1.4.9";
    b.smoothScroll.filterPath = function(b) {
        return b.replace(/^\//, "").replace(/(index|default).[a-zA-Z]{3,4}$/, "").replace(/\/$/, "")
    };
    b.fn.smoothScroll.defaults = {
        exclude: [],
        excludeWithin: [],
        offset: 0,
        direction: "top",
        scrollElement: null,
        scrollTarget: null,
        beforeScroll: function() {},
        afterScroll: function() {},
        easing: "swing",
        speed: 400,
        autoCoefficent: 2
    }
})(jQuery);
/*
 * jQuery Form Plugin; v20130616
 * http://jquery.malsup.com/form/
 * Copyright (c) 2013 M. Alsup; Dual licensed: MIT/GPL
 * https://github.com/malsup/form#copyright-and-license
 */
;
(function(e) {
    "use strict";

    function t(t) {
        var r = t.data;
        t.isDefaultPrevented() || (t.preventDefault(), e(this).ajaxSubmit(r))
    }

    function r(t) {
        var r = t.target,
            a = e(r);
        if (!a.is("[type=submit],[type=image]")) {
            var n = a.closest("[type=submit]");
            if (0 === n.length) return;
            r = n[0]
        }
        var i = this;
        if (i.clk = r, "image" == r.type)
            if (void 0 !== t.offsetX) i.clk_x = t.offsetX, i.clk_y = t.offsetY;
            else if ("function" == typeof e.fn.offset) {
            var o = a.offset();
            i.clk_x = t.pageX - o.left, i.clk_y = t.pageY - o.top
        } else i.clk_x = t.pageX - r.offsetLeft, i.clk_y = t.pageY - r.offsetTop;
        setTimeout(function() {
            i.clk = i.clk_x = i.clk_y = null
        }, 100)
    }

    function a() {
        if (e.fn.ajaxSubmit.debug) {
            var t = "[jquery.form] " + Array.prototype.join.call(arguments, "");
            window.console && window.console.log ? window.console.log(t) : window.opera && window.opera.postError && window.opera.postError(t)
        }
    }
    var n = {};
    n.fileapi = void 0 !== e("<input type='file'/>").get(0).files, n.formdata = void 0 !== window.FormData;
    var i = !!e.fn.prop;
    e.fn.attr2 = function() {
        if (!i) return this.attr.apply(this, arguments);
        var e = this.prop.apply(this, arguments);
        return e && e.jquery || "string" == typeof e ? e : this.attr.apply(this, arguments)
    }, e.fn.ajaxSubmit = function(t) {
        function r(r) {
            var a, n, i = e.param(r, t.traditional).split("&"),
                o = i.length,
                s = [];
            for (a = 0; o > a; a++) i[a] = i[a].replace(/\+/g, " "), n = i[a].split("="), s.push([decodeURIComponent(n[0]), decodeURIComponent(n[1])]);
            return s
        }

        function o(a) {
            for (var n = new FormData, i = 0; a.length > i; i++) n.append(a[i].name, a[i].value);
            if (t.extraData) {
                var o = r(t.extraData);
                for (i = 0; o.length > i; i++) o[i] && n.append(o[i][0], o[i][1])
            }
            t.data = null;
            var s = e.extend(!0, {}, e.ajaxSettings, t, {
                contentType: !1,
                processData: !1,
                cache: !1,
                type: u || "POST"
            });
            t.uploadProgress && (s.xhr = function() {
                var r = e.ajaxSettings.xhr();
                return r.upload && r.upload.addEventListener("progress", function(e) {
                    var r = 0,
                        a = e.loaded || e.position,
                        n = e.total;
                    e.lengthComputable && (r = Math.ceil(100 * (a / n))), t.uploadProgress(e, a, n, r)
                }, !1), r
            }), s.data = null;
            var l = s.beforeSend;
            return s.beforeSend = function(e, t) {
                t.data = n, l && l.call(this, e, t)
            }, e.ajax(s)
        }

        function s(r) {
            function n(e) {
                var t = null;
                try {
                    e.contentWindow && (t = e.contentWindow.document)
                } catch (r) {
                    a("cannot get iframe.contentWindow document: " + r)
                }
                if (t) return t;
                try {
                    t = e.contentDocument ? e.contentDocument : e.document
                } catch (r) {
                    a("cannot get iframe.contentDocument: " + r), t = e.document
                }
                return t
            }

            function o() {
                function t() {
                    try {
                        var e = n(g).readyState;
                        a("state = " + e), e && "uninitialized" == e.toLowerCase() && setTimeout(t, 50)
                    } catch (r) {
                        a("Server abort: ", r, " (", r.name, ")"), s(D), j && clearTimeout(j), j = void 0
                    }
                }
                var r = f.attr2("target"),
                    i = f.attr2("action");
                w.setAttribute("target", d), u || w.setAttribute("method", "POST"), i != m.url && w.setAttribute("action", m.url), m.skipEncodingOverride || u && !/post/i.test(u) || f.attr({
                    encoding: "multipart/form-data",
                    enctype: "multipart/form-data"
                }), m.timeout && (j = setTimeout(function() {
                    T = !0, s(k)
                }, m.timeout));
                var o = [];
                try {
                    if (m.extraData)
                        for (var l in m.extraData) m.extraData.hasOwnProperty(l) && (e.isPlainObject(m.extraData[l]) && m.extraData[l].hasOwnProperty("name") && m.extraData[l].hasOwnProperty("value") ? o.push(e('<input type="hidden" name="' + m.extraData[l].name + '">').val(m.extraData[l].value).appendTo(w)[0]) : o.push(e('<input type="hidden" name="' + l + '">').val(m.extraData[l]).appendTo(w)[0]));
                    m.iframeTarget || (v.appendTo("body"), g.attachEvent ? g.attachEvent("onload", s) : g.addEventListener("load", s, !1)), setTimeout(t, 15);
                    try {
                        w.submit()
                    } catch (c) {
                        var p = document.createElement("form").submit;
                        p.apply(w)
                    }
                } finally {
                    w.setAttribute("action", i), r ? w.setAttribute("target", r) : f.removeAttr("target"), e(o).remove()
                }
            }

            function s(t) {
                if (!x.aborted && !F) {
                    if (M = n(g), M || (a("cannot access response document"), t = D), t === k && x) return x.abort("timeout"), S.reject(x, "timeout"), void 0;
                    if (t == D && x) return x.abort("server abort"), S.reject(x, "error", "server abort"), void 0;
                    if (M && M.location.href != m.iframeSrc || T) {
                        g.detachEvent ? g.detachEvent("onload", s) : g.removeEventListener("load", s, !1);
                        var r, i = "success";
                        try {
                            if (T) throw "timeout";
                            var o = "xml" == m.dataType || M.XMLDocument || e.isXMLDoc(M);
                            if (a("isXml=" + o), !o && window.opera && (null === M.body || !M.body.innerHTML) && --O) return a("requeing onLoad callback, DOM not available"), setTimeout(s, 250), void 0;
                            var u = M.body ? M.body : M.documentElement;
                            x.responseText = u ? u.innerHTML : null, x.responseXML = M.XMLDocument ? M.XMLDocument : M, o && (m.dataType = "xml"), x.getResponseHeader = function(e) {
                                var t = {
                                    "content-type": m.dataType
                                };
                                return t[e]
                            }, u && (x.status = Number(u.getAttribute("status")) || x.status, x.statusText = u.getAttribute("statusText") || x.statusText);
                            var l = (m.dataType || "").toLowerCase(),
                                c = /(json|script|text)/.test(l);
                            if (c || m.textarea) {
                                var f = M.getElementsByTagName("textarea")[0];
                                if (f) x.responseText = f.value, x.status = Number(f.getAttribute("status")) || x.status, x.statusText = f.getAttribute("statusText") || x.statusText;
                                else if (c) {
                                    var d = M.getElementsByTagName("pre")[0],
                                        h = M.getElementsByTagName("body")[0];
                                    d ? x.responseText = d.textContent ? d.textContent : d.innerText : h && (x.responseText = h.textContent ? h.textContent : h.innerText)
                                }
                            } else "xml" == l && !x.responseXML && x.responseText && (x.responseXML = X(x.responseText));
                            try {
                                L = _(x, l, m)
                            } catch (b) {
                                i = "parsererror", x.error = r = b || i
                            }
                        } catch (b) {
                            a("error caught: ", b), i = "error", x.error = r = b || i
                        }
                        x.aborted && (a("upload aborted"), i = null), x.status && (i = x.status >= 200 && 300 > x.status || 304 === x.status ? "success" : "error"), "success" === i ? (m.success && m.success.call(m.context, L, "success", x), S.resolve(x.responseText, "success", x), p && e.event.trigger("ajaxSuccess", [x, m])) : i && (void 0 === r && (r = x.statusText), m.error && m.error.call(m.context, x, i, r), S.reject(x, "error", r), p && e.event.trigger("ajaxError", [x, m, r])), p && e.event.trigger("ajaxComplete", [x, m]), p && !--e.active && e.event.trigger("ajaxStop"), m.complete && m.complete.call(m.context, x, i), F = !0, m.timeout && clearTimeout(j), setTimeout(function() {
                            m.iframeTarget || v.remove(), x.responseXML = null
                        }, 100)
                    }
                }
            }
            var l, c, m, p, d, v, g, x, b, y, T, j, w = f[0],
                S = e.Deferred();
            if (r)
                for (c = 0; h.length > c; c++) l = e(h[c]), i ? l.prop("disabled", !1) : l.removeAttr("disabled");
            if (m = e.extend(!0, {}, e.ajaxSettings, t), m.context = m.context || m, d = "jqFormIO" + (new Date).getTime(), m.iframeTarget ? (v = e(m.iframeTarget), y = v.attr2("name"), y ? d = y : v.attr2("name", d)) : (v = e('<iframe name="' + d + '" src="' + m.iframeSrc + '" />'), v.css({
                    position: "absolute",
                    top: "-1000px",
                    left: "-1000px"
                })), g = v[0], x = {
                    aborted: 0,
                    responseText: null,
                    responseXML: null,
                    status: 0,
                    statusText: "n/a",
                    getAllResponseHeaders: function() {},
                    getResponseHeader: function() {},
                    setRequestHeader: function() {},
                    abort: function(t) {
                        var r = "timeout" === t ? "timeout" : "aborted";
                        a("aborting upload... " + r), this.aborted = 1;
                        try {
                            g.contentWindow.document.execCommand && g.contentWindow.document.execCommand("Stop")
                        } catch (n) {}
                        v.attr("src", m.iframeSrc), x.error = r, m.error && m.error.call(m.context, x, r, t), p && e.event.trigger("ajaxError", [x, m, r]), m.complete && m.complete.call(m.context, x, r)
                    }
                }, p = m.global, p && 0 === e.active++ && e.event.trigger("ajaxStart"), p && e.event.trigger("ajaxSend", [x, m]), m.beforeSend && m.beforeSend.call(m.context, x, m) === !1) return m.global && e.active--, S.reject(), S;
            if (x.aborted) return S.reject(), S;
            b = w.clk, b && (y = b.name, y && !b.disabled && (m.extraData = m.extraData || {}, m.extraData[y] = b.value, "image" == b.type && (m.extraData[y + ".x"] = w.clk_x, m.extraData[y + ".y"] = w.clk_y)));
            var k = 1,
                D = 2,
                A = e("meta[name=csrf-token]").attr("content"),
                E = e("meta[name=csrf-param]").attr("content");
            E && A && (m.extraData = m.extraData || {}, m.extraData[E] = A), m.forceSync ? o() : setTimeout(o, 10);
            var L, M, F, O = 50,
                X = e.parseXML || function(e, t) {
                    return window.ActiveXObject ? (t = new ActiveXObject("Microsoft.XMLDOM"), t.async = "false", t.loadXML(e)) : t = (new DOMParser).parseFromString(e, "text/xml"), t && t.documentElement && "parsererror" != t.documentElement.nodeName ? t : null
                },
                C = e.parseJSON || function(e) {
                    return window.eval("(" + e + ")")
                },
                _ = function(t, r, a) {
                    var n = t.getResponseHeader("content-type") || "",
                        i = "xml" === r || !r && n.indexOf("xml") >= 0,
                        o = i ? t.responseXML : t.responseText;
                    return i && "parsererror" === o.documentElement.nodeName && e.error && e.error("parsererror"), a && a.dataFilter && (o = a.dataFilter(o, r)), "string" == typeof o && ("json" === r || !r && n.indexOf("json") >= 0 ? o = C(o) : ("script" === r || !r && n.indexOf("javascript") >= 0) && e.globalEval(o)), o
                };
            return S
        }
        if (!this.length) return a("ajaxSubmit: skipping submit process - no element selected"), this;
        var u, l, c, f = this;
        "function" == typeof t && (t = {
            success: t
        }), u = t.type || this.attr2("method"), l = t.url || this.attr2("action"), c = "string" == typeof l ? e.trim(l) : "", c = c || window.location.href || "", c && (c = (c.match(/^([^#]+)/) || [])[1]), t = e.extend(!0, {
            url: c,
            success: e.ajaxSettings.success,
            type: u || "GET",
            iframeSrc: /^https/i.test(window.location.href || "") ? "javascript:false" : "about:blank"
        }, t);
        var m = {};
        if (this.trigger("form-pre-serialize", [this, t, m]), m.veto) return a("ajaxSubmit: submit vetoed via form-pre-serialize trigger"), this;
        if (t.beforeSerialize && t.beforeSerialize(this, t) === !1) return a("ajaxSubmit: submit aborted via beforeSerialize callback"), this;
        var p = t.traditional;
        void 0 === p && (p = e.ajaxSettings.traditional);
        var d, h = [],
            v = this.formToArray(t.semantic, h);
        if (t.data && (t.extraData = t.data, d = e.param(t.data, p)), t.beforeSubmit && t.beforeSubmit(v, this, t) === !1) return a("ajaxSubmit: submit aborted via beforeSubmit callback"), this;
        if (this.trigger("form-submit-validate", [v, this, t, m]), m.veto) return a("ajaxSubmit: submit vetoed via form-submit-validate trigger"), this;
        var g = e.param(v, p);
        d && (g = g ? g + "&" + d : d), "GET" == t.type.toUpperCase() ? (t.url += (t.url.indexOf("?") >= 0 ? "&" : "?") + g, t.data = null) : t.data = g;
        var x = [];
        if (t.resetForm && x.push(function() {
                f.resetForm()
            }), t.clearForm && x.push(function() {
                f.clearForm(t.includeHidden)
            }), !t.dataType && t.target) {
            var b = t.success || function() {};
            x.push(function(r) {
                var a = t.replaceTarget ? "replaceWith" : "html";
                e(t.target)[a](r).each(b, arguments)
            })
        } else t.success && x.push(t.success);
        if (t.success = function(e, r, a) {
                for (var n = t.context || this, i = 0, o = x.length; o > i; i++) x[i].apply(n, [e, r, a || f, f])
            }, t.error) {
            var y = t.error;
            t.error = function(e, r, a) {
                var n = t.context || this;
                y.apply(n, [e, r, a, f])
            }
        }
        if (t.complete) {
            var T = t.complete;
            t.complete = function(e, r) {
                var a = t.context || this;
                T.apply(a, [e, r, f])
            }
        }
        var j = e('input[type=file]:enabled[value!=""]', this),
            w = j.length > 0,
            S = "multipart/form-data",
            k = f.attr("enctype") == S || f.attr("encoding") == S,
            D = n.fileapi && n.formdata;
        a("fileAPI :" + D);
        var A, E = (w || k) && !D;
        t.iframe !== !1 && (t.iframe || E) ? t.closeKeepAlive ? e.get(t.closeKeepAlive, function() {
            A = s(v)
        }) : A = s(v) : A = (w || k) && D ? o(v) : e.ajax(t), f.removeData("jqxhr").data("jqxhr", A);
        for (var L = 0; h.length > L; L++) h[L] = null;
        return this.trigger("form-submit-notify", [this, t]), this
    }, e.fn.ajaxForm = function(n) {
        if (n = n || {}, n.delegation = n.delegation && e.isFunction(e.fn.on), !n.delegation && 0 === this.length) {
            var i = {
                s: this.selector,
                c: this.context
            };
            return !e.isReady && i.s ? (a("DOM not ready, queuing ajaxForm"), e(function() {
                e(i.s, i.c).ajaxForm(n)
            }), this) : (a("terminating; zero elements found by selector" + (e.isReady ? "" : " (DOM not ready)")), this)
        }
        return n.delegation ? (e(document).off("submit.form-plugin", this.selector, t).off("click.form-plugin", this.selector, r).on("submit.form-plugin", this.selector, n, t).on("click.form-plugin", this.selector, n, r), this) : this.ajaxFormUnbind().bind("submit.form-plugin", n, t).bind("click.form-plugin", n, r)
    }, e.fn.ajaxFormUnbind = function() {
        return this.unbind("submit.form-plugin click.form-plugin")
    }, e.fn.formToArray = function(t, r) {
        var a = [];
        if (0 === this.length) return a;
        var i = this[0],
            o = t ? i.getElementsByTagName("*") : i.elements;
        if (!o) return a;
        var s, u, l, c, f, m, p;
        for (s = 0, m = o.length; m > s; s++)
            if (f = o[s], l = f.name, l && !f.disabled)
                if (t && i.clk && "image" == f.type) i.clk == f && (a.push({
                    name: l,
                    value: e(f).val(),
                    type: f.type
                }), a.push({
                    name: l + ".x",
                    value: i.clk_x
                }, {
                    name: l + ".y",
                    value: i.clk_y
                }));
                else if (c = e.fieldValue(f, !0), c && c.constructor == Array)
            for (r && r.push(f), u = 0, p = c.length; p > u; u++) a.push({
                name: l,
                value: c[u]
            });
        else if (n.fileapi && "file" == f.type) {
            r && r.push(f);
            var d = f.files;
            if (d.length)
                for (u = 0; d.length > u; u++) a.push({
                    name: l,
                    value: d[u],
                    type: f.type
                });
            else a.push({
                name: l,
                value: "",
                type: f.type
            })
        } else null !== c && c !== void 0 && (r && r.push(f), a.push({
            name: l,
            value: c,
            type: f.type,
            required: f.required
        }));
        if (!t && i.clk) {
            var h = e(i.clk),
                v = h[0];
            l = v.name, l && !v.disabled && "image" == v.type && (a.push({
                name: l,
                value: h.val()
            }), a.push({
                name: l + ".x",
                value: i.clk_x
            }, {
                name: l + ".y",
                value: i.clk_y
            }))
        }
        return a
    }, e.fn.formSerialize = function(t) {
        return e.param(this.formToArray(t))
    }, e.fn.fieldSerialize = function(t) {
        var r = [];
        return this.each(function() {
            var a = this.name;
            if (a) {
                var n = e.fieldValue(this, t);
                if (n && n.constructor == Array)
                    for (var i = 0, o = n.length; o > i; i++) r.push({
                        name: a,
                        value: n[i]
                    });
                else null !== n && n !== void 0 && r.push({
                    name: this.name,
                    value: n
                })
            }
        }), e.param(r)
    }, e.fn.fieldValue = function(t) {
        for (var r = [], a = 0, n = this.length; n > a; a++) {
            var i = this[a],
                o = e.fieldValue(i, t);
            null === o || void 0 === o || o.constructor == Array && !o.length || (o.constructor == Array ? e.merge(r, o) : r.push(o))
        }
        return r
    }, e.fieldValue = function(t, r) {
        var a = t.name,
            n = t.type,
            i = t.tagName.toLowerCase();
        if (void 0 === r && (r = !0), r && (!a || t.disabled || "reset" == n || "button" == n || ("checkbox" == n || "radio" == n) && !t.checked || ("submit" == n || "image" == n) && t.form && t.form.clk != t || "select" == i && -1 == t.selectedIndex)) return null;
        if ("select" == i) {
            var o = t.selectedIndex;
            if (0 > o) return null;
            for (var s = [], u = t.options, l = "select-one" == n, c = l ? o + 1 : u.length, f = l ? o : 0; c > f; f++) {
                var m = u[f];
                if (m.selected) {
                    var p = m.value;
                    if (p || (p = m.attributes && m.attributes.value && !m.attributes.value.specified ? m.text : m.value), l) return p;
                    s.push(p)
                }
            }
            return s
        }
        return e(t).val()
    }, e.fn.clearForm = function(t) {
        return this.each(function() {
            e("input,select,textarea", this).clearFields(t)
        })
    }, e.fn.clearFields = e.fn.clearInputs = function(t) {
        var r = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;
        return this.each(function() {
            var a = this.type,
                n = this.tagName.toLowerCase();
            r.test(a) || "textarea" == n ? this.value = "" : "checkbox" == a || "radio" == a ? this.checked = !1 : "select" == n ? this.selectedIndex = -1 : "file" == a ? /MSIE/.test(navigator.userAgent) ? e(this).replaceWith(e(this).clone(!0)) : e(this).val("") : t && (t === !0 && /hidden/.test(a) || "string" == typeof t && e(this).is(t)) && (this.value = "")
        })
    }, e.fn.resetForm = function() {
        return this.each(function() {
            ("function" == typeof this.reset || "object" == typeof this.reset && !this.reset.nodeType) && this.reset()
        })
    }, e.fn.enable = function(e) {
        return void 0 === e && (e = !0), this.each(function() {
            this.disabled = !e
        })
    }, e.fn.selected = function(t) {
        return void 0 === t && (t = !0), this.each(function() {
            var r = this.type;
            if ("checkbox" == r || "radio" == r) this.checked = t;
            else if ("option" == this.tagName.toLowerCase()) {
                var a = e(this).parent("select");
                t && a[0] && "select-one" == a[0].type && a.find("option").selected(!1), this.selected = t
            }
        })
    }, e.fn.ajaxSubmit.debug = !1
})(jQuery);
/* ========================================================================
 * Bootstrap: tooltip.js v3.2.0
 * http://getbootstrap.com/javascript/#tooltip
 * Inspired by the original jQuery.tipsy by Jason Frame
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

+

function(b) {
    var c = function(f, e) {
        this.type = this.options = this.enabled = this.timeout = this.hoverState = this.$element = null;
        this.init("tooltip", f, e)
    };
    c.VERSION = "3.2.0";
    c.DEFAULTS = {
        animation: true,
        placement: "top",
        selector: false,
        template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: "hover focus",
        title: "",
        delay: 0,
        html: false,
        container: false,
        viewport: {
            selector: "body",
            padding: 0
        }
    };
    c.prototype.init = function(j, f, h) {
        this.enabled = true;
        this.type = j;
        this.$element = b(f);
        this.options = this.getOptions(h);
        this.$viewport = this.options.viewport && b(this.options.viewport.selector || this.options.viewport);
        var g = this.options.trigger.split(" ");
        for (var k = g.length; k--;) {
            var l = g[k];
            if (l == "click") {
                this.$element.on("click." + this.type, this.options.selector, b.proxy(this.toggle, this))
            } else {
                if (l != "manual") {
                    var m = l == "hover" ? "mouseenter" : "focusin";
                    var e = l == "hover" ? "mouseleave" : "focusout";
                    this.$element.on(m + "." + this.type, this.options.selector, b.proxy(this.enter, this));
                    this.$element.on(e + "." + this.type, this.options.selector, b.proxy(this.leave, this))
                }
            }
        }
        this.options.selector ? (this._options = b.extend({}, this.options, {
            trigger: "manual",
            selector: ""
        })) : this.fixTitle()
    };
    c.prototype.getDefaults = function() {
        return c.DEFAULTS
    };
    c.prototype.getOptions = function(e) {
        e = b.extend({}, this.getDefaults(), this.$element.data(), e);
        if (e.delay && typeof e.delay == "number") {
            e.delay = {
                show: e.delay,
                hide: e.delay
            }
        }
        return e
    };
    c.prototype.getDelegateOptions = function() {
        var e = {};
        var f = this.getDefaults();
        this._options && b.each(this._options, function(h, g) {
            if (f[h] != g) {
                e[h] = g
            }
        });
        return e
    };
    c.prototype.enter = function(f) {
        var e = f instanceof this.constructor ? f : b(f.currentTarget).data("bs." + this.type);
        if (!e) {
            e = new this.constructor(f.currentTarget, this.getDelegateOptions());
            b(f.currentTarget).data("bs." + this.type, e)
        }
        clearTimeout(e.timeout);
        e.hoverState = "in";
        if (!e.options.delay || !e.options.delay.show) {
            return e.show()
        }
        e.timeout = setTimeout(function() {
            if (e.hoverState == "in") {
                e.show()
            }
        }, e.options.delay.show)
    };
    c.prototype.leave = function(f) {
        var e = f instanceof this.constructor ? f : b(f.currentTarget).data("bs." + this.type);
        if (!e) {
            e = new this.constructor(f.currentTarget, this.getDelegateOptions());
            b(f.currentTarget).data("bs." + this.type, e)
        }
        clearTimeout(e.timeout);
        e.hoverState = "out";
        if (!e.options.delay || !e.options.delay.hide) {
            return e.hide()
        }
        e.timeout = setTimeout(function() {
            if (e.hoverState == "out") {
                e.hide()
            }
        }, e.options.delay.hide)
    };
    c.prototype.show = function() {
        var h = b.Event("show.bs." + this.type);
        if (this.hasContent() && this.enabled) {
            this.$element.trigger(h);
            var k = b.contains(document.documentElement, this.$element[0]);
            if (h.isDefaultPrevented() || !k) {
                return
            }
            var u = this;
            var s = this.tip();
            var q = this.getUID(this.type);
            this.setContent();
            s.attr("id", q);
            this.$element.attr("aria-describedby", q);
            if (this.options.animation) {
                s.addClass("fade")
            }
            var o = typeof this.options.placement == "function" ? this.options.placement.call(this, s[0], this.$element[0]) : this.options.placement;
            var r = /\s?auto?\s?/i;
            var p = r.test(o);
            if (p) {
                o = o.replace(r, "") || "top"
            }
            s.detach().css({
                top: 0,
                left: 0,
                display: "block"
            }).addClass(o).data("bs." + this.type, this);
            this.options.container ? s.appendTo(this.options.container) : s.insertAfter(this.$element);
            var l = this.getPosition();
            var i = s[0].offsetWidth;
            var m = s[0].offsetHeight;
            if (p) {
                var t = o;
                var f = this.$element.parent();
                var j = this.getPosition(f);
                o = o == "bottom" && l.top + l.height + m - j.scroll > j.height ? "top" : o == "top" && l.top - j.scroll - m < 0 ? "bottom" : o == "right" && l.right + i > j.width ? "left" : o == "left" && l.left - i < j.left ? "right" : o;
                s.removeClass(t).addClass(o)
            }
            var g = this.getCalculatedOffset(o, l, i, m);
            this.applyPlacement(g, o);
            var n = function() {
                u.$element.trigger("shown.bs." + u.type);
                u.hoverState = null
            };
            b.support.transition && this.$tip.hasClass("fade") ? s.one("bsTransitionEnd", n).emulateTransitionEnd(150) : n()
        }
    };
    c.prototype.applyPlacement = function(k, p) {
        var o = this.tip();
        var f = o[0].offsetWidth;
        var g = o[0].offsetHeight;
        var e = parseInt(o.css("margin-top"), 10);
        var h = parseInt(o.css("margin-left"), 10);
        if (isNaN(e)) {
            e = 0
        }
        if (isNaN(h)) {
            h = 0
        }
        k.top = k.top + e;
        k.left = k.left + h;
        b.offset.setOffset(o[0], b.extend({
            using: function(r) {
                o.css({
                    top: Math.round(r.top),
                    left: Math.round(r.left)
                })
            }
        }, k), 0);
        o.addClass("in");
        var q = o[0].offsetWidth;
        var l = o[0].offsetHeight;
        if (p == "top" && l != g) {
            k.top = k.top + g - l
        }
        var i = this.getViewportAdjustedDelta(p, k, q, l);
        if (i.left) {
            k.left += i.left
        } else {
            k.top += i.top
        }
        var j = i.left ? i.left * 2 - f + q : i.top * 2 - g + l;
        var n = i.left ? "left" : "top";
        var m = i.left ? "offsetWidth" : "offsetHeight";
        o.offset(k);
        this.replaceArrow(j, o[0][m], n)
    };
    c.prototype.replaceArrow = function(g, f, e) {
        this.arrow().css(e, g ? (50 * (1 - g / f) + "%") : "")
    };
    c.prototype.setContent = function() {
        var f = this.tip();
        var e = this.getTitle();
        f.find(".tooltip-inner")[this.options.html ? "html" : "text"](e);
        f.removeClass("fade in top bottom left right")
    };
    c.prototype.hide = function() {
        var h = this;
        var f = this.tip();
        var g = b.Event("hide.bs." + this.type);
        this.$element.removeAttr("aria-describedby");

        function i() {
            if (h.hoverState != "in") {
                f.detach()
            }
            h.$element.trigger("hidden.bs." + h.type)
        }
        this.$element.trigger(g);
        if (g.isDefaultPrevented()) {
            return
        }
        f.removeClass("in");
        b.support.transition && this.$tip.hasClass("fade") ? f.one("bsTransitionEnd", i).emulateTransitionEnd(150) : i();
        this.hoverState = null;
        return this
    };
    c.prototype.fixTitle = function() {
        var e = this.$element;
        if (e.attr("title") || typeof(e.attr("data-original-title")) != "string") {
            e.attr("data-original-title", e.attr("title") || "").attr("title", "")
        }
    };
    c.prototype.hasContent = function() {
        return this.getTitle()
    };
    c.prototype.getPosition = function(f) {
        f = f || this.$element;
        var h = f[0];
        var k = h.tagName == "BODY";
        var i = window.SVGElement && h instanceof window.SVGElement;
        var j = h.getBoundingClientRect ? h.getBoundingClientRect() : null;
        var l = k ? {
            top: 0,
            left: 0
        } : f.offset();
        var e = {
            scroll: k ? document.documentElement.scrollTop || document.body.scrollTop : f.scrollTop()
        };
        var g = i ? {} : {
            width: k ? b(window).width() : f.outerWidth(),
            height: k ? b(window).height() : f.outerHeight()
        };
        return b.extend({}, j, e, g, l)
    };
    c.prototype.getCalculatedOffset = function(e, h, f, g) {
        return e == "bottom" ? {
            top: h.top + h.height,
            left: h.left + h.width / 2 - f / 2
        } : e == "top" ? {
            top: h.top - g,
            left: h.left + h.width / 2 - f / 2
        } : e == "left" ? {
            top: h.top + h.height / 2 - g / 2,
            left: h.left - f
        } : {
            top: h.top + h.height / 2 - g / 2,
            left: h.left + h.width
        }
    };
    c.prototype.getViewportAdjustedDelta = function(h, n, g, l) {
        var i = {
            top: 0,
            left: 0
        };
        if (!this.$viewport) {
            return i
        }
        var e = this.options.viewport && this.options.viewport.padding || 0;
        var m = this.getPosition(this.$viewport);
        if (/right|left/.test(h)) {
            var f = n.top - e - m.scroll;
            var o = n.top + e - m.scroll + l;
            if (f < m.top) {
                i.top = m.top - f
            } else {
                if (o > m.top + m.height) {
                    i.top = m.top + m.height - o
                }
            }
        } else {
            var k = n.left - e;
            var j = n.left + e + g;
            if (k < m.left) {
                i.left = m.left - k
            } else {
                if (j > m.width) {
                    i.left = m.left + m.width - j
                }
            }
        }
        return i
    };
    c.prototype.getTitle = function() {
        var e;
        var f = this.$element;
        var g = this.options;
        e = f.attr("data-original-title") || (typeof g.title == "function" ? g.title.call(f[0]) : g.title);
        return e
    };
    c.prototype.getUID = function(e) {
        do {
            e += ~~(Math.random() * 1000000)
        } while (document.getElementById(e));
        return e
    };
    c.prototype.tip = function() {
        return (this.$tip = this.$tip || b(this.options.template))
    };
    c.prototype.arrow = function() {
        return (this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow"))
    };
    c.prototype.validate = function() {
        if (!this.$element[0].parentNode) {
            this.hide();
            this.$element = null;
            this.options = null
        }
    };
    c.prototype.enable = function() {
        this.enabled = true
    };
    c.prototype.disable = function() {
        this.enabled = false
    };
    c.prototype.toggleEnabled = function() {
        this.enabled = !this.enabled
    };
    c.prototype.toggle = function(f) {
        var g = this;
        if (f) {
            g = b(f.currentTarget).data("bs." + this.type);
            if (!g) {
                g = new this.constructor(f.currentTarget, this.getDelegateOptions());
                b(f.currentTarget).data("bs." + this.type, g)
            }
        }
        g.tip().hasClass("in") ? g.leave(g) : g.enter(g)
    };
    c.prototype.destroy = function() {
        clearTimeout(this.timeout);
        this.hide().$element.off("." + this.type).removeData("bs." + this.type)
    };

    function d(e) {
        return this.each(function() {
            var f = b(this);
            var h = f.data("bs.tooltip");
            var g = typeof e == "object" && e;
            if (!h && e == "destroy") {
                return
            }
            if (!h) {
                f.data("bs.tooltip", (h = new c(this, g)))
            }
            if (typeof e == "string") {
                h[e]()
            }
        })
    }
    var a = b.fn.tooltip;
    b.fn.tooltip = d;
    b.fn.tooltip.Constructor = c;
    b.fn.tooltip.noConflict = function() {
        b.fn.tooltip = a;
        return this
    }
}(jQuery);
/*
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */
! function($) {
    "use strict";
    $(document).ready(function() {
        $("[data-auto-open].dhvc-form-popup").each(function() {
            var g, h, a = $(this),
                b = a.attr("id"),
                c = a.data("open-delay"),
                d = a.data("auto-close"),
                e = a.data("close-delay"),
                f = a.data("one-time");
            clearTimeout(g), clearTimeout(h), g = setTimeout(function() {
                clearTimeout(h), f ? $.cookie(b) || ($(".dhvc-form-pop-overlay").show(), $("body").addClass("dhvc-form-opening"), a.show(), $.cookie(b, 1, {
                    expires: 3600,
                    path: "/"
                })) : ($.cookie(b, 0, {
                    expires: -1
                }), $(".dhvc-form-pop-overlay").show(), a.show())
            }, c), d && (h = setTimeout(function() {
                clearTimeout(g), $(".dhvc-form-pop-overlay").hide(), $("body").addClass("dhvc-form-opening"), a.hide()
            }, e))
        }), $(document).on("click", '[data-toggle="dhvcformpopup"],[rel="dhvcformpopup"]', function(a) {
            a.stopPropagation(), a.preventDefault();
            var b, c = $(this),
                d = $(c.attr("data-target") || (b = c.attr("href")) && b.replace(/.*(?=#[^\s]+$)/, ""));
            c.is("a") && a.preventDefault(), $(".dhvc-form-pop-overlay").show(), $("body").addClass("dhvc-form-opening"), d.show(), d.off("click").on("click", function(a) {
                a.target === a.currentTarget && ($(".dhvc-form-pop-overlay").hide(), $("body").removeClass("dhvc-form-opening"), d.hide())
            })
        }), $(document).on("click", ".dhvc-form-popup-close", function(a) {
            $(".dhvc-form-pop-overlay").hide(), $("body").removeClass("dhvc-form-opening"), $(this).closest(".dhvc-form-popup").hide()
        }), $(".dhvc-form-slider-control").each(function() {
            var a = $(this);
            a.slider({
                min: a.data("min"),
                max: a.data("max"),
                step: a.data("step"),
                range: "range" == a.data("type") || "min",
                slide: function(b, c) {
                    "range" == a.data("type") ? (a.closest(".dhvc-form-group").find(".dhvc-form-slider-value-from").text(c.values[0]), a.closest(".dhvc-form-group").find(".dhvc-form-slider-value-to").text(c.values[1]), a.closest(".dhvc-form-group").find('input[type="hidden"]').val(c.values[0] + "-" + c.values[1]).trigger("change")) : (a.closest(".dhvc-form-group").find(".dhvc-form-slider-value").text(c.value), a.closest(".dhvc-form-group").find('input[type="hidden"]').val(c.value).trigger("change"))
                }
            }), "range" == a.data("type") ? a.slider("values", [0, a.data("minmax")]) : a.slider("value", a.data("value"))
        });
        var basename = function(a, b) {
                var c = a,
                    d = c.charAt(c.length - 1);
                return "/" !== d && "\\" !== d || (c = c.slice(0, -1)), c = c.replace(/^.*[\/\\]/g, ""), "string" == typeof b && c.substr(c.length - b.length) == b && (c = c.substr(0, c.length - b.length)), c
            },
            operators = {
                ">": function(a, b) {
                    return a > b
                },
                "=": function(a, b) {
                    return a == b
                },
                "<": function(a, b) {
                    return a < b
                }
            },
            conditional_hook = function(a) {
                var f, g, b = $(a.currentTarget),
                    c = b.closest("form"),
                    d = dhvcformL10n.container_class,
                    e = b.closest(d),
                    h = b.data("conditional"),
                    i = [],
                    j = null;
                f = b.is(":checkbox") ? $.map(c.find("[data-conditional-name=" + b.data("conditional-name") + "].dhvc-form-value:checked"), function(a) {
                    return $(a).val()
                }) : b.is(":radio") ? c.find("[data-conditional-name=" + b.data("conditional-name") + "].dhvc-form-value:checked").val() : b.val(), g = b.is(":checkbox") ? !c.find("[data-conditional-name=" + b.data("conditional-name") + "].dhvc-form-value:checked").length : b.is(":radio") ? !c.find("[data-conditional-name=" + b.data("conditional-name") + "].dhvc-form-value:checked").val() : !f.length, g ? ($.each(h, function(a, b) {
                    var e = b.element.split(",");
                    $.each(e, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden")
                    })
                }), $.each(h, function(a, b) {
                    var e = b.element.split(",");
                    "is_empty" == b.type && ("hide" == b.action ? $.each(e, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(e, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }))
                })) : ($.isNumeric(f) && (f = parseInt(f)), $.each(h, function(a, b) {
                    b.value == f ? j = b : i.push(b)
                }), null != j && (i.push(j), h = i), $.each(h, function(a, b) {
                    var g = b.element.split(",");
                    e.hasClass("dhvc-form-hidden") ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden")
                    }) : "not_empty" == b.type ? "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : "is_empty" == b.type ? "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.isArray(f) ? $.inArray(b.value, f) > -1 ? "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : ($.isNumeric(f) && (f = parseInt(f)), $.isNumeric(b.value) && "0" != b.value && (b.value = parseInt(b.value)), "not_empty" != b.type && "is_empty" != b.type && operators[b.type](f, b.value) ? "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }))
                }))
            },
            conditional_init = function() {
                $("form.dhvcform").each(function() {
                    var a = $(this),
                        b = a.find(".dhvc-form-conditional");
                    $.each(b, function() {
                        var a = $(this).find("[data-conditional].dhvc-form-value");
                        $(a).bind("keyup change", conditional_hook), $.each(a, function() {
                            conditional_hook({
                                currentTarget: $(this)
                            })
                        })
                    })
                })
            };
        conditional_init(), ($(".dhvc-form-datepicker").length || $(".dhvc-form-timepicker").length) && $.datetimepicker.setLocale(dhvcformL10n.datetimepicker_lang), $(".dhvc-form-datepicker").length && $(".dhvc-form-datepicker").each(function() {
            var a = $(this);
            a.datetimepicker({
                format: dhvcformL10n.date_format,
                timepicker: !1,
                scrollMonth: !1,
                dayOfWeekStart: parseInt(dhvcformL10n.dayofweekstart),
                scrollTime: !1,
                minDate: a.data("min-date"),
                maxDate: a.data("max-date"),
                yearStart: a.data("year-start"),
                yearEnd: a.data("year-end"),
                scrollInput: !1
            })
        });
        var form_submit_loading = function(a, b) {
            b = b || !1;
            var c = $(a).find(".dhvc-form-submit"),
                d = $(a).find(".dhvc-form-submit-label"),
                e = $(a).find(".dhvc-form-submit-spinner");
            b ? (c.removeAttr("disabled"), d.removeClass("dhvc-form-submit-label-hidden"), e.hide()) : (c.attr("disabled", "disabled"), d.addClass("dhvc-form-submit-label-hidden"), e.show())
        };
        $(".dhvc-form-timepicker").length && $(".dhvc-form-timepicker").each(function() {
            var a = $(this);
            a.datetimepicker({
                format: dhvcformL10n.time_format,
                datepicker: !1,
                scrollMonth: !1,
                scrollTime: !0,
                scrollInput: !1,
                minTime: a.data("min-time"),
                maxTime: a.data("max-time"),
                step: parseInt(dhvcformL10n.time_picker_step)
            })
        }), $.extend($.validator.messages, {
            required: dhvcformL10n.validate_messages.required,
            remote: dhvcformL10n.validate_messages.remote,
            email: dhvcformL10n.validate_messages.email,
            url: dhvcformL10n.validate_messages.url,
            date: dhvcformL10n.validate_messages.date,
            dateISO: dhvcformL10n.validate_messages.dateISO,
            number: dhvcformL10n.validate_messages.number,
            digits: dhvcformL10n.validate_messages.digits,
            creditcard: dhvcformL10n.validate_messages.creditcard,
            equalTo: dhvcformL10n.validate_messages.equalTo,
            maxlength: $.validator.format(dhvcformL10n.validate_messages.maxlength),
            minlength: $.validator.format(dhvcformL10n.validate_messages.minlength),
            rangelength: $.validator.format(dhvcformL10n.validate_messages.rangelength),
            range: $.validator.format(dhvcformL10n.validate_messages.range),
            max: $.validator.format(dhvcformL10n.validate_messages.max),
            min: $.validator.format(dhvcformL10n.validate_messages.min)
        }), $.validator.addMethod("alpha", function(a, b, c) {
            return this.optional(b) || /^[a-zA-Z]+$/.test(a)
        }, dhvcformL10n.validate_messages.alpha), $.validator.addMethod("number2", function(a, b, c) {
            var d = /^(?=.*[0-9])[- +()0-9]+$/gm;
            return this.optional(b) || d.test(a)
        }, dhvcformL10n.validate_messages.number2), $.validator.addMethod("email2", function(a, b, c) {
            return this.optional(b) || /^([a-z0-9,!\#\$%&'\*\+\/=\?\^_`\{\|\}~-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z0-9,!\#\$%&'\*\+\/=\?\^_`\{\|\}~-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*@([a-z0-9-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z0-9-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*\.(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]){2,})$/i.test(a)
        }, dhvcformL10n.validate_messages.email), $.validator.addMethod("alphanum", function(a, b, c) {
            return this.optional(b) || /^[a-zA-Z0-9]+$/.test(a)
        }, dhvcformL10n.validate_messages.alphanum), $.validator.addMethod("url", function(a, b, c) {
            return a = (a || "").replace(/^\s+/, "").replace(/\s+$/, ""), this.optional(b) || /^(http|https|ftp):\/\/(([A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))(\.[A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))*)(:(\d+))?(\/[A-Z0-9~](([A-Z0-9_~-]|\.)*[A-Z0-9~]|))*\/?(.*)?$/i.test(a)
        }, dhvcformL10n.validate_messages.url), $.validator.addMethod("zip", function(a, b, c) {
            return this.optional(b) || /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(a)
        }, dhvcformL10n.validate_messages.zip), $.validator.addMethod("fax", function(a, b, c) {
            return this.optional(b) || /^(\()?\d{3}(\))?(-|\s)?\d{3}(-|\s)\d{4}$/.test(a)
        }, dhvcformL10n.validate_messages.fax), $.validator.addMethod("cpassword", function(a, b, c) {
            var d = $(b).data("validate-cpassword");
            return this.optional(b) || a === $(b).closest("form").find("#dhvc_form_control_" + d).val()
        }, dhvcformL10n.validate_messages.cpassword), $.validator.addMethod("extension", function(a, b, c) {
            return c = "string" == typeof c ? c.replace(/,/g, "|") : "png|jpe?g|gif", this.optional(b) || a.match(new RegExp(".(" + c + ")$", "i"))
        }, dhvcformL10n.validate_messages.extension), $.validator.addMethod("recaptcha", function(a, b, c) {
            var d = !0;
            return "yes" === dhvcformL10n.recaptcha_ajax_verify && (d = !1, $.ajax({
                url: dhvcformL10n.ajax_url,
                type: "POST",
                async: !1,
                data: {
                    action: "dhvc_form_recaptcha",
                    _ajax_nonce: dhvcformL10n._ajax_nonce,
                    recaptcha_challenge_field: Recaptcha.get_challenge(),
                    recaptcha_response_field: Recaptcha.get_response()
                },
                beforeSend: function() {
                    form_submit_loading($(b).closest("form"), !1)
                },
                success: function(a) {
                    form_submit_loading($(b).closest("true"), !1), a > 0 ? d = !0 : Recaptcha.reload()
                }
            })), d
        }, dhvcformL10n.validate_messages.recaptcha), $.validator.addMethod("dhvcformcaptcha", function(a, b, c) {
            var d = !1;
            return $.ajax({
                url: dhvcformL10n.ajax_url,
                type: "POST",
                async: !1,
                data: {
                    action: "dhvc_form_unique",
                    _ajax_nonce: dhvcformL10n._ajax_nonce,
                    answer: $(b).val()
                },
                beforeSend: function() {
                    form_submit_loading($(b).closest("form"), !1)
                },
                success: function(a) {
                    form_submit_loading($(b).closest("form"), !0), a > 0 ? d = !0 : $(b).parent().find("img").get(0).src = dhvcformL10n.plugin_url + "/captcha.php?t=" + Math.random()
                }
            }), d
        }, dhvcformL10n.validate_messages.captcha),  $.validator.addMethod("dhvcformunique", function(a, b, c) {
            var d = !1;
            return $.ajax({
                url: dhvcformL10n.ajax_url,
                type: "POST",
                async: !1,
                data: {
                    action: "dhvc_form_unique",
                    _ajax_nonce: dhvcformL10n._ajax_nonce,
                    answer: $(b).val()
                },
                beforeSend: function() {
                    form_submit_loading($(b).closest("form"), !1)
                },
                success: function(a) {
                	form_submit_loading($(b).closest("form"), !0), a > 0 ? d = !0 : d = !1 
                }
            }), d
        }, dhvcformL10n.validate_messages.dhvcformunique), $.validator.addMethod("dhvcformuniquewithstatus", function(a, b, c) {
            var d = !1;
            return $.ajax({
                url: dhvcformL10n.ajax_url,
                type: "POST",
                async: !1,
                data: {
                    action: "dhvc_form_unique_with_status",
                    _ajax_nonce: dhvcformL10n._ajax_nonce,
                    answer: $(b).val()
                },
                beforeSend: function() {
                    form_submit_loading($(b).closest("form"), !1)
                },
                success: function(a) {
                	form_submit_loading($(b).closest("form"), !0), a > 0 ? d = !0 : d = !1 
                }
            }), d
        }, dhvcformL10n.validate_messages.dhvcformuniquewithstatus), $.validator.addClassRules({
        	
            "dhvc-form-required-entry": {
                required: !0
            },
            "dhvc-form-validate-email": {
                email2: !0
            },
            "dhvc-form-validate-date": {
                date: !0
            },
            "dhvc-form-validate-number": {
                number: !0
            },
            "dhvc-form-validate-number2": {
                number2: !0
            },
            "dhvc-form-validate-unique2":{
				dhvcformunique : !0
			},
			"dhvc-form-validate-unique-with-status":{
				dhvcformuniquewithstatus : !0
			},
            "dhvc-form-validate-digits": {
                digits: !0
            },
            "dhvc-form-validate-alpha": {
                alpha: !0
            },
            "dhvc-form-validate-alphanum": {
                alphanum: !0
            },
            "dhvc-form-validate-url": {
                url: !0
            },
            "dhvc-form-validate-zip": {
                zip: !0
            },
            "dhvc-form-validate-fax": {
                fax: !0
            },
            "dhvc-form-validate-password": {
                required: !0,
                minlength: 6
            },
            "dhvc-form-validate-cpassword": {
                required: !0,
                minlength: 6,
                cpassword: !0
            },
            "dhvc-form-validate-captcha": {
                required: !0,
                dhvcformcaptcha: !0
            },
            "dhvc-form-validate-extension": {
                extension: dhvcformL10n.allowed_file_extension
            }
        }), $("form.dhvcform").each(function() {
            $(this).find(".dhvc-form-file").find("input[type=file]").on("change", function() {
                var a = $(this).val();
                $(this).closest("label").find(".dhvc-form-control").prop("value", basename(a))
            }), $(this).find(".dhvc-form-file").each(function() {
                $(this).find('input[type="text"]').css({
                    "padding-right": $(this).find(".dhvc-form-file-button").outerWidth(!0) + "px"
                }), $(this).find('input[type="text"]').on("click", function() {
                    $(this).closest("label").trigger("click")
                })
            }), $(this).find(".dhvc-form-rate .dhvc-form-rate-star").tooltip({
                html: !0,
                container: $("body")
            }), $(this).validate({
                onkeyup: !1,
                onfocusout: !1,
                onclick: !1,
                errorClass: "dhvc-form-error",
                validClass: "dhvc-form-valid",
                errorElement: "span",
                errorPlacement: function(a, b) {
                    b.is(":radio") || b.is(":checkbox") ? a.appendTo(b.parent().parent()) : "recaptcha_response_field" == $(b).attr("id") ? a.appendTo($(b).closest(".dhvc-form-group-recaptcha")) : a.appendTo(b.parent().parent())
                },
                rules: {
                    recaptcha_response_field: {
                        required: !0,
                        recaptcha: !0
                    }
                },
                invalidHandler: function(a, b) {
                    /(iPad|iPhone|iPod)/g.test(window.navigator.userAgent) && window.setTimeout(function() {
                        $("html,body").animate({
                            scrollTop: $(b.errorList[0].element).offset().top
                        })
                    }, 0)
                },
                submitHandler: function(form) {
                    var user_ajax = $(form).data("use-ajax"),
                        msg_container = $(form).closest(".dhvc-form-container").find(".dhvc-form-message"),
                        recaptcha2_valid = !0,
                        submit = $(form).find(".dhvc-form-submit");
                    return "yes" === dhvcformL10n.recaptcha_ajax_verify && $(form).find(".dhvc-form-recaptcha2").length && $.ajax({
                        url: dhvcformL10n.ajax_url,
                        type: "POST",
                        async: !1,
                        data: {
                            action: "dhvc_form_recaptcha2",
                            _ajax_nonce: dhvcformL10n._ajax_nonce,
                            recaptcha2_response: grecaptcha.getResponse($(form).find(".dhvc-form-recaptcha2:first").data("grecaptcha"))
                        },
                        beforeSend: function() {
                            form_submit_loading($(form), !1), msg_container.empty().fadeOut()
                        },
                        success: function(a) {
                            form_submit_loading($(form), !0), 0 == a.success && (recaptcha2_valid = !1, $(a.message).appendTo($(form).find(".dhvc-form-recaptcha2")))
                        }
                    }), user_ajax && recaptcha2_valid ? ($.ajax({
                        url: dhvcformL10n.ajax_url,
                        type: "POST",
                        data: $(form).serialize(),
                        dataType: "json",
                        beforeSend: function() {
                            form_submit_loading($(form), !1), msg_container.empty().removeClass("dhvc-form-show").fadeOut(), $(document.body).trigger("dhvc_form_before_submit", [$(form), $(submit)])
                        },
                        success: function(resp) {
                            form_submit_loading($(form), !0), $(document.body).trigger("dhvc_form_after_submit", [$(form), $(submit), resp]), resp.success ? (resp.scripts_on_sent_ok && $.each(resp.scripts_on_sent_ok, function(i, n) {
                                eval(n)
                            }), "message" == resp.on_success ? (msg_container.html(resp.message).addClass("dhvc-form-show").fadeIn(), "1" == $(form).data("ajax_reset_submit") && ($(form).resetForm(), $('input[type="text"], textarea', $(form)).blur()), $(form).find(".dhvc-form-captcha").each(function() {
                                $(this).find("img").get(0).src = dhvcformL10n.plugin_url + "/captcha.php?t=" + Math.random()
                            }), !$(form).data("popup") && $(form).data("scroll_to_msg") && $.smoothScroll({
                                scrollTarget: msg_container,
                                offset: -100,
                                speed: 500
                            })) : window.location = resp.redirect_url) : (msg_container.html('<span class="error">' + resp.message + "</span>").addClass("dhvc-form-show").fadeIn(), "1" == $(form).data("ajax_reset_submit") && ($(form).resetForm(), $('input[type="text"], textarea', $(form)).blur()), $(form).find(".dhvc-form-captcha").each(function() {
                                $(this).find("img").get(0).src = dhvcformL10n.plugin_url + "/captcha.php?t=" + Math.random()
                            }), $(form).find(".dhvc-form-recaptcha2").length && grecaptcha.reset($(form).find(".dhvc-form-group-recaptcha:first").data("grecaptcha")), !$(form).data("popup") && $(form).data("scroll_to_msg") && $.smoothScroll({
                                scrollTarget: msg_container,
                                offset: -100,
                                speed: 500
                            }))
                        }
                    }), !1) : (recaptcha2_valid && form.submit(), !1)
                }
            })
        })
    })
}(window.jQuery);
/*
 *DHVC Form Script 
 * @param jQuery
 */
! function($) {
    "use strict";
    $(document).ready(function() {
        $("[data-auto-open].dhvc-form-popup").each(function() {
            var g, h, a = $(this),
                b = a.attr("id"),
                c = a.data("open-delay"),
                d = a.data("auto-close"),
                e = a.data("close-delay"),
                f = a.data("one-time");
            clearTimeout(g), clearTimeout(h), g = setTimeout(function() {
                clearTimeout(h), f ? $.cookie(b) || ($(".dhvc-form-pop-overlay").show(), $("body").addClass("dhvc-form-opening"), a.show(), $.cookie(b, 1, {
                    expires: 3600,
                    path: "/"
                })) : ($.cookie(b, 0, {
                    expires: -1
                }), $(".dhvc-form-pop-overlay").show(), a.show())
            }, c), d && (h = setTimeout(function() {
                clearTimeout(g), $(".dhvc-form-pop-overlay").hide(), $("body").addClass("dhvc-form-opening"), a.hide()
            }, e))
        }), $(document).on("click", '[data-toggle="dhvcformpopup"],[rel="dhvcformpopup"]', function(a) {
            a.stopPropagation(), a.preventDefault();
            var b, c = $(this),
                d = $(c.attr("data-target") || (b = c.attr("href")) && b.replace(/.*(?=#[^\s]+$)/, ""));
            c.is("a") && a.preventDefault(), $(".dhvc-form-pop-overlay").show(), $("body").addClass("dhvc-form-opening"), d.show(), d.off("click").on("click", function(a) {
                a.target === a.currentTarget && ($(".dhvc-form-pop-overlay").hide(), $("body").removeClass("dhvc-form-opening"), d.hide())
            })
        }), $(document).on("click", ".dhvc-form-popup-close", function(a) {
            $(".dhvc-form-pop-overlay").hide(), $("body").removeClass("dhvc-form-opening"), $(this).closest(".dhvc-form-popup").hide()
        }), $(".dhvc-form-slider-control").each(function() {
            var a = $(this);
            a.slider({
                min: a.data("min"),
                max: a.data("max"),
                step: a.data("step"),
                range: "range" == a.data("type") || "min",
                slide: function(b, c) {
                    "range" == a.data("type") ? (a.closest(".dhvc-form-group").find(".dhvc-form-slider-value-from").text(c.values[0]), a.closest(".dhvc-form-group").find(".dhvc-form-slider-value-to").text(c.values[1]), a.closest(".dhvc-form-group").find('input[type="hidden"]').val(c.values[0] + "-" + c.values[1]).trigger("change")) : (a.closest(".dhvc-form-group").find(".dhvc-form-slider-value").text(c.value), a.closest(".dhvc-form-group").find('input[type="hidden"]').val(c.value).trigger("change"))
                }
            }), "range" == a.data("type") ? a.slider("values", [0, a.data("minmax")]) : a.slider("value", a.data("value"))
        });
        var basename = function(a, b) {
                var c = a,
                    d = c.charAt(c.length - 1);
                return "/" !== d && "\\" !== d || (c = c.slice(0, -1)), c = c.replace(/^.*[\/\\]/g, ""), "string" == typeof b && c.substr(c.length - b.length) == b && (c = c.substr(0, c.length - b.length)), c
            },
            operators = {
                ">": function(a, b) {
                    return a > b
                },
                "=": function(a, b) {
                    return a == b
                },
                "<": function(a, b) {
                    return a < b
                }
            },
            conditional_hook = function(a) {
                var f, g, b = $(a.currentTarget),
                    c = b.closest("form"),
                    d = dhvcformL10n.container_class,
                    e = b.closest(d),
                    h = b.data("conditional"),
                    i = [],
                    j = null;
                f = b.is(":checkbox") ? $.map(c.find("[data-conditional-name=" + b.data("conditional-name") + "].dhvc-form-value:checked"), function(a) {
                    return $(a).val()
                }) : b.is(":radio") ? c.find("[data-conditional-name=" + b.data("conditional-name") + "].dhvc-form-value:checked").val() : b.val(), g = b.is(":checkbox") ? !c.find("[data-conditional-name=" + b.data("conditional-name") + "].dhvc-form-value:checked").length : b.is(":radio") ? !c.find("[data-conditional-name=" + b.data("conditional-name") + "].dhvc-form-value:checked").val() : !f.length, g ? ($.each(h, function(a, b) {
                    var e = b.element.split(",");
                    $.each(e, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden")
                    })
                }), $.each(h, function(a, b) {
                    var e = b.element.split(",");
                    "is_empty" == b.type && ("hide" == b.action ? $.each(e, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(e, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }))
                })) : ($.isNumeric(f) && (f = parseInt(f)), $.each(h, function(a, b) {
                    b.value == f ? j = b : i.push(b)
                }), null != j && (i.push(j), h = i), $.each(h, function(a, b) {
                    var g = b.element.split(",");
                    e.hasClass("dhvc-form-hidden") ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden")
                    }) : "not_empty" == b.type ? "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : "is_empty" == b.type ? "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.isArray(f) ? $.inArray(b.value, f) > -1 ? "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : ($.isNumeric(f) && (f = parseInt(f)), $.isNumeric(b.value) && "0" != b.value && (b.value = parseInt(b.value)), "not_empty" != b.type && "is_empty" != b.type && operators[b.type](f, b.value) ? "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : "hide" == b.action ? $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).removeClass("dhvc-form-hidden"), e.trigger("change")
                    }) : $.each(g, function(a, b) {
                        var e = c.find(".dhvc-form-control-" + b);
                        e.closest(d).addClass("dhvc-form-hidden"), e.trigger("change")
                    }))
                }))
            },
            conditional_init = function() {
                $("form.dhvcform").each(function() {
                    var a = $(this),
                        b = a.find(".dhvc-form-conditional");
                    $.each(b, function() {
                        var a = $(this).find("[data-conditional].dhvc-form-value");
                        $(a).bind("keyup change", conditional_hook), $.each(a, function() {
                            conditional_hook({
                                currentTarget: $(this)
                            })
                        })
                    })
                })
            };
        conditional_init(), ($(".dhvc-form-datepicker").length || $(".dhvc-form-timepicker").length) && $.datetimepicker.setLocale(dhvcformL10n.datetimepicker_lang), $(".dhvc-form-datepicker").length && $(".dhvc-form-datepicker").each(function() {
            var a = $(this);
            a.datetimepicker({
                format: dhvcformL10n.date_format,
                timepicker: !1,
                scrollMonth: !1,
                dayOfWeekStart: parseInt(dhvcformL10n.dayofweekstart),
                scrollTime: !1,
                minDate: a.data("min-date"),
                maxDate: a.data("max-date"),
                yearStart: a.data("year-start"),
                yearEnd: a.data("year-end"),
                scrollInput: !1
            })
        });
        var form_submit_loading = function(a, b) {
            b = b || !1;
            var c = $(a).find(".dhvc-form-submit"),
                d = $(a).find(".dhvc-form-submit-label"),
                e = $(a).find(".dhvc-form-submit-spinner");
            b ? (c.removeAttr("disabled"), d.removeClass("dhvc-form-submit-label-hidden"), e.hide()) : (c.attr("disabled", "disabled"), d.addClass("dhvc-form-submit-label-hidden"), e.show())
        };
        $(".dhvc-form-timepicker").length && $(".dhvc-form-timepicker").each(function() {
            var a = $(this);
            a.datetimepicker({
                format: dhvcformL10n.time_format,
                datepicker: !1,
                scrollMonth: !1,
                scrollTime: !0,
                scrollInput: !1,
                minTime: a.data("min-time"),
                maxTime: a.data("max-time"),
                step: parseInt(dhvcformL10n.time_picker_step)
            })
        }), $.extend($.validator.messages, {
            required: dhvcformL10n.validate_messages.required,
            remote: dhvcformL10n.validate_messages.remote,
            email: dhvcformL10n.validate_messages.email,
            url: dhvcformL10n.validate_messages.url,
            date: dhvcformL10n.validate_messages.date,
            dateISO: dhvcformL10n.validate_messages.dateISO,
            number: dhvcformL10n.validate_messages.number,
            digits: dhvcformL10n.validate_messages.digits,
            creditcard: dhvcformL10n.validate_messages.creditcard,
            equalTo: dhvcformL10n.validate_messages.equalTo,
            maxlength: $.validator.format(dhvcformL10n.validate_messages.maxlength),
            minlength: $.validator.format(dhvcformL10n.validate_messages.minlength),
            rangelength: $.validator.format(dhvcformL10n.validate_messages.rangelength),
            range: $.validator.format(dhvcformL10n.validate_messages.range),
            max: $.validator.format(dhvcformL10n.validate_messages.max),
            min: $.validator.format(dhvcformL10n.validate_messages.min)
        }), $.validator.addMethod("alpha", function(a, b, c) {
            return this.optional(b) || /^[a-zA-Z]+$/.test(a)
        }, dhvcformL10n.validate_messages.alpha), $.validator.addMethod("number2", function(a, b, c) {
            var d = /^(?=.*[0-9])[- +()0-9]+$/gm;
            return this.optional(b) || d.test(a)
        }, dhvcformL10n.validate_messages.number2), $.validator.addMethod("email2", function(a, b, c) {
            return this.optional(b) || /^([a-z0-9,!\#\$%&'\*\+\/=\?\^_`\{\|\}~-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z0-9,!\#\$%&'\*\+\/=\?\^_`\{\|\}~-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*@([a-z0-9-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z0-9-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*\.(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]){2,})$/i.test(a)
        }, dhvcformL10n.validate_messages.email), $.validator.addMethod("alphanum", function(a, b, c) {
            return this.optional(b) || /^[a-zA-Z0-9]+$/.test(a)
        }, dhvcformL10n.validate_messages.alphanum), $.validator.addMethod("url", function(a, b, c) {
            return a = (a || "").replace(/^\s+/, "").replace(/\s+$/, ""), this.optional(b) || /^(http|https|ftp):\/\/(([A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))(\.[A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))*)(:(\d+))?(\/[A-Z0-9~](([A-Z0-9_~-]|\.)*[A-Z0-9~]|))*\/?(.*)?$/i.test(a)
        }, dhvcformL10n.validate_messages.url), $.validator.addMethod("zip", function(a, b, c) {
            return this.optional(b) || /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(a)
        }, dhvcformL10n.validate_messages.zip), $.validator.addMethod("fax", function(a, b, c) {
            return this.optional(b) || /^(\()?\d{3}(\))?(-|\s)?\d{3}(-|\s)\d{4}$/.test(a)
        }, dhvcformL10n.validate_messages.fax), $.validator.addMethod("cpassword", function(a, b, c) {
            var d = $(b).data("validate-cpassword");
            return this.optional(b) || a === $(b).closest("form").find("#dhvc_form_control_" + d).val()
        }, dhvcformL10n.validate_messages.cpassword), $.validator.addMethod("extension", function(a, b, c) {
            return c = "string" == typeof c ? c.replace(/,/g, "|") : "png|jpe?g|gif", this.optional(b) || a.match(new RegExp(".(" + c + ")$", "i"))
        }, dhvcformL10n.validate_messages.extension), $.validator.addMethod("recaptcha", function(a, b, c) {
            var d = !0;
            return "yes" === dhvcformL10n.recaptcha_ajax_verify && (d = !1, $.ajax({
                url: dhvcformL10n.ajax_url,
                type: "POST",
                async: !1,
                data: {
                    action: "dhvc_form_recaptcha",
                    _ajax_nonce: dhvcformL10n._ajax_nonce,
                    recaptcha_challenge_field: Recaptcha.get_challenge(),
                    recaptcha_response_field: Recaptcha.get_response()
                },
                beforeSend: function() {
                    form_submit_loading($(b).closest("form"), !1)
                },
                success: function(a) {
                	
                    form_submit_loading($(b).closest("true"), !1), a > 0 ? d = !0 : Recaptcha.reload()
                }
            })), d
        }, dhvcformL10n.validate_messages.recaptcha), $.validator.addMethod("dhvcformcaptcha", function(a, b, c) {
            var d = !1;
            return $.ajax({
                url: dhvcformL10n.ajax_url,
                type: "POST",
                async: !1,
                data: {
                    action: "dhvc_form_captcha",
                    _ajax_nonce: dhvcformL10n._ajax_nonce,
                    answer: $(b).val()
                },
                beforeSend: function() {
                    form_submit_loading($(b).closest("form"), !1)
                },
                success: function(a) {
                    form_submit_loading($(b).closest("form"), !0), a > 0 ? d = !0 : $(b).parent().find("img").get(0).src = dhvcformL10n.plugin_url + "/captcha.php?t=" + Math.random()
                }
            }), d
        }, dhvcformL10n.validate_messages.captcha), $.validator.addMethod("dhvcformunique", function(a, b, c) {
        	
        	   var d = !1;
	            return $.ajax({
	                url: dhvcformL10n.ajax_url,
	                type: "POST",
	                async: !1,
	                data: {
	                    action: "dhvc_form_unique",
	                    _ajax_nonce: dhvcformL10n._ajax_nonce,
	                    unique_key: $(b).attr('name'),
	                    form_data: $(b).closest("form").serialize(),
	                },
	                beforeSend: function() {
	                    form_submit_loading($(b).closest("form"), !1)
	                },
	                success: function(a) {
	                	form_submit_loading($(b).closest("form"), !0), a > 0 ? d = !0 : d = !1 
	                }
	            }), d
        },dhvcformL10n.validate_messages.dhvcformunique),$.validator.addMethod("dhvcformuniquewithstatus", function(a, b, c) {
            var d = !1;
            return $.ajax({
                url: dhvcformL10n.ajax_url,
                type: "POST",
                async: !1,
                data: {
                    action: "dhvc_form_unique_with_status",   
                    _ajax_nonce: dhvcformL10n._ajax_nonce,
	                unique_key: $(b).attr('name'),
	                form_data: $(b).closest("form").serialize(),
                },
                beforeSend: function() {
                    form_submit_loading($(b).closest("form"), !1)
                },
                success: function(a) {
                    if(a == 2){
                        $('#overlay_form_7').show();
                        return;
                    }
                	form_submit_loading($(b).closest("form"), !0), a > 0 ? d = !0 : d = !1 
                }
            }), d
        }, dhvcformL10n.validate_messages.dhvcformuniquewithstatus), $.validator.addClassRules({
        	
            "dhvc-form-required-entry": {
                required: !0
            },
            "dhvc-form-validate-email": {
                email2: !0
            },
            "dhvc-form-validate-date": {
                date: !0
            },
            "dhvc-form-validate-number": {
                number: !0
            },
            "dhvc-form-validate-number2": {
                number2: !0
            },
            "dhvc-form-validate-unique2":{
				dhvcformunique : !0
			},
			"dhvc-form-validate-unique-with-status":{
				dhvcformuniquewithstatus : !0
			},
            "dhvc-form-validate-digits": {
                digits: !0
            },
            "dhvc-form-validate-alpha": {
                alpha: !0
            },
            "dhvc-form-validate-alphanum": {
                alphanum: !0
            },
            "dhvc-form-validate-url": {
                url: !0
            },
            "dhvc-form-validate-zip": {
                zip: !0
            },
            "dhvc-form-validate-fax": {
                fax: !0
            },
            "dhvc-form-validate-password": {
                required: !0,
                minlength: 6
            },
            "dhvc-form-validate-cpassword": {
                required: !0,
                minlength: 6,
                cpassword: !0
            },
            "dhvc-form-validate-captcha": {
                required: !0,
                dhvcformcaptcha: !0
            },
            "dhvc-form-validate-extension": {
                extension: dhvcformL10n.allowed_file_extension
            }
        }), $("form.dhvcform").each(function() {
            $(this).find(".dhvc-form-file").find("input[type=file]").on("change", function() {
                var a = $(this).val();
                $(this).closest("label").find(".dhvc-form-control").prop("value", basename(a))
            }), $(this).find(".dhvc-form-file").each(function() {
                $(this).find('input[type="text"]').css({
                    "padding-right": $(this).find(".dhvc-form-file-button").outerWidth(!0) + "px"
                }), $(this).find('input[type="text"]').on("click", function() {
                    $(this).closest("label").trigger("click")
                })
            }), $(this).find(".dhvc-form-rate .dhvc-form-rate-star").tooltip({
                html: !0,
                container: $("body")
            }), $(this).validate({
                onkeyup: !1,
                onfocusout: !1,
                onclick: !1,
                errorClass: "dhvc-form-error",
                validClass: "dhvc-form-valid",
                errorElement: "span",
                errorPlacement: function(a, b) {
                    b.is(":radio") || b.is(":checkbox") ? a.appendTo(b.parent().parent()) : "recaptcha_response_field" == $(b).attr("id") ? a.appendTo($(b).closest(".dhvc-form-group-recaptcha")) : a.appendTo(b.parent().parent())
                },
                rules: {
                    recaptcha_response_field: {
                        required: !0,
                        recaptcha: !0
                    }
                },
                invalidHandler: function(a, b) {
                    /(iPad|iPhone|iPod)/g.test(window.navigator.userAgent) && window.setTimeout(function() {
                        $("html,body").animate({
                            scrollTop: $(b.errorList[0].element).offset().top
                        })
                    }, 0)
                },
                submitHandler: function(form) {
                    var user_ajax = $(form).data("use-ajax"),
                        msg_container = $(form).closest(".dhvc-form-container").find(".dhvc-form-message"),
                        recaptcha2_valid = !0,
                        submit = $(form).find(".dhvc-form-submit");
                    return "yes" === dhvcformL10n.recaptcha_ajax_verify && $(form).find(".dhvc-form-recaptcha2").length && $.ajax({
                        url: dhvcformL10n.ajax_url,
                        type: "POST",
                        async: !1,
                        data: {
                            action: "dhvc_form_recaptcha2",
                            _ajax_nonce: dhvcformL10n._ajax_nonce,
                            recaptcha2_response: grecaptcha.getResponse($(form).find(".dhvc-form-recaptcha2:first").data("grecaptcha"))
                        },
                        beforeSend: function() {
                            form_submit_loading($(form), !1), msg_container.empty().fadeOut()
                        },
                        success: function(a) {
                            form_submit_loading($(form), !0), 0 == a.success && (recaptcha2_valid = !1, $(a.message).appendTo($(form).find(".dhvc-form-recaptcha2")))
                        }
                    }), user_ajax && recaptcha2_valid ? ($.ajax({
                        url: dhvcformL10n.ajax_url,
                        type: "POST",
                        data: $(form).serialize(),
                        dataType: "json",
                        beforeSend: function() {
                            form_submit_loading($(form), !1), msg_container.empty().removeClass("dhvc-form-show").fadeOut(), $(document.body).trigger("dhvc_form_before_submit", [$(form), $(submit)])
                        },
                        success: function(resp) {
                            form_submit_loading($(form), !0), $(document.body).trigger("dhvc_form_after_submit", [$(form), $(submit), resp]), resp.success ? (resp.scripts_on_sent_ok && $.each(resp.scripts_on_sent_ok, function(i, n) {
                                eval(n)
                            }), "message" == resp.on_success ? (msg_container.html(resp.message).addClass("dhvc-form-show").fadeIn(), "1" == $(form).data("ajax_reset_submit") && ($(form).resetForm(), $('input[type="text"], textarea', $(form)).blur()), $(form).find(".dhvc-form-captcha").each(function() {
                                $(this).find("img").get(0).src = dhvcformL10n.plugin_url + "/captcha.php?t=" + Math.random()
                            }), !$(form).data("popup") && $(form).data("scroll_to_msg") && $.smoothScroll({
                                scrollTarget: msg_container,
                                offset: -100,
                                speed: 500
                            })) : window.location = resp.redirect_url) : (msg_container.html('<span class="error">' + resp.message + "</span>").addClass("dhvc-form-show").fadeIn(), "1" == $(form).data("ajax_reset_submit") && ($(form).resetForm(), $('input[type="text"], textarea', $(form)).blur()), $(form).find(".dhvc-form-captcha").each(function() {
                                $(this).find("img").get(0).src = dhvcformL10n.plugin_url + "/captcha.php?t=" + Math.random()
                            }), $(form).find(".dhvc-form-recaptcha2").length && grecaptcha.reset($(form).find(".dhvc-form-group-recaptcha:first").data("grecaptcha")), !$(form).data("popup") && $(form).data("scroll_to_msg") && $.smoothScroll({
                                scrollTarget: msg_container,
                                offset: -100,
                                speed: 500
                            }))
                        }
                    }), !1) : (recaptcha2_valid && form.submit(), !1)
                }
            })
        })
    })
}(window.jQuery);