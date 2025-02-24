/*! jQuery v3.7.1 -ajax,-ajax/jsonp,-ajax/load,-ajax/script,-ajax/var/location,-ajax/var/nonce,-ajax/var/rquery,-ajax/xhr,-manipulation/_evalUrl,-deprecated/ajax-event-alias,-effects,-effects/animatedSelector,-effects/Tween | (c) OpenJS Foundation and other contributors | jquery.org/license */
(function (P, Ne) {
    "use strict";
    typeof module == "object" && typeof module.exports == "object" ? module.exports = P.document ? Ne(P, !0) : function (U) {
        if (!U.document)
            throw new Error("jQuery requires a window with a document");
        return Ne(U)
    }
        : Ne(P)
}
)(typeof window < "u" ? window : this, function (P, Ne) {
    "use strict";
    var U = []
        , gt = Object.getPrototypeOf
        , J = U.slice
        , vt = U.flat ? function (e) {
            return U.flat.call(e)
        }
            : function (e) {
                return U.concat.apply([], e)
            }
        , Ie = U.push
        , ie = U.indexOf
        , We = {}
        , mt = We.toString
        , Le = We.hasOwnProperty
        , yt = Le.toString
        , ln = yt.call(Object)
        , N = {}
        , H = function (e) {
            return typeof e == "function" && typeof e.nodeType != "number" && typeof e.item != "function"
        }
        , ye = function (e) {
            return e != null && e === e.window
        }
        , D = P.document
        , cn = {
            type: !0,
            src: !0,
            nonce: !0,
            noModule: !0
        };
    function bt(e, t, n) {
        var i, o, a = (n = n || D).createElement("script");
        if (a.text = e,
            t)
            for (i in cn)
                (o = t[i] || t.getAttribute && t.getAttribute(i)) && a.setAttribute(i, o);
        n.head.appendChild(a).parentNode.removeChild(a)
    }
    function be(e) {
        return e == null ? e + "" : typeof e == "object" || typeof e == "function" ? We[mt.call(e)] || "object" : typeof e
    }
    var xt = "3.7.1 -ajax,-ajax/jsonp,-ajax/load,-ajax/script,-ajax/var/location,-ajax/var/nonce,-ajax/var/rquery,-ajax/xhr,-manipulation/_evalUrl,-deprecated/ajax-event-alias,-effects,-effects/animatedSelector,-effects/Tween"
        , fn = /HTML$/i
        , r = function (e, t) {
            return new r.fn.init(e, t)
        };
    function Je(e) {
        var t = !!e && "length" in e && e.length
            , n = be(e);
        return !H(e) && !ye(e) && (n === "array" || t === 0 || typeof t == "number" && 0 < t && t - 1 in e)
    }
    function I(e, t) {
        return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
    }
    r.fn = r.prototype = {
        jquery: xt,
        constructor: r,
        length: 0,
        toArray: function () {
            return J.call(this)
        },
        get: function (e) {
            return e == null ? J.call(this) : e < 0 ? this[e + this.length] : this[e]
        },
        pushStack: function (e) {
            var t = r.merge(this.constructor(), e);
            return t.prevObject = this,
                t
        },
        each: function (e) {
            return r.each(this, e)
        },
        map: function (e) {
            return this.pushStack(r.map(this, function (t, n) {
                return e.call(t, n, t)
            }))
        },
        slice: function () {
            return this.pushStack(J.apply(this, arguments))
        },
        first: function () {
            return this.eq(0)
        },
        last: function () {
            return this.eq(-1)
        },
        even: function () {
            return this.pushStack(r.grep(this, function (e, t) {
                return (t + 1) % 2
            }))
        },
        odd: function () {
            return this.pushStack(r.grep(this, function (e, t) {
                return t % 2
            }))
        },
        eq: function (e) {
            var t = this.length
                , n = +e + (e < 0 ? t : 0);
            return this.pushStack(0 <= n && n < t ? [this[n]] : [])
        },
        end: function () {
            return this.prevObject || this.constructor()
        },
        push: Ie,
        sort: U.sort,
        splice: U.splice
    },
        r.extend = r.fn.extend = function () {
            var e, t, n, i, o, a, u = arguments[0] || {}, f = 1, c = arguments.length, v = !1;
            for (typeof u == "boolean" && (v = u,
                u = arguments[f] || {},
                f++),
                typeof u == "object" || H(u) || (u = {}),
                f === c && (u = this,
                    f--); f < c; f++)
                if ((e = arguments[f]) != null)
                    for (t in e)
                        i = e[t],
                            t !== "__proto__" && u !== i && (v && i && (r.isPlainObject(i) || (o = Array.isArray(i))) ? (n = u[t],
                                a = o && !Array.isArray(n) ? [] : o || r.isPlainObject(n) ? n : {},
                                o = !1,
                                u[t] = r.extend(v, a, i)) : i !== void 0 && (u[t] = i));
            return u
        }
        ,
        r.extend({
            expando: "jQuery" + (xt + Math.random()).replace(/\D/g, ""),
            isReady: !0,
            error: function (e) {
                throw new Error(e)
            },
            noop: function () { },
            isPlainObject: function (e) {
                var t, n;
                return !(!e || mt.call(e) !== "[object Object]") && (!(t = gt(e)) || typeof (n = Le.call(t, "constructor") && t.constructor) == "function" && yt.call(n) === ln)
            },
            isEmptyObject: function (e) {
                var t;
                for (t in e)
                    return !1;
                return !0
            },
            globalEval: function (e, t, n) {
                bt(e, {
                    nonce: t && t.nonce
                }, n)
            },
            each: function (e, t) {
                var n, i = 0;
                if (Je(e))
                    for (n = e.length; i < n && t.call(e[i], i, e[i]) !== !1; i++)
                        ;
                else
                    for (i in e)
                        if (t.call(e[i], i, e[i]) === !1)
                            break;
                return e
            },
            text: function (e) {
                var t, n = "", i = 0, o = e.nodeType;
                if (!o)
                    for (; t = e[i++];)
                        n += r.text(t);
                return o === 1 || o === 11 ? e.textContent : o === 9 ? e.documentElement.textContent : o === 3 || o === 4 ? e.nodeValue : n
            },
            makeArray: function (e, t) {
                var n = t || [];
                return e != null && (Je(Object(e)) ? r.merge(n, typeof e == "string" ? [e] : e) : Ie.call(n, e)),
                    n
            },
            inArray: function (e, t, n) {
                return t == null ? -1 : ie.call(t, e, n)
            },
            isXMLDoc: function (e) {
                var t = e && e.namespaceURI
                    , n = e && (e.ownerDocument || e).documentElement;
                return !fn.test(t || n && n.nodeName || "HTML")
            },
            merge: function (e, t) {
                for (var n = +t.length, i = 0, o = e.length; i < n; i++)
                    e[o++] = t[i];
                return e.length = o,
                    e
            },
            grep: function (e, t, n) {
                for (var i = [], o = 0, a = e.length, u = !n; o < a; o++)
                    !t(e[o], o) !== u && i.push(e[o]);
                return i
            },
            map: function (e, t, n) {
                var i, o, a = 0, u = [];
                if (Je(e))
                    for (i = e.length; a < i; a++)
                        (o = t(e[a], a, n)) != null && u.push(o);
                else
                    for (a in e)
                        (o = t(e[a], a, n)) != null && u.push(o);
                return vt(u)
            },
            guid: 1,
            support: N
        }),
        typeof Symbol == "function" && (r.fn[Symbol.iterator] = U[Symbol.iterator]),
        r.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function (e, t) {
            We["[object " + t + "]"] = t.toLowerCase()
        });
    var dn = U.pop
        , pn = U.sort
        , hn = U.splice
        , M = "[\\x20\\t\\r\\n\\f]"
        , je = new RegExp("^" + M + "+|((?:^|[^\\\\])(?:\\\\.)*)" + M + "+$", "g");
    r.contains = function (e, t) {
        var n = t && t.parentNode;
        return e === n || !(!n || n.nodeType !== 1 || !(e.contains ? e.contains(n) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(n)))
    }
        ;
    var gn = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\x80-\uFFFF\w-]/g;
    function vn(e, t) {
        return t ? e === "\0" ? "\uFFFD" : e.slice(0, -1) + "\\" + e.charCodeAt(e.length - 1).toString(16) + " " : "\\" + e
    }
    r.escapeSelector = function (e) {
        return (e + "").replace(gn, vn)
    }
        ;
    var oe = D
        , Ze = Ie;
    (function () {
        var e, t, n, i, o, a, u, f, c, v, h = Ze, m = r.expando, C = 0, y = 0, L = Ve(), q = Ve(), B = Ve(), V = Ve(), F = function (s, l) {
            return s === l && (o = !0),
                0
        }, Q = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped", $ = "(?:\\\\[\\da-fA-F]{1,6}" + M + "?|\\\\[^\\r\\n\\f]|[\\w-]|[^\0-\\x7f])+", en = "\\[" + M + "*(" + $ + ")(?:" + M + "*([*^$|!~]?=)" + M + `*(?:'((?:\\\\.|[^\\\\'])*)'|"((?:\\\\.|[^\\\\"])*)"|(` + $ + "))|)" + M + "*\\]", tn = ":(" + $ + `)(?:\\((('((?:\\\\.|[^\\\\'])*)'|"((?:\\\\.|[^\\\\"])*)")|((?:\\\\.|[^\\\\()[\\]]|` + en + ")*)|.*)\\)|)", Un = new RegExp(M + "+", "g"), Xn = new RegExp("^" + M + "*," + M + "*"), nn = new RegExp("^" + M + "*([>+~]|" + M + ")" + M + "*"), Vn = new RegExp(M + "|>"), Qn = new RegExp(tn), Yn = new RegExp("^" + $ + "$"), Xe = {
            ID: new RegExp("^#(" + $ + ")"),
            CLASS: new RegExp("^\\.(" + $ + ")"),
            TAG: new RegExp("^(" + $ + "|[*])"),
            ATTR: new RegExp("^" + en),
            PSEUDO: new RegExp("^" + tn),
            CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + M + "*(even|odd|(([+-]|)(\\d*)n|)" + M + "*(?:([+-]|)" + M + "*(\\d+)|))" + M + "*\\)|)", "i"),
            bool: new RegExp("^(?:" + Q + ")$", "i"),
            needsContext: new RegExp("^" + M + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + M + "*((?:-\\d)?\\d*)" + M + "*\\)|)(?=[^-]|$)", "i")
        }, Gn = /^(?:input|select|textarea|button)$/i, Kn = /^h\d$/i, Jn = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, lt = /[+~]/, ue = new RegExp("\\\\[\\da-fA-F]{1,6}" + M + "?|\\\\([^\\r\\n\\f])", "g"), le = function (s, l) {
            var d = "0x" + s.slice(1) - 65536;
            return l || (d < 0 ? String.fromCharCode(d + 65536) : String.fromCharCode(d >> 10 | 55296, 1023 & d | 56320))
        }, Zn = function () {
            fe()
        }, er = Ye(function (s) {
            return s.disabled === !0 && I(s, "fieldset")
        }, {
            dir: "parentNode",
            next: "legend"
        });
        try {
            h.apply(U = J.call(oe.childNodes), oe.childNodes),
                U[oe.childNodes.length].nodeType
        } catch {
            h = {
                apply: function (l, d) {
                    Ze.apply(l, J.call(d))
                },
                call: function (l) {
                    Ze.apply(l, J.call(arguments, 1))
                }
            }
        }
        function R(s, l, d, p) {
            var g, x, w, T, b, O, S, A = l && l.ownerDocument, j = l ? l.nodeType : 9;
            if (d = d || [],
                typeof s != "string" || !s || j !== 1 && j !== 9 && j !== 11)
                return d;
            if (!p && (fe(l),
                l = l || a,
                f)) {
                if (j !== 11 && (b = Jn.exec(s)))
                    if (g = b[1]) {
                        if (j === 9) {
                            if (!(w = l.getElementById(g)))
                                return d;
                            if (w.id === g)
                                return h.call(d, w),
                                    d
                        } else if (A && (w = A.getElementById(g)) && R.contains(l, w) && w.id === g)
                            return h.call(d, w),
                                d
                    } else {
                        if (b[2])
                            return h.apply(d, l.getElementsByTagName(s)),
                                d;
                        if ((g = b[3]) && l.getElementsByClassName)
                            return h.apply(d, l.getElementsByClassName(g)),
                                d
                    }
                if (!(V[s + " "] || c && c.test(s))) {
                    if (S = s,
                        A = l,
                        j === 1 && (Vn.test(s) || nn.test(s))) {
                        for ((A = lt.test(s) && ct(l.parentNode) || l) == l && N.scope || ((T = l.getAttribute("id")) ? T = r.escapeSelector(T) : l.setAttribute("id", T = m)),
                            x = (O = Me(s)).length; x--;)
                            O[x] = (T ? "#" + T : ":scope") + " " + Qe(O[x]);
                        S = O.join(",")
                    }
                    try {
                        return h.apply(d, A.querySelectorAll(S)),
                            d
                    } catch {
                        V(s, !0)
                    } finally {
                        T === m && l.removeAttribute("id")
                    }
                }
            }
            return an(s.replace(je, "$1"), l, d, p)
        }
        function Ve() {
            var s = [];
            return function l(d, p) {
                return s.push(d + " ") > t.cacheLength && delete l[s.shift()],
                    l[d + " "] = p
            }
        }
        function te(s) {
            return s[m] = !0,
                s
        }
        function De(s) {
            var l = a.createElement("fieldset");
            try {
                return !!s(l)
            } catch {
                return !1
            } finally {
                l.parentNode && l.parentNode.removeChild(l),
                    l = null
            }
        }
        function tr(s) {
            return function (l) {
                return I(l, "input") && l.type === s
            }
        }
        function nr(s) {
            return function (l) {
                return (I(l, "input") || I(l, "button")) && l.type === s
            }
        }
        function rn(s) {
            return function (l) {
                return "form" in l ? l.parentNode && l.disabled === !1 ? "label" in l ? "label" in l.parentNode ? l.parentNode.disabled === s : l.disabled === s : l.isDisabled === s || l.isDisabled !== !s && er(l) === s : l.disabled === s : "label" in l && l.disabled === s
            }
        }
        function ve(s) {
            return te(function (l) {
                return l = +l,
                    te(function (d, p) {
                        for (var g, x = s([], d.length, l), w = x.length; w--;)
                            d[g = x[w]] && (d[g] = !(p[g] = d[g]))
                    })
            })
        }
        function ct(s) {
            return s && typeof s.getElementsByTagName < "u" && s
        }
        function fe(s) {
            var l, d = s ? s.ownerDocument || s : oe;
            return d != a && d.nodeType === 9 && d.documentElement && (u = (a = d).documentElement,
                f = !r.isXMLDoc(a),
                v = u.matches || u.webkitMatchesSelector || u.msMatchesSelector,
                u.msMatchesSelector && oe != a && (l = a.defaultView) && l.top !== l && l.addEventListener("unload", Zn),
                N.getById = De(function (p) {
                    return u.appendChild(p).id = r.expando,
                        !a.getElementsByName || !a.getElementsByName(r.expando).length
                }),
                N.disconnectedMatch = De(function (p) {
                    return v.call(p, "*")
                }),
                N.scope = De(function () {
                    return a.querySelectorAll(":scope")
                }),
                N.cssHas = De(function () {
                    try {
                        return a.querySelector(":has(*,:jqfake)"),
                            !1
                    } catch {
                        return !0
                    }
                }),
                N.getById ? (t.filter.ID = function (p) {
                    var g = p.replace(ue, le);
                    return function (x) {
                        return x.getAttribute("id") === g
                    }
                }
                    ,
                    t.find.ID = function (p, g) {
                        if (typeof g.getElementById < "u" && f) {
                            var x = g.getElementById(p);
                            return x ? [x] : []
                        }
                    }
                ) : (t.filter.ID = function (p) {
                    var g = p.replace(ue, le);
                    return function (x) {
                        var w = typeof x.getAttributeNode < "u" && x.getAttributeNode("id");
                        return w && w.value === g
                    }
                }
                    ,
                    t.find.ID = function (p, g) {
                        if (typeof g.getElementById < "u" && f) {
                            var x, w, T, b = g.getElementById(p);
                            if (b) {
                                if ((x = b.getAttributeNode("id")) && x.value === p)
                                    return [b];
                                for (T = g.getElementsByName(p),
                                    w = 0; b = T[w++];)
                                    if ((x = b.getAttributeNode("id")) && x.value === p)
                                        return [b]
                            }
                            return []
                        }
                    }
                ),
                t.find.TAG = function (p, g) {
                    return typeof g.getElementsByTagName < "u" ? g.getElementsByTagName(p) : g.querySelectorAll(p)
                }
                ,
                t.find.CLASS = function (p, g) {
                    if (typeof g.getElementsByClassName < "u" && f)
                        return g.getElementsByClassName(p)
                }
                ,
                c = [],
                De(function (p) {
                    var g;
                    u.appendChild(p).innerHTML = "<a id='" + m + "' href='' disabled='disabled'></a><select id='" + m + "-\r\\' disabled='disabled'><option selected=''></option></select>",
                        p.querySelectorAll("[selected]").length || c.push("\\[" + M + "*(?:value|" + Q + ")"),
                        p.querySelectorAll("[id~=" + m + "-]").length || c.push("~="),
                        p.querySelectorAll("a#" + m + "+*").length || c.push(".#.+[+~]"),
                        p.querySelectorAll(":checked").length || c.push(":checked"),
                        (g = a.createElement("input")).setAttribute("type", "hidden"),
                        p.appendChild(g).setAttribute("name", "D"),
                        u.appendChild(p).disabled = !0,
                        p.querySelectorAll(":disabled").length !== 2 && c.push(":enabled", ":disabled"),
                        (g = a.createElement("input")).setAttribute("name", ""),
                        p.appendChild(g),
                        p.querySelectorAll("[name='']").length || c.push("\\[" + M + "*name" + M + "*=" + M + `*(?:''|"")`)
                }),
                N.cssHas || c.push(":has"),
                c = c.length && new RegExp(c.join("|")),
                F = function (p, g) {
                    if (p === g)
                        return o = !0,
                            0;
                    var x = !p.compareDocumentPosition - !g.compareDocumentPosition;
                    return x || (1 & (x = (p.ownerDocument || p) == (g.ownerDocument || g) ? p.compareDocumentPosition(g) : 1) || !N.sortDetached && g.compareDocumentPosition(p) === x ? p === a || p.ownerDocument == oe && R.contains(oe, p) ? -1 : g === a || g.ownerDocument == oe && R.contains(oe, g) ? 1 : i ? ie.call(i, p) - ie.call(i, g) : 0 : 4 & x ? -1 : 1)
                }
            ),
                a
        }
        for (e in R.matches = function (s, l) {
            return R(s, null, null, l)
        }
            ,
            R.matchesSelector = function (s, l) {
                if (fe(s),
                    f && !V[l + " "] && (!c || !c.test(l)))
                    try {
                        var d = v.call(s, l);
                        if (d || N.disconnectedMatch || s.document && s.document.nodeType !== 11)
                            return d
                    } catch {
                        V(l, !0)
                    }
                return 0 < R(l, a, null, [s]).length
            }
            ,
            R.contains = function (s, l) {
                return (s.ownerDocument || s) != a && fe(s),
                    r.contains(s, l)
            }
            ,
            R.attr = function (s, l) {
                (s.ownerDocument || s) != a && fe(s);
                var d = t.attrHandle[l.toLowerCase()]
                    , p = d && Le.call(t.attrHandle, l.toLowerCase()) ? d(s, l, !f) : void 0;
                return p !== void 0 ? p : s.getAttribute(l)
            }
            ,
            R.error = function (s) {
                throw new Error("Syntax error, unrecognized expression: " + s)
            }
            ,
            r.uniqueSort = function (s) {
                var l, d = [], p = 0, g = 0;
                if (o = !N.sortStable,
                    i = !N.sortStable && J.call(s, 0),
                    pn.call(s, F),
                    o) {
                    for (; l = s[g++];)
                        l === s[g] && (p = d.push(g));
                    for (; p--;)
                        hn.call(s, d[p], 1)
                }
                return i = null,
                    s
            }
            ,
            r.fn.uniqueSort = function () {
                return this.pushStack(r.uniqueSort(J.apply(this)))
            }
            ,
            (t = r.expr = {
                cacheLength: 50,
                createPseudo: te,
                match: Xe,
                attrHandle: {},
                find: {},
                relative: {
                    ">": {
                        dir: "parentNode",
                        first: !0
                    },
                    " ": {
                        dir: "parentNode"
                    },
                    "+": {
                        dir: "previousSibling",
                        first: !0
                    },
                    "~": {
                        dir: "previousSibling"
                    }
                },
                preFilter: {
                    ATTR: function (s) {
                        return s[1] = s[1].replace(ue, le),
                            s[3] = (s[3] || s[4] || s[5] || "").replace(ue, le),
                            s[2] === "~=" && (s[3] = " " + s[3] + " "),
                            s.slice(0, 4)
                    },
                    CHILD: function (s) {
                        return s[1] = s[1].toLowerCase(),
                            s[1].slice(0, 3) === "nth" ? (s[3] || R.error(s[0]),
                                s[4] = +(s[4] ? s[5] + (s[6] || 1) : 2 * (s[3] === "even" || s[3] === "odd")),
                                s[5] = +(s[7] + s[8] || s[3] === "odd")) : s[3] && R.error(s[0]),
                            s
                    },
                    PSEUDO: function (s) {
                        var l, d = !s[6] && s[2];
                        return Xe.CHILD.test(s[0]) ? null : (s[3] ? s[2] = s[4] || s[5] || "" : d && Qn.test(d) && (l = Me(d, !0)) && (l = d.indexOf(")", d.length - l) - d.length) && (s[0] = s[0].slice(0, l),
                            s[2] = d.slice(0, l)),
                            s.slice(0, 3))
                    }
                },
                filter: {
                    TAG: function (s) {
                        var l = s.replace(ue, le).toLowerCase();
                        return s === "*" ? function () {
                            return !0
                        }
                            : function (d) {
                                return I(d, l)
                            }
                    },
                    CLASS: function (s) {
                        var l = L[s + " "];
                        return l || (l = new RegExp("(^|" + M + ")" + s + "(" + M + "|$)")) && L(s, function (d) {
                            return l.test(typeof d.className == "string" && d.className || typeof d.getAttribute < "u" && d.getAttribute("class") || "")
                        })
                    },
                    ATTR: function (s, l, d) {
                        return function (p) {
                            var g = R.attr(p, s);
                            return g == null ? l === "!=" : !l || (g += "",
                                l === "=" ? g === d : l === "!=" ? g !== d : l === "^=" ? d && g.indexOf(d) === 0 : l === "*=" ? d && -1 < g.indexOf(d) : l === "$=" ? d && g.slice(-d.length) === d : l === "~=" ? -1 < (" " + g.replace(Un, " ") + " ").indexOf(d) : l === "|=" && (g === d || g.slice(0, d.length + 1) === d + "-"))
                        }
                    },
                    CHILD: function (s, l, d, p, g) {
                        var x = s.slice(0, 3) !== "nth"
                            , w = s.slice(-4) !== "last"
                            , T = l === "of-type";
                        return p === 1 && g === 0 ? function (b) {
                            return !!b.parentNode
                        }
                            : function (b, O, S) {
                                var A, j, k, _, X, z = x !== w ? "nextSibling" : "previousSibling", K = b.parentNode, Z = T && b.nodeName.toLowerCase(), ne = !S && !T, W = !1;
                                if (K) {
                                    if (x) {
                                        for (; z;) {
                                            for (k = b; k = k[z];)
                                                if (T ? I(k, Z) : k.nodeType === 1)
                                                    return !1;
                                            X = z = s === "only" && !X && "nextSibling"
                                        }
                                        return !0
                                    }
                                    if (X = [w ? K.firstChild : K.lastChild],
                                        w && ne) {
                                        for (W = (_ = (A = (j = K[m] || (K[m] = {}))[s] || [])[0] === C && A[1]) && A[2],
                                            k = _ && K.childNodes[_]; k = ++_ && k && k[z] || (W = _ = 0) || X.pop();)
                                            if (k.nodeType === 1 && ++W && k === b) {
                                                j[s] = [C, _, W];
                                                break
                                            }
                                    } else if (ne && (W = _ = (A = (j = b[m] || (b[m] = {}))[s] || [])[0] === C && A[1]),
                                        W === !1)
                                        for (; (k = ++_ && k && k[z] || (W = _ = 0) || X.pop()) && !((T ? I(k, Z) : k.nodeType === 1) && ++W && (ne && ((j = k[m] || (k[m] = {}))[s] = [C, W]),
                                            k === b));)
                                            ;
                                    return (W -= g) === p || W % p == 0 && 0 <= W / p
                                }
                            }
                    },
                    PSEUDO: function (s, l) {
                        var d, p = t.pseudos[s] || t.setFilters[s.toLowerCase()] || R.error("unsupported pseudo: " + s);
                        return p[m] ? p(l) : 1 < p.length ? (d = [s, s, "", l],
                            t.setFilters.hasOwnProperty(s.toLowerCase()) ? te(function (g, x) {
                                for (var w, T = p(g, l), b = T.length; b--;)
                                    g[w = ie.call(g, T[b])] = !(x[w] = T[b])
                            }) : function (g) {
                                return p(g, 0, d)
                            }
                        ) : p
                    }
                },
                pseudos: {
                    not: te(function (s) {
                        var l = []
                            , d = []
                            , p = ht(s.replace(je, "$1"));
                        return p[m] ? te(function (g, x, w, T) {
                            for (var b, O = p(g, null, T, []), S = g.length; S--;)
                                (b = O[S]) && (g[S] = !(x[S] = b))
                        }) : function (g, x, w) {
                            return l[0] = g,
                                p(l, null, w, d),
                                l[0] = null,
                                !d.pop()
                        }
                    }),
                    has: te(function (s) {
                        return function (l) {
                            return 0 < R(s, l).length
                        }
                    }),
                    contains: te(function (s) {
                        return s = s.replace(ue, le),
                            function (l) {
                                return -1 < (l.textContent || r.text(l)).indexOf(s)
                            }
                    }),
                    lang: te(function (s) {
                        return Yn.test(s || "") || R.error("unsupported lang: " + s),
                            s = s.replace(ue, le).toLowerCase(),
                            function (l) {
                                var d;
                                do
                                    if (d = f ? l.lang : l.getAttribute("xml:lang") || l.getAttribute("lang"))
                                        return (d = d.toLowerCase()) === s || d.indexOf(s + "-") === 0;
                                while ((l = l.parentNode) && l.nodeType === 1);
                                return !1
                            }
                    }),
                    target: function (s) {
                        var l = P.location && P.location.hash;
                        return l && l.slice(1) === s.id
                    },
                    root: function (s) {
                        return s === u
                    },
                    focus: function (s) {
                        return s === function () {
                            try {
                                return a.activeElement
                            } catch { }
                        }() && a.hasFocus() && !!(s.type || s.href || ~s.tabIndex)
                    },
                    enabled: rn(!1),
                    disabled: rn(!0),
                    checked: function (s) {
                        return I(s, "input") && !!s.checked || I(s, "option") && !!s.selected
                    },
                    selected: function (s) {
                        return s.parentNode && s.parentNode.selectedIndex,
                            s.selected === !0
                    },
                    empty: function (s) {
                        for (s = s.firstChild; s; s = s.nextSibling)
                            if (s.nodeType < 6)
                                return !1;
                        return !0
                    },
                    parent: function (s) {
                        return !t.pseudos.empty(s)
                    },
                    header: function (s) {
                        return Kn.test(s.nodeName)
                    },
                    input: function (s) {
                        return Gn.test(s.nodeName)
                    },
                    button: function (s) {
                        return I(s, "input") && s.type === "button" || I(s, "button")
                    },
                    text: function (s) {
                        var l;
                        return I(s, "input") && s.type === "text" && ((l = s.getAttribute("type")) == null || l.toLowerCase() === "text")
                    },
                    first: ve(function () {
                        return [0]
                    }),
                    last: ve(function (s, l) {
                        return [l - 1]
                    }),
                    eq: ve(function (s, l, d) {
                        return [d < 0 ? d + l : d]
                    }),
                    even: ve(function (s, l) {
                        for (var d = 0; d < l; d += 2)
                            s.push(d);
                        return s
                    }),
                    odd: ve(function (s, l) {
                        for (var d = 1; d < l; d += 2)
                            s.push(d);
                        return s
                    }),
                    lt: ve(function (s, l, d) {
                        var p;
                        for (p = d < 0 ? d + l : l < d ? l : d; 0 <= --p;)
                            s.push(p);
                        return s
                    }),
                    gt: ve(function (s, l, d) {
                        for (var p = d < 0 ? d + l : d; ++p < l;)
                            s.push(p);
                        return s
                    })
                }
            }).pseudos.nth = t.pseudos.eq,
        {
            radio: !0,
            checkbox: !0,
            file: !0,
            password: !0,
            image: !0
        })
            t.pseudos[e] = tr(e);
        for (e in {
            submit: !0,
            reset: !0
        })
            t.pseudos[e] = nr(e);
        function on() { }
        function Me(s, l) {
            var d, p, g, x, w, T, b, O = q[s + " "];
            if (O)
                return l ? 0 : O.slice(0);
            for (w = s,
                T = [],
                b = t.preFilter; w;) {
                for (x in d && !(p = Xn.exec(w)) || (p && (w = w.slice(p[0].length) || w),
                    T.push(g = [])),
                    d = !1,
                    (p = nn.exec(w)) && (d = p.shift(),
                        g.push({
                            value: d,
                            type: p[0].replace(je, " ")
                        }),
                        w = w.slice(d.length)),
                    t.filter)
                    !(p = Xe[x].exec(w)) || b[x] && !(p = b[x](p)) || (d = p.shift(),
                        g.push({
                            value: d,
                            type: x,
                            matches: p
                        }),
                        w = w.slice(d.length));
                if (!d)
                    break
            }
            return l ? w.length : w ? R.error(s) : q(s, T).slice(0)
        }
        function Qe(s) {
            for (var l = 0, d = s.length, p = ""; l < d; l++)
                p += s[l].value;
            return p
        }
        function Ye(s, l, d) {
            var p = l.dir
                , g = l.next
                , x = g || p
                , w = d && x === "parentNode"
                , T = y++;
            return l.first ? function (b, O, S) {
                for (; b = b[p];)
                    if (b.nodeType === 1 || w)
                        return s(b, O, S);
                return !1
            }
                : function (b, O, S) {
                    var A, j, k = [C, T];
                    if (S) {
                        for (; b = b[p];)
                            if ((b.nodeType === 1 || w) && s(b, O, S))
                                return !0
                    } else
                        for (; b = b[p];)
                            if (b.nodeType === 1 || w)
                                if (j = b[m] || (b[m] = {}),
                                    g && I(b, g))
                                    b = b[p] || b;
                                else {
                                    if ((A = j[x]) && A[0] === C && A[1] === T)
                                        return k[2] = A[2];
                                    if ((j[x] = k)[2] = s(b, O, S))
                                        return !0
                                }
                    return !1
                }
        }
        function ft(s) {
            return 1 < s.length ? function (l, d, p) {
                for (var g = s.length; g--;)
                    if (!s[g](l, d, p))
                        return !1;
                return !0
            }
                : s[0]
        }
        function Ge(s, l, d, p, g) {
            for (var x, w = [], T = 0, b = s.length, O = l != null; T < b; T++)
                (x = s[T]) && (d && !d(x, p, g) || (w.push(x),
                    O && l.push(T)));
            return w
        }
        function dt(s, l, d, p, g, x) {
            return p && !p[m] && (p = dt(p)),
                g && !g[m] && (g = dt(g, x)),
                te(function (w, T, b, O) {
                    var S, A, j, k, _ = [], X = [], z = T.length, K = w || function (ne, W, me) {
                        for (var re = 0, Ke = W.length; re < Ke; re++)
                            R(ne, W[re], me);
                        return me
                    }(l || "*", b.nodeType ? [b] : b, []), Z = !s || !w && l ? K : Ge(K, _, s, b, O);
                    if (d ? d(Z, k = g || (w ? s : z || p) ? [] : T, b, O) : k = Z,
                        p)
                        for (S = Ge(k, X),
                            p(S, [], b, O),
                            A = S.length; A--;)
                            (j = S[A]) && (k[X[A]] = !(Z[X[A]] = j));
                    if (w) {
                        if (g || s) {
                            if (g) {
                                for (S = [],
                                    A = k.length; A--;)
                                    (j = k[A]) && S.push(Z[A] = j);
                                g(null, k = [], S, O)
                            }
                            for (A = k.length; A--;)
                                (j = k[A]) && -1 < (S = g ? ie.call(w, j) : _[A]) && (w[S] = !(T[S] = j))
                        }
                    } else
                        k = Ge(k === T ? k.splice(z, k.length) : k),
                            g ? g(null, T, k, O) : h.apply(T, k)
                })
        }
        function pt(s) {
            for (var l, d, p, g = s.length, x = t.relative[s[0].type], w = x || t.relative[" "], T = x ? 1 : 0, b = Ye(function (A) {
                return A === l
            }, w, !0), O = Ye(function (A) {
                return -1 < ie.call(l, A)
            }, w, !0), S = [function (A, j, k) {
                var _ = !x && (k || j != n) || ((l = j).nodeType ? b(A, j, k) : O(A, j, k));
                return l = null,
                    _
            }
            ]; T < g; T++)
                if (d = t.relative[s[T].type])
                    S = [Ye(ft(S), d)];
                else {
                    if ((d = t.filter[s[T].type].apply(null, s[T].matches))[m]) {
                        for (p = ++T; p < g && !t.relative[s[p].type]; p++)
                            ;
                        return dt(1 < T && ft(S), 1 < T && Qe(s.slice(0, T - 1).concat({
                            value: s[T - 2].type === " " ? "*" : ""
                        })).replace(je, "$1"), d, T < p && pt(s.slice(T, p)), p < g && pt(s = s.slice(p)), p < g && Qe(s))
                    }
                    S.push(d)
                }
            return ft(S)
        }
        function ht(s, l) {
            var d, p, g, x, w, T, b = [], O = [], S = B[s + " "];
            if (!S) {
                for (l || (l = Me(s)),
                    d = l.length; d--;)
                    (S = pt(l[d]))[m] ? b.push(S) : O.push(S);
                (S = B(s, (p = O,
                    x = 0 < (g = b).length,
                    w = 0 < p.length,
                    T = function (A, j, k, _, X) {
                        var z, K, Z, ne = 0, W = "0", me = A && [], re = [], Ke = n, sn = A || w && t.find.TAG("*", X), un = C += Ke == null ? 1 : Math.random() || .1, rr = sn.length;
                        for (X && (n = j == a || j || X); W !== rr && (z = sn[W]) != null; W++) {
                            if (w && z) {
                                for (K = 0,
                                    j || z.ownerDocument == a || (fe(z),
                                        k = !f); Z = p[K++];)
                                    if (Z(z, j || a, k)) {
                                        h.call(_, z);
                                        break
                                    }
                                X && (C = un)
                            }
                            x && ((z = !Z && z) && ne--,
                                A && me.push(z))
                        }
                        if (ne += W,
                            x && W !== ne) {
                            for (K = 0; Z = g[K++];)
                                Z(me, re, j, k);
                            if (A) {
                                if (0 < ne)
                                    for (; W--;)
                                        me[W] || re[W] || (re[W] = dn.call(_));
                                re = Ge(re)
                            }
                            h.apply(_, re),
                                X && !A && 0 < re.length && 1 < ne + g.length && r.uniqueSort(_)
                        }
                        return X && (C = un,
                            n = Ke),
                            me
                    }
                    ,
                    x ? te(T) : T))).selector = s
            }
            return S
        }
        function an(s, l, d, p) {
            var g, x, w, T, b, O = typeof s == "function" && s, S = !p && Me(s = O.selector || s);
            if (d = d || [],
                S.length === 1) {
                if (2 < (x = S[0] = S[0].slice(0)).length && (w = x[0]).type === "ID" && l.nodeType === 9 && f && t.relative[x[1].type]) {
                    if (!(l = (t.find.ID(w.matches[0].replace(ue, le), l) || [])[0]))
                        return d;
                    O && (l = l.parentNode),
                        s = s.slice(x.shift().value.length)
                }
                for (g = Xe.needsContext.test(s) ? 0 : x.length; g-- && (w = x[g],
                    !t.relative[T = w.type]);)
                    if ((b = t.find[T]) && (p = b(w.matches[0].replace(ue, le), lt.test(x[0].type) && ct(l.parentNode) || l))) {
                        if (x.splice(g, 1),
                            !(s = p.length && Qe(x)))
                            return h.apply(d, p),
                                d;
                        break
                    }
            }
            return (O || ht(s, S))(p, l, !f, d, !l || lt.test(s) && ct(l.parentNode) || l),
                d
        }
        on.prototype = t.filters = t.pseudos,
            t.setFilters = new on,
            N.sortStable = m.split("").sort(F).join("") === m,
            fe(),
            N.sortDetached = De(function (s) {
                return 1 & s.compareDocumentPosition(a.createElement("fieldset"))
            }),
            r.find = R,
            r.expr[":"] = r.expr.pseudos,
            r.unique = r.uniqueSort,
            R.compile = ht,
            R.select = an,
            R.setDocument = fe,
            R.tokenize = Me,
            R.escape = r.escapeSelector,
            R.getText = r.text,
            R.isXML = r.isXMLDoc,
            R.selectors = r.expr,
            R.support = r.support,
            R.uniqueSort = r.uniqueSort
    }
    )();
    var xe = function (e, t, n) {
        for (var i = [], o = n !== void 0; (e = e[t]) && e.nodeType !== 9;)
            if (e.nodeType === 1) {
                if (o && r(e).is(n))
                    break;
                i.push(e)
            }
        return i
    }
        , wt = function (e, t) {
            for (var n = []; e; e = e.nextSibling)
                e.nodeType === 1 && e !== t && n.push(e);
            return n
        }
        , Ct = r.expr.match.needsContext
        , Tt = /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;
    function et(e, t, n) {
        return H(t) ? r.grep(e, function (i, o) {
            return !!t.call(i, o, i) !== n
        }) : t.nodeType ? r.grep(e, function (i) {
            return i === t !== n
        }) : typeof t != "string" ? r.grep(e, function (i) {
            return -1 < ie.call(t, i) !== n
        }) : r.filter(t, e, n)
    }
    r.filter = function (e, t, n) {
        var i = t[0];
        return n && (e = ":not(" + e + ")"),
            t.length === 1 && i.nodeType === 1 ? r.find.matchesSelector(i, e) ? [i] : [] : r.find.matches(e, r.grep(t, function (o) {
                return o.nodeType === 1
            }))
    }
        ,
        r.fn.extend({
            find: function (e) {
                var t, n, i = this.length, o = this;
                if (typeof e != "string")
                    return this.pushStack(r(e).filter(function () {
                        for (t = 0; t < i; t++)
                            if (r.contains(o[t], this))
                                return !0
                    }));
                for (n = this.pushStack([]),
                    t = 0; t < i; t++)
                    r.find(e, o[t], n);
                return 1 < i ? r.uniqueSort(n) : n
            },
            filter: function (e) {
                return this.pushStack(et(this, e || [], !1))
            },
            not: function (e) {
                return this.pushStack(et(this, e || [], !0))
            },
            is: function (e) {
                return !!et(this, typeof e == "string" && Ct.test(e) ? r(e) : e || [], !1).length
            }
        });
    var Et, mn = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/;
    (r.fn.init = function (e, t, n) {
        var i, o;
        if (!e)
            return this;
        if (n = n || Et,
            typeof e == "string") {
            if (!(i = e[0] === "<" && e[e.length - 1] === ">" && 3 <= e.length ? [null, e, null] : mn.exec(e)) || !i[1] && t)
                return !t || t.jquery ? (t || n).find(e) : this.constructor(t).find(e);
            if (i[1]) {
                if (t = t instanceof r ? t[0] : t,
                    r.merge(this, r.parseHTML(i[1], t && t.nodeType ? t.ownerDocument || t : D, !0)),
                    Tt.test(i[1]) && r.isPlainObject(t))
                    for (i in t)
                        H(this[i]) ? this[i](t[i]) : this.attr(i, t[i]);
                return this
            }
            return (o = D.getElementById(i[2])) && (this[0] = o,
                this.length = 1),
                this
        }
        return e.nodeType ? (this[0] = e,
            this.length = 1,
            this) : H(e) ? n.ready !== void 0 ? n.ready(e) : e(r) : r.makeArray(e, this)
    }
    ).prototype = r.fn,
        Et = r(D);
    var yn = /^(?:parents|prev(?:Until|All))/
        , bn = {
            children: !0,
            contents: !0,
            next: !0,
            prev: !0
        };
    function kt(e, t) {
        for (; (e = e[t]) && e.nodeType !== 1;)
            ;
        return e
    }
    r.fn.extend({
        has: function (e) {
            var t = r(e, this)
                , n = t.length;
            return this.filter(function () {
                for (var i = 0; i < n; i++)
                    if (r.contains(this, t[i]))
                        return !0
            })
        },
        closest: function (e, t) {
            var n, i = 0, o = this.length, a = [], u = typeof e != "string" && r(e);
            if (!Ct.test(e)) {
                for (; i < o; i++)
                    for (n = this[i]; n && n !== t; n = n.parentNode)
                        if (n.nodeType < 11 && (u ? -1 < u.index(n) : n.nodeType === 1 && r.find.matchesSelector(n, e))) {
                            a.push(n);
                            break
                        }
            }
            return this.pushStack(1 < a.length ? r.uniqueSort(a) : a)
        },
        index: function (e) {
            return e ? typeof e == "string" ? ie.call(r(e), this[0]) : ie.call(this, e.jquery ? e[0] : e) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        },
        add: function (e, t) {
            return this.pushStack(r.uniqueSort(r.merge(this.get(), r(e, t))))
        },
        addBack: function (e) {
            return this.add(e == null ? this.prevObject : this.prevObject.filter(e))
        }
    }),
        r.each({
            parent: function (e) {
                var t = e.parentNode;
                return t && t.nodeType !== 11 ? t : null
            },
            parents: function (e) {
                return xe(e, "parentNode")
            },
            parentsUntil: function (e, t, n) {
                return xe(e, "parentNode", n)
            },
            next: function (e) {
                return kt(e, "nextSibling")
            },
            prev: function (e) {
                return kt(e, "previousSibling")
            },
            nextAll: function (e) {
                return xe(e, "nextSibling")
            },
            prevAll: function (e) {
                return xe(e, "previousSibling")
            },
            nextUntil: function (e, t, n) {
                return xe(e, "nextSibling", n)
            },
            prevUntil: function (e, t, n) {
                return xe(e, "previousSibling", n)
            },
            siblings: function (e) {
                return wt((e.parentNode || {}).firstChild, e)
            },
            children: function (e) {
                return wt(e.firstChild)
            },
            contents: function (e) {
                return e.contentDocument != null && gt(e.contentDocument) ? e.contentDocument : (I(e, "template") && (e = e.content || e),
                    r.merge([], e.childNodes))
            }
        }, function (e, t) {
            r.fn[e] = function (n, i) {
                var o = r.map(this, t, n);
                return e.slice(-5) !== "Until" && (i = n),
                    i && typeof i == "string" && (o = r.filter(i, o)),
                    1 < this.length && (bn[e] || r.uniqueSort(o),
                        yn.test(e) && o.reverse()),
                    this.pushStack(o)
            }
        });
    var de = /[^\x20\t\r\n\f]+/g;
    function we(e) {
        return e
    }
    function Be(e) {
        throw e
    }
    function St(e, t, n, i) {
        var o;
        try {
            e && H(o = e.promise) ? o.call(e).done(t).fail(n) : e && H(o = e.then) ? o.call(e, t, n) : t.apply(void 0, [e].slice(i))
        } catch (a) {
            n.apply(void 0, [a])
        }
    }
    r.Callbacks = function (e) {
        var t, n;
        e = typeof e == "string" ? (t = e,
            n = {},
            r.each(t.match(de) || [], function (C, y) {
                n[y] = !0
            }),
            n) : r.extend({}, e);
        var i, o, a, u, f = [], c = [], v = -1, h = function () {
            for (u = u || e.once,
                a = i = !0; c.length; v = -1)
                for (o = c.shift(); ++v < f.length;)
                    f[v].apply(o[0], o[1]) === !1 && e.stopOnFalse && (v = f.length,
                        o = !1);
            e.memory || (o = !1),
                i = !1,
                u && (f = o ? [] : "")
        }, m = {
            add: function () {
                return f && (o && !i && (v = f.length - 1,
                    c.push(o)),
                    function C(y) {
                        r.each(y, function (L, q) {
                            H(q) ? e.unique && m.has(q) || f.push(q) : q && q.length && be(q) !== "string" && C(q)
                        })
                    }(arguments),
                    o && !i && h()),
                    this
            },
            remove: function () {
                return r.each(arguments, function (C, y) {
                    for (var L; -1 < (L = r.inArray(y, f, L));)
                        f.splice(L, 1),
                            L <= v && v--
                }),
                    this
            },
            has: function (C) {
                return C ? -1 < r.inArray(C, f) : 0 < f.length
            },
            empty: function () {
                return f && (f = []),
                    this
            },
            disable: function () {
                return u = c = [],
                    f = o = "",
                    this
            },
            disabled: function () {
                return !f
            },
            lock: function () {
                return u = c = [],
                    o || i || (f = o = ""),
                    this
            },
            locked: function () {
                return !!u
            },
            fireWith: function (C, y) {
                return u || (y = [C, (y = y || []).slice ? y.slice() : y],
                    c.push(y),
                    i || h()),
                    this
            },
            fire: function () {
                return m.fireWith(this, arguments),
                    this
            },
            fired: function () {
                return !!a
            }
        };
        return m
    }
        ,
        r.extend({
            Deferred: function (e) {
                var t = [["notify", "progress", r.Callbacks("memory"), r.Callbacks("memory"), 2], ["resolve", "done", r.Callbacks("once memory"), r.Callbacks("once memory"), 0, "resolved"], ["reject", "fail", r.Callbacks("once memory"), r.Callbacks("once memory"), 1, "rejected"]]
                    , n = "pending"
                    , i = {
                        state: function () {
                            return n
                        },
                        always: function () {
                            return o.done(arguments).fail(arguments),
                                this
                        },
                        catch: function (a) {
                            return i.then(null, a)
                        },
                        pipe: function () {
                            var a = arguments;
                            return r.Deferred(function (u) {
                                r.each(t, function (f, c) {
                                    var v = H(a[c[4]]) && a[c[4]];
                                    o[c[1]](function () {
                                        var h = v && v.apply(this, arguments);
                                        h && H(h.promise) ? h.promise().progress(u.notify).done(u.resolve).fail(u.reject) : u[c[0] + "With"](this, v ? [h] : arguments)
                                    })
                                }),
                                    a = null
                            }).promise()
                        },
                        then: function (a, u, f) {
                            var c = 0;
                            function v(h, m, C, y) {
                                return function () {
                                    var L = this
                                        , q = arguments
                                        , B = function () {
                                            var F, Q;
                                            if (!(h < c)) {
                                                if ((F = C.apply(L, q)) === m.promise())
                                                    throw new TypeError("Thenable self-resolution");
                                                Q = F && (typeof F == "object" || typeof F == "function") && F.then,
                                                    H(Q) ? y ? Q.call(F, v(c, m, we, y), v(c, m, Be, y)) : (c++,
                                                        Q.call(F, v(c, m, we, y), v(c, m, Be, y), v(c, m, we, m.notifyWith))) : (C !== we && (L = void 0,
                                                            q = [F]),
                                                            (y || m.resolveWith)(L, q))
                                            }
                                        }
                                        , V = y ? B : function () {
                                            try {
                                                B()
                                            } catch (F) {
                                                r.Deferred.exceptionHook && r.Deferred.exceptionHook(F, V.error),
                                                    c <= h + 1 && (C !== Be && (L = void 0,
                                                        q = [F]),
                                                        m.rejectWith(L, q))
                                            }
                                        }
                                        ;
                                    h ? V() : (r.Deferred.getErrorHook ? V.error = r.Deferred.getErrorHook() : r.Deferred.getStackHook && (V.error = r.Deferred.getStackHook()),
                                        P.setTimeout(V))
                                }
                            }
                            return r.Deferred(function (h) {
                                t[0][3].add(v(0, h, H(f) ? f : we, h.notifyWith)),
                                    t[1][3].add(v(0, h, H(a) ? a : we)),
                                    t[2][3].add(v(0, h, H(u) ? u : Be))
                            }).promise()
                        },
                        promise: function (a) {
                            return a != null ? r.extend(a, i) : i
                        }
                    }
                    , o = {};
                return r.each(t, function (a, u) {
                    var f = u[2]
                        , c = u[5];
                    i[u[1]] = f.add,
                        c && f.add(function () {
                            n = c
                        }, t[3 - a][2].disable, t[3 - a][3].disable, t[0][2].lock, t[0][3].lock),
                        f.add(u[3].fire),
                        o[u[0]] = function () {
                            return o[u[0] + "With"](this === o ? void 0 : this, arguments),
                                this
                        }
                        ,
                        o[u[0] + "With"] = f.fireWith
                }),
                    i.promise(o),
                    e && e.call(o, o),
                    o
            },
            when: function (e) {
                var t = arguments.length
                    , n = t
                    , i = Array(n)
                    , o = J.call(arguments)
                    , a = r.Deferred()
                    , u = function (f) {
                        return function (c) {
                            i[f] = this,
                                o[f] = 1 < arguments.length ? J.call(arguments) : c,
                                --t || a.resolveWith(i, o)
                        }
                    };
                if (t <= 1 && (St(e, a.done(u(n)).resolve, a.reject, !t),
                    a.state() === "pending" || H(o[n] && o[n].then)))
                    return a.then();
                for (; n--;)
                    St(o[n], u(n), a.reject);
                return a.promise()
            }
        });
    var xn = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;
    r.Deferred.exceptionHook = function (e, t) {
        P.console && P.console.warn && e && xn.test(e.name) && P.console.warn("jQuery.Deferred exception: " + e.message, e.stack, t)
    }
        ,
        r.readyException = function (e) {
            P.setTimeout(function () {
                throw e
            })
        }
        ;
    var tt = r.Deferred();
    function Fe() {
        D.removeEventListener("DOMContentLoaded", Fe),
            P.removeEventListener("load", Fe),
            r.ready()
    }
    r.fn.ready = function (e) {
        return tt.then(e).catch(function (t) {
            r.readyException(t)
        }),
            this
    }
        ,
        r.extend({
            isReady: !1,
            readyWait: 1,
            ready: function (e) {
                (e === !0 ? --r.readyWait : r.isReady) || (r.isReady = !0) !== e && 0 < --r.readyWait || tt.resolveWith(D, [r])
            }
        }),
        r.ready.then = tt.then,
        D.readyState === "complete" || D.readyState !== "loading" && !D.documentElement.doScroll ? P.setTimeout(r.ready) : (D.addEventListener("DOMContentLoaded", Fe),
            P.addEventListener("load", Fe));
    var ae = function (e, t, n, i, o, a, u) {
        var f = 0
            , c = e.length
            , v = n == null;
        if (be(n) === "object")
            for (f in o = !0,
                n)
                ae(e, t, f, n[f], !0, a, u);
        else if (i !== void 0 && (o = !0,
            H(i) || (u = !0),
            v && (u ? (t.call(e, i),
                t = null) : (v = t,
                    t = function (h, m, C) {
                        return v.call(r(h), C)
                    }
            )),
            t))
            for (; f < c; f++)
                t(e[f], n, u ? i : i.call(e[f], f, t(e[f], n)));
        return o ? e : v ? t.call(e) : c ? t(e[0], n) : a
    }
        , wn = /^-ms-/
        , Cn = /-([a-z])/g;
    function Tn(e, t) {
        return t.toUpperCase()
    }
    function se(e) {
        return e.replace(wn, "ms-").replace(Cn, Tn)
    }
    var Oe = function (e) {
        return e.nodeType === 1 || e.nodeType === 9 || !+e.nodeType
    };
    function Pe() {
        this.expando = r.expando + Pe.uid++
    }
    Pe.uid = 1,
        Pe.prototype = {
            cache: function (e) {
                var t = e[this.expando];
                return t || (t = {},
                    Oe(e) && (e.nodeType ? e[this.expando] = t : Object.defineProperty(e, this.expando, {
                        value: t,
                        configurable: !0
                    }))),
                    t
            },
            set: function (e, t, n) {
                var i, o = this.cache(e);
                if (typeof t == "string")
                    o[se(t)] = n;
                else
                    for (i in t)
                        o[se(i)] = t[i];
                return o
            },
            get: function (e, t) {
                return t === void 0 ? this.cache(e) : e[this.expando] && e[this.expando][se(t)]
            },
            access: function (e, t, n) {
                return t === void 0 || t && typeof t == "string" && n === void 0 ? this.get(e, t) : (this.set(e, t, n),
                    n !== void 0 ? n : t)
            },
            remove: function (e, t) {
                var n, i = e[this.expando];
                if (i !== void 0) {
                    if (t !== void 0)
                        for (n = (t = Array.isArray(t) ? t.map(se) : (t = se(t)) in i ? [t] : t.match(de) || []).length; n--;)
                            delete i[t[n]];
                    (t === void 0 || r.isEmptyObject(i)) && (e.nodeType ? e[this.expando] = void 0 : delete e[this.expando])
                }
            },
            hasData: function (e) {
                var t = e[this.expando];
                return t !== void 0 && !r.isEmptyObject(t)
            }
        };
    var E = new Pe
        , Y = new Pe
        , En = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/
        , kn = /[A-Z]/g;
    function At(e, t, n) {
        var i, o;
        if (n === void 0 && e.nodeType === 1)
            if (i = "data-" + t.replace(kn, "-$&").toLowerCase(),
                typeof (n = e.getAttribute(i)) == "string") {
                try {
                    n = (o = n) === "true" || o !== "false" && (o === "null" ? null : o === +o + "" ? +o : En.test(o) ? JSON.parse(o) : o)
                } catch { }
                Y.set(e, t, n)
            } else
                n = void 0;
        return n
    }
    r.extend({
        hasData: function (e) {
            return Y.hasData(e) || E.hasData(e)
        },
        data: function (e, t, n) {
            return Y.access(e, t, n)
        },
        removeData: function (e, t) {
            Y.remove(e, t)
        },
        _data: function (e, t, n) {
            return E.access(e, t, n)
        },
        _removeData: function (e, t) {
            E.remove(e, t)
        }
    }),
        r.fn.extend({
            data: function (e, t) {
                var n, i, o, a = this[0], u = a && a.attributes;
                if (e === void 0) {
                    if (this.length && (o = Y.get(a),
                        a.nodeType === 1 && !E.get(a, "hasDataAttrs"))) {
                        for (n = u.length; n--;)
                            u[n] && (i = u[n].name).indexOf("data-") === 0 && (i = se(i.slice(5)),
                                At(a, i, o[i]));
                        E.set(a, "hasDataAttrs", !0)
                    }
                    return o
                }
                return typeof e == "object" ? this.each(function () {
                    Y.set(this, e)
                }) : ae(this, function (f) {
                    var c;
                    if (a && f === void 0)
                        return (c = Y.get(a, e)) !== void 0 || (c = At(a, e)) !== void 0 ? c : void 0;
                    this.each(function () {
                        Y.set(this, e, f)
                    })
                }, null, t, 1 < arguments.length, null, !0)
            },
            removeData: function (e) {
                return this.each(function () {
                    Y.remove(this, e)
                })
            }
        }),
        r.extend({
            queue: function (e, t, n) {
                var i;
                if (e)
                    return t = (t || "fx") + "queue",
                        i = E.get(e, t),
                        n && (!i || Array.isArray(n) ? i = E.access(e, t, r.makeArray(n)) : i.push(n)),
                        i || []
            },
            dequeue: function (e, t) {
                t = t || "fx";
                var n = r.queue(e, t)
                    , i = n.length
                    , o = n.shift()
                    , a = r._queueHooks(e, t);
                o === "inprogress" && (o = n.shift(),
                    i--),
                    o && (t === "fx" && n.unshift("inprogress"),
                        delete a.stop,
                        o.call(e, function () {
                            r.dequeue(e, t)
                        }, a)),
                    !i && a && a.empty.fire()
            },
            _queueHooks: function (e, t) {
                var n = t + "queueHooks";
                return E.get(e, n) || E.access(e, n, {
                    empty: r.Callbacks("once memory").add(function () {
                        E.remove(e, [t + "queue", n])
                    })
                })
            }
        }),
        r.fn.extend({
            queue: function (e, t) {
                var n = 2;
                return typeof e != "string" && (t = e,
                    e = "fx",
                    n--),
                    arguments.length < n ? r.queue(this[0], e) : t === void 0 ? this : this.each(function () {
                        var i = r.queue(this, e, t);
                        r._queueHooks(this, e),
                            e === "fx" && i[0] !== "inprogress" && r.dequeue(this, e)
                    })
            },
            dequeue: function (e) {
                return this.each(function () {
                    r.dequeue(this, e)
                })
            },
            clearQueue: function (e) {
                return this.queue(e || "fx", [])
            },
            promise: function (e, t) {
                var n, i = 1, o = r.Deferred(), a = this, u = this.length, f = function () {
                    --i || o.resolveWith(a, [a])
                };
                for (typeof e != "string" && (t = e,
                    e = void 0),
                    e = e || "fx"; u--;)
                    (n = E.get(a[u], e + "queueHooks")) && n.empty && (i++,
                        n.empty.add(f));
                return f(),
                    o.promise(t)
            }
        });
    var Dt = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source
        , $e = new RegExp("^(?:([+-])=|)(" + Dt + ")([a-z%]*)$", "i")
        , ce = ["Top", "Right", "Bottom", "Left"]
        , pe = D.documentElement
        , Ce = function (e) {
            return r.contains(e.ownerDocument, e)
        }
        , Sn = {
            composed: !0
        };
    pe.getRootNode && (Ce = function (e) {
        return r.contains(e.ownerDocument, e) || e.getRootNode(Sn) === e.ownerDocument
    }
    );
    var Nt = function (e, t) {
        return (e = t || e).style.display === "none" || e.style.display === "" && Ce(e) && r.css(e, "display") === "none"
    }
        , Lt = {};
    function jt(e, t) {
        for (var n, i, o, a, u, f, c, v = [], h = 0, m = e.length; h < m; h++)
            (i = e[h]).style && (n = i.style.display,
                t ? (n === "none" && (v[h] = E.get(i, "display") || null,
                    v[h] || (i.style.display = "")),
                    i.style.display === "" && Nt(i) && (v[h] = (c = u = a = void 0,
                        u = (o = i).ownerDocument,
                        f = o.nodeName,
                        (c = Lt[f]) || (a = u.body.appendChild(u.createElement(f)),
                            c = r.css(a, "display"),
                            a.parentNode.removeChild(a),
                            c === "none" && (c = "block"),
                            Lt[f] = c)))) : n !== "none" && (v[h] = "none",
                                E.set(i, "display", n)));
        for (h = 0; h < m; h++)
            v[h] != null && (e[h].style.display = v[h]);
        return e
    }
    r.fn.extend({
        show: function () {
            return jt(this, !0)
        },
        hide: function () {
            return jt(this)
        },
        toggle: function (e) {
            return typeof e == "boolean" ? e ? this.show() : this.hide() : this.each(function () {
                Nt(this) ? r(this).show() : r(this).hide()
            })
        }
    });
    var he, _e, He = /^(?:checkbox|radio)$/i, Ot = /<([a-z][^\/\0>\x20\t\r\n\f]*)/i, Pt = /^$|^module$|\/(?:java|ecma)script/i;
    he = D.createDocumentFragment().appendChild(D.createElement("div")),
        (_e = D.createElement("input")).setAttribute("type", "radio"),
        _e.setAttribute("checked", "checked"),
        _e.setAttribute("name", "t"),
        he.appendChild(_e),
        N.checkClone = he.cloneNode(!0).cloneNode(!0).lastChild.checked,
        he.innerHTML = "<textarea>x</textarea>",
        N.noCloneChecked = !!he.cloneNode(!0).lastChild.defaultValue,
        he.innerHTML = "<option></option>",
        N.option = !!he.lastChild;
    var ee = {
        thead: [1, "<table>", "</table>"],
        col: [2, "<table><colgroup>", "</colgroup></table>"],
        tr: [2, "<table><tbody>", "</tbody></table>"],
        td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        _default: [0, "", ""]
    };
    function G(e, t) {
        var n;
        return n = typeof e.getElementsByTagName < "u" ? e.getElementsByTagName(t || "*") : typeof e.querySelectorAll < "u" ? e.querySelectorAll(t || "*") : [],
            t === void 0 || t && I(e, t) ? r.merge([e], n) : n
    }
    function nt(e, t) {
        for (var n = 0, i = e.length; n < i; n++)
            E.set(e[n], "globalEval", !t || E.get(t[n], "globalEval"))
    }
    ee.tbody = ee.tfoot = ee.colgroup = ee.caption = ee.thead,
        ee.th = ee.td,
        N.option || (ee.optgroup = ee.option = [1, "<select multiple='multiple'>", "</select>"]);
    var An = /<|&#?\w+;/;
    function Ht(e, t, n, i, o) {
        for (var a, u, f, c, v, h, m = t.createDocumentFragment(), C = [], y = 0, L = e.length; y < L; y++)
            if ((a = e[y]) || a === 0)
                if (be(a) === "object")
                    r.merge(C, a.nodeType ? [a] : a);
                else if (An.test(a)) {
                    for (u = u || m.appendChild(t.createElement("div")),
                        f = (Ot.exec(a) || ["", ""])[1].toLowerCase(),
                        c = ee[f] || ee._default,
                        u.innerHTML = c[1] + r.htmlPrefilter(a) + c[2],
                        h = c[0]; h--;)
                        u = u.lastChild;
                    r.merge(C, u.childNodes),
                        (u = m.firstChild).textContent = ""
                } else
                    C.push(t.createTextNode(a));
        for (m.textContent = "",
            y = 0; a = C[y++];)
            if (i && -1 < r.inArray(a, i))
                o && o.push(a);
            else if (v = Ce(a),
                u = G(m.appendChild(a), "script"),
                v && nt(u),
                n)
                for (h = 0; a = u[h++];)
                    Pt.test(a.type || "") && n.push(a);
        return m
    }
    var qt = /^([^.]*)(?:\.(.+)|)/;
    function Te() {
        return !0
    }
    function Ee() {
        return !1
    }
    function rt(e, t, n, i, o, a) {
        var u, f;
        if (typeof t == "object") {
            for (f in typeof n != "string" && (i = i || n,
                n = void 0),
                t)
                rt(e, f, n, i, t[f], a);
            return e
        }
        if (i == null && o == null ? (o = n,
            i = n = void 0) : o == null && (typeof n == "string" ? (o = i,
                i = void 0) : (o = i,
                    i = n,
                    n = void 0)),
            o === !1)
            o = Ee;
        else if (!o)
            return e;
        return a === 1 && (u = o,
            (o = function (c) {
                return r().off(c),
                    u.apply(this, arguments)
            }
            ).guid = u.guid || (u.guid = r.guid++)),
            e.each(function () {
                r.event.add(this, t, o, i, n)
            })
    }
    function ze(e, t, n) {
        n ? (E.set(e, t, !1),
            r.event.add(e, t, {
                namespace: !1,
                handler: function (i) {
                    var o, a = E.get(this, t);
                    if (1 & i.isTrigger && this[t]) {
                        if (a)
                            (r.event.special[t] || {}).delegateType && i.stopPropagation();
                        else if (a = J.call(arguments),
                            E.set(this, t, a),
                            this[t](),
                            o = E.get(this, t),
                            E.set(this, t, !1),
                            a !== o)
                            return i.stopImmediatePropagation(),
                                i.preventDefault(),
                                o
                    } else
                        a && (E.set(this, t, r.event.trigger(a[0], a.slice(1), this)),
                            i.stopPropagation(),
                            i.isImmediatePropagationStopped = Te)
                }
            })) : E.get(e, t) === void 0 && r.event.add(e, t, Te)
    }
    r.event = {
        global: {},
        add: function (e, t, n, i, o) {
            var a, u, f, c, v, h, m, C, y, L, q, B = E.get(e);
            if (Oe(e))
                for (n.handler && (n = (a = n).handler,
                    o = a.selector),
                    o && r.find.matchesSelector(pe, o),
                    n.guid || (n.guid = r.guid++),
                    (c = B.events) || (c = B.events = Object.create(null)),
                    (u = B.handle) || (u = B.handle = function (V) {
                        return typeof r < "u" && r.event.triggered !== V.type ? r.event.dispatch.apply(e, arguments) : void 0
                    }
                    ),
                    v = (t = (t || "").match(de) || [""]).length; v--;)
                    y = q = (f = qt.exec(t[v]) || [])[1],
                        L = (f[2] || "").split(".").sort(),
                        y && (m = r.event.special[y] || {},
                            y = (o ? m.delegateType : m.bindType) || y,
                            m = r.event.special[y] || {},
                            h = r.extend({
                                type: y,
                                origType: q,
                                data: i,
                                handler: n,
                                guid: n.guid,
                                selector: o,
                                needsContext: o && r.expr.match.needsContext.test(o),
                                namespace: L.join(".")
                            }, a),
                            (C = c[y]) || ((C = c[y] = []).delegateCount = 0,
                                m.setup && m.setup.call(e, i, L, u) !== !1 || e.addEventListener && e.addEventListener(y, u)),
                            m.add && (m.add.call(e, h),
                                h.handler.guid || (h.handler.guid = n.guid)),
                            o ? C.splice(C.delegateCount++, 0, h) : C.push(h),
                            r.event.global[y] = !0)
        },
        remove: function (e, t, n, i, o) {
            var a, u, f, c, v, h, m, C, y, L, q, B = E.hasData(e) && E.get(e);
            if (B && (c = B.events)) {
                for (v = (t = (t || "").match(de) || [""]).length; v--;)
                    if (y = q = (f = qt.exec(t[v]) || [])[1],
                        L = (f[2] || "").split(".").sort(),
                        y) {
                        for (m = r.event.special[y] || {},
                            C = c[y = (i ? m.delegateType : m.bindType) || y] || [],
                            f = f[2] && new RegExp("(^|\\.)" + L.join("\\.(?:.*\\.|)") + "(\\.|$)"),
                            u = a = C.length; a--;)
                            h = C[a],
                                !o && q !== h.origType || n && n.guid !== h.guid || f && !f.test(h.namespace) || i && i !== h.selector && (i !== "**" || !h.selector) || (C.splice(a, 1),
                                    h.selector && C.delegateCount--,
                                    m.remove && m.remove.call(e, h));
                        u && !C.length && (m.teardown && m.teardown.call(e, L, B.handle) !== !1 || r.removeEvent(e, y, B.handle),
                            delete c[y])
                    } else
                        for (y in c)
                            r.event.remove(e, y + t[v], n, i, !0);
                r.isEmptyObject(c) && E.remove(e, "handle events")
            }
        },
        dispatch: function (e) {
            var t, n, i, o, a, u, f = new Array(arguments.length), c = r.event.fix(e), v = (E.get(this, "events") || Object.create(null))[c.type] || [], h = r.event.special[c.type] || {};
            for (f[0] = c,
                t = 1; t < arguments.length; t++)
                f[t] = arguments[t];
            if (c.delegateTarget = this,
                !h.preDispatch || h.preDispatch.call(this, c) !== !1) {
                for (u = r.event.handlers.call(this, c, v),
                    t = 0; (o = u[t++]) && !c.isPropagationStopped();)
                    for (c.currentTarget = o.elem,
                        n = 0; (a = o.handlers[n++]) && !c.isImmediatePropagationStopped();)
                        c.rnamespace && a.namespace !== !1 && !c.rnamespace.test(a.namespace) || (c.handleObj = a,
                            c.data = a.data,
                            (i = ((r.event.special[a.origType] || {}).handle || a.handler).apply(o.elem, f)) !== void 0 && (c.result = i) === !1 && (c.preventDefault(),
                                c.stopPropagation()));
                return h.postDispatch && h.postDispatch.call(this, c),
                    c.result
            }
        },
        handlers: function (e, t) {
            var n, i, o, a, u, f = [], c = t.delegateCount, v = e.target;
            if (c && v.nodeType && !(e.type === "click" && 1 <= e.button)) {
                for (; v !== this; v = v.parentNode || this)
                    if (v.nodeType === 1 && (e.type !== "click" || v.disabled !== !0)) {
                        for (a = [],
                            u = {},
                            n = 0; n < c; n++)
                            u[o = (i = t[n]).selector + " "] === void 0 && (u[o] = i.needsContext ? -1 < r(o, this).index(v) : r.find(o, this, null, [v]).length),
                                u[o] && a.push(i);
                        a.length && f.push({
                            elem: v,
                            handlers: a
                        })
                    }
            }
            return v = this,
                c < t.length && f.push({
                    elem: v,
                    handlers: t.slice(c)
                }),
                f
        },
        addProp: function (e, t) {
            Object.defineProperty(r.Event.prototype, e, {
                enumerable: !0,
                configurable: !0,
                get: H(t) ? function () {
                    if (this.originalEvent)
                        return t(this.originalEvent)
                }
                    : function () {
                        if (this.originalEvent)
                            return this.originalEvent[e]
                    }
                ,
                set: function (n) {
                    Object.defineProperty(this, e, {
                        enumerable: !0,
                        configurable: !0,
                        writable: !0,
                        value: n
                    })
                }
            })
        },
        fix: function (e) {
            return e[r.expando] ? e : new r.Event(e)
        },
        special: {
            load: {
                noBubble: !0
            },
            click: {
                setup: function (e) {
                    var t = this || e;
                    return He.test(t.type) && t.click && I(t, "input") && ze(t, "click", !0),
                        !1
                },
                trigger: function (e) {
                    var t = this || e;
                    return He.test(t.type) && t.click && I(t, "input") && ze(t, "click"),
                        !0
                },
                _default: function (e) {
                    var t = e.target;
                    return He.test(t.type) && t.click && I(t, "input") && E.get(t, "click") || I(t, "a")
                }
            },
            beforeunload: {
                postDispatch: function (e) {
                    e.result !== void 0 && e.originalEvent && (e.originalEvent.returnValue = e.result)
                }
            }
        }
    },
        r.removeEvent = function (e, t, n) {
            e.removeEventListener && e.removeEventListener(t, n)
        }
        ,
        r.Event = function (e, t) {
            if (!(this instanceof r.Event))
                return new r.Event(e, t);
            e && e.type ? (this.originalEvent = e,
                this.type = e.type,
                this.isDefaultPrevented = e.defaultPrevented || e.defaultPrevented === void 0 && e.returnValue === !1 ? Te : Ee,
                this.target = e.target && e.target.nodeType === 3 ? e.target.parentNode : e.target,
                this.currentTarget = e.currentTarget,
                this.relatedTarget = e.relatedTarget) : this.type = e,
                t && r.extend(this, t),
                this.timeStamp = e && e.timeStamp || Date.now(),
                this[r.expando] = !0
        }
        ,
        r.Event.prototype = {
            constructor: r.Event,
            isDefaultPrevented: Ee,
            isPropagationStopped: Ee,
            isImmediatePropagationStopped: Ee,
            isSimulated: !1,
            preventDefault: function () {
                var e = this.originalEvent;
                this.isDefaultPrevented = Te,
                    e && !this.isSimulated && e.preventDefault()
            },
            stopPropagation: function () {
                var e = this.originalEvent;
                this.isPropagationStopped = Te,
                    e && !this.isSimulated && e.stopPropagation()
            },
            stopImmediatePropagation: function () {
                var e = this.originalEvent;
                this.isImmediatePropagationStopped = Te,
                    e && !this.isSimulated && e.stopImmediatePropagation(),
                    this.stopPropagation()
            }
        },
        r.each({
            altKey: !0,
            bubbles: !0,
            cancelable: !0,
            changedTouches: !0,
            ctrlKey: !0,
            detail: !0,
            eventPhase: !0,
            metaKey: !0,
            pageX: !0,
            pageY: !0,
            shiftKey: !0,
            view: !0,
            char: !0,
            code: !0,
            charCode: !0,
            key: !0,
            keyCode: !0,
            button: !0,
            buttons: !0,
            clientX: !0,
            clientY: !0,
            offsetX: !0,
            offsetY: !0,
            pointerId: !0,
            pointerType: !0,
            screenX: !0,
            screenY: !0,
            targetTouches: !0,
            toElement: !0,
            touches: !0,
            which: !0
        }, r.event.addProp),
        r.each({
            focus: "focusin",
            blur: "focusout"
        }, function (e, t) {
            function n(i) {
                if (D.documentMode) {
                    var o = E.get(this, "handle")
                        , a = r.event.fix(i);
                    a.type = i.type === "focusin" ? "focus" : "blur",
                        a.isSimulated = !0,
                        o(i),
                        a.target === a.currentTarget && o(a)
                } else
                    r.event.simulate(t, i.target, r.event.fix(i))
            }
            r.event.special[e] = {
                setup: function () {
                    var i;
                    if (ze(this, e, !0),
                        !D.documentMode)
                        return !1;
                    (i = E.get(this, t)) || this.addEventListener(t, n),
                        E.set(this, t, (i || 0) + 1)
                },
                trigger: function () {
                    return ze(this, e),
                        !0
                },
                teardown: function () {
                    var i;
                    if (!D.documentMode)
                        return !1;
                    (i = E.get(this, t) - 1) ? E.set(this, t, i) : (this.removeEventListener(t, n),
                        E.remove(this, t))
                },
                _default: function (i) {
                    return E.get(i.target, e)
                },
                delegateType: t
            },
                r.event.special[t] = {
                    setup: function () {
                        var i = this.ownerDocument || this.document || this
                            , o = D.documentMode ? this : i
                            , a = E.get(o, t);
                        a || (D.documentMode ? this.addEventListener(t, n) : i.addEventListener(e, n, !0)),
                            E.set(o, t, (a || 0) + 1)
                    },
                    teardown: function () {
                        var i = this.ownerDocument || this.document || this
                            , o = D.documentMode ? this : i
                            , a = E.get(o, t) - 1;
                        a ? E.set(o, t, a) : (D.documentMode ? this.removeEventListener(t, n) : i.removeEventListener(e, n, !0),
                            E.remove(o, t))
                    }
                }
        }),
        r.each({
            mouseenter: "mouseover",
            mouseleave: "mouseout",
            pointerenter: "pointerover",
            pointerleave: "pointerout"
        }, function (e, t) {
            r.event.special[e] = {
                delegateType: t,
                bindType: t,
                handle: function (n) {
                    var i, o = n.relatedTarget, a = n.handleObj;
                    return o && (o === this || r.contains(this, o)) || (n.type = a.origType,
                        i = a.handler.apply(this, arguments),
                        n.type = t),
                        i
                }
            }
        }),
        r.fn.extend({
            on: function (e, t, n, i) {
                return rt(this, e, t, n, i)
            },
            one: function (e, t, n, i) {
                return rt(this, e, t, n, i, 1)
            },
            off: function (e, t, n) {
                var i, o;
                if (e && e.preventDefault && e.handleObj)
                    return i = e.handleObj,
                        r(e.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace : i.origType, i.selector, i.handler),
                        this;
                if (typeof e == "object") {
                    for (o in e)
                        this.off(o, t, e[o]);
                    return this
                }
                return t !== !1 && typeof t != "function" || (n = t,
                    t = void 0),
                    n === !1 && (n = Ee),
                    this.each(function () {
                        r.event.remove(this, e, n, t)
                    })
            }
        });
    var Dn = /<script|<style|<link/i
        , Nn = /checked\s*(?:[^=]|=\s*.checked.)/i
        , Ln = /^\s*<!\[CDATA\[|\]\]>\s*$/g;
    function Rt(e, t) {
        return I(e, "table") && I(t.nodeType !== 11 ? t : t.firstChild, "tr") && r(e).children("tbody")[0] || e
    }
    function jn(e) {
        return e.type = (e.getAttribute("type") !== null) + "/" + e.type,
            e
    }
    function On(e) {
        return (e.type || "").slice(0, 5) === "true/" ? e.type = e.type.slice(5) : e.removeAttribute("type"),
            e
    }
    function Mt(e, t) {
        var n, i, o, a, u, f;
        if (t.nodeType === 1) {
            if (E.hasData(e) && (f = E.get(e).events))
                for (o in E.remove(t, "handle events"),
                    f)
                    for (n = 0,
                        i = f[o].length; n < i; n++)
                        r.event.add(t, o, f[o][n]);
            Y.hasData(e) && (a = Y.access(e),
                u = r.extend({}, a),
                Y.set(t, u))
        }
    }
    function ke(e, t, n, i) {
        t = vt(t);
        var o, a, u, f, c, v, h = 0, m = e.length, C = m - 1, y = t[0], L = H(y);
        if (L || 1 < m && typeof y == "string" && !N.checkClone && Nn.test(y))
            return e.each(function (q) {
                var B = e.eq(q);
                L && (t[0] = y.call(this, q, B.html())),
                    ke(B, t, n, i)
            });
        if (m && (a = (o = Ht(t, e[0].ownerDocument, !1, e, i)).firstChild,
            o.childNodes.length === 1 && (o = a),
            a || i)) {
            for (f = (u = r.map(G(o, "script"), jn)).length; h < m; h++)
                c = o,
                    h !== C && (c = r.clone(c, !0, !0),
                        f && r.merge(u, G(c, "script"))),
                    n.call(e[h], c, h);
            if (f)
                for (v = u[u.length - 1].ownerDocument,
                    r.map(u, On),
                    h = 0; h < f; h++)
                    c = u[h],
                        Pt.test(c.type || "") && !E.access(c, "globalEval") && r.contains(v, c) && (c.src && (c.type || "").toLowerCase() !== "module" ? r._evalUrl && !c.noModule && r._evalUrl(c.src, {
                            nonce: c.nonce || c.getAttribute("nonce")
                        }, v) : bt(c.textContent.replace(Ln, ""), c, v))
        }
        return e
    }
    function It(e, t, n) {
        for (var i, o = t ? r.filter(t, e) : e, a = 0; (i = o[a]) != null; a++)
            n || i.nodeType !== 1 || r.cleanData(G(i)),
                i.parentNode && (n && Ce(i) && nt(G(i, "script")),
                    i.parentNode.removeChild(i));
        return e
    }
    r.extend({
        htmlPrefilter: function (e) {
            return e
        },
        clone: function (e, t, n) {
            var i, o, a, u, f, c, v, h = e.cloneNode(!0), m = Ce(e);
            if (!(N.noCloneChecked || e.nodeType !== 1 && e.nodeType !== 11 || r.isXMLDoc(e)))
                for (u = G(h),
                    i = 0,
                    o = (a = G(e)).length; i < o; i++)
                    f = a[i],
                        c = u[i],
                        (v = c.nodeName.toLowerCase()) === "input" && He.test(f.type) ? c.checked = f.checked : v !== "input" && v !== "textarea" || (c.defaultValue = f.defaultValue);
            if (t)
                if (n)
                    for (a = a || G(e),
                        u = u || G(h),
                        i = 0,
                        o = a.length; i < o; i++)
                        Mt(a[i], u[i]);
                else
                    Mt(e, h);
            return 0 < (u = G(h, "script")).length && nt(u, !m && G(e, "script")),
                h
        },
        cleanData: function (e) {
            for (var t, n, i, o = r.event.special, a = 0; (n = e[a]) !== void 0; a++)
                if (Oe(n)) {
                    if (t = n[E.expando]) {
                        if (t.events)
                            for (i in t.events)
                                o[i] ? r.event.remove(n, i) : r.removeEvent(n, i, t.handle);
                        n[E.expando] = void 0
                    }
                    n[Y.expando] && (n[Y.expando] = void 0)
                }
        }
    }),
        r.fn.extend({
            detach: function (e) {
                return It(this, e, !0)
            },
            remove: function (e) {
                return It(this, e)
            },
            text: function (e) {
                return ae(this, function (t) {
                    return t === void 0 ? r.text(this) : this.empty().each(function () {
                        this.nodeType !== 1 && this.nodeType !== 11 && this.nodeType !== 9 || (this.textContent = t)
                    })
                }, null, e, arguments.length)
            },
            append: function () {
                return ke(this, arguments, function (e) {
                    this.nodeType !== 1 && this.nodeType !== 11 && this.nodeType !== 9 || Rt(this, e).appendChild(e)
                })
            },
            prepend: function () {
                return ke(this, arguments, function (e) {
                    if (this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9) {
                        var t = Rt(this, e);
                        t.insertBefore(e, t.firstChild)
                    }
                })
            },
            before: function () {
                return ke(this, arguments, function (e) {
                    this.parentNode && this.parentNode.insertBefore(e, this)
                })
            },
            after: function () {
                return ke(this, arguments, function (e) {
                    this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
                })
            },
            empty: function () {
                for (var e, t = 0; (e = this[t]) != null; t++)
                    e.nodeType === 1 && (r.cleanData(G(e, !1)),
                        e.textContent = "");
                return this
            },
            clone: function (e, t) {
                return e = e != null && e,
                    t = t ?? e,
                    this.map(function () {
                        return r.clone(this, e, t)
                    })
            },
            html: function (e) {
                return ae(this, function (t) {
                    var n = this[0] || {}
                        , i = 0
                        , o = this.length;
                    if (t === void 0 && n.nodeType === 1)
                        return n.innerHTML;
                    if (typeof t == "string" && !Dn.test(t) && !ee[(Ot.exec(t) || ["", ""])[1].toLowerCase()]) {
                        t = r.htmlPrefilter(t);
                        try {
                            for (; i < o; i++)
                                (n = this[i] || {}).nodeType === 1 && (r.cleanData(G(n, !1)),
                                    n.innerHTML = t);
                            n = 0
                        } catch { }
                    }
                    n && this.empty().append(t)
                }, null, e, arguments.length)
            },
            replaceWith: function () {
                var e = [];
                return ke(this, arguments, function (t) {
                    var n = this.parentNode;
                    r.inArray(this, e) < 0 && (r.cleanData(G(this)),
                        n && n.replaceChild(t, this))
                }, e)
            }
        }),
        r.each({
            appendTo: "append",
            prependTo: "prepend",
            insertBefore: "before",
            insertAfter: "after",
            replaceAll: "replaceWith"
        }, function (e, t) {
            r.fn[e] = function (n) {
                for (var i, o = [], a = r(n), u = a.length - 1, f = 0; f <= u; f++)
                    i = f === u ? this : this.clone(!0),
                        r(a[f])[t](i),
                        Ie.apply(o, i.get());
                return this.pushStack(o)
            }
        });
    var it = new RegExp("^(" + Dt + ")(?!px)[a-z%]+$", "i")
        , ot = /^--/
        , Ue = function (e) {
            var t = e.ownerDocument.defaultView;
            return t && t.opener || (t = P),
                t.getComputedStyle(e)
        }
        , Wt = function (e, t, n) {
            var i, o, a = {};
            for (o in t)
                a[o] = e.style[o],
                    e.style[o] = t[o];
            for (o in i = n.call(e),
                t)
                e.style[o] = a[o];
            return i
        }
        , Pn = new RegExp(ce.join("|"), "i");
    function qe(e, t, n) {
        var i, o, a, u, f = ot.test(t), c = e.style;
        return (n = n || Ue(e)) && (u = n.getPropertyValue(t) || n[t],
            f && u && (u = u.replace(je, "$1") || void 0),
            u !== "" || Ce(e) || (u = r.style(e, t)),
            !N.pixelBoxStyles() && it.test(u) && Pn.test(t) && (i = c.width,
                o = c.minWidth,
                a = c.maxWidth,
                c.minWidth = c.maxWidth = c.width = u,
                u = n.width,
                c.width = i,
                c.minWidth = o,
                c.maxWidth = a)),
            u !== void 0 ? u + "" : u
    }
    function Bt(e, t) {
        return {
            get: function () {
                if (!e())
                    return (this.get = t).apply(this, arguments);
                delete this.get
            }
        }
    }
    (function () {
        function e() {
            if (v) {
                c.style.cssText = "position:absolute;left:-11111px;width:60px;margin-top:1px;padding:0;border:0",
                    v.style.cssText = "position:relative;display:block;box-sizing:border-box;overflow:scroll;margin:auto;border:1px;padding:1px;width:60%;top:1%",
                    pe.appendChild(c).appendChild(v);
                var h = P.getComputedStyle(v);
                n = h.top !== "1%",
                    f = t(h.marginLeft) === 12,
                    v.style.right = "60%",
                    a = t(h.right) === 36,
                    i = t(h.width) === 36,
                    v.style.position = "absolute",
                    o = t(v.offsetWidth / 3) === 12,
                    pe.removeChild(c),
                    v = null
            }
        }
        function t(h) {
            return Math.round(parseFloat(h))
        }
        var n, i, o, a, u, f, c = D.createElement("div"), v = D.createElement("div");
        v.style && (v.style.backgroundClip = "content-box",
            v.cloneNode(!0).style.backgroundClip = "",
            N.clearCloneStyle = v.style.backgroundClip === "content-box",
            r.extend(N, {
                boxSizingReliable: function () {
                    return e(),
                        i
                },
                pixelBoxStyles: function () {
                    return e(),
                        a
                },
                pixelPosition: function () {
                    return e(),
                        n
                },
                reliableMarginLeft: function () {
                    return e(),
                        f
                },
                scrollboxSize: function () {
                    return e(),
                        o
                },
                reliableTrDimensions: function () {
                    var h, m, C, y;
                    return u == null && (h = D.createElement("table"),
                        m = D.createElement("tr"),
                        C = D.createElement("div"),
                        h.style.cssText = "position:absolute;left:-11111px;border-collapse:separate",
                        m.style.cssText = "box-sizing:content-box;border:1px solid",
                        m.style.height = "1px",
                        C.style.height = "9px",
                        C.style.display = "block",
                        pe.appendChild(h).appendChild(m).appendChild(C),
                        y = P.getComputedStyle(m),
                        u = parseInt(y.height, 10) + parseInt(y.borderTopWidth, 10) + parseInt(y.borderBottomWidth, 10) === m.offsetHeight,
                        pe.removeChild(h)),
                        u
                }
            }))
    }
    )();
    var Ft = ["Webkit", "Moz", "ms"]
        , $t = D.createElement("div").style
        , _t = {};
    function zt(e) {
        var t = r.cssProps[e] || _t[e];
        return t || (e in $t ? e : _t[e] = function (n) {
            for (var i = n[0].toUpperCase() + n.slice(1), o = Ft.length; o--;)
                if ((n = Ft[o] + i) in $t)
                    return n
        }(e) || e)
    }
    var Se, Ut, Hn = /^(none|table(?!-c[ea]).+)/, qn = {
        position: "absolute",
        visibility: "hidden",
        display: "block"
    }, Xt = {
        letterSpacing: "0",
        fontWeight: "400"
    };
    function Vt(e, t, n) {
        var i = $e.exec(t);
        return i ? Math.max(0, i[2] - (n || 0)) + (i[3] || "px") : t
    }
    function at(e, t, n, i, o, a) {
        var u = t === "width" ? 1 : 0
            , f = 0
            , c = 0
            , v = 0;
        if (n === (i ? "border" : "content"))
            return 0;
        for (; u < 4; u += 2)
            n === "margin" && (v += r.css(e, n + ce[u], !0, o)),
                i ? (n === "content" && (c -= r.css(e, "padding" + ce[u], !0, o)),
                    n !== "margin" && (c -= r.css(e, "border" + ce[u] + "Width", !0, o))) : (c += r.css(e, "padding" + ce[u], !0, o),
                        n !== "padding" ? c += r.css(e, "border" + ce[u] + "Width", !0, o) : f += r.css(e, "border" + ce[u] + "Width", !0, o));
        return !i && 0 <= a && (c += Math.max(0, Math.ceil(e["offset" + t[0].toUpperCase() + t.slice(1)] - a - c - f - .5)) || 0),
            c + v
    }
    function Qt(e, t, n) {
        var i = Ue(e)
            , o = (!N.boxSizingReliable() || n) && r.css(e, "boxSizing", !1, i) === "border-box"
            , a = o
            , u = qe(e, t, i)
            , f = "offset" + t[0].toUpperCase() + t.slice(1);
        if (it.test(u)) {
            if (!n)
                return u;
            u = "auto"
        }
        return (!N.boxSizingReliable() && o || !N.reliableTrDimensions() && I(e, "tr") || u === "auto" || !parseFloat(u) && r.css(e, "display", !1, i) === "inline") && e.getClientRects().length && (o = r.css(e, "boxSizing", !1, i) === "border-box",
            (a = f in e) && (u = e[f])),
            (u = parseFloat(u) || 0) + at(e, t, n || (o ? "border" : "content"), a, i, u) + "px"
    }
    r.extend({
        cssHooks: {
            opacity: {
                get: function (e, t) {
                    if (t) {
                        var n = qe(e, "opacity");
                        return n === "" ? "1" : n
                    }
                }
            }
        },
        cssNumber: {
            animationIterationCount: !0,
            aspectRatio: !0,
            borderImageSlice: !0,
            columnCount: !0,
            flexGrow: !0,
            flexShrink: !0,
            fontWeight: !0,
            gridArea: !0,
            gridColumn: !0,
            gridColumnEnd: !0,
            gridColumnStart: !0,
            gridRow: !0,
            gridRowEnd: !0,
            gridRowStart: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            scale: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0,
            fillOpacity: !0,
            floodOpacity: !0,
            stopOpacity: !0,
            strokeMiterlimit: !0,
            strokeOpacity: !0
        },
        cssProps: {},
        style: function (e, t, n, i) {
            if (e && e.nodeType !== 3 && e.nodeType !== 8 && e.style) {
                var o, a, u, f = se(t), c = ot.test(t), v = e.style;
                if (c || (t = zt(f)),
                    u = r.cssHooks[t] || r.cssHooks[f],
                    n === void 0)
                    return u && "get" in u && (o = u.get(e, !1, i)) !== void 0 ? o : v[t];
                (a = typeof n) == "string" && (o = $e.exec(n)) && o[1] && (n = function (h, m, C, y) {
                    var L, q, B = 20, V = y ? function () {
                        return y.cur()
                    }
                        : function () {
                            return r.css(h, m, "")
                        }
                        , F = V(), Q = C && C[3] || (r.cssNumber[m] ? "" : "px"), $ = h.nodeType && (r.cssNumber[m] || Q !== "px" && +F) && $e.exec(r.css(h, m));
                    if ($ && $[3] !== Q) {
                        for (F /= 2,
                            Q = Q || $[3],
                            $ = +F || 1; B--;)
                            r.style(h, m, $ + Q),
                                (1 - q) * (1 - (q = V() / F || .5)) <= 0 && (B = 0),
                                $ /= q;
                        $ *= 2,
                            r.style(h, m, $ + Q),
                            C = C || []
                    }
                    return C && ($ = +$ || +F || 0,
                        L = C[1] ? $ + (C[1] + 1) * C[2] : +C[2],
                        y && (y.unit = Q,
                            y.start = $,
                            y.end = L)),
                        L
                }(e, t, o),
                    a = "number"),
                    n != null && n == n && (a !== "number" || c || (n += o && o[3] || (r.cssNumber[f] ? "" : "px")),
                        N.clearCloneStyle || n !== "" || t.indexOf("background") !== 0 || (v[t] = "inherit"),
                        u && "set" in u && (n = u.set(e, n, i)) === void 0 || (c ? v.setProperty(t, n) : v[t] = n))
            }
        },
        css: function (e, t, n, i) {
            var o, a, u, f = se(t);
            return ot.test(t) || (t = zt(f)),
                (u = r.cssHooks[t] || r.cssHooks[f]) && "get" in u && (o = u.get(e, !0, n)),
                o === void 0 && (o = qe(e, t, i)),
                o === "normal" && t in Xt && (o = Xt[t]),
                n === "" || n ? (a = parseFloat(o),
                    n === !0 || isFinite(a) ? a || 0 : o) : o
        }
    }),
        r.each(["height", "width"], function (e, t) {
            r.cssHooks[t] = {
                get: function (n, i, o) {
                    if (i)
                        return !Hn.test(r.css(n, "display")) || n.getClientRects().length && n.getBoundingClientRect().width ? Qt(n, t, o) : Wt(n, qn, function () {
                            return Qt(n, t, o)
                        })
                },
                set: function (n, i, o) {
                    var a, u = Ue(n), f = !N.scrollboxSize() && u.position === "absolute", c = (f || o) && r.css(n, "boxSizing", !1, u) === "border-box", v = o ? at(n, t, o, c, u) : 0;
                    return c && f && (v -= Math.ceil(n["offset" + t[0].toUpperCase() + t.slice(1)] - parseFloat(u[t]) - at(n, t, "border", !1, u) - .5)),
                        v && (a = $e.exec(i)) && (a[3] || "px") !== "px" && (n.style[t] = i,
                            i = r.css(n, t)),
                        Vt(0, i, v)
                }
            }
        }),
        r.cssHooks.marginLeft = Bt(N.reliableMarginLeft, function (e, t) {
            if (t)
                return (parseFloat(qe(e, "marginLeft")) || e.getBoundingClientRect().left - Wt(e, {
                    marginLeft: 0
                }, function () {
                    return e.getBoundingClientRect().left
                })) + "px"
        }),
        r.each({
            margin: "",
            padding: "",
            border: "Width"
        }, function (e, t) {
            r.cssHooks[e + t] = {
                expand: function (n) {
                    for (var i = 0, o = {}, a = typeof n == "string" ? n.split(" ") : [n]; i < 4; i++)
                        o[e + ce[i] + t] = a[i] || a[i - 2] || a[0];
                    return o
                }
            },
                e !== "margin" && (r.cssHooks[e + t].set = Vt)
        }),
        r.fn.extend({
            css: function (e, t) {
                return ae(this, function (n, i, o) {
                    var a, u, f = {}, c = 0;
                    if (Array.isArray(i)) {
                        for (a = Ue(n),
                            u = i.length; c < u; c++)
                            f[i[c]] = r.css(n, i[c], !1, a);
                        return f
                    }
                    return o !== void 0 ? r.style(n, i, o) : r.css(n, i)
                }, e, t, 1 < arguments.length)
            }
        }),
        r.fn.delay = function (e, t) {
            return e = r.fx && r.fx.speeds[e] || e,
                t = t || "fx",
                this.queue(t, function (n, i) {
                    var o = P.setTimeout(n, e);
                    i.stop = function () {
                        P.clearTimeout(o)
                    }
                })
        }
        ,
        Se = D.createElement("input"),
        Ut = D.createElement("select").appendChild(D.createElement("option")),
        Se.type = "checkbox",
        N.checkOn = Se.value !== "",
        N.optSelected = Ut.selected,
        (Se = D.createElement("input")).value = "t",
        Se.type = "radio",
        N.radioValue = Se.value === "t";
    var Yt, Re = r.expr.attrHandle;
    r.fn.extend({
        attr: function (e, t) {
            return ae(this, r.attr, e, t, 1 < arguments.length)
        },
        removeAttr: function (e) {
            return this.each(function () {
                r.removeAttr(this, e)
            })
        }
    }),
        r.extend({
            attr: function (e, t, n) {
                var i, o, a = e.nodeType;
                if (a !== 3 && a !== 8 && a !== 2)
                    return typeof e.getAttribute > "u" ? r.prop(e, t, n) : (a === 1 && r.isXMLDoc(e) || (o = r.attrHooks[t.toLowerCase()] || (r.expr.match.bool.test(t) ? Yt : void 0)),
                        n !== void 0 ? n === null ? void r.removeAttr(e, t) : o && "set" in o && (i = o.set(e, n, t)) !== void 0 ? i : (e.setAttribute(t, n + ""),
                            n) : o && "get" in o && (i = o.get(e, t)) !== null ? i : (i = r.find.attr(e, t)) == null ? void 0 : i)
            },
            attrHooks: {
                type: {
                    set: function (e, t) {
                        if (!N.radioValue && t === "radio" && I(e, "input")) {
                            var n = e.value;
                            return e.setAttribute("type", t),
                                n && (e.value = n),
                                t
                        }
                    }
                }
            },
            removeAttr: function (e, t) {
                var n, i = 0, o = t && t.match(de);
                if (o && e.nodeType === 1)
                    for (; n = o[i++];)
                        e.removeAttribute(n)
            }
        }),
        Yt = {
            set: function (e, t, n) {
                return t === !1 ? r.removeAttr(e, n) : e.setAttribute(n, n),
                    n
            }
        },
        r.each(r.expr.match.bool.source.match(/\w+/g), function (e, t) {
            var n = Re[t] || r.find.attr;
            Re[t] = function (i, o, a) {
                var u, f, c = o.toLowerCase();
                return a || (f = Re[c],
                    Re[c] = u,
                    u = n(i, o, a) != null ? c : null,
                    Re[c] = f),
                    u
            }
        });
    var Rn = /^(?:input|select|textarea|button)$/i
        , Mn = /^(?:a|area)$/i;
    function Ae(e) {
        return (e.match(de) || []).join(" ")
    }
    function ge(e) {
        return e.getAttribute && e.getAttribute("class") || ""
    }
    function st(e) {
        return Array.isArray(e) ? e : typeof e == "string" && e.match(de) || []
    }
    r.fn.extend({
        prop: function (e, t) {
            return ae(this, r.prop, e, t, 1 < arguments.length)
        },
        removeProp: function (e) {
            return this.each(function () {
                delete this[r.propFix[e] || e]
            })
        }
    }),
        r.extend({
            prop: function (e, t, n) {
                var i, o, a = e.nodeType;
                if (a !== 3 && a !== 8 && a !== 2)
                    return a === 1 && r.isXMLDoc(e) || (t = r.propFix[t] || t,
                        o = r.propHooks[t]),
                        n !== void 0 ? o && "set" in o && (i = o.set(e, n, t)) !== void 0 ? i : e[t] = n : o && "get" in o && (i = o.get(e, t)) !== null ? i : e[t]
            },
            propHooks: {
                tabIndex: {
                    get: function (e) {
                        var t = r.find.attr(e, "tabindex");
                        return t ? parseInt(t, 10) : Rn.test(e.nodeName) || Mn.test(e.nodeName) && e.href ? 0 : -1
                    }
                }
            },
            propFix: {
                for: "htmlFor",
                class: "className"
            }
        }),
        N.optSelected || (r.propHooks.selected = {
            get: function (e) {
                var t = e.parentNode;
                return t && t.parentNode && t.parentNode.selectedIndex,
                    null
            },
            set: function (e) {
                var t = e.parentNode;
                t && (t.selectedIndex,
                    t.parentNode && t.parentNode.selectedIndex)
            }
        }),
        r.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
            r.propFix[this.toLowerCase()] = this
        }),
        r.fn.extend({
            addClass: function (e) {
                var t, n, i, o, a, u;
                return H(e) ? this.each(function (f) {
                    r(this).addClass(e.call(this, f, ge(this)))
                }) : (t = st(e)).length ? this.each(function () {
                    if (i = ge(this),
                        n = this.nodeType === 1 && " " + Ae(i) + " ") {
                        for (a = 0; a < t.length; a++)
                            o = t[a],
                                n.indexOf(" " + o + " ") < 0 && (n += o + " ");
                        u = Ae(n),
                            i !== u && this.setAttribute("class", u)
                    }
                }) : this
            },
            removeClass: function (e) {
                var t, n, i, o, a, u;
                return H(e) ? this.each(function (f) {
                    r(this).removeClass(e.call(this, f, ge(this)))
                }) : arguments.length ? (t = st(e)).length ? this.each(function () {
                    if (i = ge(this),
                        n = this.nodeType === 1 && " " + Ae(i) + " ") {
                        for (a = 0; a < t.length; a++)
                            for (o = t[a]; -1 < n.indexOf(" " + o + " ");)
                                n = n.replace(" " + o + " ", " ");
                        u = Ae(n),
                            i !== u && this.setAttribute("class", u)
                    }
                }) : this : this.attr("class", "")
            },
            toggleClass: function (e, t) {
                var n, i, o, a, u = typeof e, f = u === "string" || Array.isArray(e);
                return H(e) ? this.each(function (c) {
                    r(this).toggleClass(e.call(this, c, ge(this), t), t)
                }) : typeof t == "boolean" && f ? t ? this.addClass(e) : this.removeClass(e) : (n = st(e),
                    this.each(function () {
                        if (f)
                            for (a = r(this),
                                o = 0; o < n.length; o++)
                                i = n[o],
                                    a.hasClass(i) ? a.removeClass(i) : a.addClass(i);
                        else
                            e !== void 0 && u !== "boolean" || ((i = ge(this)) && E.set(this, "__className__", i),
                                this.setAttribute && this.setAttribute("class", i || e === !1 ? "" : E.get(this, "__className__") || ""))
                    }))
            },
            hasClass: function (e) {
                var t, n, i = 0;
                for (t = " " + e + " "; n = this[i++];)
                    if (n.nodeType === 1 && -1 < (" " + Ae(ge(n)) + " ").indexOf(t))
                        return !0;
                return !1
            }
        });
    var In = /\r/g;
    r.fn.extend({
        val: function (e) {
            var t, n, i, o = this[0];
            return arguments.length ? (i = H(e),
                this.each(function (a) {
                    var u;
                    this.nodeType === 1 && ((u = i ? e.call(this, a, r(this).val()) : e) == null ? u = "" : typeof u == "number" ? u += "" : Array.isArray(u) && (u = r.map(u, function (f) {
                        return f == null ? "" : f + ""
                    })),
                        (t = r.valHooks[this.type] || r.valHooks[this.nodeName.toLowerCase()]) && "set" in t && t.set(this, u, "value") !== void 0 || (this.value = u))
                })) : o ? (t = r.valHooks[o.type] || r.valHooks[o.nodeName.toLowerCase()]) && "get" in t && (n = t.get(o, "value")) !== void 0 ? n : typeof (n = o.value) == "string" ? n.replace(In, "") : n ?? "" : void 0
        }
    }),
        r.extend({
            valHooks: {
                option: {
                    get: function (e) {
                        var t = r.find.attr(e, "value");
                        return t ?? Ae(r.text(e))
                    }
                },
                select: {
                    get: function (e) {
                        var t, n, i, o = e.options, a = e.selectedIndex, u = e.type === "select-one", f = u ? null : [], c = u ? a + 1 : o.length;
                        for (i = a < 0 ? c : u ? a : 0; i < c; i++)
                            if (((n = o[i]).selected || i === a) && !n.disabled && (!n.parentNode.disabled || !I(n.parentNode, "optgroup"))) {
                                if (t = r(n).val(),
                                    u)
                                    return t;
                                f.push(t)
                            }
                        return f
                    },
                    set: function (e, t) {
                        for (var n, i, o = e.options, a = r.makeArray(t), u = o.length; u--;)
                            ((i = o[u]).selected = -1 < r.inArray(r.valHooks.option.get(i), a)) && (n = !0);
                        return n || (e.selectedIndex = -1),
                            a
                    }
                }
            }
        }),
        r.each(["radio", "checkbox"], function () {
            r.valHooks[this] = {
                set: function (e, t) {
                    if (Array.isArray(t))
                        return e.checked = -1 < r.inArray(r(e).val(), t)
                }
            },
                N.checkOn || (r.valHooks[this].get = function (e) {
                    return e.getAttribute("value") === null ? "on" : e.value
                }
                )
        }),
        r.parseXML = function (e) {
            var t, n;
            if (!e || typeof e != "string")
                return null;
            try {
                t = new P.DOMParser().parseFromString(e, "text/xml")
            } catch { }
            return n = t && t.getElementsByTagName("parsererror")[0],
                t && !n || r.error("Invalid XML: " + (n ? r.map(n.childNodes, function (i) {
                    return i.textContent
                }).join(`
`) : e)),
                t
        }
        ;
    var Gt = /^(?:focusinfocus|focusoutblur)$/
        , Kt = function (e) {
            e.stopPropagation()
        };
    r.extend(r.event, {
        trigger: function (e, t, n, i) {
            var o, a, u, f, c, v, h, m, C = [n || D], y = Le.call(e, "type") ? e.type : e, L = Le.call(e, "namespace") ? e.namespace.split(".") : [];
            if (a = m = u = n = n || D,
                n.nodeType !== 3 && n.nodeType !== 8 && !Gt.test(y + r.event.triggered) && (-1 < y.indexOf(".") && (y = (L = y.split(".")).shift(),
                    L.sort()),
                    c = y.indexOf(":") < 0 && "on" + y,
                    (e = e[r.expando] ? e : new r.Event(y, typeof e == "object" && e)).isTrigger = i ? 2 : 3,
                    e.namespace = L.join("."),
                    e.rnamespace = e.namespace ? new RegExp("(^|\\.)" + L.join("\\.(?:.*\\.|)") + "(\\.|$)") : null,
                    e.result = void 0,
                    e.target || (e.target = n),
                    t = t == null ? [e] : r.makeArray(t, [e]),
                    h = r.event.special[y] || {},
                    i || !h.trigger || h.trigger.apply(n, t) !== !1)) {
                if (!i && !h.noBubble && !ye(n)) {
                    for (f = h.delegateType || y,
                        Gt.test(f + y) || (a = a.parentNode); a; a = a.parentNode)
                        C.push(a),
                            u = a;
                    u === (n.ownerDocument || D) && C.push(u.defaultView || u.parentWindow || P)
                }
                for (o = 0; (a = C[o++]) && !e.isPropagationStopped();)
                    m = a,
                        e.type = 1 < o ? f : h.bindType || y,
                        (v = (E.get(a, "events") || Object.create(null))[e.type] && E.get(a, "handle")) && v.apply(a, t),
                        (v = c && a[c]) && v.apply && Oe(a) && (e.result = v.apply(a, t),
                            e.result === !1 && e.preventDefault());
                return e.type = y,
                    i || e.isDefaultPrevented() || h._default && h._default.apply(C.pop(), t) !== !1 || !Oe(n) || c && H(n[y]) && !ye(n) && ((u = n[c]) && (n[c] = null),
                        r.event.triggered = y,
                        e.isPropagationStopped() && m.addEventListener(y, Kt),
                        n[y](),
                        e.isPropagationStopped() && m.removeEventListener(y, Kt),
                        r.event.triggered = void 0,
                        u && (n[c] = u)),
                    e.result
            }
        },
        simulate: function (e, t, n) {
            var i = r.extend(new r.Event, n, {
                type: e,
                isSimulated: !0
            });
            r.event.trigger(i, null, t)
        }
    }),
        r.fn.extend({
            trigger: function (e, t) {
                return this.each(function () {
                    r.event.trigger(e, t, this)
                })
            },
            triggerHandler: function (e, t) {
                var n = this[0];
                if (n)
                    return r.event.trigger(e, t, n, !0)
            }
        });
    var Jt, Wn = /\[\]$/, Zt = /\r?\n/g, Bn = /^(?:submit|button|image|reset|file)$/i, Fn = /^(?:input|select|textarea|keygen)/i;
    function ut(e, t, n, i) {
        var o;
        if (Array.isArray(t))
            r.each(t, function (a, u) {
                n || Wn.test(e) ? i(e, u) : ut(e + "[" + (typeof u == "object" && u != null ? a : "") + "]", u, n, i)
            });
        else if (n || be(t) !== "object")
            i(e, t);
        else
            for (o in t)
                ut(e + "[" + o + "]", t[o], n, i)
    }
    r.param = function (e, t) {
        var n, i = [], o = function (a, u) {
            var f = H(u) ? u() : u;
            i[i.length] = encodeURIComponent(a) + "=" + encodeURIComponent(f ?? "")
        };
        if (e == null)
            return "";
        if (Array.isArray(e) || e.jquery && !r.isPlainObject(e))
            r.each(e, function () {
                o(this.name, this.value)
            });
        else
            for (n in e)
                ut(n, e[n], t, o);
        return i.join("&")
    }
        ,
        r.fn.extend({
            serialize: function () {
                return r.param(this.serializeArray())
            },
            serializeArray: function () {
                return this.map(function () {
                    var e = r.prop(this, "elements");
                    return e ? r.makeArray(e) : this
                }).filter(function () {
                    var e = this.type;
                    return this.name && !r(this).is(":disabled") && Fn.test(this.nodeName) && !Bn.test(e) && (this.checked || !He.test(e))
                }).map(function (e, t) {
                    var n = r(this).val();
                    return n == null ? null : Array.isArray(n) ? r.map(n, function (i) {
                        return {
                            name: t.name,
                            value: i.replace(Zt, `\r
`)
                        }
                    }) : {
                        name: t.name,
                        value: n.replace(Zt, `\r
`)
                    }
                }).get()
            }
        }),
        r.fn.extend({
            wrapAll: function (e) {
                var t;
                return this[0] && (H(e) && (e = e.call(this[0])),
                    t = r(e, this[0].ownerDocument).eq(0).clone(!0),
                    this[0].parentNode && t.insertBefore(this[0]),
                    t.map(function () {
                        for (var n = this; n.firstElementChild;)
                            n = n.firstElementChild;
                        return n
                    }).append(this)),
                    this
            },
            wrapInner: function (e) {
                return H(e) ? this.each(function (t) {
                    r(this).wrapInner(e.call(this, t))
                }) : this.each(function () {
                    var t = r(this)
                        , n = t.contents();
                    n.length ? n.wrapAll(e) : t.append(e)
                })
            },
            wrap: function (e) {
                var t = H(e);
                return this.each(function (n) {
                    r(this).wrapAll(t ? e.call(this, n) : e)
                })
            },
            unwrap: function (e) {
                return this.parent(e).not("body").each(function () {
                    r(this).replaceWith(this.childNodes)
                }),
                    this
            }
        }),
        r.expr.pseudos.hidden = function (e) {
            return !r.expr.pseudos.visible(e)
        }
        ,
        r.expr.pseudos.visible = function (e) {
            return !!(e.offsetWidth || e.offsetHeight || e.getClientRects().length)
        }
        ,
        N.createHTMLDocument = ((Jt = D.implementation.createHTMLDocument("").body).innerHTML = "<form></form><form></form>",
            Jt.childNodes.length === 2),
        r.parseHTML = function (e, t, n) {
            return typeof e != "string" ? [] : (typeof t == "boolean" && (n = t,
                t = !1),
                t || (N.createHTMLDocument ? ((i = (t = D.implementation.createHTMLDocument("")).createElement("base")).href = D.location.href,
                    t.head.appendChild(i)) : t = D),
                a = !n && [],
                (o = Tt.exec(e)) ? [t.createElement(o[1])] : (o = Ht([e], t, a),
                    a && a.length && r(a).remove(),
                    r.merge([], o.childNodes)));
            var i, o, a
        }
        ,
        r.offset = {
            setOffset: function (e, t, n) {
                var i, o, a, u, f, c, v = r.css(e, "position"), h = r(e), m = {};
                v === "static" && (e.style.position = "relative"),
                    f = h.offset(),
                    a = r.css(e, "top"),
                    c = r.css(e, "left"),
                    (v === "absolute" || v === "fixed") && -1 < (a + c).indexOf("auto") ? (u = (i = h.position()).top,
                        o = i.left) : (u = parseFloat(a) || 0,
                            o = parseFloat(c) || 0),
                    H(t) && (t = t.call(e, n, r.extend({}, f))),
                    t.top != null && (m.top = t.top - f.top + u),
                    t.left != null && (m.left = t.left - f.left + o),
                    "using" in t ? t.using.call(e, m) : h.css(m)
            }
        },
        r.fn.extend({
            offset: function (e) {
                if (arguments.length)
                    return e === void 0 ? this : this.each(function (o) {
                        r.offset.setOffset(this, e, o)
                    });
                var t, n, i = this[0];
                return i ? i.getClientRects().length ? (t = i.getBoundingClientRect(),
                    n = i.ownerDocument.defaultView,
                {
                    top: t.top + n.pageYOffset,
                    left: t.left + n.pageXOffset
                }) : {
                    top: 0,
                    left: 0
                } : void 0
            },
            position: function () {
                if (this[0]) {
                    var e, t, n, i = this[0], o = {
                        top: 0,
                        left: 0
                    };
                    if (r.css(i, "position") === "fixed")
                        t = i.getBoundingClientRect();
                    else {
                        for (t = this.offset(),
                            n = i.ownerDocument,
                            e = i.offsetParent || n.documentElement; e && (e === n.body || e === n.documentElement) && r.css(e, "position") === "static";)
                            e = e.parentNode;
                        e && e !== i && e.nodeType === 1 && ((o = r(e).offset()).top += r.css(e, "borderTopWidth", !0),
                            o.left += r.css(e, "borderLeftWidth", !0))
                    }
                    return {
                        top: t.top - o.top - r.css(i, "marginTop", !0),
                        left: t.left - o.left - r.css(i, "marginLeft", !0)
                    }
                }
            },
            offsetParent: function () {
                return this.map(function () {
                    for (var e = this.offsetParent; e && r.css(e, "position") === "static";)
                        e = e.offsetParent;
                    return e || pe
                })
            }
        }),
        r.each({
            scrollLeft: "pageXOffset",
            scrollTop: "pageYOffset"
        }, function (e, t) {
            var n = t === "pageYOffset";
            r.fn[e] = function (i) {
                return ae(this, function (o, a, u) {
                    var f;
                    if (ye(o) ? f = o : o.nodeType === 9 && (f = o.defaultView),
                        u === void 0)
                        return f ? f[t] : o[a];
                    f ? f.scrollTo(n ? f.pageXOffset : u, n ? u : f.pageYOffset) : o[a] = u
                }, e, i, arguments.length)
            }
        }),
        r.each(["top", "left"], function (e, t) {
            r.cssHooks[t] = Bt(N.pixelPosition, function (n, i) {
                if (i)
                    return i = qe(n, t),
                        it.test(i) ? r(n).position()[t] + "px" : i
            })
        }),
        r.each({
            Height: "height",
            Width: "width"
        }, function (e, t) {
            r.each({
                padding: "inner" + e,
                content: t,
                "": "outer" + e
            }, function (n, i) {
                r.fn[i] = function (o, a) {
                    var u = arguments.length && (n || typeof o != "boolean")
                        , f = n || (o === !0 || a === !0 ? "margin" : "border");
                    return ae(this, function (c, v, h) {
                        var m;
                        return ye(c) ? i.indexOf("outer") === 0 ? c["inner" + e] : c.document.documentElement["client" + e] : c.nodeType === 9 ? (m = c.documentElement,
                            Math.max(c.body["scroll" + e], m["scroll" + e], c.body["offset" + e], m["offset" + e], m["client" + e])) : h === void 0 ? r.css(c, v, f) : r.style(c, v, h, f)
                    }, t, u ? o : void 0, u)
                }
            })
        }),
        r.fn.extend({
            bind: function (e, t, n) {
                return this.on(e, null, t, n)
            },
            unbind: function (e, t) {
                return this.off(e, null, t)
            },
            delegate: function (e, t, n, i) {
                return this.on(t, e, n, i)
            },
            undelegate: function (e, t, n) {
                return arguments.length === 1 ? this.off(e, "**") : this.off(t, e || "**", n)
            },
            hover: function (e, t) {
                return this.on("mouseenter", e).on("mouseleave", t || e)
            }
        }),
        r.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "), function (e, t) {
            r.fn[t] = function (n, i) {
                return 0 < arguments.length ? this.on(t, null, n, i) : this.trigger(t)
            }
        });
    var $n = /^[\s\uFEFF\xA0]+|([^\s\uFEFF\xA0])[\s\uFEFF\xA0]+$/g;
    r.proxy = function (e, t) {
        var n, i, o;
        if (typeof t == "string" && (n = e[t],
            t = e,
            e = n),
            H(e))
            return i = J.call(arguments, 2),
                (o = function () {
                    return e.apply(t || this, i.concat(J.call(arguments)))
                }
                ).guid = e.guid = e.guid || r.guid++,
                o
    }
        ,
        r.holdReady = function (e) {
            e ? r.readyWait++ : r.ready(!0)
        }
        ,
        r.isArray = Array.isArray,
        r.parseJSON = JSON.parse,
        r.nodeName = I,
        r.isFunction = H,
        r.isWindow = ye,
        r.camelCase = se,
        r.type = be,
        r.now = Date.now,
        r.isNumeric = function (e) {
            var t = r.type(e);
            return (t === "number" || t === "string") && !isNaN(e - parseFloat(e))
        }
        ,
        r.trim = function (e) {
            return e == null ? "" : (e + "").replace($n, "$1")
        }
        ,
        typeof define == "function" && define.amd && define("jquery", [], function () {
            return r
        });
    var _n = P.jQuery
        , zn = P.$;
    return r.noConflict = function (e) {
        return P.$ === r && (P.$ = zn),
            e && P.jQuery === r && (P.jQuery = _n),
            r
    }
        ,
        typeof Ne > "u" && (P.jQuery = P.$ = r),
        r
});
