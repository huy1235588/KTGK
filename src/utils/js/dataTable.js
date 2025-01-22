/*! DataTables 2.2.1
 * © SpryMedia Ltd - datatables.net/license
 */
(function (c) {
    "use strict";
    if (typeof define == "function" && define.amd)
        define(["jquery"], function (I) {
            return c(I, window, document)
        });
    else if (typeof exports == "object") {
        var N = require("jquery");
        typeof window > "u" ? module.exports = function (I, b) {
            return I || (I = window),
                b || (b = N(I)),
                c(b, I, I.document)
        }
            : module.exports = c(N, window, window.document)
    } else
        window.DataTable = c(jQuery, window, document)
}
)(function (c, N, I) {
    "use strict";
    var b = function (e, r) {
        if (b.factory(e, r))
            return b;
        if (this instanceof b)
            return c(e).DataTable(r);
        r = e;
        var a = this
            , n = r === void 0
            , t = this.length;
        return n && (r = {}),
            this.api = function () {
                return new x(this)
            }
            ,
            this.each(function () {
                var l = {}, i = t > 1 ? ra(l, r, !0) : r, o = 0, u, d = this.getAttribute("id"), f = b.defaults, s = c(this);
                if (this.nodeName.toLowerCase() != "table") {
                    U(null, 0, "Non-table node initialisation (" + this.nodeName + ")", 2);
                    return
                }
                c(this).trigger("options.dt", i),
                    Ir(f),
                    Pr(f.column),
                    Q(f, f, !0),
                    Q(f.column, f.column, !0),
                    Q(f, c.extend(i, s.data()), !0);
                var h = b.settings;
                for (o = 0,
                    u = h.length; o < u; o++) {
                    var p = h[o];
                    if (p.nTable == this || p.nTHead && p.nTHead.parentNode == this || p.nTFoot && p.nTFoot.parentNode == this) {
                        var m = i.bRetrieve !== void 0 ? i.bRetrieve : f.bRetrieve
                            , _ = i.bDestroy !== void 0 ? i.bDestroy : f.bDestroy;
                        if (n || m)
                            return p.oInstance;
                        if (_) {
                            new b.Api(p).destroy();
                            break
                        } else {
                            U(p, 0, "Cannot reinitialise DataTable", 3);
                            return
                        }
                    }
                    if (p.sTableId == this.id) {
                        h.splice(o, 1);
                        break
                    }
                }
                (d === null || d === "") && (d = "DataTables_Table_" + b.ext._unique++,
                    this.id = d);
                var v = c.extend(!0, {}, b.models.oSettings, {
                    sDestroyWidth: s[0].style.width,
                    sInstance: d,
                    sTableId: d,
                    colgroup: c("<colgroup>").prependTo(this),
                    fastData: function (E, Y, be) {
                        return q(v, E, Y, be)
                    }
                });
                v.nTable = this,
                    v.oInit = i,
                    h.push(v),
                    v.api = new x(v),
                    v.oInstance = a.length === 1 ? a : s.dataTable(),
                    Ir(i),
                    i.aLengthMenu && !i.iDisplayLength && (i.iDisplayLength = Array.isArray(i.aLengthMenu[0]) ? i.aLengthMenu[0][0] : c.isPlainObject(i.aLengthMenu[0]) ? i.aLengthMenu[0].value : i.aLengthMenu[0]),
                    i = ra(c.extend(!0, {}, f), i),
                    re(v.oFeatures, i, ["bPaginate", "bLengthChange", "bFilter", "bSort", "bSortMulti", "bInfo", "bProcessing", "bAutoWidth", "bSortClasses", "bServerSide", "bDeferRender"]),
                    re(v, i, ["ajax", "fnFormatNumber", "sServerMethod", "aaSorting", "aaSortingFixed", "aLengthMenu", "sPaginationType", "iStateDuration", "bSortCellsTop", "iTabIndex", "sDom", "fnStateLoadCallback", "fnStateSaveCallback", "renderer", "searchDelay", "rowId", "caption", "layout", "orderDescReverse", "typeDetect", ["iCookieDuration", "iStateDuration"], ["oSearch", "oPreviousSearch"], ["aoSearchCols", "aoPreSearchCols"], ["iDisplayLength", "_iDisplayLength"]]),
                    re(v.oScroll, i, [["sScrollX", "sX"], ["sScrollXInner", "sXInner"], ["sScrollY", "sY"], ["bScrollCollapse", "bCollapse"]]),
                    re(v.oLanguage, i, "fnInfoCallback"),
                    z(v, "aoDrawCallback", i.fnDrawCallback),
                    z(v, "aoStateSaveParams", i.fnStateSaveParams),
                    z(v, "aoStateLoadParams", i.fnStateLoadParams),
                    z(v, "aoStateLoaded", i.fnStateLoaded),
                    z(v, "aoRowCallback", i.fnRowCallback),
                    z(v, "aoRowCreatedCallback", i.fnCreatedRow),
                    z(v, "aoHeaderCallback", i.fnHeaderCallback),
                    z(v, "aoFooterCallback", i.fnFooterCallback),
                    z(v, "aoInitComplete", i.fnInitComplete),
                    z(v, "aoPreDrawCallback", i.fnPreDrawCallback),
                    v.rowIdFn = de(i.rowId),
                    Sa(v);
                var D = v.oClasses;
                c.extend(D, b.ext.classes, i.oClasses),
                    s.addClass(D.table),
                    v.oFeatures.bPaginate || (i.iDisplayStart = 0),
                    v.iInitDisplayStart === void 0 && (v.iInitDisplayStart = i.iDisplayStart,
                        v._iDisplayStart = i.iDisplayStart);
                var y = i.iDeferLoading;
                if (y !== null) {
                    v.deferLoading = !0;
                    var S = Array.isArray(y);
                    v._iRecordsDisplay = S ? y[0] : y,
                        v._iRecordsTotal = S ? y[1] : y
                }
                var C = []
                    , A = this.getElementsByTagName("thead")
                    , L = zr(v, A[0]);
                if (i.aoColumns)
                    C = i.aoColumns;
                else if (L.length)
                    for (o = 0,
                        u = L[0].length; o < u; o++)
                        C.push(null);
                for (o = 0,
                    u = C.length; o < u; o++)
                    Or(v);
                La(v, i.aoColumnDefs, C, L, function (E, Y) {
                    $e(v, E, Y)
                });
                var R = s.children("tbody").find("tr").eq(0);
                if (R.length) {
                    var j = function (E, Y) {
                        return E.getAttribute("data-" + Y) !== null ? Y : null
                    };
                    c(R[0]).children("th, td").each(function (E, Y) {
                        var be = v.aoColumns[E];
                        if (be || U(v, 0, "Incorrect column count", 18),
                            be.mData === E) {
                            var we = j(Y, "sort") || j(Y, "order")
                                , Lr = j(Y, "filter") || j(Y, "search");
                            (we !== null || Lr !== null) && (be.mData = {
                                _: E + ".display",
                                sort: we !== null ? E + ".@data-" + we : void 0,
                                type: we !== null ? E + ".@data-" + we : void 0,
                                filter: Lr !== null ? E + ".@data-" + Lr : void 0
                            },
                                be._isArrayHost = !0,
                                $e(v, E))
                        }
                    })
                }
                z(v, "aoDrawCallback", We);
                var G = v.oFeatures;
                if (i.bStateSave && (G.bStateSave = !0),
                    i.aaSorting === void 0) {
                    var J = v.aaSorting;
                    for (o = 0,
                        u = J.length; o < u; o++)
                        J[o][1] = v.aoColumns[o].asSorting[0]
                }
                vr(v),
                    z(v, "aoDrawCallback", function () {
                        (v.bSorted || X(v) === "ssp" || G.bDeferRender) && vr(v)
                    });
                var P = s.children("caption");
                v.caption && (P.length === 0 && (P = c("<caption/>").appendTo(s)),
                    P.html(v.caption)),
                    P.length && (P[0]._captionSide = P.css("caption-side"),
                        v.captionNode = P[0]),
                    A.length === 0 && (A = c("<thead/>").appendTo(s)),
                    v.nTHead = A[0];
                var ne = s.children("tbody");
                ne.length === 0 && (ne = c("<tbody/>").insertAfter(A)),
                    v.nTBody = ne[0];
                var Se = s.children("tfoot");
                Se.length === 0 && (Se = c("<tfoot/>").appendTo(s)),
                    v.nTFoot = Se[0],
                    v.aiDisplay = v.aiDisplayMaster.slice(),
                    v.bInitialised = !0;
                var Ye = v.oLanguage;
                c.extend(!0, Ye, i.oLanguage),
                    Ye.sUrl ? c.ajax({
                        dataType: "json",
                        url: Ye.sUrl,
                        success: function (E) {
                            Q(f.oLanguage, E),
                                c.extend(!0, Ye, E, v.oInit.oLanguage),
                                w(v, null, "i18n", [v], !0),
                                Me(v)
                        },
                        error: function () {
                            U(v, 0, "i18n file loading error", 21),
                                Me(v)
                        }
                    }) : (w(v, null, "i18n", [v], !0),
                        Me(v))
            }),
            a = null,
            this
    };
    b.ext = F = {
        buttons: {},
        classes: {},
        builder: "-source-",
        errMode: "alert",
        feature: [],
        features: {},
        search: [],
        selector: {
            cell: [],
            column: [],
            row: []
        },
        legacy: {
            ajax: null
        },
        pager: {},
        renderer: {
            pageButton: {},
            header: {}
        },
        order: {},
        type: {
            className: {},
            detect: [],
            render: {},
            search: {},
            order: {}
        },
        _unique: 0,
        fnVersionCheck: b.fnVersionCheck,
        iApiIndex: 0,
        sVersion: b.version
    },
        c.extend(F, {
            afnFiltering: F.search,
            aTypes: F.type.detect,
            ofnSearch: F.type.search,
            oSort: F.type.order,
            afnSortData: F.order,
            aoFeatures: F.feature,
            oStdClasses: F.classes,
            oPagination: F.pager
        }),
        c.extend(b.ext.classes, {
            container: "dt-container",
            empty: {
                row: "dt-empty"
            },
            info: {
                container: "dt-info"
            },
            layout: {
                row: "dt-layout-row",
                cell: "dt-layout-cell",
                tableRow: "dt-layout-table",
                tableCell: "",
                start: "dt-layout-start",
                end: "dt-layout-end",
                full: "dt-layout-full"
            },
            length: {
                container: "dt-length",
                select: "dt-input"
            },
            order: {
                canAsc: "dt-orderable-asc",
                canDesc: "dt-orderable-desc",
                isAsc: "dt-ordering-asc",
                isDesc: "dt-ordering-desc",
                none: "dt-orderable-none",
                position: "sorting_"
            },
            processing: {
                container: "dt-processing"
            },
            scrolling: {
                body: "dt-scroll-body",
                container: "dt-scroll",
                footer: {
                    self: "dt-scroll-foot",
                    inner: "dt-scroll-footInner"
                },
                header: {
                    self: "dt-scroll-head",
                    inner: "dt-scroll-headInner"
                }
            },
            search: {
                container: "dt-search",
                input: "dt-input"
            },
            table: "dataTable",
            tbody: {
                cell: "",
                row: ""
            },
            thead: {
                cell: "",
                row: ""
            },
            tfoot: {
                cell: "",
                row: ""
            },
            paging: {
                active: "current",
                button: "dt-paging-button",
                container: "dt-paging",
                disabled: "disabled",
                nav: ""
            }
        });
    var F, x, T, g, Je = {}, ya = /[\r\n\u2028]/g, Ze = /<([^>]*>)/g, Da = Math.pow(2, 28), Ar = /^\d{2,4}[./-]\d{1,2}[./-]\d{1,2}([T ]{1}\d{1,2}[:.]\d{2}([.:]\d{2})?)?$/, Ta = new RegExp("(\\" + ["/", ".", "*", "+", "?", "|", "(", ")", "[", "]", "{", "}", "\\", "$", "^", "-"].join("|\\") + ")", "g"), Ke = /['\u00A0,$£€¥%\u2009\u202F\u20BD\u20a9\u20BArfkɃΞ]/gi, B = function (e) {
        return !e || e === !0 || e === "-"
    }, Fr = function (e) {
        var r = parseInt(e, 10);
        return !isNaN(r) && isFinite(e) ? r : null
    }, Nr = function (e, r) {
        return Je[r] || (Je[r] = new RegExp(dr(r), "g")),
            typeof e == "string" && r !== "." ? e.replace(/\./g, "").replace(Je[r], ".") : e
    }, me = function (e, r, a, n) {
        var t = typeof e
            , l = t === "string";
        return t === "number" || t === "bigint" || n && B(e) ? !0 : (r && l && (e = Nr(e, r)),
            a && l && (e = e.replace(Ke, "")),
            !isNaN(parseFloat(e)) && isFinite(e))
    }, Ca = function (e) {
        return B(e) || typeof e == "string"
    }, ge = function (e, r, a, n) {
        if (n && B(e))
            return !0;
        if (typeof e == "string" && e.match(/<(input|select)/i))
            return null;
        var t = Ca(e);
        return t && me(Z(e), r, a, n) ? !0 : null
    }, O = function (e, r, a) {
        var n = []
            , t = 0
            , l = e.length;
        if (a !== void 0)
            for (; t < l; t++)
                e[t] && e[t][r] && n.push(e[t][r][a]);
        else
            for (; t < l; t++)
                e[t] && n.push(e[t][r]);
        return n
    }, _e = function (e, r, a, n) {
        var t = []
            , l = 0
            , i = r.length;
        if (n !== void 0)
            for (; l < i; l++)
                e[r[l]] && e[r[l]][a] && t.push(e[r[l]][a][n]);
        else
            for (; l < i; l++)
                e[r[l]] && t.push(e[r[l]][a]);
        return t
    }, K = function (e, r) {
        var a = [], n;
        r === void 0 ? (r = 0,
            n = e) : (n = r,
                r = e);
        for (var t = r; t < n; t++)
            a.push(t);
        return a
    }, Rr = function (e) {
        for (var r = [], a = 0, n = e.length; a < n; a++)
            e[a] && r.push(e[a]);
        return r
    }, Z = function (e) {
        if (!e || typeof e != "string")
            return e;
        if (e.length > Da)
            throw new Error("Exceeded max str len");
        var r;
        e = e.replace(Ze, "");
        do
            r = e,
                e = e.replace(/<script/i, "");
        while (e !== r);
        return r
    }, ue = function (e) {
        return Array.isArray(e) && (e = e.join(",")),
            typeof e == "string" ? e.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;") : e
    }, Le = function (e, r) {
        if (typeof e != "string")
            return e;
        var a = e.normalize ? e.normalize("NFD") : e;
        return a.length !== e.length ? (r === !0 ? e + " " : "") + a.replace(/[\u0300-\u036f]/g, "") : a
    }, xa = function (e) {
        if (e.length < 2)
            return !0;
        for (var r = e.slice().sort(), a = r[0], n = 1, t = r.length; n < t; n++) {
            if (r[n] === a)
                return !1;
            a = r[n]
        }
        return !0
    }, te = function (e) {
        if (Array.from && Set)
            return Array.from(new Set(e));
        if (xa(e))
            return e.slice();
        var r = [], a, n, t = e.length, l, i = 0;
        e: for (n = 0; n < t; n++) {
            for (a = e[n],
                l = 0; l < i; l++)
                if (r[l] === a)
                    continue e;
            r.push(a),
                i++
        }
        return r
    }, Qe = function (e, r) {
        if (Array.isArray(r))
            for (var a = 0; a < r.length; a++)
                Qe(e, r[a]);
        else
            e.push(r);
        return e
    };
    function fe(e, r) {
        r && r.split(" ").forEach(function (a) {
            a && e.classList.add(a)
        })
    }
    b.util = {
        diacritics: function (e, r) {
            var a = typeof e;
            if (a !== "function")
                return Le(e, r);
            Le = e
        },
        debounce: function (e, r) {
            var a;
            return function () {
                var n = this
                    , t = arguments;
                clearTimeout(a),
                    a = setTimeout(function () {
                        e.apply(n, t)
                    }, r || 250)
            }
        },
        throttle: function (e, r) {
            var a = r !== void 0 ? r : 200, n, t;
            return function () {
                var l = this
                    , i = +new Date
                    , o = arguments;
                n && i < n + a ? (clearTimeout(t),
                    t = setTimeout(function () {
                        n = void 0,
                            e.apply(l, o)
                    }, a)) : (n = i,
                        e.apply(l, o))
            }
        },
        escapeRegex: function (e) {
            return e.replace(Ta, "\\$1")
        },
        set: function (e) {
            if (c.isPlainObject(e))
                return b.util.set(e._);
            if (e === null)
                return function () { }
                    ;
            if (typeof e == "function")
                return function (a, n, t) {
                    e(a, "set", n, t)
                }
                    ;
            if (typeof e == "string" && (e.indexOf(".") !== -1 || e.indexOf("[") !== -1 || e.indexOf("(") !== -1)) {
                var r = function (a, n, t) {
                    for (var l = Mr(t), i, o = l[l.length - 1], u, d, f, s, h = 0, p = l.length - 1; h < p; h++) {
                        if (l[h] === "__proto__" || l[h] === "constructor")
                            throw new Error("Cannot set prototype values");
                        if (u = l[h].match(De),
                            d = l[h].match(ce),
                            u) {
                            if (l[h] = l[h].replace(De, ""),
                                a[l[h]] = [],
                                i = l.slice(),
                                i.splice(0, h + 1),
                                s = i.join("."),
                                Array.isArray(n))
                                for (var m = 0, _ = n.length; m < _; m++)
                                    f = {},
                                        r(f, n[m], s),
                                        a[l[h]].push(f);
                            else
                                a[l[h]] = n;
                            return
                        } else
                            d && (l[h] = l[h].replace(ce, ""),
                                a = a[l[h]](n));
                        (a[l[h]] === null || a[l[h]] === void 0) && (a[l[h]] = {}),
                            a = a[l[h]]
                    }
                    o.match(ce) ? a = a[o.replace(ce, "")](n) : a[o.replace(De, "")] = n
                };
                return function (a, n) {
                    return r(a, n, e)
                }
            } else
                return function (a, n) {
                    a[e] = n
                }
        },
        get: function (e) {
            if (c.isPlainObject(e)) {
                var r = {};
                return c.each(e, function (n, t) {
                    t && (r[n] = b.util.get(t))
                }),
                    function (n, t, l, i) {
                        var o = r[t] || r._;
                        return o !== void 0 ? o(n, t, l, i) : n
                    }
            } else {
                if (e === null)
                    return function (n) {
                        return n
                    }
                        ;
                if (typeof e == "function")
                    return function (n, t, l, i) {
                        return e(n, t, l, i)
                    }
                        ;
                if (typeof e == "string" && (e.indexOf(".") !== -1 || e.indexOf("[") !== -1 || e.indexOf("(") !== -1)) {
                    var a = function (n, t, l) {
                        var i, o, u, d;
                        if (l !== "")
                            for (var f = Mr(l), s = 0, h = f.length; s < h; s++) {
                                if (i = f[s].match(De),
                                    o = f[s].match(ce),
                                    i) {
                                    if (f[s] = f[s].replace(De, ""),
                                        f[s] !== "" && (n = n[f[s]]),
                                        u = [],
                                        f.splice(0, s + 1),
                                        d = f.join("."),
                                        Array.isArray(n))
                                        for (var p = 0, m = n.length; p < m; p++)
                                            u.push(a(n[p], t, d));
                                    var _ = i[0].substring(1, i[0].length - 1);
                                    n = _ === "" ? u : u.join(_);
                                    break
                                } else if (o) {
                                    f[s] = f[s].replace(ce, ""),
                                        n = n[f[s]]();
                                    continue
                                }
                                if (n === null || n[f[s]] === null)
                                    return null;
                                if (n === void 0 || n[f[s]] === void 0)
                                    return;
                                n = n[f[s]]
                            }
                        return n
                    };
                    return function (n, t) {
                        return a(n, t, e)
                    }
                } else
                    return function (n) {
                        return n[e]
                    }
            }
        },
        stripHtml: function (e) {
            var r = typeof e;
            if (r === "function") {
                Z = e;
                return
            } else if (r === "string")
                return Z(e);
            return e
        },
        escapeHtml: function (e) {
            var r = typeof e;
            if (r === "function") {
                ue = e;
                return
            } else if (r === "string" || Array.isArray(e))
                return ue(e);
            return e
        },
        unique: te
    };
    function Ae(e) {
        var r = "a aa ai ao as b fn i m o s ", a, n, t = {};
        c.each(e, function (l) {
            a = l.match(/^([^A-Z]+?)([A-Z])/),
                a && r.indexOf(a[1] + " ") !== -1 && (n = l.replace(a[0], a[2].toLowerCase()),
                    t[n] = l,
                    a[1] === "o" && Ae(e[l]))
        }),
            e._hungarianMap = t
    }
    function Q(e, r, a) {
        e._hungarianMap || Ae(e);
        var n;
        c.each(r, function (t) {
            n = e._hungarianMap[t],
                n !== void 0 && (a || r[n] === void 0) && (n.charAt(0) === "o" ? (r[n] || (r[n] = {}),
                    c.extend(!0, r[n], r[t]),
                    Q(e[n], r[n], a)) : r[n] = r[t])
        })
    }
    var W = function (e, r, a) {
        e[r] !== void 0 && (e[a] = e[r])
    };
    function Ir(e) {
        W(e, "ordering", "bSort"),
            W(e, "orderMulti", "bSortMulti"),
            W(e, "orderClasses", "bSortClasses"),
            W(e, "orderCellsTop", "bSortCellsTop"),
            W(e, "order", "aaSorting"),
            W(e, "orderFixed", "aaSortingFixed"),
            W(e, "paging", "bPaginate"),
            W(e, "pagingType", "sPaginationType"),
            W(e, "pageLength", "iDisplayLength"),
            W(e, "searching", "bFilter"),
            typeof e.sScrollX == "boolean" && (e.sScrollX = e.sScrollX ? "100%" : ""),
            typeof e.scrollX == "boolean" && (e.scrollX = e.scrollX ? "100%" : "");
        var r = e.aoSearchCols;
        if (r)
            for (var a = 0, n = r.length; a < n; a++)
                r[a] && Q(b.models.oSearch, r[a]);
        e.serverSide && !e.searchDelay && (e.searchDelay = 400)
    }
    function Pr(e) {
        W(e, "orderable", "bSortable"),
            W(e, "orderData", "aDataSort"),
            W(e, "orderSequence", "asSorting"),
            W(e, "orderDataType", "sortDataType");
        var r = e.aDataSort;
        typeof r == "number" && !Array.isArray(r) && (e.aDataSort = [r])
    }
    function Sa(e) {
        if (!b.__browser) {
            var r = {};
            b.__browser = r;
            var a = c("<div/>").css({
                position: "fixed",
                top: 0,
                left: -1 * N.pageXOffset,
                height: 1,
                width: 1,
                overflow: "hidden"
            }).append(c("<div/>").css({
                position: "absolute",
                top: 1,
                left: 1,
                width: 100,
                overflow: "scroll"
            }).append(c("<div/>").css({
                width: "100%",
                height: 10
            }))).appendTo("body")
                , n = a.children()
                , t = n.children();
            r.barWidth = n[0].offsetWidth - n[0].clientWidth,
                r.bScrollbarLeft = Math.round(t.offset().left) !== 1,
                a.remove()
        }
        c.extend(e.oBrowser, b.__browser),
            e.oScroll.iBarWidth = b.__browser.barWidth
    }
    function Or(e) {
        var r = b.defaults.column
            , a = e.aoColumns.length
            , n = c.extend({}, b.models.oColumn, r, {
                aDataSort: r.aDataSort ? r.aDataSort : [a],
                mData: r.mData ? r.mData : a,
                idx: a,
                searchFixed: {},
                colEl: c("<col>").attr("data-dt-column", a)
            });
        e.aoColumns.push(n);
        var t = e.aoPreSearchCols;
        t[a] = c.extend({}, b.models.oSearch, t[a])
    }
    function $e(e, r, a) {
        var n = e.aoColumns[r];
        if (a != null) {
            Pr(a),
                Q(b.defaults.column, a, !0),
                a.mDataProp !== void 0 && !a.mData && (a.mData = a.mDataProp),
                a.sType && (n._sManualType = a.sType),
                a.className && !a.sClass && (a.sClass = a.className);
            var t = n.sClass;
            c.extend(n, a),
                re(n, a, "sWidth", "sWidthOrig"),
                t !== n.sClass && (n.sClass = t + " " + n.sClass),
                a.iDataSort !== void 0 && (n.aDataSort = [a.iDataSort]),
                re(n, a, "aDataSort")
        }
        var l = n.mData
            , i = de(l);
        if (n.mRender && Array.isArray(n.mRender)) {
            var o = n.mRender.slice()
                , u = o.shift();
            n.mRender = b.render[u].apply(N, o)
        }
        n._render = n.mRender ? de(n.mRender) : null;
        var d = function (f) {
            return typeof f == "string" && f.indexOf("@") !== -1
        };
        n._bAttrSrc = c.isPlainObject(l) && (d(l.sort) || d(l.type) || d(l.filter)),
            n._setter = null,
            n.fnGetData = function (f, s, h) {
                var p = i(f, s, void 0, h);
                return n._render && s ? n._render(p, s, f, h) : p
            }
            ,
            n.fnSetData = function (f, s, h) {
                return ie(l)(f, s, h)
            }
            ,
            typeof l != "number" && !n._isArrayHost && (e._rowReadObject = !0),
            e.oFeatures.bSort || (n.bSortable = !1)
    }
    function Fe(e) {
        Va(e),
            wa(e);
        var r = e.oScroll;
        (r.sY !== "" || r.sX !== "") && Zr(e),
            w(e, null, "column-sizing", [e])
    }
    function wa(e) {
        for (var r = e.aoColumns, a = 0; a < r.length; a++) {
            var n = Er(e, [a], !1, !1);
            r[a].colEl.css("width", n),
                e.oScroll.sX && r[a].colEl.css("min-width", n)
        }
    }
    function er(e, r) {
        var a = rr(e, "bVisible");
        return typeof a[r] == "number" ? a[r] : null
    }
    function ye(e, r) {
        var a = rr(e, "bVisible")
            , n = a.indexOf(r);
        return n !== -1 ? n : null
    }
    function Ne(e) {
        var r = e.aoHeader
            , a = e.aoColumns
            , n = 0;
        if (r.length)
            for (var t = 0, l = r[0].length; t < l; t++)
                a[t].bVisible && c(r[0][t].cell).css("display") !== "none" && n++;
        return n
    }
    function rr(e, r) {
        var a = [];
        return e.aoColumns.map(function (n, t) {
            n[r] && a.push(t)
        }),
            a
    }
    function ar(e, r) {
        return r === !0 ? e._name : r
    }
    function nr(e) {
        var r = e.aoColumns, a = e.aoData, n = b.ext.type.detect, t, l, i, o, u, d, f, s, h;
        for (t = 0,
            l = r.length; t < l; t++) {
            if (f = r[t],
                h = [],
                !f.sType && f._sManualType)
                f.sType = f._sManualType;
            else if (!f.sType) {
                if (!e.typeDetect)
                    return;
                for (i = 0,
                    o = n.length; i < o; i++) {
                    var p = n[i]
                        , m = p.oneOf
                        , _ = p.allOf || p
                        , v = p.init
                        , D = !1;
                    if (s = null,
                        v && (s = ar(p, v(e, f, t)),
                            s)) {
                        f.sType = s;
                        break
                    }
                    for (u = 0,
                        d = a.length; u < d && !(a[u] && (h[u] === void 0 && (h[u] = q(e, u, t, "type")),
                            m && !D && (D = ar(p, m(h[u], e))),
                            s = ar(p, _(h[u], e)),
                            !s && i !== n.length - 3 || s === "html" && !B(h[u]))); u++)
                        ;
                    if (m && D && s || !m && s) {
                        f.sType = s;
                        break
                    }
                }
                f.sType || (f.sType = "string")
            }
            var y = F.type.className[f.sType];
            y && (jr(e.aoHeader, t, y),
                jr(e.aoFooter, t, y));
            var S = F.type.render[f.sType];
            S && !f._render && (f._render = b.util.get(S),
                ga(e, t))
        }
    }
    function ga(e, r) {
        for (var a = e.aoData, n = 0; n < a.length; n++)
            if (a[n].nTr) {
                var t = q(e, n, r, "display");
                a[n].displayData[r] = t,
                    Re(a[n].anCells[r], t)
            }
    }
    function jr(e, r, a) {
        e.forEach(function (n) {
            n[r] && n[r].unique && fe(n[r].cell, a)
        })
    }
    function La(e, r, a, n, t) {
        var l, i, o, u, d, f, s, h = e.aoColumns;
        if (a)
            for (l = 0,
                i = a.length; l < i; l++)
                a[l] && a[l].name && (h[l].sName = a[l].name);
        if (r)
            for (l = r.length - 1; l >= 0; l--) {
                s = r[l];
                var p = s.target !== void 0 ? s.target : s.targets !== void 0 ? s.targets : s.aTargets;
                for (Array.isArray(p) || (p = [p]),
                    o = 0,
                    u = p.length; o < u; o++) {
                    var m = p[o];
                    if (typeof m == "number" && m >= 0) {
                        for (; h.length <= m;)
                            Or(e);
                        t(m, s)
                    } else if (typeof m == "number" && m < 0)
                        t(h.length + m, s);
                    else if (typeof m == "string")
                        for (d = 0,
                            f = h.length; d < f; d++)
                            m === "_all" ? t(d, s) : m.indexOf(":name") !== -1 ? h[d].sName === m.replace(":name", "") && t(d, s) : n.forEach(function (_) {
                                if (_[d]) {
                                    var v = c(_[d].cell);
                                    m.match(/^[a-z][\w-]*$/i) && (m = "." + m),
                                        v.is(m) && t(d, s)
                                }
                            })
                }
            }
        if (a)
            for (l = 0,
                i = a.length; l < i; l++)
                t(l, a[l])
    }
    function Er(e, r, a, n) {
        Array.isArray(r) || (r = tr(r));
        for (var t = 0, l, i = e.aoColumns, o = 0, u = r.length; o < u; o++) {
            var d = i[r[o]]
                , f = a ? d.sWidthOrig : d.sWidth;
            if (!(!n && d.bVisible === !1)) {
                if (f == null)
                    return null;
                if (typeof f == "number")
                    l = "px",
                        t += f;
                else {
                    var s = f.match(/([\d\.]+)([^\d]*)/);
                    s && (t += s[1] * 1,
                        l = s.length === 3 ? s[2] : "px")
                }
            }
        }
        return t + l
    }
    function tr(e) {
        var r = c(e).closest("[data-dt-column]").attr("data-dt-column");
        return r ? r.split(",").map(function (a) {
            return a * 1
        }) : []
    }
    function le(e, r, a, n) {
        var t = e.aoData.length
            , l = c.extend(!0, {}, b.models.oRow, {
                src: a ? "dom" : "data",
                idx: t
            });
        l._aData = r,
            e.aoData.push(l);
        for (var i = e.aoColumns, o = 0, u = i.length; o < u; o++)
            i[o].sType = null;
        e.aiDisplayMaster.push(t);
        var d = e.rowIdFn(r);
        return d !== void 0 && (e.aIds[d] = l),
            (a || !e.oFeatures.bDeferRender) && Wr(e, t, a, n),
            t
    }
    function lr(e, r) {
        var a;
        return r instanceof c || (r = c(r)),
            r.map(function (n, t) {
                return a = Hr(e, t),
                    le(e, a.data, t, a.cells)
            })
    }
    function q(e, r, a, n) {
        n === "search" ? n = "filter" : n === "order" && (n = "sort");
        var t = e.aoData[r];
        if (t) {
            var l = e.iDraw
                , i = e.aoColumns[a]
                , o = t._aData
                , u = i.sDefaultContent
                , d = i.fnGetData(o, n, {
                    settings: e,
                    row: r,
                    col: a
                });
            if (n !== "display" && d && typeof d == "object" && d.nodeName && (d = d.innerHTML),
                d === void 0)
                return e.iDrawError != l && u === null && (U(e, 0, "Requested unknown parameter " + (typeof i.mData == "function" ? "{function}" : "'" + i.mData + "'") + " for row " + r + ", column " + a, 4),
                    e.iDrawError = l),
                    u;
            if ((d === o || d === null) && u !== null && n !== void 0)
                d = u;
            else if (typeof d == "function")
                return d.call(o);
            if (d === null && n === "display")
                return "";
            if (n === "filter") {
                var f = b.ext.type.search;
                f[i.sType] && (d = f[i.sType](d))
            }
            return d
        }
    }
    function Aa(e, r, a, n) {
        var t = e.aoColumns[a]
            , l = e.aoData[r]._aData;
        t.fnSetData(l, n, {
            settings: e,
            row: r,
            col: a
        })
    }
    function Re(e, r) {
        r && typeof r == "object" && r.nodeName ? c(e).empty().append(r) : e.innerHTML = r
    }
    var De = /\[.*?\]$/
        , ce = /\(\)$/;
    function Mr(e) {
        var r = e.match(/(\\.|[^.])+/g) || [""];
        return r.map(function (a) {
            return a.replace(/\\\./g, ".")
        })
    }
    var de = b.util.get
        , ie = b.util.set;
    function kr(e) {
        return O(e.aoData, "_aData")
    }
    function ir(e) {
        e.aoData.length = 0,
            e.aiDisplayMaster.length = 0,
            e.aiDisplay.length = 0,
            e.aIds = {}
    }
    function Ie(e, r, a, n) {
        var t = e.aoData[r], l, i;
        if (t._aSortData = null,
            t._aFilterData = null,
            t.displayData = null,
            a === "dom" || (!a || a === "auto") && t.src === "dom")
            t._aData = Hr(e, t, n, n === void 0 ? void 0 : t._aData).data;
        else {
            var o = t.anCells
                , u = or(e, r);
            if (o)
                if (n !== void 0)
                    Re(o[n], u[n]);
                else
                    for (l = 0,
                        i = o.length; l < i; l++)
                        Re(o[l], u[l])
        }
        var d = e.aoColumns;
        if (n !== void 0)
            d[n].sType = null,
                d[n].maxLenString = null;
        else {
            for (l = 0,
                i = d.length; l < i; l++)
                d[l].sType = null,
                    d[l].maxLenString = null;
            Br(e, t)
        }
    }
    function Hr(e, r, a, n) {
        var t = [], l = r.firstChild, i, o, u = 0, d, f = e.aoColumns, s = e._rowReadObject;
        n = n !== void 0 ? n : s ? {} : [];
        var h = function (y, S) {
            if (typeof y == "string") {
                var C = y.indexOf("@");
                if (C !== -1) {
                    var A = y.substring(C + 1)
                        , L = ie(y);
                    L(n, S.getAttribute(A))
                }
            }
        }
            , p = function (y) {
                if (a === void 0 || a === u)
                    if (o = f[u],
                        d = y.innerHTML.trim(),
                        o && o._bAttrSrc) {
                        var S = ie(o.mData._);
                        S(n, d),
                            h(o.mData.sort, y),
                            h(o.mData.type, y),
                            h(o.mData.filter, y)
                    } else
                        s ? (o._setter || (o._setter = ie(o.mData)),
                            o._setter(n, d)) : n[u] = d;
                u++
            };
        if (l)
            for (; l;)
                i = l.nodeName.toUpperCase(),
                    (i == "TD" || i == "TH") && (p(l),
                        t.push(l)),
                    l = l.nextSibling;
        else {
            t = r.anCells;
            for (var m = 0, _ = t.length; m < _; m++)
                p(t[m])
        }
        var v = r.firstChild ? r : r.nTr;
        if (v) {
            var D = v.getAttribute("id");
            D && ie(e.rowId)(n, D)
        }
        return {
            data: n,
            cells: t
        }
    }
    function or(e, r) {
        var a = e.aoData[r]
            , n = e.aoColumns;
        if (!a.displayData) {
            a.displayData = [];
            for (var t = 0, l = n.length; t < l; t++)
                a.displayData.push(q(e, r, t, "display"))
        }
        return a.displayData
    }
    function Wr(e, r, a, n) {
        var t = e.aoData[r], l = t._aData, i = [], o, u, d, f, s, h, p = e.oClasses.tbody.row;
        if (t.nTr === null) {
            for (o = a || I.createElement("tr"),
                t.nTr = o,
                t.anCells = i,
                fe(o, p),
                o._DT_RowIndex = r,
                Br(e, t),
                f = 0,
                s = e.aoColumns.length; f < s; f++) {
                d = e.aoColumns[f],
                    h = !(a && n[f]),
                    u = h ? I.createElement(d.sCellType) : n[f],
                    u || U(e, 0, "Incorrect column count", 18),
                    u._DT_CellIndex = {
                        row: r,
                        column: f
                    },
                    i.push(u);
                var m = or(e, r);
                (h || (d.mRender || d.mData !== f) && (!c.isPlainObject(d.mData) || d.mData._ !== f + ".display")) && Re(u, m[f]),
                    fe(u, d.sClass),
                    d.bVisible && h ? o.appendChild(u) : !d.bVisible && !h && u.parentNode.removeChild(u),
                    d.fnCreatedCell && d.fnCreatedCell.call(e.oInstance, u, q(e, r, f), l, r, f)
            }
            w(e, "aoRowCreatedCallback", "row-created", [o, l, r, i])
        } else
            fe(t.nTr, p)
    }
    function Br(e, r) {
        var a = r.nTr
            , n = r._aData;
        if (a) {
            var t = e.rowIdFn(n);
            if (t && (a.id = t),
                n.DT_RowClass) {
                var l = n.DT_RowClass.split(" ");
                r.__rowc = r.__rowc ? te(r.__rowc.concat(l)) : l,
                    c(a).removeClass(r.__rowc.join(" ")).addClass(n.DT_RowClass)
            }
            n.DT_RowAttr && c(a).attr(n.DT_RowAttr),
                n.DT_RowData && c(a).data(n.DT_RowData)
        }
    }
    function Vr(e, r) {
        var a = e.oClasses, n = e.aoColumns, t, l, i, o = r === "header" ? e.nTHead : e.nTFoot, u = r === "header" ? "sTitle" : r;
        if (o) {
            if ((r === "header" || O(e.aoColumns, u).join("")) && (i = c("tr", o),
                i.length || (i = c("<tr/>").appendTo(o)),
                i.length === 1)) {
                var d = 0;
                for (c("td, th", i).each(function () {
                    d += this.colSpan
                }),
                    t = d,
                    l = n.length; t < l; t++)
                    c("<th/>").html(n[t][u] || "").appendTo(i)
            }
            var f = zr(e, o, !0);
            r === "header" ? (e.aoHeader = f,
                c("tr", o).addClass(a.thead.row)) : (e.aoFooter = f,
                    c("tr", o).addClass(a.tfoot.row)),
                c(o).children("tr").children("th, td").each(function () {
                    Be(e, r)(e, c(this), a)
                })
        }
    }
    function Xr(e, r, a) {
        var n, t, l, i = [], o = [], u = e.aoColumns, d = u.length, f, s;
        if (r) {
            for (a || (a = K(d).filter(function (m) {
                return u[m].bVisible
            })),
                n = 0; n < r.length; n++)
                i[n] = r[n].slice().filter(function (m, _) {
                    return a.includes(_)
                }),
                    o.push([]);
            for (n = 0; n < i.length; n++)
                for (t = 0; t < i[n].length; t++)
                    if (f = 1,
                        s = 1,
                        o[n][t] === void 0) {
                        for (l = i[n][t].cell; i[n + f] !== void 0 && i[n][t].cell == i[n + f][t].cell;)
                            o[n + f][t] = null,
                                f++;
                        for (; i[n][t + s] !== void 0 && i[n][t].cell == i[n][t + s].cell;) {
                            for (var h = 0; h < f; h++)
                                o[n + h][t + s] = null;
                            s++
                        }
                        var p = c("span.dt-column-title", l);
                        o[n][t] = {
                            cell: l,
                            colspan: s,
                            rowspan: f,
                            title: p.length ? p.html() : c(l).html()
                        }
                    }
            return o
        }
    }
    function Pe(e, r) {
        for (var a = Xr(e, r), n, t, l = 0; l < r.length; l++) {
            if (n = r[l].row,
                n)
                for (; t = n.firstChild;)
                    n.removeChild(t);
            for (var i = 0; i < a[l].length; i++) {
                var o = a[l][i];
                o && c(o.cell).appendTo(n).attr("rowspan", o.rowspan).attr("colspan", o.colspan)
            }
        }
    }
    function se(e, r) {
        Pa(e);
        var a = w(e, "aoPreDrawCallback", "preDraw", [e]);
        if (a.indexOf(!1) !== -1) {
            V(e, !1);
            return
        }
        var n = []
            , t = 0
            , l = X(e) == "ssp"
            , i = e.aiDisplay
            , o = e._iDisplayStart
            , u = e.fnDisplayEnd()
            , d = e.aoColumns
            , f = c(e.nTBody);
        if (e.bDrawing = !0,
            e.deferLoading)
            e.deferLoading = !1,
                e.iDraw++,
                V(e, !1);
        else if (!l)
            e.iDraw++;
        else if (!e.bDestroying && !r) {
            e.iDraw === 0 && f.empty().append(qr(e)),
                Oa(e);
            return
        }
        if (i.length !== 0)
            for (var s = l ? 0 : o, h = l ? e.aoData.length : u, p = s; p < h; p++) {
                var m = i[p]
                    , _ = e.aoData[m];
                _.nTr === null && Wr(e, m);
                for (var v = _.nTr, D = 0; D < d.length; D++) {
                    var y = d[D]
                        , S = _.anCells[D];
                    fe(S, F.type.className[y.sType]),
                        fe(S, e.oClasses.tbody.cell)
                }
                w(e, "aoRowCallback", null, [v, _._aData, t, p, m]),
                    n.push(v),
                    t++
            }
        else
            n[0] = qr(e);
        w(e, "aoHeaderCallback", "header", [c(e.nTHead).children("tr")[0], kr(e), o, u, i]),
            w(e, "aoFooterCallback", "footer", [c(e.nTFoot).children("tr")[0], kr(e), o, u, i]),
            f[0].replaceChildren ? f[0].replaceChildren.apply(f[0], n) : (f.children().detach(),
                f.append(c(n))),
            c(e.nTableWrapper).toggleClass("dt-empty-footer", c("tr", e.nTFoot).length === 0),
            w(e, "aoDrawCallback", "draw", [e], !0),
            e.bSorted = !1,
            e.bFiltered = !1,
            e.bDrawing = !1
    }
    function he(e, r, a) {
        var n = e.oFeatures
            , t = n.bSort
            , l = n.bFilter;
        (a === void 0 || a === !0) && (nr(e),
            t && hr(e),
            l ? Te(e, e.oPreviousSearch) : e.aiDisplay = e.aiDisplayMaster.slice()),
            r !== !0 && (e._iDisplayStart = 0),
            e._drawHold = r,
            se(e),
            e._drawHold = !1
    }
    function qr(e) {
        var r = e.oLanguage
            , a = r.sZeroRecords
            , n = X(e);
        return e.iDraw < 1 && n === "ssp" || e.iDraw <= 1 && n === "ajax" ? a = r.sLoadingRecords : r.sEmptyTable && e.fnRecordsTotal() === 0 && (a = r.sEmptyTable),
            c("<tr/>").append(c("<td />", {
                colSpan: Ne(e),
                class: e.oClasses.empty.row
            }).html(a))[0]
    }
    function ur(e, r, a) {
        if (Array.isArray(a)) {
            for (var n = 0; n < a.length; n++)
                ur(e, r, a[n]);
            return
        }
        var t = e[r];
        c.isPlainObject(a) ? a.features ? (a.rowId && (e.id = a.rowId),
            a.rowClass && (e.className = a.rowClass),
            t.id = a.id,
            t.className = a.className,
            ur(e, r, a.features)) : Object.keys(a).map(function (l) {
                t.contents.push({
                    feature: l,
                    opts: a[l]
                })
            }) : t.contents.push(a)
    }
    function Fa(e, r, a) {
        for (var n, t = 0; t < e.length; t++)
            if (n = e[t],
                n.rowNum === r && (a === "full" && n.full || (a === "start" || a === "end") && (n.start || n.end)))
                return n[a] || (n[a] = {
                    contents: []
                }),
                    n;
        return n = {
            rowNum: r
        },
            n[a] = {
                contents: []
            },
            e.push(n),
            n
    }
    function Ur(e, r, a) {
        var n = [];
        c.each(r, function (l, i) {
            if (i !== null) {
                var o = l.match(/^([a-z]+)([0-9]*)([A-Za-z]*)$/)
                    , u = o[2] ? o[2] * 1 : 0
                    , d = o[3] ? o[3].toLowerCase() : "full";
                if (o[1] === a) {
                    var f = Fa(n, u, d);
                    ur(f, d, i)
                }
            }
        }),
            n.sort(function (l, i) {
                var o = l.rowNum
                    , u = i.rowNum;
                if (o === u) {
                    var d = l.full && !i.full ? -1 : 1;
                    return a === "bottom" ? d * -1 : d
                }
                return u - o
            }),
            a === "bottom" && n.reverse();
        for (var t = 0; t < n.length; t++)
            delete n[t].rowNum,
                Na(e, n[t]);
        return n
    }
    function Na(e, r) {
        var a = function (t, l) {
            return F.features[t] || U(e, 0, "Unknown feature: " + t),
                F.features[t].apply(this, [e, l])
        }
            , n = function (t) {
                if (r[t])
                    for (var l = r[t].contents, i = 0, o = l.length; i < o; i++)
                        if (l[i]) {
                            if (typeof l[i] == "string")
                                l[i] = a(l[i], null);
                            else if (c.isPlainObject(l[i]))
                                l[i] = a(l[i].feature, l[i].opts);
                            else if (typeof l[i].node == "function")
                                l[i] = l[i].node(e);
                            else if (typeof l[i] == "function") {
                                var u = l[i](e);
                                l[i] = typeof u.node == "function" ? u.node() : u
                            }
                        } else
                            continue
            };
        n("start"),
            n("end"),
            n("full")
    }
    function Ra(e) {
        var r = e.oClasses
            , a = c(e.nTable)
            , n = c("<div/>").attr({
                id: e.sTableId + "_wrapper",
                class: r.container
            }).insertBefore(a);
        if (e.nTableWrapper = n[0],
            e.sDom)
            Ia(e, e.sDom, n);
        else {
            var t = Ur(e, e.layout, "top")
                , l = Ur(e, e.layout, "bottom")
                , i = Be(e, "layout");
            t.forEach(function (o) {
                i(e, n, o)
            }),
                i(e, n, {
                    full: {
                        table: !0,
                        contents: [Jr(e)]
                    }
                }),
                l.forEach(function (o) {
                    i(e, n, o)
                })
        }
        Ba(e)
    }
    function Ia(e, r, a) {
        for (var n = r.match(/(".*?")|('.*?')|./g), t, l, i, o, u, d = 0; d < n.length; d++) {
            if (t = null,
                l = n[d],
                l == "<") {
                if (i = c("<div/>"),
                    o = n[d + 1],
                    o[0] == "'" || o[0] == '"') {
                    u = o.replace(/['"]/g, "");
                    var f = "", s;
                    if (u.indexOf(".") != -1) {
                        var h = u.split(".");
                        f = h[0],
                            s = h[1]
                    } else
                        u[0] == "#" ? f = u : s = u;
                    i.attr("id", f.substring(1)).addClass(s),
                        d++
                }
                a.append(i),
                    a = i
            } else
                l == ">" ? a = a.parent() : l == "t" ? t = Jr(e) : b.ext.feature.forEach(function (p) {
                    l == p.cFeature && (t = p.fnInit(e))
                });
            t && a.append(t)
        }
    }
    function zr(e, r, a) {
        var n = e.aoColumns, t = c(r).children("tr"), l, i, o, u, d, f, s, h, p, m, _ = r && r.nodeName.toLowerCase() === "thead", v = [], D, y = function (R, j, G) {
            for (var J = R[j]; J[G];)
                G++;
            return G
        };
        for (o = 0,
            f = t.length; o < f; o++)
            v.push([]);
        for (o = 0,
            f = t.length; o < f; o++)
            for (l = t[o],
                h = 0,
                i = l.firstChild; i;) {
                if (i.nodeName.toUpperCase() == "TD" || i.nodeName.toUpperCase() == "TH") {
                    var S = [];
                    if (p = i.getAttribute("colspan") * 1,
                        m = i.getAttribute("rowspan") * 1,
                        p = !p || p === 0 || p === 1 ? 1 : p,
                        m = !m || m === 0 || m === 1 ? 1 : m,
                        s = y(v, o, h),
                        D = p === 1,
                        a) {
                        if (D) {
                            $e(e, s, c(i).data());
                            var C = n[s]
                                , A = i.getAttribute("width") || null
                                , L = i.style.width.match(/width:\s*(\d+[pxem%]+)/);
                            L && (A = L[1]),
                                C.sWidthOrig = C.sWidth || A,
                                _ ? (C.sTitle !== null && !C.autoTitle && (i.innerHTML = C.sTitle),
                                    !C.sTitle && D && (C.sTitle = Z(i.innerHTML),
                                        C.autoTitle = !0)) : C.footer && (i.innerHTML = C.footer),
                                C.ariaTitle || (C.ariaTitle = c(i).attr("aria-label") || C.sTitle),
                                C.className && c(i).addClass(C.className)
                        }
                        c("span.dt-column-title", i).length === 0 && c("<span>").addClass("dt-column-title").append(i.childNodes).appendTo(i),
                            _ && c("span.dt-column-order", i).length === 0 && c("<span>").addClass("dt-column-order").appendTo(i)
                    }
                    for (d = 0; d < p; d++) {
                        for (u = 0; u < m; u++)
                            v[o + u][s + d] = {
                                cell: i,
                                unique: D
                            },
                                v[o + u].row = l;
                        S.push(s + d)
                    }
                    i.setAttribute("data-dt-column", te(S).join(","))
                }
                i = i.nextSibling
            }
        return v
    }
    function Pa(e) {
        var r = X(e) == "ssp"
            , a = e.iInitDisplayStart;
        a !== void 0 && a !== -1 && (e._iDisplayStart = r ? a : a >= e.fnRecordsDisplay() ? 0 : a,
            e.iInitDisplayStart = -1)
    }
    function fr(e, r, a) {
        var n, t = e.ajax, l = e.oInstance, i = function (f) {
            var s = e.jqXHR ? e.jqXHR.status : null;
            (f === null || typeof s == "number" && s == 204) && (f = {},
                Oe(e, f, []));
            var h = f.error || f.sError;
            if (h && U(e, 0, h),
                f.d && typeof f.d == "string")
                try {
                    f = JSON.parse(f.d)
                } catch { }
            e.json = f,
                w(e, null, "xhr", [e, f, e.jqXHR], !0),
                a(f)
        };
        if (c.isPlainObject(t) && t.data) {
            n = t.data;
            var o = typeof n == "function" ? n(r, e) : n;
            r = typeof n == "function" && o ? o : c.extend(!0, r, o),
                delete t.data
        }
        var u = {
            url: typeof t == "string" ? t : "",
            data: r,
            success: i,
            dataType: "json",
            cache: !1,
            type: e.sServerMethod,
            error: function (f, s) {
                var h = w(e, null, "xhr", [e, null, e.jqXHR], !0);
                h.indexOf(!0) === -1 && (s == "parsererror" ? U(e, 0, "Invalid JSON response", 1) : f.readyState === 4 && U(e, 0, "Ajax error", 7)),
                    V(e, !1)
            }
        };
        if (c.isPlainObject(t) && c.extend(u, t),
            e.oAjaxData = r,
            w(e, null, "preXhr", [e, r, u], !0),
            typeof t == "function")
            e.jqXHR = t.call(l, r, i, e);
        else if (t.url === "") {
            var d = {};
            b.util.set(t.dataSrc)(d, []),
                i(d)
        } else
            e.jqXHR = c.ajax(u);
        n && (t.data = n)
    }
    function Oa(e) {
        e.iDraw++,
            V(e, !0),
            fr(e, ja(e), function (r) {
                Ea(e, r)
            })
    }
    function ja(e) {
        var r = e.aoColumns
            , a = e.oFeatures
            , n = e.oPreviousSearch
            , t = e.aoPreSearchCols
            , l = function (i, o) {
                return typeof r[i][o] == "function" ? "function" : r[i][o]
            };
        return {
            draw: e.iDraw,
            columns: r.map(function (i, o) {
                return {
                    data: l(o, "mData"),
                    name: i.sName,
                    searchable: i.bSearchable,
                    orderable: i.bSortable,
                    search: {
                        value: t[o].search,
                        regex: t[o].regex,
                        fixed: Object.keys(i.searchFixed).map(function (u) {
                            return {
                                name: u,
                                term: i.searchFixed[u].toString()
                            }
                        })
                    }
                }
            }),
            order: He(e).map(function (i) {
                return {
                    column: i.col,
                    dir: i.dir,
                    name: l(i.col, "sName")
                }
            }),
            start: e._iDisplayStart,
            length: a.bPaginate ? e._iDisplayLength : -1,
            search: {
                value: n.search,
                regex: n.regex,
                fixed: Object.keys(e.searchFixed).map(function (i) {
                    return {
                        name: i,
                        term: e.searchFixed[i].toString()
                    }
                })
            }
        }
    }
    function Ea(e, r) {
        var a = Oe(e, r)
            , n = cr(e, "draw", r)
            , t = cr(e, "recordsTotal", r)
            , l = cr(e, "recordsFiltered", r);
        if (n !== void 0) {
            if (n * 1 < e.iDraw)
                return;
            e.iDraw = n * 1
        }
        a || (a = []),
            ir(e),
            e._iRecordsTotal = parseInt(t, 10),
            e._iRecordsDisplay = parseInt(l, 10);
        for (var i = 0, o = a.length; i < o; i++)
            le(e, a[i]);
        e.aiDisplay = e.aiDisplayMaster.slice(),
            nr(e),
            se(e, !0),
            ke(e),
            V(e, !1)
    }
    function Oe(e, r, a) {
        var n = "data";
        if (c.isPlainObject(e.ajax) && e.ajax.dataSrc !== void 0) {
            var t = e.ajax.dataSrc;
            typeof t == "string" || typeof t == "function" ? n = t : t.data !== void 0 && (n = t.data)
        }
        if (!a)
            return n === "data" ? r.aaData || r[n] : n !== "" ? de(n)(r) : r;
        ie(n)(r, a)
    }
    function cr(e, r, a) {
        var n = c.isPlainObject(e.ajax) ? e.ajax.dataSrc : null;
        if (n && n[r])
            return de(n[r])(a);
        var t = "";
        return r === "draw" ? t = "sEcho" : r === "recordsTotal" ? t = "iTotalRecords" : r === "recordsFiltered" && (t = "iTotalDisplayRecords"),
            a[t] !== void 0 ? a[t] : a[r]
    }
    function Te(e, r) {
        var a = e.aoPreSearchCols;
        if (X(e) != "ssp") {
            Wa(e),
                e.aiDisplay = e.aiDisplayMaster.slice(),
                je(e.aiDisplay, e, r.search, r),
                c.each(e.searchFixed, function (l, i) {
                    je(e.aiDisplay, e, i, {})
                });
            for (var n = 0; n < a.length; n++) {
                var t = a[n];
                je(e.aiDisplay, e, t.search, t, n),
                    c.each(e.aoColumns[n].searchFixed, function (l, i) {
                        je(e.aiDisplay, e, i, {}, n)
                    })
            }
            Ma(e)
        }
        e.bFiltered = !0,
            w(e, null, "search", [e])
    }
    function Ma(e) {
        for (var r = b.ext.search, a = e.aiDisplay, n, t, l = 0, i = r.length; l < i; l++) {
            for (var o = [], u = 0, d = a.length; u < d; u++)
                t = a[u],
                    n = e.aoData[t],
                    r[l](e, n._aFilterData, t, n._aData, u) && o.push(t);
            a.length = 0,
                Ve(a, o)
        }
    }
    function je(e, r, a, n, t) {
        if (a !== "") {
            var l = 0
                , i = []
                , o = typeof a == "function" ? a : null
                , u = a instanceof RegExp ? a : o ? null : ka(a, n);
            for (l = 0; l < e.length; l++) {
                var d = r.aoData[e[l]]
                    , f = t === void 0 ? d._sFilterRow : d._aFilterData[t];
                (o && o(f, d._aData, e[l], t) || u && u.test(f)) && i.push(e[l])
            }
            for (e.length = i.length,
                l = 0; l < i.length; l++)
                e[l] = i[l]
        }
    }
    function ka(e, r) {
        var a = []
            , n = c.extend({}, {
                boundary: !1,
                caseInsensitive: !0,
                exact: !1,
                regex: !1,
                smart: !0
            }, r);
        if (typeof e != "string" && (e = e.toString()),
            e = Le(e),
            n.exact)
            return new RegExp("^" + dr(e) + "$", n.caseInsensitive ? "i" : "");
        if (e = n.regex ? e : dr(e),
            n.smart) {
            var t = e.match(/!?["\u201C][^"\u201D]+["\u201D]|[^ ]+/g) || [""]
                , l = t.map(function (u) {
                    var d = !1, f;
                    return u.charAt(0) === "!" && (d = !0,
                        u = u.substring(1)),
                        u.charAt(0) === '"' ? (f = u.match(/^"(.*)"$/),
                            u = f ? f[1] : u) : u.charAt(0) === "\u201C" && (f = u.match(/^\u201C(.*)\u201D$/),
                                u = f ? f[1] : u),
                        d && (u.length > 1 && a.push("(?!" + u + ")"),
                            u = ""),
                        u.replace(/"/g, "")
                })
                , i = a.length ? a.join("") : ""
                , o = n.boundary ? "\\b" : "";
            e = "^(?=.*?" + o + l.join(")(?=.*?" + o) + ")(" + i + ".)*$"
        }
        return new RegExp(e, n.caseInsensitive ? "i" : "")
    }
    var dr = b.util.escapeRegex
        , Ee = c("<div>")[0]
        , Ha = Ee.textContent !== void 0;
    function Wa(e) {
        for (var r = e.aoColumns, a = e.aoData, n, t, l, i, o, u, d = !1, f = 0; f < a.length; f++)
            if (a[f] && (u = a[f],
                !u._aFilterData)) {
                for (i = [],
                    t = 0,
                    l = r.length; t < l; t++)
                    n = r[t],
                        n.bSearchable ? (o = q(e, f, t, "filter"),
                            o === null && (o = ""),
                            typeof o != "string" && o.toString && (o = o.toString())) : o = "",
                        o.indexOf && o.indexOf("&") !== -1 && (Ee.innerHTML = o,
                            o = Ha ? Ee.textContent : Ee.innerText),
                        o.replace && (o = o.replace(/[\r\n\u2028]/g, "")),
                        i.push(o);
                u._aFilterData = i,
                    u._sFilterRow = i.join("  "),
                    d = !0
            }
        return d
    }
    function Me(e) {
        var r, a = e.oInit, n = e.deferLoading, t = X(e);
        if (!e.bInitialised) {
            setTimeout(function () {
                Me(e)
            }, 200);
            return
        }
        Vr(e, "header"),
            Vr(e, "footer"),
            Ga(e, a, function () {
                Pe(e, e.aoHeader),
                    Pe(e, e.aoFooter);
                var l = e.iInitDisplayStart;
                if (a.aaData)
                    for (r = 0; r < a.aaData.length; r++)
                        le(e, a.aaData[r]);
                else
                    (n || t == "dom") && lr(e, c(e.nTBody).children("tr"));
                e.aiDisplay = e.aiDisplayMaster.slice(),
                    Ra(e),
                    qa(e),
                    Qr(e),
                    V(e, !0),
                    w(e, null, "preInit", [e], !0),
                    he(e),
                    (t != "ssp" || n) && (t == "ajax" ? fr(e, {}, function (i) {
                        var o = Oe(e, i);
                        for (r = 0; r < o.length; r++)
                            le(e, o[r]);
                        e.iInitDisplayStart = l,
                            he(e),
                            V(e, !1),
                            ke(e)
                    }, e) : (ke(e),
                        V(e, !1)))
            })
    }
    function ke(e) {
        if (!e._bInitComplete) {
            var r = [e, e.json];
            e._bInitComplete = !0,
                Fe(e),
                w(e, null, "plugin-init", r, !0),
                w(e, "aoInitComplete", "init", r, !0)
        }
    }
    function Gr(e, r) {
        var a = parseInt(r, 10);
        e._iDisplayLength = a,
            na(e),
            w(e, null, "length", [e, a])
    }
    function sr(e, r, a) {
        var n = e._iDisplayStart
            , t = e._iDisplayLength
            , l = e.fnRecordsDisplay();
        if (l === 0 || t === -1)
            n = 0;
        else if (typeof r == "number")
            n = r * t,
                n > l && (n = 0);
        else if (r == "first")
            n = 0;
        else if (r == "previous")
            n = t >= 0 ? n - t : 0,
                n < 0 && (n = 0);
        else if (r == "next")
            n + t < l && (n += t);
        else if (r == "last")
            n = Math.floor((l - 1) / t) * t;
        else {
            if (r === "ellipsis")
                return;
            U(e, 0, "Unknown paging action: " + r, 5)
        }
        var i = e._iDisplayStart !== n;
        return e._iDisplayStart = n,
            w(e, null, i ? "page" : "page-nc", [e]),
            i && a && se(e),
            i
    }
    function Ba(e) {
        var r = e.nTable
            , a = e.oScroll.sX !== "" || e.oScroll.sY !== "";
        if (e.oFeatures.bProcessing) {
            var n = c("<div/>", {
                id: e.sTableId + "_processing",
                class: e.oClasses.processing.container,
                role: "status"
            }).html(e.oLanguage.sProcessing).append("<div><div></div><div></div><div></div><div></div></div>");
            a ? n.prependTo(c("div.dt-scroll", e.nTableWrapper)) : n.insertBefore(r),
                c(r).on("processing.dt.DT", function (t, l, i) {
                    n.css("display", i ? "block" : "none")
                })
        }
    }
    function V(e, r) {
        e.bDrawing && r === !1 || w(e, null, "processing", [e, r])
    }
    function Yr(e, r, a) {
        r ? (V(e, !0),
            setTimeout(function () {
                a(),
                    V(e, !1)
            }, 0)) : a()
    }
    function Jr(e) {
        var r = c(e.nTable)
            , a = e.oScroll;
        if (a.sX === "" && a.sY === "")
            return e.nTable;
        var n = a.sX
            , t = a.sY
            , l = e.oClasses.scrolling
            , i = e.captionNode
            , o = i ? i._captionSide : null
            , u = c(r[0].cloneNode(!1))
            , d = c(r[0].cloneNode(!1))
            , f = r.children("tfoot")
            , s = "<div/>"
            , h = function (y) {
                return y ? ee(y) : null
            };
        f.length || (f = null);
        var p = c(s, {
            class: l.container
        }).append(c(s, {
            class: l.header.self
        }).css({
            overflow: "hidden",
            position: "relative",
            border: 0,
            width: n ? h(n) : "100%"
        }).append(c(s, {
            class: l.header.inner
        }).css({
            "box-sizing": "content-box",
            width: a.sXInner || "100%"
        }).append(u.removeAttr("id").css("margin-left", 0).append(o === "top" ? i : null).append(r.children("thead"))))).append(c(s, {
            class: l.body
        }).css({
            position: "relative",
            overflow: "auto",
            width: h(n)
        }).append(r));
        f && p.append(c(s, {
            class: l.footer.self
        }).css({
            overflow: "hidden",
            border: 0,
            width: n ? h(n) : "100%"
        }).append(c(s, {
            class: l.footer.inner
        }).append(d.removeAttr("id").css("margin-left", 0).append(o === "bottom" ? i : null).append(r.children("tfoot")))));
        var m = p.children()
            , _ = m[0]
            , v = m[1]
            , D = f ? m[2] : null;
        return c(v).on("scroll.DT", function () {
            var y = this.scrollLeft;
            _.scrollLeft = y,
                f && (D.scrollLeft = y)
        }),
            c("th, td", _).on("focus", function () {
                var y = _.scrollLeft;
                v.scrollLeft = y,
                    f && (v.scrollLeft = y)
            }),
            c(v).css("max-height", t),
            a.bCollapse || c(v).css("height", t),
            e.nScrollHead = _,
            e.nScrollBody = v,
            e.nScrollFoot = D,
            e.aoDrawCallback.push(Zr),
            p[0]
    }
    function Zr(e) {
        var r = e.oScroll, a = r.iBarWidth, n = c(e.nScrollHead), t = n.children("div"), l = t.children("table"), i = e.nScrollBody, o = c(i), u = c(e.nScrollFoot), d = u.children("div"), f = d.children("table"), s = c(e.nTHead), h = c(e.nTable), p = e.nTFoot && c("th, td", e.nTFoot).length ? c(e.nTFoot) : null, m = e.oBrowser, _, v, D = i.scrollHeight > i.clientHeight;
        if (e.scrollBarVis !== D && e.scrollBarVis !== void 0) {
            e.scrollBarVis = D,
                Fe(e);
            return
        } else
            e.scrollBarVis = D;
        if (h.children("thead, tfoot").remove(),
            _ = s.clone().prependTo(h),
            _.find("th, td").removeAttr("tabindex"),
            _.find("[id]").removeAttr("id"),
            p && (v = p.clone().prependTo(h),
                v.find("[id]").removeAttr("id")),
            e.aiDisplay.length) {
            var y = null
                , S = X(e) !== "ssp" ? e._iDisplayStart : 0;
            for (R = S; R < S + e.aiDisplay.length; R++) {
                var C = e.aiDisplay[R]
                    , A = e.aoData[C].nTr;
                if (A) {
                    y = A;
                    break
                }
            }
            if (y)
                for (var L = c(y).children("th, td").map(function (Se) {
                    return {
                        idx: er(e, Se),
                        width: c(this).outerWidth()
                    }
                }), R = 0; R < L.length; R++) {
                    var j = e.aoColumns[L[R].idx].colEl[0]
                        , G = j.style.width.replace("px", "");
                    G !== L[R].width && (j.style.width = L[R].width + "px",
                        r.sX && (j.style.minWidth = L[R].width + "px"))
                }
        }
        l.find("colgroup").remove(),
            l.append(e.colgroup.clone()),
            p && (f.find("colgroup").remove(),
                f.append(e.colgroup.clone())),
            c("th, td", _).each(function () {
                c(this.childNodes).wrapAll('<div class="dt-scroll-sizing">')
            }),
            p && c("th, td", v).each(function () {
                c(this.childNodes).wrapAll('<div class="dt-scroll-sizing">')
            });
        var J = Math.floor(h.height()) > i.clientHeight || o.css("overflow-y") == "scroll"
            , P = "padding" + (m.bScrollbarLeft ? "Left" : "Right")
            , ne = h.outerWidth();
        l.css("width", ee(ne)),
            t.css("width", ee(ne)).css(P, J ? a + "px" : "0px"),
            p && (f.css("width", ee(ne)),
                d.css("width", ee(ne)).css(P, J ? a + "px" : "0px")),
            h.children("colgroup").prependTo(h),
            o.trigger("scroll"),
            (e.bSorted || e.bFiltered) && !e._drawHold && (i.scrollTop = 0)
    }
    function Va(e) {
        if (e.oFeatures.bAutoWidth) {
            var r = e.nTable, a = e.aoColumns, n = e.oScroll, t = n.sY, l = n.sX, i = n.sXInner, o = rr(e, "bVisible"), u = r.getAttribute("width"), d = r.parentNode, f, s, h, p = r.style.width, m = Kr(e);
            if (m === e.containerWidth)
                return !1;
            e.containerWidth = m,
                !p && !u && (r.style.width = "100%",
                    p = "100%"),
                p && p.indexOf("%") !== -1 && (u = p),
                w(e, null, "column-calc", {
                    visible: o
                }, !1);
            var _ = c(r.cloneNode()).css("visibility", "hidden").removeAttr("id");
            _.append("<tbody>");
            var v = c("<tr/>").appendTo(_.find("tbody"));
            for (_.append(c(e.nTHead).clone()).append(c(e.nTFoot).clone()),
                _.find("tfoot th, tfoot td").css("width", ""),
                _.find("thead th, thead td").each(function () {
                    var P = Er(e, this, !0, !1);
                    P ? (this.style.width = P,
                        l && (this.style.minWidth = P,
                            c(this).append(c("<div/>").css({
                                width: P,
                                margin: 0,
                                padding: 0,
                                border: 0,
                                height: 1
                            })))) : this.style.width = ""
                }),
                f = 0; f < o.length; f++) {
                h = o[f],
                    s = a[h];
                var D = Xa(e, h)
                    , y = F.type.className[s.sType]
                    , S = D + s.sContentPadding
                    , C = D.indexOf("<") === -1 ? I.createTextNode(S) : S;
                c("<td/>").addClass(y).addClass(s.sClass).append(C).appendTo(v)
            }
            c("[name]", _).removeAttr("name");
            var A = c("<div/>").css(l || t ? {
                position: "absolute",
                top: 0,
                left: 0,
                height: 1,
                right: 0,
                overflow: "hidden"
            } : {}).append(_).appendTo(d);
            l && i ? _.width(i) : l ? (_.css("width", "auto"),
                _.removeAttr("width"),
                _.outerWidth() < d.clientWidth && u && _.outerWidth(d.clientWidth)) : t ? _.outerWidth(d.clientWidth) : u && _.outerWidth(u);
            var L = 0
                , R = _.find("tbody tr").eq(0).children();
            for (f = 0; f < o.length; f++) {
                var j = R[f].getBoundingClientRect().width;
                L += j,
                    a[o[f]].sWidth = ee(j)
            }
            if (r.style.width = ee(L),
                A.remove(),
                u && (r.style.width = ee(u)),
                (u || l) && !e._reszEvt) {
                var G = b.util.throttle(function () {
                    var P = Kr(e);
                    !e.bDestroying && P !== 0 && Fe(e)
                });
                if (N.ResizeObserver) {
                    var J = c(e.nTableWrapper).is(":visible");
                    e.resizeObserver = new ResizeObserver(function (P) {
                        J ? J = !1 : G()
                    }
                    ),
                        e.resizeObserver.observe(e.nTableWrapper)
                } else
                    c(N).on("resize.DT-" + e.sInstance, G);
                e._reszEvt = !0
            }
        }
    }
    function Kr(e) {
        return c(e.nTableWrapper).is(":visible") ? c(e.nTableWrapper).width() : 0
    }
    function Xa(e, r) {
        var a = e.aoColumns[r];
        if (!a.maxLenString) {
            for (var n, t = "", l = -1, i = 0, o = e.aiDisplayMaster.length; i < o; i++) {
                var u = e.aiDisplayMaster[i]
                    , d = or(e, u)[r]
                    , f = d && typeof d == "object" && d.nodeType ? d.innerHTML : d + "";
                f = f.replace(/id=".*?"/g, "").replace(/name=".*?"/g, ""),
                    n = Z(f).replace(/&nbsp;/g, " "),
                    n.length > l && (t = f,
                        l = n.length)
            }
            a.maxLenString = t
        }
        return a.maxLenString
    }
    function ee(e) {
        return e === null ? "0px" : typeof e == "number" ? e < 0 ? "0px" : e + "px" : e.match(/\d$/) ? e + "px" : e
    }
    function Qr(e) {
        var r = e.aoColumns;
        for (e.colgroup.empty(),
            k = 0; k < r.length; k++)
            r[k].bVisible && e.colgroup.append(r[k].colEl)
    }
    function qa(e) {
        var r = e.nTHead
            , a = r.querySelectorAll("tr")
            , n = e.bSortCellsTop
            , t = ':not([data-dt-order="disable"]):not([data-dt-order="icon-only"])';
        n === !0 ? r = a[0] : n === !1 && (r = a[a.length - 1]),
            $r(e, r, r === e.nTHead ? "tr" + t + " th" + t + ", tr" + t + " td" + t : "th" + t + ", td" + t);
        var l = [];
        ve(e, l, e.aaSorting),
            e.aaSorting = l
    }
    function $r(e, r, a, n, t) {
        aa(r, a, function (l) {
            var i = !1
                , o = n === void 0 ? tr(l.target) : [n];
            if (o.length) {
                for (var u = 0, d = o.length; u < d; u++) {
                    var f = Ua(e, o[u], u, l.shiftKey);
                    if (f !== !1 && (i = !0),
                        e.aaSorting.length === 1 && e.aaSorting[0][1] === "")
                        break
                }
                i && Yr(e, !0, function () {
                    hr(e),
                        ea(e, e.aiDisplay),
                        he(e, !1, !1),
                        t && t()
                })
            }
        })
    }
    function ea(e, r) {
        if (!(r.length < 2)) {
            var a = e.aiDisplayMaster, n = {}, t = {}, l;
            for (l = 0; l < a.length; l++)
                n[a[l]] = l;
            for (l = 0; l < r.length; l++)
                t[r[l]] = n[r[l]];
            r.sort(function (i, o) {
                return t[i] - t[o]
            })
        }
    }
    function ve(e, r, a) {
        var n = function (l) {
            if (c.isPlainObject(l)) {
                if (l.idx !== void 0)
                    r.push([l.idx, l.dir]);
                else if (l.name) {
                    var i = O(e.aoColumns, "sName")
                        , o = i.indexOf(l.name);
                    o !== -1 && r.push([o, l.dir])
                }
            } else
                r.push(l)
        };
        if (c.isPlainObject(a))
            n(a);
        else if (a.length && typeof a[0] == "number")
            n(a);
        else if (a.length)
            for (var t = 0; t < a.length; t++)
                n(a[t])
    }
    function He(e) {
        var r, a, n, t = [], l = b.ext.type.order, i = e.aoColumns, o, u, d, f, s = e.aaSortingFixed, h = c.isPlainObject(s), p = [];
        if (!e.oFeatures.bSort)
            return t;
        for (Array.isArray(s) && ve(e, p, s),
            h && s.pre && ve(e, p, s.pre),
            ve(e, p, e.aaSorting),
            h && s.post && ve(e, p, s.post),
            r = 0; r < p.length; r++)
            if (f = p[r][0],
                i[f])
                for (o = i[f].aDataSort,
                    a = 0,
                    n = o.length; a < n; a++)
                    u = o[a],
                        d = i[u].sType || "string",
                        p[r]._idx === void 0 && (p[r]._idx = i[u].asSorting.indexOf(p[r][1])),
                        p[r][1] && t.push({
                            src: f,
                            col: u,
                            dir: p[r][1],
                            index: p[r]._idx,
                            type: d,
                            formatter: l[d + "-pre"],
                            sorter: l[d + "-" + p[r][1]]
                        });
        return t
    }
    function hr(e, r, a) {
        var n, t, l, i = [], o = b.ext.type.order, u = e.aoData, d, f = e.aiDisplayMaster, s;
        if (r !== void 0) {
            var h = e.aoColumns[r];
            s = [{
                src: r,
                col: r,
                dir: a,
                index: 0,
                type: h.sType,
                formatter: o[h.sType + "-pre"],
                sorter: o[h.sType + "-" + a]
            }],
                f = f.slice()
        } else
            s = He(e);
        for (n = 0,
            t = s.length; n < t; n++)
            d = s[n],
                za(e, d.col);
        if (X(e) != "ssp" && s.length !== 0) {
            for (n = 0,
                l = f.length; n < l; n++)
                i[n] = n;
            s.length && s[0].dir === "desc" && e.orderDescReverse && i.reverse(),
                f.sort(function (p, m) {
                    var _, v, D, y, S, C = s.length, A = u[p]._aSortData, L = u[m]._aSortData;
                    for (D = 0; D < C; D++)
                        if (S = s[D],
                            _ = A[S.col],
                            v = L[S.col],
                            S.sorter) {
                            if (y = S.sorter(_, v),
                                y !== 0)
                                return y
                        } else if (y = _ < v ? -1 : _ > v ? 1 : 0,
                            y !== 0)
                            return S.dir === "asc" ? y : -y;
                    return _ = i[p],
                        v = i[m],
                        _ < v ? -1 : _ > v ? 1 : 0
                })
        } else
            s.length === 0 && f.sort(function (p, m) {
                return p < m ? -1 : p > m ? 1 : 0
            });
        return r === void 0 && (e.bSorted = !0,
            e.sortDetails = s,
            w(e, null, "order", [e, s])),
            f
    }
    function Ua(e, r, a, n) {
        var t = e.aoColumns[r], l = e.aaSorting, i = t.asSorting, o, u = function (f, s) {
            var h = f._idx;
            return h === void 0 && (h = i.indexOf(f[1])),
                h + 1 < i.length ? h + 1 : s ? null : 0
        };
        if (!t.bSortable)
            return !1;
        if (typeof l[0] == "number" && (l = e.aaSorting = [l]),
            (n || a) && e.oFeatures.bSortMulti) {
            var d = O(l, "0").indexOf(r);
            d !== -1 ? (o = u(l[d], !0),
                o === null && l.length === 1 && (o = 0),
                o === null ? l.splice(d, 1) : (l[d][1] = i[o],
                    l[d]._idx = o)) : n ? (l.push([r, i[0], 0]),
                        l[l.length - 1]._idx = 0) : (l.push([r, l[0][1], 0]),
                            l[l.length - 1]._idx = 0)
        } else
            l.length && l[0][0] == r ? (o = u(l[0]),
                l.length = 1,
                l[0][1] = i[o],
                l[0]._idx = o) : (l.length = 0,
                    l.push([r, i[0]]),
                    l[0]._idx = 0)
    }
    function vr(e) {
        var r = e.aLastSort, a = e.oClasses.order.position, n = He(e), t = e.oFeatures, l, i, o;
        if (t.bSort && t.bSortClasses) {
            for (l = 0,
                i = r.length; l < i; l++)
                o = r[l].src,
                    c(O(e.aoData, "anCells", o)).removeClass(a + (l < 2 ? l + 1 : 3));
            for (l = 0,
                i = n.length; l < i; l++)
                o = n[l].src,
                    c(O(e.aoData, "anCells", o)).addClass(a + (l < 2 ? l + 1 : 3))
        }
        e.aLastSort = n
    }
    function za(e, r) {
        var a = e.aoColumns[r], n = b.ext.order[a.sSortDataType], t;
        n && (t = n.call(e.oInstance, e, r, ye(e, r)));
        for (var l, i, o = b.ext.type.order[a.sType + "-pre"], u = e.aoData, d = 0; d < u.length; d++)
            u[d] && (l = u[d],
                l._aSortData || (l._aSortData = []),
                (!l._aSortData[r] || n) && (i = n ? t[d] : q(e, d, r, "sort"),
                    l._aSortData[r] = o ? o(i, e) : i))
    }
    function We(e) {
        if (!e._bLoadingState) {
            var r = [];
            ve(e, r, e.aaSorting);
            var a = e.aoColumns
                , n = {
                    time: +new Date,
                    start: e._iDisplayStart,
                    length: e._iDisplayLength,
                    order: r.map(function (t) {
                        return a[t[0]] && a[t[0]].sName ? [a[t[0]].sName, t[1]] : t.slice()
                    }),
                    search: c.extend({}, e.oPreviousSearch),
                    columns: e.aoColumns.map(function (t, l) {
                        return {
                            name: t.sName,
                            visible: t.bVisible,
                            search: c.extend({}, e.aoPreSearchCols[l])
                        }
                    })
                };
            e.oSavedState = n,
                w(e, "aoStateSaveParams", "stateSaveParams", [e, n]),
                e.oFeatures.bStateSave && !e.bDestroying && e.fnStateSaveCallback.call(e.oInstance, e, n)
        }
    }
    function Ga(e, r, a) {
        if (!e.oFeatures.bStateSave) {
            a();
            return
        }
        var n = function (l) {
            pr(e, l, a)
        }
            , t = e.fnStateLoadCallback.call(e.oInstance, e, n);
        return t !== void 0 && pr(e, t, a),
            !0
    }
    function pr(e, r, a) {
        var n, t, l = e.aoColumns, i = O(e.aoColumns, "sName");
        e._bLoadingState = !0;
        var o = e._bInitComplete ? new b.Api(e) : null;
        if (!r || !r.time) {
            e._bLoadingState = !1,
                a();
            return
        }
        var u = e.iStateDuration;
        if (u > 0 && r.time < +new Date - u * 1e3) {
            e._bLoadingState = !1,
                a();
            return
        }
        var d = w(e, "aoStateLoadParams", "stateLoadParams", [e, r]);
        if (d.indexOf(!1) !== -1) {
            e._bLoadingState = !1,
                a();
            return
        }
        if (e.oLoadedState = c.extend(!0, {}, r),
            w(e, null, "stateLoadInit", [e, r], !0),
            r.length !== void 0 && (o ? o.page.len(r.length) : e._iDisplayLength = r.length),
            r.start !== void 0 && (o === null ? (e._iDisplayStart = r.start,
                e.iInitDisplayStart = r.start) : sr(e, r.start / e._iDisplayLength)),
            r.order !== void 0 && (e.aaSorting = [],
                c.each(r.order, function (m, _) {
                    var v = [_[0], _[1]];
                    if (typeof _[0] == "string") {
                        var D = i.indexOf(_[0]);
                        v[0] = D >= 0 ? D : 0
                    } else
                        v[0] >= l.length && (v[0] = 0);
                    e.aaSorting.push(v)
                })),
            r.search !== void 0 && c.extend(e.oPreviousSearch, r.search),
            r.columns) {
            var f = r.columns
                , s = O(r.columns, "name");
            if (s.join("").length && s.join("") !== i.join(""))
                for (f = [],
                    n = 0; n < i.length; n++)
                    if (i[n] != "") {
                        var h = s.indexOf(i[n]);
                        h >= 0 ? f.push(r.columns[h]) : f.push({})
                    } else
                        f.push({});
            if (f.length === l.length) {
                for (n = 0,
                    t = f.length; n < t; n++) {
                    var p = f[n];
                    p.visible !== void 0 && (o ? o.column(n).visible(p.visible, !1) : l[n].bVisible = p.visible),
                        p.search !== void 0 && c.extend(e.aoPreSearchCols[n], p.search)
                }
                o && o.columns.adjust()
            }
        }
        e._bLoadingState = !1,
            w(e, "aoStateLoaded", "stateLoaded", [e, r]),
            a()
    }
    function U(e, r, a, n) {
        if (a = "DataTables warning: " + (e ? "table id=" + e.sTableId + " - " : "") + a,
            n && (a += ". For more information about this error, please see https://datatables.net/tn/" + n),
            r)
            N.console && console.log && console.log(a);
        else {
            var t = b.ext
                , l = t.sErrMode || t.errMode;
            if (e && w(e, null, "dt-error", [e, n, a], !0),
                l == "alert")
                alert(a);
            else {
                if (l == "throw")
                    throw new Error(a);
                typeof l == "function" && l(e, n, a)
            }
        }
    }
    function re(e, r, a, n) {
        if (Array.isArray(a)) {
            c.each(a, function (t, l) {
                Array.isArray(l) ? re(e, r, l[0], l[1]) : re(e, r, l)
            });
            return
        }
        n === void 0 && (n = a),
            r[a] !== void 0 && (e[n] = r[a])
    }
    function ra(e, r, a) {
        var n;
        for (var t in r)
            Object.prototype.hasOwnProperty.call(r, t) && (n = r[t],
                c.isPlainObject(n) ? (c.isPlainObject(e[t]) || (e[t] = {}),
                    c.extend(!0, e[t], n)) : a && t !== "data" && t !== "aaData" && Array.isArray(n) ? e[t] = n.slice() : e[t] = n);
        return e
    }
    function aa(e, r, a) {
        c(e).on("click.DT", r, function (n) {
            a(n)
        }).on("keypress.DT", r, function (n) {
            n.which === 13 && (n.preventDefault(),
                a(n))
        }).on("selectstart.DT", r, function () {
            return !1
        })
    }
    function z(e, r, a) {
        a && e[r].push(a)
    }
    function w(e, r, a, n, t) {
        var l = [];
        if (r && (l = e[r].slice().reverse().map(function (u) {
            return u.apply(e.oInstance, n)
        })),
            a !== null) {
            var i = c.Event(a + ".dt")
                , o = c(e.nTable);
            i.dt = e.api,
                o[t ? "trigger" : "triggerHandler"](i, n),
                t && o.parents("body").length === 0 && c("body").trigger(i, n),
                l.push(i.result)
        }
        return l
    }
    function na(e) {
        var r = e._iDisplayStart
            , a = e.fnDisplayEnd()
            , n = e._iDisplayLength;
        r >= a && (r = a - n),
            r -= r % n,
            (n === -1 || r < 0) && (r = 0),
            e._iDisplayStart = r
    }
    function Be(e, r) {
        var a = e.renderer
            , n = b.ext.renderer[r];
        return c.isPlainObject(a) && a[r] ? n[a[r]] || n._ : typeof a == "string" && n[a] || n._
    }
    function X(e) {
        return e.oFeatures.bServerSide ? "ssp" : e.ajax ? "ajax" : "dom"
    }
    function br(e, r, a) {
        var n = e.fnFormatNumber
            , t = e._iDisplayStart + 1
            , l = e._iDisplayLength
            , i = e.fnRecordsDisplay()
            , o = e.fnRecordsTotal()
            , u = l === -1;
        return r.replace(/_START_/g, n.call(e, t)).replace(/_END_/g, n.call(e, e.fnDisplayEnd())).replace(/_MAX_/g, n.call(e, o)).replace(/_TOTAL_/g, n.call(e, i)).replace(/_PAGE_/g, n.call(e, u ? 1 : Math.ceil(t / l))).replace(/_PAGES_/g, n.call(e, u ? 1 : Math.ceil(i / l))).replace(/_ENTRIES_/g, e.api.i18n("entries", "", a)).replace(/_ENTRIES-MAX_/g, e.api.i18n("entries", "", o)).replace(/_ENTRIES-TOTAL_/g, e.api.i18n("entries", "", i))
    }
    function Ve(e, r) {
        if (r)
            if (r.length < 1e4)
                e.push.apply(e, r);
            else
                for (k = 0; k < r.length; k++)
                    e.push(r[k])
    }
    var mr = []
        , M = Array.prototype
        , Ya = function (e) {
            var r, a, n = b.settings, t = O(n, "nTable");
            if (e) {
                if (e.nTable && e.oFeatures)
                    return [e];
                if (e.nodeName && e.nodeName.toLowerCase() === "table")
                    return r = t.indexOf(e),
                        r !== -1 ? [n[r]] : null;
                if (e && typeof e.settings == "function")
                    return e.settings().toArray();
                typeof e == "string" ? a = c(e).get() : e instanceof c && (a = e.get())
            } else
                return [];
            if (a)
                return n.filter(function (l, i) {
                    return a.includes(t[i])
                })
        };
    x = function (e, r) {
        if (!(this instanceof x))
            return new x(e, r);
        var a, n = [], t = function (l) {
            var i = Ya(l);
            i && n.push.apply(n, i)
        };
        if (Array.isArray(e))
            for (a = 0; a < e.length; a++)
                t(e[a]);
        else
            t(e);
        this.context = n.length > 1 ? te(n) : n,
            Ve(this, r),
            this.selector = {
                rows: null,
                cols: null,
                opts: null
            },
            x.extend(this, this, mr)
    }
        ,
        b.Api = x,
        c.extend(x.prototype, {
            any: function () {
                return this.count() !== 0
            },
            context: [],
            count: function () {
                return this.flatten().length
            },
            each: function (e) {
                for (var r = 0, a = this.length; r < a; r++)
                    e.call(this, this[r], r, this);
                return this
            },
            eq: function (e) {
                var r = this.context;
                return r.length > e ? new x(r[e], this[e]) : null
            },
            filter: function (e) {
                var r = M.filter.call(this, e, this);
                return new x(this.context, r)
            },
            flatten: function () {
                var e = [];
                return new x(this.context, e.concat.apply(e, this.toArray()))
            },
            get: function (e) {
                return this[e]
            },
            join: M.join,
            includes: function (e) {
                return this.indexOf(e) !== -1
            },
            indexOf: M.indexOf,
            iterator: function (e, r, a, n) {
                var t = [], l, i, o, u, d, f = this.context, s, h, p, m = this.selector;
                for (typeof e == "string" && (n = a,
                    a = r,
                    r = e,
                    e = !1),
                    i = 0,
                    o = f.length; i < o; i++) {
                    var _ = new x(f[i]);
                    if (r === "table")
                        l = a.call(_, f[i], i),
                            l !== void 0 && t.push(l);
                    else if (r === "columns" || r === "rows")
                        l = a.call(_, f[i], this[i], i),
                            l !== void 0 && t.push(l);
                    else if (r === "every" || r === "column" || r === "column-rows" || r === "row" || r === "cell")
                        for (h = this[i],
                            r === "column-rows" && (s = Xe(f[i], m.opts)),
                            u = 0,
                            d = h.length; u < d; u++)
                            p = h[u],
                                r === "cell" ? l = a.call(_, f[i], p.row, p.column, i, u) : l = a.call(_, f[i], p, i, u, s),
                                l !== void 0 && t.push(l)
                }
                if (t.length || n) {
                    var v = new x(f, e ? t.concat.apply([], t) : t)
                        , D = v.selector;
                    return D.rows = m.rows,
                        D.cols = m.cols,
                        D.opts = m.opts,
                        v
                }
                return this
            },
            lastIndexOf: M.lastIndexOf,
            length: 0,
            map: function (e) {
                var r = M.map.call(this, e, this);
                return new x(this.context, r)
            },
            pluck: function (e) {
                var r = b.util.get(e);
                return this.map(function (a) {
                    return r(a)
                })
            },
            pop: M.pop,
            push: M.push,
            reduce: M.reduce,
            reduceRight: M.reduceRight,
            reverse: M.reverse,
            selector: null,
            shift: M.shift,
            slice: function () {
                return new x(this.context, this)
            },
            sort: M.sort,
            splice: M.splice,
            toArray: function () {
                return M.slice.call(this)
            },
            to$: function () {
                return c(this)
            },
            toJQuery: function () {
                return c(this)
            },
            unique: function () {
                return new x(this.context, te(this.toArray()))
            },
            unshift: M.unshift
        });
    function Ja(e, r, a) {
        return function () {
            var n = r.apply(e || this, arguments);
            return x.extend(n, n, a.methodExt),
                n
        }
    }
    function Za(e, r) {
        for (var a = 0, n = e.length; a < n; a++)
            if (e[a].name === r)
                return e[a];
        return null
    }
    N.__apiStruct = mr,
        x.extend = function (e, r, a) {
            if (!(!a.length || !r || !(r instanceof x) && !r.__dt_wrapper)) {
                var n, t, l;
                for (n = 0,
                    t = a.length; n < t; n++)
                    l = a[n],
                        l.name !== "__proto__" && (r[l.name] = l.type === "function" ? Ja(e, l.val, l) : l.type === "object" ? {} : l.val,
                            r[l.name].__dt_wrapper = !0,
                            x.extend(e, r[l.name], l.propExt))
            }
        }
        ,
        x.register = T = function (e, r) {
            if (Array.isArray(e)) {
                for (var a = 0, n = e.length; a < n; a++)
                    x.register(e[a], r);
                return
            }
            var t, l, i = e.split("."), o = mr, u, d;
            for (t = 0,
                l = i.length; t < l; t++) {
                d = i[t].indexOf("()") !== -1,
                    u = d ? i[t].replace("()", "") : i[t];
                var f = Za(o, u);
                f || (f = {
                    name: u,
                    val: {},
                    methodExt: [],
                    propExt: [],
                    type: "object"
                },
                    o.push(f)),
                    t === l - 1 ? (f.val = r,
                        f.type = typeof r == "function" ? "function" : c.isPlainObject(r) ? "object" : "other") : o = d ? f.methodExt : f.propExt
            }
        }
        ,
        x.registerPlural = g = function (e, r, a) {
            x.register(e, a),
                x.register(r, function () {
                    var n = a.apply(this, arguments);
                    return n === this ? this : n instanceof x ? n.length ? Array.isArray(n[0]) ? new x(n.context, n[0]) : n[0] : void 0 : n
                })
        }
        ;
    var ta = function (e, r) {
        if (Array.isArray(e)) {
            var a = [];
            return e.forEach(function (t) {
                var l = ta(t, r);
                Ve(a, l)
            }),
                a.filter(function (t) {
                    return t
                })
        }
        if (typeof e == "number")
            return [r[e]];
        var n = r.map(function (t) {
            return t.nTable
        });
        return c(n).filter(e).map(function () {
            var t = n.indexOf(this);
            return r[t]
        }).toArray()
    };
    T("tables()", function (e) {
        return e != null ? new x(ta(e, this.context)) : this
    }),
        T("table()", function (e) {
            var r = this.tables(e)
                , a = r.context;
            return a.length ? new x(a[0]) : r
        }),
        [["nodes", "node", "nTable"], ["body", "body", "nTBody"], ["header", "header", "nTHead"], ["footer", "footer", "nTFoot"]].forEach(function (e) {
            g("tables()." + e[0] + "()", "table()." + e[1] + "()", function () {
                return this.iterator("table", function (r) {
                    return r[e[2]]
                }, 1)
            })
        }),
        [["header", "aoHeader"], ["footer", "aoFooter"]].forEach(function (e) {
            T("table()." + e[0] + ".structure()", function (r) {
                var a = this.columns(r).indexes().flatten()
                    , n = this.context[0];
                return Xr(n, n[e[1]], a)
            })
        }),
        g("tables().containers()", "table().container()", function () {
            return this.iterator("table", function (e) {
                return e.nTableWrapper
            }, 1)
        }),
        T("tables().every()", function (e) {
            var r = this;
            return this.iterator("table", function (a, n) {
                e.call(r.table(n), n)
            })
        }),
        T("caption()", function (e, r) {
            var a = this.context;
            if (e === void 0) {
                var n = a[0].captionNode;
                return n && a.length ? n.innerHTML : null
            }
            return this.iterator("table", function (t) {
                var l = c(t.nTable)
                    , i = c(t.captionNode)
                    , o = c(t.nTableWrapper);
                if (i.length || (i = c("<caption/>").html(e),
                    t.captionNode = i[0],
                    r || (l.prepend(i),
                        r = i.css("caption-side"))),
                    i.html(e),
                    r && (i.css("caption-side", r),
                        i[0]._captionSide = r),
                    o.find("div.dataTables_scroll").length) {
                    var u = r === "top" ? "Head" : "Foot";
                    o.find("div.dataTables_scroll" + u + " table").prepend(i)
                } else
                    l.prepend(i)
            }, 1)
        }),
        T("caption.node()", function () {
            var e = this.context;
            return e.length ? e[0].captionNode : null
        }),
        T("draw()", function (e) {
            return this.iterator("table", function (r) {
                e === "page" ? se(r) : (typeof e == "string" && (e = e !== "full-hold"),
                    he(r, e === !1))
            })
        }),
        T("page()", function (e) {
            return e === void 0 ? this.page.info().page : this.iterator("table", function (r) {
                sr(r, e)
            })
        }),
        T("page.info()", function () {
            if (this.context.length !== 0) {
                var e = this.context[0]
                    , r = e._iDisplayStart
                    , a = e.oFeatures.bPaginate ? e._iDisplayLength : -1
                    , n = e.fnRecordsDisplay()
                    , t = a === -1;
                return {
                    page: t ? 0 : Math.floor(r / a),
                    pages: t ? 1 : Math.ceil(n / a),
                    start: r,
                    end: e.fnDisplayEnd(),
                    length: a,
                    recordsTotal: e.fnRecordsTotal(),
                    recordsDisplay: n,
                    serverSide: X(e) === "ssp"
                }
            }
        }),
        T("page.len()", function (e) {
            return e === void 0 ? this.context.length !== 0 ? this.context[0]._iDisplayLength : void 0 : this.iterator("table", function (r) {
                Gr(r, e)
            })
        });
    var la = function (e, r, a) {
        if (a) {
            var n = new x(e);
            n.one("draw", function () {
                a(n.ajax.json())
            })
        }
        if (X(e) == "ssp")
            he(e, r);
        else {
            V(e, !0);
            var t = e.jqXHR;
            t && t.readyState !== 4 && t.abort(),
                fr(e, {}, function (l) {
                    ir(e);
                    for (var i = Oe(e, l), o = 0, u = i.length; o < u; o++)
                        le(e, i[o]);
                    he(e, r),
                        ke(e),
                        V(e, !1)
                })
        }
    };
    T("ajax.json()", function () {
        var e = this.context;
        if (e.length > 0)
            return e[0].json
    }),
        T("ajax.params()", function () {
            var e = this.context;
            if (e.length > 0)
                return e[0].oAjaxData
        }),
        T("ajax.reload()", function (e, r) {
            return this.iterator("table", function (a) {
                la(a, r === !1, e)
            })
        }),
        T("ajax.url()", function (e) {
            var r = this.context;
            return e === void 0 ? r.length === 0 ? void 0 : (r = r[0],
                c.isPlainObject(r.ajax) ? r.ajax.url : r.ajax) : this.iterator("table", function (a) {
                    c.isPlainObject(a.ajax) ? a.ajax.url = e : a.ajax = e
                })
        }),
        T("ajax.url().load()", function (e, r) {
            return this.iterator("table", function (a) {
                la(a, r === !1, e)
            })
        });
    var _r = function (e, r, a, n, t) {
        var l = [], i, o, u, d, f, s, h = typeof r;
        for ((!r || h === "string" || h === "function" || r.length === void 0) && (r = [r]),
            u = 0,
            d = r.length; u < d; u++)
            for (o = r[u] && r[u].split && !r[u].match(/[[(:]/) ? r[u].split(",") : [r[u]],
                f = 0,
                s = o.length; f < s; f++)
                i = a(typeof o[f] == "string" ? o[f].trim() : o[f]),
                    i = i.filter(function (m) {
                        return m != null
                    }),
                    i && i.length && (l = l.concat(i));
        var p = F.selector[e];
        if (p.length)
            for (u = 0,
                d = p.length; u < d; u++)
                l = p[u](n, t, l);
        return te(l)
    }
        , yr = function (e) {
            return e || (e = {}),
                e.filter && e.search === void 0 && (e.search = e.filter),
                c.extend({
                    search: "none",
                    order: "current",
                    page: "all"
                }, e)
        }
        , Dr = function (e) {
            var r = new x(e.context[0]);
            return e.length && r.push(e[0]),
                r.selector = e.selector,
                r.length && r[0].length > 1 && r[0].splice(1),
                r
        }
        , Xe = function (e, r) {
            var a, n, t, l = [], i = e.aiDisplay, o = e.aiDisplayMaster, u = r.search, d = r.order, f = r.page;
            if (X(e) == "ssp")
                return u === "removed" ? [] : K(0, o.length);
            if (f == "current")
                for (a = e._iDisplayStart,
                    n = e.fnDisplayEnd(); a < n; a++)
                    l.push(i[a]);
            else if (d == "current" || d == "applied") {
                if (u == "none")
                    l = o.slice();
                else if (u == "applied")
                    l = i.slice();
                else if (u == "removed") {
                    var s = {};
                    for (a = 0,
                        n = i.length; a < n; a++)
                        s[i[a]] = null;
                    o.forEach(function (p) {
                        Object.prototype.hasOwnProperty.call(s, p) || l.push(p)
                    })
                }
            } else if (d == "index" || d == "original")
                for (a = 0,
                    n = e.aoData.length; a < n; a++)
                    e.aoData[a] && (u == "none" ? l.push(a) : (t = i.indexOf(a),
                        (t === -1 && u == "removed" || t >= 0 && u == "applied") && l.push(a)));
            else if (typeof d == "number") {
                var h = hr(e, d, "asc");
                if (u === "none")
                    l = h;
                else
                    for (a = 0; a < h.length; a++)
                        t = i.indexOf(h[a]),
                            (t === -1 && u == "removed" || t >= 0 && u == "applied") && l.push(h[a])
            }
            return l
        }
        , Ka = function (e, r, a) {
            var n, t = function (i) {
                var o = Fr(i)
                    , u = e.aoData;
                if (o !== null && !a)
                    return [o];
                if (n || (n = Xe(e, a)),
                    o !== null && n.indexOf(o) !== -1)
                    return [o];
                if (i == null || i === "")
                    return n;
                if (typeof i == "function")
                    return n.map(function (m) {
                        var _ = u[m];
                        return i(m, _._aData, _.nTr) ? m : null
                    });
                if (i.nodeName) {
                    var d = i._DT_RowIndex
                        , f = i._DT_CellIndex;
                    if (d !== void 0)
                        return u[d] && u[d].nTr === i ? [d] : [];
                    if (f)
                        return u[f.row] && u[f.row].nTr === i.parentNode ? [f.row] : [];
                    var s = c(i).closest("*[data-dt-row]");
                    return s.length ? [s.data("dt-row")] : []
                }
                if (typeof i == "string" && i.charAt(0) === "#") {
                    var h = e.aIds[i.replace(/^#/, "")];
                    if (h !== void 0)
                        return [h.idx]
                }
                var p = Rr(_e(e.aoData, n, "nTr"));
                return c(p).filter(i).map(function () {
                    return this._DT_RowIndex
                }).toArray()
            }, l = _r("row", r, t, e, a);
            return (a.order === "current" || a.order === "applied") && ea(e, l),
                l
        };
    T("rows()", function (e, r) {
        e === void 0 ? e = "" : c.isPlainObject(e) && (r = e,
            e = ""),
            r = yr(r);
        var a = this.iterator("table", function (n) {
            return Ka(n, e, r)
        }, 1);
        return a.selector.rows = e,
            a.selector.opts = r,
            a
    }),
        T("rows().nodes()", function () {
            return this.iterator("row", function (e, r) {
                return e.aoData[r].nTr || void 0
            }, 1)
        }),
        T("rows().data()", function () {
            return this.iterator(!0, "rows", function (e, r) {
                return _e(e.aoData, r, "_aData")
            }, 1)
        }),
        g("rows().cache()", "row().cache()", function (e) {
            return this.iterator("row", function (r, a) {
                var n = r.aoData[a];
                return e === "search" ? n._aFilterData : n._aSortData
            }, 1)
        }),
        g("rows().invalidate()", "row().invalidate()", function (e) {
            return this.iterator("row", function (r, a) {
                Ie(r, a, e)
            })
        }),
        g("rows().indexes()", "row().index()", function () {
            return this.iterator("row", function (e, r) {
                return r
            }, 1)
        }),
        g("rows().ids()", "row().id()", function (e) {
            for (var r = [], a = this.context, n = 0, t = a.length; n < t; n++)
                for (var l = 0, i = this[n].length; l < i; l++) {
                    var o = a[n].rowIdFn(a[n].aoData[this[n][l]]._aData);
                    r.push((e === !0 ? "#" : "") + o)
                }
            return new x(a, r)
        }),
        g("rows().remove()", "row().remove()", function () {
            return this.iterator("row", function (e, r) {
                var a = e.aoData
                    , n = a[r]
                    , t = e.aiDisplayMaster.indexOf(r);
                t !== -1 && e.aiDisplayMaster.splice(t, 1),
                    e._iRecordsDisplay > 0 && e._iRecordsDisplay--,
                    na(e);
                var l = e.rowIdFn(n._aData);
                l !== void 0 && delete e.aIds[l],
                    a[r] = null
            }),
                this
        }),
        T("rows.add()", function (e) {
            var r = this.iterator("table", function (n) {
                var t, l, i, o = [];
                for (l = 0,
                    i = e.length; l < i; l++)
                    t = e[l],
                        t.nodeName && t.nodeName.toUpperCase() === "TR" ? o.push(lr(n, t)[0]) : o.push(le(n, t));
                return o
            }, 1)
                , a = this.rows(-1);
            return a.pop(),
                Ve(a, r),
                a
        }),
        T("row()", function (e, r) {
            return Dr(this.rows(e, r))
        }),
        T("row().data()", function (e) {
            var r = this.context;
            if (e === void 0)
                return r.length && this.length && this[0].length ? r[0].aoData[this[0]]._aData : void 0;
            var a = r[0].aoData[this[0]];
            return a._aData = e,
                Array.isArray(e) && a.nTr && a.nTr.id && ie(r[0].rowId)(e, a.nTr.id),
                Ie(r[0], this[0], "data"),
                this
        }),
        T("row().node()", function () {
            var e = this.context;
            if (e.length && this.length && this[0].length) {
                var r = e[0].aoData[this[0]];
                if (r && r.nTr)
                    return r.nTr
            }
            return null
        }),
        T("row.add()", function (e) {
            e instanceof c && e.length && (e = e[0]);
            var r = this.iterator("table", function (a) {
                return e.nodeName && e.nodeName.toUpperCase() === "TR" ? lr(a, e)[0] : le(a, e)
            });
            return this.row(r[0])
        }),
        c(I).on("plugin-init.dt", function (e, r) {
            var a = new x(r);
            a.on("stateSaveParams.DT", function (n, t, l) {
                for (var i = t.rowIdFn, o = t.aiDisplayMaster, u = [], d = 0; d < o.length; d++) {
                    var f = o[d]
                        , s = t.aoData[f];
                    s._detailsShow && u.push("#" + i(s._aData))
                }
                l.childRows = u
            }),
                a.on("stateLoaded.DT", function (n, t, l) {
                    ia(a, l)
                }),
                ia(a, a.state.loaded())
        });
    var ia = function (e, r) {
        r && r.childRows && e.rows(r.childRows.map(function (a) {
            return a.replace(/([^:\\]*(?:\\.[^:\\]*)*):/g, "$1\\:")
        })).every(function () {
            w(e.settings()[0], null, "requestChild", [this])
        })
    }
        , Qa = function (e, r, a, n) {
            var t = []
                , l = function (i, o) {
                    if (Array.isArray(i) || i instanceof c) {
                        for (var u = 0, d = i.length; u < d; u++)
                            l(i[u], o);
                        return
                    }
                    if (i.nodeName && i.nodeName.toLowerCase() === "tr")
                        i.setAttribute("data-dt-row", r.idx),
                            t.push(i);
                    else {
                        var f = c("<tr><td></td></tr>").attr("data-dt-row", r.idx).addClass(o);
                        c("td", f).addClass(o).html(i)[0].colSpan = Ne(e),
                            t.push(f[0])
                    }
                };
            l(a, n),
                r._details && r._details.detach(),
                r._details = c(t),
                r._detailsShow && r._details.insertAfter(r.nTr)
        }
        , oa = b.util.throttle(function (e) {
            We(e[0])
        }, 500)
        , Tr = function (e, r) {
            var a = e.context;
            if (a.length) {
                var n = a[0].aoData[r !== void 0 ? r : e[0]];
                n && n._details && (n._details.remove(),
                    n._detailsShow = void 0,
                    n._details = void 0,
                    c(n.nTr).removeClass("dt-hasChild"),
                    oa(a))
            }
        }
        , ua = function (e, r) {
            var a = e.context;
            if (a.length && e.length) {
                var n = a[0].aoData[e[0]];
                n._details && (n._detailsShow = r,
                    r ? (n._details.insertAfter(n.nTr),
                        c(n.nTr).addClass("dt-hasChild")) : (n._details.detach(),
                            c(n.nTr).removeClass("dt-hasChild")),
                    w(a[0], null, "childRow", [r, e.row(e[0])]),
                    $a(a[0]),
                    oa(a))
            }
        }
        , $a = function (e) {
            var r = new x(e)
                , a = ".dt.DT_details"
                , n = "draw" + a
                , t = "column-sizing" + a
                , l = "destroy" + a
                , i = e.aoData;
            r.off(n + " " + t + " " + l),
                O(i, "_details").length > 0 && (r.on(n, function (o, u) {
                    e === u && r.rows({
                        page: "current"
                    }).eq(0).each(function (d) {
                        var f = i[d];
                        f._detailsShow && f._details.insertAfter(f.nTr)
                    })
                }),
                    r.on(t, function (o, u) {
                        if (e === u)
                            for (var d, f = Ne(u), s = 0, h = i.length; s < h; s++)
                                d = i[s],
                                    d && d._details && d._details.each(function () {
                                        var p = c(this).children("td");
                                        p.length == 1 && p.attr("colspan", f)
                                    })
                    }),
                    r.on(l, function (o, u) {
                        if (e === u)
                            for (var d = 0, f = i.length; d < f; d++)
                                i[d] && i[d]._details && Tr(r, d)
                    }))
        }
        , en = ""
        , Ce = en + "row().child"
        , qe = Ce + "()";
    T(qe, function (e, r) {
        var a = this.context;
        return e === void 0 ? a.length && this.length && a[0].aoData[this[0]] ? a[0].aoData[this[0]]._details : void 0 : (e === !0 ? this.child.show() : e === !1 ? Tr(this) : a.length && this.length && Qa(a[0], a[0].aoData[this[0]], e, r),
            this)
    }),
        T([Ce + ".show()", qe + ".show()"], function () {
            return ua(this, !0),
                this
        }),
        T([Ce + ".hide()", qe + ".hide()"], function () {
            return ua(this, !1),
                this
        }),
        T([Ce + ".remove()", qe + ".remove()"], function () {
            return Tr(this),
                this
        }),
        T(Ce + ".isShown()", function () {
            var e = this.context;
            return e.length && this.length && e[0].aoData[this[0]] && e[0].aoData[this[0]]._detailsShow || !1
        });
    var rn = /^([^:]+)?:(name|title|visIdx|visible)$/
        , Cr = function (e, r, a, n, t, l) {
            for (var i = [], o = 0, u = t.length; o < u; o++)
                i.push(q(e, t[o], r, l));
            return i
        }
        , fa = function (e, r, a) {
            var n = e.aoHeader
                , t = a !== void 0 ? a : e.bSortCellsTop ? 0 : n.length - 1;
            return n[t][r].cell
        }
        , an = function (e, r, a) {
            var n = e.aoColumns
                , t = O(n, "sName")
                , l = O(n, "sTitle")
                , i = b.util.get("[].[].cell")(e.aoHeader)
                , o = te(Qe([], i))
                , u = function (d) {
                    var f = Fr(d);
                    if (d === "")
                        return K(n.length);
                    if (f !== null)
                        return [f >= 0 ? f : n.length + f];
                    if (typeof d == "function") {
                        var s = Xe(e, a);
                        return n.map(function (D, y) {
                            return d(y, Cr(e, y, 0, 0, s), fa(e, y)) ? y : null
                        })
                    }
                    var h = typeof d == "string" ? d.match(rn) : "";
                    if (h)
                        switch (h[2]) {
                            case "visIdx":
                            case "visible":
                                if (h[1] && h[1].match(/^\d+$/)) {
                                    var p = parseInt(h[1], 10);
                                    if (p < 0) {
                                        var m = n.map(function (D, y) {
                                            return D.bVisible ? y : null
                                        });
                                        return [m[m.length + p]]
                                    }
                                    return [er(e, p)]
                                }
                                return n.map(function (D, y) {
                                    return D.bVisible ? h[1] ? c(o[y]).filter(h[1]).length > 0 ? y : null : y : null
                                });
                            case "name":
                                return t.map(function (D, y) {
                                    return D === h[1] ? y : null
                                });
                            case "title":
                                return l.map(function (D, y) {
                                    return D === h[1] ? y : null
                                });
                            default:
                                return []
                        }
                    if (d.nodeName && d._DT_CellIndex)
                        return [d._DT_CellIndex.column];
                    var _ = c(o).filter(d).map(function () {
                        return tr(this)
                    }).toArray().sort(function (D, y) {
                        return D - y
                    });
                    if (_.length || !d.nodeName)
                        return _;
                    var v = c(d).closest("*[data-dt-column]");
                    return v.length ? [v.data("dt-column")] : []
                };
            return _r("column", r, u, e, a)
        }
        , nn = function (e, r, a) {
            var n = e.aoColumns, t = n[r], l = e.aoData, i, o, u, d;
            if (a === void 0)
                return t.bVisible;
            if (t.bVisible === a)
                return !1;
            if (a) {
                var f = O(n, "bVisible").indexOf(!0, r + 1);
                for (o = 0,
                    u = l.length; o < u; o++)
                    l[o] && (d = l[o].nTr,
                        i = l[o].anCells,
                        d && d.insertBefore(i[r], i[f] || null))
            } else
                c(O(e.aoData, "anCells", r)).detach();
            return t.bVisible = a,
                Qr(e),
                !0
        };
    T("columns()", function (e, r) {
        e === void 0 ? e = "" : c.isPlainObject(e) && (r = e,
            e = ""),
            r = yr(r);
        var a = this.iterator("table", function (n) {
            return an(n, e, r)
        }, 1);
        return a.selector.cols = e,
            a.selector.opts = r,
            a
    }),
        g("columns().header()", "column().header()", function (e) {
            return this.iterator("column", function (r, a) {
                return fa(r, a, e)
            }, 1)
        }),
        g("columns().footer()", "column().footer()", function (e) {
            return this.iterator("column", function (r, a) {
                var n = r.aoFooter;
                return n.length ? r.aoFooter[e !== void 0 ? e : 0][a].cell : null
            }, 1)
        }),
        g("columns().data()", "column().data()", function () {
            return this.iterator("column-rows", Cr, 1)
        }),
        g("columns().render()", "column().render()", function (e) {
            return this.iterator("column-rows", function (r, a, n, t, l) {
                return Cr(r, a, n, t, l, e)
            }, 1)
        }),
        g("columns().dataSrc()", "column().dataSrc()", function () {
            return this.iterator("column", function (e, r) {
                return e.aoColumns[r].mData
            }, 1)
        }),
        g("columns().cache()", "column().cache()", function (e) {
            return this.iterator("column-rows", function (r, a, n, t, l) {
                return _e(r.aoData, l, e === "search" ? "_aFilterData" : "_aSortData", a)
            }, 1)
        }),
        g("columns().init()", "column().init()", function () {
            return this.iterator("column", function (e, r) {
                return e.aoColumns[r]
            }, 1)
        }),
        g("columns().nodes()", "column().nodes()", function () {
            return this.iterator("column-rows", function (e, r, a, n, t) {
                return _e(e.aoData, t, "anCells", r)
            }, 1)
        }),
        g("columns().titles()", "column().title()", function (e, r) {
            return this.iterator("column", function (a, n) {
                typeof e == "number" && (r = e,
                    e = void 0);
                var t = c("span.dt-column-title", this.column(n).header(r));
                return e !== void 0 ? (t.html(e),
                    this) : t.html()
            }, 1)
        }),
        g("columns().types()", "column().type()", function () {
            return this.iterator("column", function (e, r) {
                var a = e.aoColumns[r].sType;
                return a || nr(e),
                    a
            }, 1)
        }),
        g("columns().visible()", "column().visible()", function (e, r) {
            var a = this
                , n = []
                , t = this.iterator("column", function (l, i) {
                    if (e === void 0)
                        return l.aoColumns[i].bVisible;
                    nn(l, i, e) && n.push(i)
                });
            return e !== void 0 && this.iterator("table", function (l) {
                Pe(l, l.aoHeader),
                    Pe(l, l.aoFooter),
                    l.aiDisplay.length || c(l.nTBody).find("td[colspan]").attr("colspan", Ne(l)),
                    We(l),
                    a.iterator("column", function (i, o) {
                        n.includes(o) && w(i, null, "column-visibility", [i, o, e, r])
                    }),
                    n.length && (r === void 0 || r) && a.columns.adjust()
            }),
                t
        }),
        g("columns().widths()", "column().width()", function () {
            var e = this.columns(":visible").count()
                , r = c("<tr>").html("<td>" + Array(e).join("</td><td>") + "</td>");
            c(this.table().body()).append(r);
            var a = r.children().map(function () {
                return c(this).outerWidth()
            });
            return r.remove(),
                this.iterator("column", function (n, t) {
                    var l = ye(n, t);
                    return l !== null ? a[l] : 0
                }, 1)
        }),
        g("columns().indexes()", "column().index()", function (e) {
            return this.iterator("column", function (r, a) {
                return e === "visible" ? ye(r, a) : a
            }, 1)
        }),
        T("columns.adjust()", function () {
            return this.iterator("table", function (e) {
                e.containerWidth = -1,
                    Fe(e)
            }, 1)
        }),
        T("column.index()", function (e, r) {
            if (this.context.length !== 0) {
                var a = this.context[0];
                if (e === "fromVisible" || e === "toData")
                    return er(a, r);
                if (e === "fromData" || e === "toVisible")
                    return ye(a, r)
            }
        }),
        T("column()", function (e, r) {
            return Dr(this.columns(e, r))
        });
    var tn = function (e, r, a) {
        var n = e.aoData, t = Xe(e, a), l = Rr(_e(n, t, "anCells")), i = c(Qe([], l)), o, u = e.aoColumns.length, d, f, s, h, p, m, _ = function (v) {
            var D = typeof v == "function";
            if (v == null || D) {
                for (d = [],
                    f = 0,
                    s = t.length; f < s; f++)
                    for (o = t[f],
                        h = 0; h < u; h++)
                        p = {
                            row: o,
                            column: h
                        },
                            D ? (m = n[o],
                                v(p, q(e, o, h), m.anCells ? m.anCells[h] : null) && d.push(p)) : d.push(p);
                return d
            }
            if (c.isPlainObject(v))
                return v.column !== void 0 && v.row !== void 0 && t.indexOf(v.row) !== -1 ? [v] : [];
            var y = i.filter(v).map(function (S, C) {
                return {
                    row: C._DT_CellIndex.row,
                    column: C._DT_CellIndex.column
                }
            }).toArray();
            return y.length || !v.nodeName ? y : (m = c(v).closest("*[data-dt-row]"),
                m.length ? [{
                    row: m.data("dt-row"),
                    column: m.data("dt-column")
                }] : [])
        };
        return _r("cell", r, _, e, a)
    };
    T("cells()", function (e, r, a) {
        if (c.isPlainObject(e) && (e.row === void 0 ? (a = e,
            e = null) : (a = r,
                r = null)),
            c.isPlainObject(r) && (a = r,
                r = null),
            r == null)
            return this.iterator("table", function (h) {
                return tn(h, e, yr(a))
            });
        var n = a ? {
            page: a.page,
            order: a.order,
            search: a.search
        } : {}, t = this.columns(r, n), l = this.rows(e, n), i, o, u, d, f = this.iterator("table", function (h, p) {
            var m = [];
            for (i = 0,
                o = l[p].length; i < o; i++)
                for (u = 0,
                    d = t[p].length; u < d; u++)
                    m.push({
                        row: l[p][i],
                        column: t[p][u]
                    });
            return m
        }, 1), s = a && a.selected ? this.cells(f, a) : f;
        return c.extend(s.selector, {
            cols: r,
            rows: e,
            opts: a
        }),
            s
    }),
        g("cells().nodes()", "cell().node()", function () {
            return this.iterator("cell", function (e, r, a) {
                var n = e.aoData[r];
                return n && n.anCells ? n.anCells[a] : void 0
            }, 1)
        }),
        T("cells().data()", function () {
            return this.iterator("cell", function (e, r, a) {
                return q(e, r, a)
            }, 1)
        }),
        g("cells().cache()", "cell().cache()", function (e) {
            return e = e === "search" ? "_aFilterData" : "_aSortData",
                this.iterator("cell", function (r, a, n) {
                    return r.aoData[a][e][n]
                }, 1)
        }),
        g("cells().render()", "cell().render()", function (e) {
            return this.iterator("cell", function (r, a, n) {
                return q(r, a, n, e)
            }, 1)
        }),
        g("cells().indexes()", "cell().index()", function () {
            return this.iterator("cell", function (e, r, a) {
                return {
                    row: r,
                    column: a,
                    columnVisible: ye(e, a)
                }
            }, 1)
        }),
        g("cells().invalidate()", "cell().invalidate()", function (e) {
            return this.iterator("cell", function (r, a, n) {
                Ie(r, a, e, n)
            })
        }),
        T("cell()", function (e, r, a) {
            return Dr(this.cells(e, r, a))
        }),
        T("cell().data()", function (e) {
            var r = this.context
                , a = this[0];
            return e === void 0 ? r.length && a.length ? q(r[0], a[0].row, a[0].column) : void 0 : (Aa(r[0], a[0].row, a[0].column, e),
                Ie(r[0], a[0].row, "data", a[0].column),
                this)
        }),
        T("order()", function (e, r) {
            var a = this.context
                , n = Array.prototype.slice.call(arguments);
            return e === void 0 ? a.length !== 0 ? a[0].aaSorting : void 0 : (typeof e == "number" ? e = [[e, r]] : n.length > 1 && (e = n),
                this.iterator("table", function (t) {
                    t.aaSorting = Array.isArray(e) ? e.slice() : e
                }))
        }),
        T("order.listener()", function (e, r, a) {
            return this.iterator("table", function (n) {
                $r(n, e, {}, r, a)
            })
        }),
        T("order.fixed()", function (e) {
            if (!e) {
                var r = this.context
                    , a = r.length ? r[0].aaSortingFixed : void 0;
                return Array.isArray(a) ? {
                    pre: a
                } : a
            }
            return this.iterator("table", function (n) {
                n.aaSortingFixed = c.extend(!0, {}, e)
            })
        }),
        T(["columns().order()", "column().order()"], function (e) {
            var r = this;
            return e ? this.iterator("table", function (a, n) {
                a.aaSorting = r[n].map(function (t) {
                    return [t, e]
                })
            }) : this.iterator("column", function (a, n) {
                for (var t = He(a), l = 0, i = t.length; l < i; l++)
                    if (t[l].col === n)
                        return t[l].dir;
                return null
            }, 1)
        }),
        g("columns().orderable()", "column().orderable()", function (e) {
            return this.iterator("column", function (r, a) {
                var n = r.aoColumns[a];
                return e ? n.asSorting : n.bSortable
            }, 1)
        }),
        T("processing()", function (e) {
            return this.iterator("table", function (r) {
                V(r, e)
            })
        }),
        T("search()", function (e, r, a, n) {
            var t = this.context;
            return e === void 0 ? t.length !== 0 ? t[0].oPreviousSearch.search : void 0 : this.iterator("table", function (l) {
                l.oFeatures.bFilter && (typeof r == "object" ? Te(l, c.extend(l.oPreviousSearch, r, {
                    search: e
                })) : Te(l, c.extend(l.oPreviousSearch, {
                    search: e,
                    regex: r === null ? !1 : r,
                    smart: a === null ? !0 : a,
                    caseInsensitive: n === null ? !0 : n
                })))
            })
        }),
        T("search.fixed()", function (e, r) {
            var a = this.iterator(!0, "table", function (n) {
                var t = n.searchFixed;
                if (e) {
                    if (r === void 0)
                        return t[e];
                    r === null ? delete t[e] : t[e] = r
                } else
                    return Object.keys(t);
                return this
            });
            return e !== void 0 && r === void 0 ? a[0] : a
        }),
        g("columns().search()", "column().search()", function (e, r, a, n) {
            return this.iterator("column", function (t, l) {
                var i = t.aoPreSearchCols;
                if (e === void 0)
                    return i[l].search;
                t.oFeatures.bFilter && (typeof r == "object" ? c.extend(i[l], r, {
                    search: e
                }) : c.extend(i[l], {
                    search: e,
                    regex: r === null ? !1 : r,
                    smart: a === null ? !0 : a,
                    caseInsensitive: n === null ? !0 : n
                }),
                    Te(t, t.oPreviousSearch))
            })
        }),
        T(["columns().search.fixed()", "column().search.fixed()"], function (e, r) {
            var a = this.iterator(!0, "column", function (n, t) {
                var l = n.aoColumns[t].searchFixed;
                if (e) {
                    if (r === void 0)
                        return l[e];
                    r === null ? delete l[e] : l[e] = r
                } else
                    return Object.keys(l);
                return this
            });
            return e !== void 0 && r === void 0 ? a[0] : a
        }),
        T("state()", function (e, r) {
            if (!e)
                return this.context.length ? this.context[0].oSavedState : null;
            var a = c.extend(!0, {}, e);
            return this.iterator("table", function (n) {
                r !== !1 && (a.time = +new Date + 100),
                    pr(n, a, function () { })
            })
        }),
        T("state.clear()", function () {
            return this.iterator("table", function (e) {
                e.fnStateSaveCallback.call(e.oInstance, e, {})
            })
        }),
        T("state.loaded()", function () {
            return this.context.length ? this.context[0].oLoadedState : null
        }),
        T("state.save()", function () {
            return this.iterator("table", function (e) {
                We(e)
            })
        });
    var ca, da;
    b.use = function (e, r) {
        var a = typeof e == "string" ? r : e
            , n = typeof r == "string" ? r : e;
        if (a === void 0 && typeof n == "string")
            switch (n) {
                case "lib":
                case "jq":
                    return c;
                case "win":
                    return N;
                case "datetime":
                    return b.DateTime;
                case "luxon":
                    return ae;
                case "moment":
                    return oe;
                case "bootstrap":
                    return ca || N.bootstrap;
                case "foundation":
                    return da || N.Foundation;
                default:
                    return null
            }
        n === "lib" || n === "jq" || a && a.fn && a.fn.jquery ? c = a : n === "win" || a && a.document ? (N = a,
            I = a.document) : n === "datetime" || a && a.type === "DateTime" ? b.DateTime = a : n === "luxon" || a && a.FixedOffsetZone ? ae = a : n === "moment" || a && a.isMoment ? oe = a : n === "bootstrap" || a && a.Modal && a.Modal.NAME === "modal" ? ca = a : (n === "foundation" || a && a.Reveal) && (da = a)
    }
        ,
        b.factory = function (e, r) {
            var a = !1;
            return e && e.document && (N = e,
                I = e.document),
                r && r.fn && r.fn.jquery && (c = r,
                    a = !0),
                a
        }
        ,
        b.versionCheck = function (e, r) {
            for (var a = r ? r.split(".") : b.version.split("."), n = e.split("."), t, l, i = 0, o = n.length; i < o; i++)
                if (t = parseInt(a[i], 10) || 0,
                    l = parseInt(n[i], 10) || 0,
                    t !== l)
                    return t > l;
            return !0
        }
        ,
        b.isDataTable = function (e) {
            var r = c(e).get(0)
                , a = !1;
            return e instanceof b.Api ? !0 : (c.each(b.settings, function (n, t) {
                var l = t.nScrollHead ? c("table", t.nScrollHead)[0] : null
                    , i = t.nScrollFoot ? c("table", t.nScrollFoot)[0] : null;
                (t.nTable === r || l === r || i === r) && (a = !0)
            }),
                a)
        }
        ,
        b.tables = function (e) {
            var r = !1;
            c.isPlainObject(e) && (r = e.api,
                e = e.visible);
            var a = b.settings.filter(function (n) {
                return !!(!e || e && c(n.nTable).is(":visible"))
            }).map(function (n) {
                return n.nTable
            });
            return r ? new x(a) : a
        }
        ,
        b.camelToHungarian = Q,
        T("$()", function (e, r) {
            var a = this.rows(r).nodes()
                , n = c(a);
            return c([].concat(n.filter(e).toArray(), n.find(e).toArray()))
        }),
        c.each(["on", "one", "off"], function (e, r) {
            T(r + "()", function () {
                var a = Array.prototype.slice.call(arguments);
                a[0] = a[0].split(/\s/).map(function (t) {
                    return t.match(/\.dt\b/) ? t : t + ".dt"
                }).join(" ");
                var n = c(this.tables().nodes());
                return n[r].apply(n, a),
                    this
            })
        }),
        T("clear()", function () {
            return this.iterator("table", function (e) {
                ir(e)
            })
        }),
        T("error()", function (e) {
            return this.iterator("table", function (r) {
                U(r, 0, e)
            })
        }),
        T("settings()", function () {
            return new x(this.context, this.context)
        }),
        T("init()", function () {
            var e = this.context;
            return e.length ? e[0].oInit : null
        }),
        T("data()", function () {
            return this.iterator("table", function (e) {
                return O(e.aoData, "_aData")
            }).flatten()
        }),
        T("trigger()", function (e, r, a) {
            return this.iterator("table", function (n) {
                return w(n, null, e, r, a)
            }).flatten()
        }),
        T("ready()", function (e) {
            var r = this.context;
            return e ? this.tables().every(function () {
                this.context[0]._bInitComplete ? e.call(this) : this.on("init.dt.DT", function () {
                    e.call(this)
                })
            }) : r.length ? r[0]._bInitComplete || !1 : null
        }),
        T("destroy()", function (e) {
            return e = e || !1,
                this.iterator("table", function (r) {
                    var a = r.oClasses
                        , n = r.nTable
                        , t = r.nTBody
                        , l = r.nTHead
                        , i = r.nTFoot
                        , o = c(n)
                        , u = c(t)
                        , d = c(r.nTableWrapper)
                        , f = r.aoData.map(function (v) {
                            return v ? v.nTr : null
                        })
                        , s = a.order;
                    r.bDestroying = !0,
                        w(r, "aoDestroyCallback", "destroy", [r], !0),
                        e || new x(r).columns().visible(!0),
                        r.resizeObserver && r.resizeObserver.disconnect(),
                        d.off(".DT").find(":not(tbody *)").off(".DT"),
                        c(N).off(".DT-" + r.sInstance),
                        n != l.parentNode && (o.children("thead").detach(),
                            o.append(l)),
                        i && n != i.parentNode && (o.children("tfoot").detach(),
                            o.append(i)),
                        r.colgroup.remove(),
                        r.aaSorting = [],
                        r.aaSortingFixed = [],
                        vr(r),
                        c("th, td", l).removeClass(s.canAsc + " " + s.canDesc + " " + s.isAsc + " " + s.isDesc).css("width", ""),
                        u.children().detach(),
                        u.append(f);
                    var h = r.nTableWrapper.parentNode
                        , p = r.nTableWrapper.nextSibling
                        , m = e ? "remove" : "detach";
                    o[m](),
                        d[m](),
                        !e && h && (h.insertBefore(n, p),
                            o.css("width", r.sDestroyWidth).removeClass(a.table));
                    var _ = b.settings.indexOf(r);
                    _ !== -1 && b.settings.splice(_, 1)
                })
        }),
        c.each(["column", "row", "cell"], function (e, r) {
            T(r + "s().every()", function (a) {
                var n = this.selector.opts, t = this, l, i = 0;
                return this.iterator("every", function (o, u, d) {
                    l = t[r](u, n),
                        r === "cell" ? a.call(l, l[0][0].row, l[0][0].column, d, i) : a.call(l, u, d, i),
                        i++
                })
            })
        }),
        T("i18n()", function (e, r, a) {
            var n = this.context[0]
                , t = de(e)(n.oLanguage);
            return t === void 0 && (t = r),
                c.isPlainObject(t) && (t = a !== void 0 && t[a] !== void 0 ? t[a] : t._),
                typeof t == "string" ? t.replace("%d", a) : t
        }),
        b.version = "2.2.1",
        b.settings = [],
        b.models = {},
        b.models.oSearch = {
            caseInsensitive: !0,
            search: "",
            regex: !1,
            smart: !0,
            return: !1
        },
        b.models.oRow = {
            nTr: null,
            anCells: null,
            _aData: [],
            _aSortData: null,
            _aFilterData: null,
            _sFilterRow: null,
            src: null,
            idx: -1,
            displayData: null
        },
        b.models.oColumn = {
            idx: null,
            aDataSort: null,
            asSorting: null,
            bSearchable: null,
            bSortable: null,
            bVisible: null,
            _sManualType: null,
            _bAttrSrc: !1,
            fnCreatedCell: null,
            fnGetData: null,
            fnSetData: null,
            mData: null,
            mRender: null,
            sClass: null,
            sContentPadding: null,
            sDefaultContent: null,
            sName: null,
            sSortDataType: "std",
            sSortingClass: null,
            sTitle: null,
            sType: null,
            sWidth: null,
            sWidthOrig: null,
            maxLenString: null,
            searchFixed: null
        },
        b.defaults = {
            aaData: null,
            aaSorting: [[0, "asc"]],
            aaSortingFixed: [],
            ajax: null,
            aLengthMenu: [10, 25, 50, 100],
            aoColumns: null,
            aoColumnDefs: null,
            aoSearchCols: [],
            bAutoWidth: !0,
            bDeferRender: !0,
            bDestroy: !1,
            bFilter: !0,
            bInfo: !0,
            bLengthChange: !0,
            bPaginate: !0,
            bProcessing: !1,
            bRetrieve: !1,
            bScrollCollapse: !1,
            bServerSide: !1,
            bSort: !0,
            bSortMulti: !0,
            bSortCellsTop: null,
            bSortClasses: !0,
            bStateSave: !1,
            fnCreatedRow: null,
            fnDrawCallback: null,
            fnFooterCallback: null,
            fnFormatNumber: function (e) {
                return e.toString().replace(/\B(?=(\d{3})+(?!\d))/g, this.oLanguage.sThousands)
            },
            fnHeaderCallback: null,
            fnInfoCallback: null,
            fnInitComplete: null,
            fnPreDrawCallback: null,
            fnRowCallback: null,
            fnStateLoadCallback: function (e) {
                try {
                    return JSON.parse((e.iStateDuration === -1 ? sessionStorage : localStorage).getItem("DataTables_" + e.sInstance + "_" + location.pathname))
                } catch {
                    return {}
                }
            },
            fnStateLoadParams: null,
            fnStateLoaded: null,
            fnStateSaveCallback: function (e, r) {
                try {
                    (e.iStateDuration === -1 ? sessionStorage : localStorage).setItem("DataTables_" + e.sInstance + "_" + location.pathname, JSON.stringify(r))
                } catch { }
            },
            fnStateSaveParams: null,
            iStateDuration: 7200,
            iDisplayLength: 10,
            iDisplayStart: 0,
            iTabIndex: 0,
            oClasses: {},
            oLanguage: {
                oAria: {
                    orderable: ": Activate to sort",
                    orderableReverse: ": Activate to invert sorting",
                    orderableRemove: ": Activate to remove sorting",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous",
                        number: ""
                    }
                },
                oPaginate: {
                    sFirst: "\xAB",
                    sLast: "\xBB",
                    sNext: "\u203A",
                    sPrevious: "\u2039"
                },
                entries: {
                    _: "entries",
                    1: "entry"
                },
                sEmptyTable: "No data available in table",
                sInfo: "Showing _START_ to _END_ of _TOTAL_ _ENTRIES-TOTAL_",
                sInfoEmpty: "Showing 0 to 0 of 0 _ENTRIES-TOTAL_",
                sInfoFiltered: "(filtered from _MAX_ total _ENTRIES-MAX_)",
                sInfoPostFix: "",
                sDecimal: "",
                sThousands: ",",
                sLengthMenu: "_MENU_ _ENTRIES_ per page",
                sLoadingRecords: "Loading...",
                sProcessing: "",
                sSearch: "Search:",
                sSearchPlaceholder: "",
                sUrl: "",
                sZeroRecords: "No matching records found"
            },
            orderDescReverse: !0,
            oSearch: c.extend({}, b.models.oSearch),
            layout: {
                topStart: "pageLength",
                topEnd: "search",
                bottomStart: "info",
                bottomEnd: "paging"
            },
            sDom: null,
            searchDelay: null,
            sPaginationType: "",
            sScrollX: "",
            sScrollXInner: "",
            sScrollY: "",
            sServerMethod: "GET",
            renderer: null,
            rowId: "DT_RowId",
            caption: null,
            iDeferLoading: null
        },
        Ae(b.defaults),
        b.defaults.column = {
            aDataSort: null,
            iDataSort: -1,
            ariaTitle: "",
            asSorting: ["asc", "desc", ""],
            bSearchable: !0,
            bSortable: !0,
            bVisible: !0,
            fnCreatedCell: null,
            mData: null,
            mRender: null,
            sCellType: "td",
            sClass: "",
            sContentPadding: "",
            sDefaultContent: null,
            sName: "",
            sSortDataType: "std",
            sTitle: null,
            sType: null,
            sWidth: null
        },
        Ae(b.defaults.column),
        b.models.oSettings = {
            oFeatures: {
                bAutoWidth: null,
                bDeferRender: null,
                bFilter: null,
                bInfo: !0,
                bLengthChange: !0,
                bPaginate: null,
                bProcessing: null,
                bServerSide: null,
                bSort: null,
                bSortMulti: null,
                bSortClasses: null,
                bStateSave: null
            },
            oScroll: {
                bCollapse: null,
                iBarWidth: 0,
                sX: null,
                sXInner: null,
                sY: null
            },
            oLanguage: {
                fnInfoCallback: null
            },
            oBrowser: {
                bScrollbarLeft: !1,
                barWidth: 0
            },
            ajax: null,
            aanFeatures: [],
            aoData: [],
            aiDisplay: [],
            aiDisplayMaster: [],
            aIds: {},
            aoColumns: [],
            aoHeader: [],
            aoFooter: [],
            oPreviousSearch: {},
            searchFixed: {},
            aoPreSearchCols: [],
            aaSorting: null,
            aaSortingFixed: [],
            sDestroyWidth: 0,
            aoRowCallback: [],
            aoHeaderCallback: [],
            aoFooterCallback: [],
            aoDrawCallback: [],
            aoRowCreatedCallback: [],
            aoPreDrawCallback: [],
            aoInitComplete: [],
            aoStateSaveParams: [],
            aoStateLoadParams: [],
            aoStateLoaded: [],
            sTableId: "",
            nTable: null,
            nTHead: null,
            nTFoot: null,
            nTBody: null,
            nTableWrapper: null,
            bInitialised: !1,
            aoOpenRows: [],
            sDom: null,
            searchDelay: null,
            sPaginationType: "two_button",
            pagingControls: 0,
            iStateDuration: 0,
            aoStateSave: [],
            aoStateLoad: [],
            oSavedState: null,
            oLoadedState: null,
            bAjaxDataGet: !0,
            jqXHR: null,
            json: void 0,
            oAjaxData: void 0,
            sServerMethod: null,
            fnFormatNumber: null,
            aLengthMenu: null,
            iDraw: 0,
            bDrawing: !1,
            iDrawError: -1,
            _iDisplayLength: 10,
            _iDisplayStart: 0,
            _iRecordsTotal: 0,
            _iRecordsDisplay: 0,
            oClasses: {},
            bFiltered: !1,
            bSorted: !1,
            bSortCellsTop: null,
            oInit: null,
            aoDestroyCallback: [],
            fnRecordsTotal: function () {
                return X(this) == "ssp" ? this._iRecordsTotal * 1 : this.aiDisplayMaster.length
            },
            fnRecordsDisplay: function () {
                return X(this) == "ssp" ? this._iRecordsDisplay * 1 : this.aiDisplay.length
            },
            fnDisplayEnd: function () {
                var e = this._iDisplayLength
                    , r = this._iDisplayStart
                    , a = r + e
                    , n = this.aiDisplay.length
                    , t = this.oFeatures
                    , l = t.bPaginate;
                return t.bServerSide ? l === !1 || e === -1 ? r + n : Math.min(r + e, this._iRecordsDisplay) : !l || a > n || e === -1 ? n : a
            },
            oInstance: null,
            sInstance: null,
            iTabIndex: 0,
            nScrollHead: null,
            nScrollFoot: null,
            aLastSort: [],
            oPlugins: {},
            rowIdFn: null,
            rowId: null,
            caption: "",
            captionNode: null,
            colgroup: null,
            deferLoading: null,
            typeDetect: !0,
            resizeObserver: null,
            containerWidth: -1
        };
    var ln = b.ext.pager;
    c.extend(ln, {
        simple: function () {
            return ["previous", "next"]
        },
        full: function () {
            return ["first", "previous", "next", "last"]
        },
        numbers: function () {
            return ["numbers"]
        },
        simple_numbers: function () {
            return ["previous", "numbers", "next"]
        },
        full_numbers: function () {
            return ["first", "previous", "numbers", "next", "last"]
        },
        first_last: function () {
            return ["first", "last"]
        },
        first_last_numbers: function () {
            return ["first", "numbers", "last"]
        },
        _numbers: _a,
        numbers_length: 7
    }),
        c.extend(!0, b.ext.renderer, {
            pagingButton: {
                _: function (e, r, a, n, t) {
                    var l = e.oClasses.paging, i = [l.button], o;
                    return n && i.push(l.active),
                        t && i.push(l.disabled),
                        r === "ellipsis" ? o = c('<span class="ellipsis"></span>').html(a)[0] : o = c("<button>", {
                            class: i.join(" "),
                            role: "link",
                            type: "button"
                        }).html(a),
                    {
                        display: o,
                        clicker: o
                    }
                }
            },
            pagingContainer: {
                _: function (e, r) {
                    return r
                }
            }
        });
    var pe = function (e, r) {
        return function (a) {
            return B(a) || typeof a != "string" || (a = a.replace(ya, " "),
                e && (a = Z(a)),
                r && (a = Le(a, !1))),
                a
        }
    };
    function sa(e, r, a, n, t) {
        return oe ? e[r](t) : ae ? e[a](t) : n ? e[n](t) : e
    }
    var ha = !1, ae, oe;
    function on() {
        N.luxon && !ae && (ae = N.luxon),
            N.moment && !oe && (oe = N.moment)
    }
    function Ue(e, r, a) {
        var n;
        if (on(),
            oe) {
            if (n = oe.utc(e, r, a, !0),
                !n.isValid())
                return null
        } else if (ae) {
            if (n = r && typeof e == "string" ? ae.DateTime.fromFormat(e, r) : ae.DateTime.fromISO(e),
                !n.isValid)
                return null;
            n = n.setLocale(a)
        } else
            r ? (ha || alert("DataTables warning: Formatted date without Moment.js or Luxon - https://datatables.net/tn/17"),
                ha = !0) : n = new Date(e);
        return n
    }
    function xr(e) {
        return function (r, a, n, t) {
            arguments.length === 0 ? (n = "en",
                a = null,
                r = null) : arguments.length === 1 ? (n = "en",
                    a = r,
                    r = null) : arguments.length === 2 && (n = a,
                        a = r,
                        r = null);
            var l = "datetime" + (a ? "-" + a : "");
            return b.ext.type.order[l + "-pre"] || b.type(l, {
                detect: function (i) {
                    return i === l ? l : !1
                },
                order: {
                    pre: function (i) {
                        return i.valueOf()
                    }
                },
                className: "dt-right"
            }),
                function (i, o) {
                    if (i == null)
                        if (t === "--now") {
                            var u = new Date;
                            i = new Date(Date.UTC(u.getFullYear(), u.getMonth(), u.getDate(), u.getHours(), u.getMinutes(), u.getSeconds()))
                        } else
                            i = "";
                    if (o === "type")
                        return l;
                    if (i === "")
                        return o !== "sort" ? "" : Ue("0000-01-01 00:00:00", null, n);
                    if (a !== null && r === a && o !== "sort" && o !== "type" && !(i instanceof Date))
                        return i;
                    var d = Ue(i, r, n);
                    if (d === null)
                        return i;
                    if (o === "sort")
                        return d;
                    var f = a === null ? sa(d, "toDate", "toJSDate", "")[e]() : sa(d, "format", "toFormat", "toISOString", a);
                    return o === "display" ? ue(f) : f
                }
        }
    }
    var va = ","
        , pa = ".";
    if (N.Intl !== void 0)
        try {
            for (var xe = new Intl.NumberFormat().formatToParts(100000.1), k = 0; k < xe.length; k++)
                xe[k].type === "group" ? va = xe[k].value : xe[k].type === "decimal" && (pa = xe[k].value)
        } catch { }
    b.datetime = function (e, r) {
        var a = "datetime-" + e;
        r || (r = "en"),
            b.ext.type.order[a] || b.type(a, {
                detect: function (n) {
                    var t = Ue(n, e, r);
                    return n === "" || t ? a : !1
                },
                order: {
                    pre: function (n) {
                        return Ue(n, e, r) || 0
                    }
                },
                className: "dt-right"
            })
    }
        ,
        b.render = {
            date: xr("toLocaleDateString"),
            datetime: xr("toLocaleString"),
            time: xr("toLocaleTimeString"),
            number: function (e, r, a, n, t) {
                return e == null && (e = va),
                    r == null && (r = pa),
                {
                    display: function (l) {
                        if (typeof l != "number" && typeof l != "string" || l === "" || l === null)
                            return l;
                        var i = l < 0 ? "-" : ""
                            , o = parseFloat(l)
                            , u = Math.abs(o);
                        if (u >= 1e11 || u < 1e-4 && u !== 0) {
                            var d = o.toExponential(a).split(/e\+?/);
                            return d[0] + " x 10<sup>" + d[1] + "</sup>"
                        }
                        if (isNaN(o))
                            return ue(l);
                        o = o.toFixed(a),
                            l = Math.abs(o);
                        var f = parseInt(l, 10)
                            , s = a ? r + (l - f).toFixed(a).substring(2) : "";
                        return f === 0 && parseFloat(s) === 0 && (i = ""),
                            i + (n || "") + f.toString().replace(/\B(?=(\d{3})+(?!\d))/g, e) + s + (t || "")
                    }
                }
            },
            text: function () {
                return {
                    display: ue,
                    filter: ue
                }
            }
        };
    var H = b.ext.type;
    b.type = function (e, r, a) {
        if (!r)
            return {
                className: H.className[e],
                detect: H.detect.find(function (i) {
                    return i._name === e
                }),
                order: {
                    pre: H.order[e + "-pre"],
                    asc: H.order[e + "-asc"],
                    desc: H.order[e + "-desc"]
                },
                render: H.render[e],
                search: H.search[e]
            };
        var n = function (i, o) {
            H[i][e] = o
        }
            , t = function (i) {
                Object.defineProperty(i, "_name", {
                    value: e
                });
                var o = H.detect.findIndex(function (u) {
                    return u._name === e
                });
                o === -1 ? H.detect.unshift(i) : H.detect.splice(o, 1, i)
            }
            , l = function (i) {
                H.order[e + "-pre"] = i.pre,
                    H.order[e + "-asc"] = i.asc,
                    H.order[e + "-desc"] = i.desc
            };
        a === void 0 && (a = r,
            r = null),
            r === "className" ? n("className", a) : r === "detect" ? t(a) : r === "order" ? l(a) : r === "render" ? n("render", a) : r === "search" ? n("search", a) : r || (a.className && n("className", a.className),
                a.detect !== void 0 && t(a.detect),
                a.order && l(a.order),
                a.render !== void 0 && n("render", a.render),
                a.search !== void 0 && n("search", a.search))
    }
        ,
        b.types = function () {
            return H.detect.map(function (e) {
                return e._name
            })
        }
        ;
    var Sr = function (e, r) {
        return e = e != null ? e.toString().toLowerCase() : "",
            r = r != null ? r.toString().toLowerCase() : "",
            e.localeCompare(r, navigator.languages[0] || navigator.language, {
                numeric: !0,
                ignorePunctuation: !0
            })
    }
        , ba = function (e, r) {
            return e = Z(e),
                r = Z(r),
                Sr(e, r)
        };
    b.type("string", {
        detect: function () {
            return "string"
        },
        order: {
            pre: function (e) {
                return B(e) && typeof e != "boolean" ? "" : typeof e == "string" ? e.toLowerCase() : e.toString ? e.toString() : ""
            }
        },
        search: pe(!1, !0)
    }),
        b.type("string-utf8", {
            detect: {
                allOf: function (e) {
                    return !0
                },
                oneOf: function (e) {
                    return !B(e) && navigator.languages && typeof e == "string" && e.match(/[^\x00-\x7F]/)
                }
            },
            order: {
                asc: Sr,
                desc: function (e, r) {
                    return Sr(e, r) * -1
                }
            },
            search: pe(!1, !0)
        }),
        b.type("html", {
            detect: {
                allOf: function (e) {
                    return B(e) || typeof e == "string" && e.indexOf("<") !== -1
                },
                oneOf: function (e) {
                    return !B(e) && typeof e == "string" && e.indexOf("<") !== -1
                }
            },
            order: {
                pre: function (e) {
                    return B(e) ? "" : e.replace ? Z(e).trim().toLowerCase() : e + ""
                }
            },
            search: pe(!0, !0)
        }),
        b.type("html-utf8", {
            detect: {
                allOf: function (e) {
                    return B(e) || typeof e == "string" && e.indexOf("<") !== -1
                },
                oneOf: function (e) {
                    return navigator.languages && !B(e) && typeof e == "string" && e.indexOf("<") !== -1 && typeof e == "string" && e.match(/[^\x00-\x7F]/)
                }
            },
            order: {
                asc: ba,
                desc: function (e, r) {
                    return ba(e, r) * -1
                }
            },
            search: pe(!0, !0)
        }),
        b.type("date", {
            className: "dt-type-date",
            detect: {
                allOf: function (e) {
                    if (e && !(e instanceof Date) && !Ar.test(e))
                        return null;
                    var r = Date.parse(e);
                    return r !== null && !isNaN(r) || B(e)
                },
                oneOf: function (e) {
                    return e instanceof Date || typeof e == "string" && Ar.test(e)
                }
            },
            order: {
                pre: function (e) {
                    var r = Date.parse(e);
                    return isNaN(r) ? -1 / 0 : r
                }
            }
        }),
        b.type("html-num-fmt", {
            className: "dt-type-numeric",
            detect: {
                allOf: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return ge(e, a, !0, !1)
                },
                oneOf: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return ge(e, a, !0, !1)
                }
            },
            order: {
                pre: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return ze(e, a, Ze, Ke)
                }
            },
            search: pe(!0, !0)
        }),
        b.type("html-num", {
            className: "dt-type-numeric",
            detect: {
                allOf: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return ge(e, a, !1, !0)
                },
                oneOf: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return ge(e, a, !1, !1)
                }
            },
            order: {
                pre: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return ze(e, a, Ze)
                }
            },
            search: pe(!0, !0)
        }),
        b.type("num-fmt", {
            className: "dt-type-numeric",
            detect: {
                allOf: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return me(e, a, !0, !0)
                },
                oneOf: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return me(e, a, !0, !1)
                }
            },
            order: {
                pre: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return ze(e, a, Ke)
                }
            }
        }),
        b.type("num", {
            className: "dt-type-numeric",
            detect: {
                allOf: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return me(e, a, !1, !0)
                },
                oneOf: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return me(e, a, !1, !1)
                }
            },
            order: {
                pre: function (e, r) {
                    var a = r.oLanguage.sDecimal;
                    return ze(e, a)
                }
            }
        });
    var ze = function (e, r, a, n) {
        if (e !== 0 && (!e || e === "-"))
            return -1 / 0;
        var t = typeof e;
        return t === "number" || t === "bigint" ? e : (r && (e = Nr(e, r)),
            e.replace && (a && (e = e.replace(a, "")),
                n && (e = e.replace(n, ""))),
            e * 1)
    };
    c.extend(!0, b.ext.renderer, {
        footer: {
            _: function (e, r, a) {
                r.addClass(a.tfoot.cell)
            }
        },
        header: {
            _: function (e, r, a) {
                r.addClass(a.thead.cell),
                    e.oFeatures.bSort || r.addClass(a.order.none);
                var n = e.bSortCellsTop
                    , t = r.closest("thead").find("tr")
                    , l = r.parent().index();
                r.attr("data-dt-order") === "disable" || r.parent().attr("data-dt-order") === "disable" || n === !0 && l !== 0 || n === !1 && l !== t.length - 1 || c(e.nTable).on("order.dt.DT column-visibility.dt.DT", function (i, o) {
                    if (e === o) {
                        var u = o.sortDetails;
                        if (u) {
                            var d, f = a.order, s = o.api.columns(r), h = e.aoColumns[s.flatten()[0]], p = s.orderable().includes(!0), m = "", _ = s.indexes(), v = s.orderable(!0).flatten(), D = O(u, "col"), y = e.iTabIndex;
                            r.removeClass(f.isAsc + " " + f.isDesc).toggleClass(f.none, !p).toggleClass(f.canAsc, p && v.includes("asc")).toggleClass(f.canDesc, p && v.includes("desc"));
                            var S = !0;
                            for (d = 0; d < _.length; d++)
                                D.includes(_[d]) || (S = !1);
                            if (S) {
                                var C = s.order();
                                r.addClass(C.includes("asc") ? f.isAsc : "" + C.includes("desc") ? f.isDesc : "")
                            }
                            var A = -1;
                            for (d = 0; d < D.length; d++)
                                if (e.aoColumns[D[d]].bVisible) {
                                    A = D[d];
                                    break
                                }
                            if (_[0] == A) {
                                var L = u[0]
                                    , R = h.asSorting;
                                r.attr("aria-sort", L.dir === "asc" ? "ascending" : "descending"),
                                    m = R[L.index + 1] ? "Reverse" : "Remove"
                            } else
                                r.removeAttr("aria-sort");
                            if (r.attr("aria-label", p ? h.ariaTitle + o.api.i18n("oAria.orderable" + m) : h.ariaTitle),
                                p) {
                                var j = r.find(".dt-column-order");
                                j.attr("role", "button"),
                                    y !== -1 && j.attr("tabindex", y)
                            }
                        }
                    }
                })
            }
        },
        layout: {
            _: function (e, r, a) {
                var n = e.oClasses.layout
                    , t = c("<div/>").attr("id", a.id || null).addClass(a.className || n.row).appendTo(r);
                b.ext.renderer.layout._forLayoutRow(a, function (l, i) {
                    if (!(l === "id" || l === "className")) {
                        var o = "";
                        i.table && (t.addClass(n.tableRow),
                            o += n.tableCell + " "),
                            l === "start" ? o += n.start : l === "end" ? o += n.end : o += n.full,
                            c("<div/>").attr({
                                id: i.id || null,
                                class: i.className ? i.className : n.cell + " " + o
                            }).append(i.contents).appendTo(t)
                    }
                })
            },
            _forLayoutRow: function (e, r) {
                var a = function (n) {
                    switch (n) {
                        case "":
                            return 0;
                        case "start":
                            return 1;
                        case "end":
                            return 2;
                        default:
                            return 3
                    }
                };
                Object.keys(e).sort(function (n, t) {
                    return a(n) - a(t)
                }).forEach(function (n) {
                    r(n, e[n])
                })
            }
        }
    }),
        b.feature = {},
        b.feature.register = function (e, r, a) {
            b.ext.features[e] = r,
                a && F.feature.push({
                    cFeature: a,
                    fnInit: r
                })
        }
        ;
    function Ge(e, r, a) {
        a && (e[r] = a)
    }
    b.feature.register("div", function (e, r) {
        var a = c("<div>")[0];
        return r && (Ge(a, "className", r.className),
            Ge(a, "id", r.id),
            Ge(a, "innerHTML", r.html),
            Ge(a, "textContent", r.text)),
            a
    }),
        b.feature.register("info", function (e, r) {
            if (!e.oFeatures.bInfo)
                return null;
            var a = e.oLanguage
                , n = e.sTableId
                , t = c("<div/>", {
                    class: e.oClasses.info.container
                });
            return r = c.extend({
                callback: a.fnInfoCallback,
                empty: a.sInfoEmpty,
                postfix: a.sInfoPostFix,
                search: a.sInfoFiltered,
                text: a.sInfo
            }, r),
                e.aoDrawCallback.push(function (l) {
                    un(l, r, t)
                }),
                e._infoEl || (t.attr({
                    "aria-live": "polite",
                    id: n + "_info",
                    role: "status"
                }),
                    c(e.nTable).attr("aria-describedby", n + "_info"),
                    e._infoEl = t),
                t
        }, "i");
    function un(e, r, a) {
        var n = e._iDisplayStart + 1
            , t = e.fnDisplayEnd()
            , l = e.fnRecordsTotal()
            , i = e.fnRecordsDisplay()
            , o = i ? r.text : r.empty;
        i !== l && (o += " " + r.search),
            o += r.postfix,
            o = br(e, o),
            r.callback && (o = r.callback.call(e.oInstance, e, n, t, l, i, o)),
            a.html(o),
            w(e, null, "info", [e, a[0], o])
    }
    var wr = 0;
    b.feature.register("search", function (e, r) {
        if (!e.oFeatures.bFilter)
            return null;
        var a = e.oClasses.search
            , n = e.sTableId
            , t = e.oLanguage
            , l = e.oPreviousSearch
            , i = '<input type="search" class="' + a.input + '"/>';
        r = c.extend({
            placeholder: t.sSearchPlaceholder,
            processing: !1,
            text: t.sSearch
        }, r),
            r.text.indexOf("_INPUT_") === -1 && (r.text += "_INPUT_"),
            r.text = br(e, r.text);
        var o = r.text.match(/_INPUT_$/)
            , u = r.text.match(/^_INPUT_/)
            , d = r.text.replace(/_INPUT_/, "")
            , f = "<label>" + r.text + "</label>";
        u ? f = "_INPUT_<label>" + d + "</label>" : o && (f = "<label>" + d + "</label>_INPUT_");
        var s = c("<div>").addClass(a.container).append(f.replace(/_INPUT_/, i));
        s.find("label").attr("for", "dt-search-" + wr),
            s.find("input").attr("id", "dt-search-" + wr),
            wr++;
        var h = function (_) {
            var v = this.value;
            l.return && _.key !== "Enter" || v != l.search && Yr(e, r.processing, function () {
                l.search = v,
                    Te(e, l),
                    e._iDisplayStart = 0,
                    se(e)
            })
        }
            , p = e.searchDelay !== null ? e.searchDelay : 0
            , m = c("input", s).val(l.search).attr("placeholder", r.placeholder).on("keyup.DT search.DT input.DT paste.DT cut.DT", p ? b.util.debounce(h, p) : h).on("mouseup.DT", function (_) {
                setTimeout(function () {
                    h.call(m[0], _)
                }, 10)
            }).on("keypress.DT", function (_) {
                if (_.keyCode == 13)
                    return !1
            }).attr("aria-controls", n);
        return c(e.nTable).on("search.dt.DT", function (_, v) {
            e === v && m[0] !== I.activeElement && m.val(typeof l.search != "function" ? l.search : "")
        }),
            s
    }, "f"),
        b.feature.register("paging", function (e, r) {
            if (!e.oFeatures.bPaginate)
                return null;
            r = c.extend({
                buttons: b.ext.pager.numbers_length,
                type: e.sPaginationType,
                boundaryNumbers: !0,
                firstLast: !0,
                previousNext: !0,
                numbers: !0
            }, r);
            var a = c("<div/>").addClass(e.oClasses.paging.container + (r.type ? " paging_" + r.type : "")).append(c("<nav>").attr("aria-label", "pagination").addClass(e.oClasses.paging.nav))
                , n = function () {
                    ma(e, a.children(), r)
                };
            return e.aoDrawCallback.push(n),
                c(e.nTable).on("column-sizing.dt.DT", n),
                a
        }, "p");
    function fn(e) {
        var r = [];
        return e.numbers && r.push("numbers"),
            e.previousNext && (r.unshift("previous"),
                r.push("next")),
            e.firstLast && (r.unshift("first"),
                r.push("last")),
            r
    }
    function ma(e, r, a) {
        if (e._bInitComplete) {
            var n = a.type ? b.ext.pager[a.type] : fn
                , t = e.oLanguage.oAria.paginate || {}
                , l = e._iDisplayStart
                , i = e._iDisplayLength
                , o = e.fnRecordsDisplay()
                , u = i === -1
                , d = u ? 0 : Math.ceil(l / i)
                , f = u ? 1 : Math.ceil(o / i)
                , s = []
                , h = []
                , p = n(a).map(function (L) {
                    return L === "numbers" ? _a(d, f, a.buttons, a.boundaryNumbers) : L
                });
            s = s.concat.apply(s, p);
            for (var m = 0; m < s.length; m++) {
                var _ = s[m]
                    , v = cn(e, _, d, f)
                    , D = Be(e, "pagingButton")(e, _, v.display, v.active, v.disabled)
                    , y = typeof _ == "string" ? t[_] : t.number ? t.number + (_ + 1) : null;
                c(D.clicker).attr({
                    "aria-controls": e.sTableId,
                    "aria-disabled": v.disabled ? "true" : null,
                    "aria-current": v.active ? "page" : null,
                    "aria-label": y,
                    "data-dt-idx": _,
                    tabIndex: v.disabled ? -1 : e.iTabIndex && D.clicker[0].nodeName.toLowerCase() !== "span" ? e.iTabIndex : null
                }),
                    typeof _ != "number" && c(D.clicker).addClass(_),
                    aa(D.clicker, {
                        action: _
                    }, function (L) {
                        L.preventDefault(),
                            sr(e, L.data.action, !0)
                    }),
                    h.push(D.display)
            }
            var S = Be(e, "pagingContainer")(e, h)
                , C = r.find(I.activeElement).data("dt-idx");
            if (r.empty().append(S),
                C !== void 0 && r.find("[data-dt-idx=" + C + "]").trigger("focus"),
                h.length) {
                var A = c(h[0]).outerHeight();
                a.buttons > 1 && A > 0 && c(r).height() >= A * 2 - 10 && ma(e, r, c.extend({}, a, {
                    buttons: a.buttons - 2
                }))
            }
        }
    }
    function cn(e, r, a, n) {
        var t = e.oLanguage.oPaginate
            , l = {
                display: "",
                active: !1,
                disabled: !1
            };
        switch (r) {
            case "ellipsis":
                l.display = "&#x2026;";
                break;
            case "first":
                l.display = t.sFirst,
                    a === 0 && (l.disabled = !0);
                break;
            case "previous":
                l.display = t.sPrevious,
                    a === 0 && (l.disabled = !0);
                break;
            case "next":
                l.display = t.sNext,
                    (n === 0 || a === n - 1) && (l.disabled = !0);
                break;
            case "last":
                l.display = t.sLast,
                    (n === 0 || a === n - 1) && (l.disabled = !0);
                break;
            default:
                typeof r == "number" && (l.display = e.fnFormatNumber(r + 1),
                    a === r && (l.active = !0));
                break
        }
        return l
    }
    function _a(e, r, a, n) {
        var t = []
            , l = Math.floor(a / 2)
            , i = n ? 2 : 1
            , o = n ? 1 : 0;
        return r <= a ? t = K(0, r) : a === 1 ? t = [e] : a === 3 ? e <= 1 ? t = [0, 1, "ellipsis"] : e >= r - 2 ? (t = K(r - 2, r),
            t.unshift("ellipsis")) : t = ["ellipsis", e, "ellipsis"] : e <= l ? (t = K(0, a - i),
                t.push("ellipsis"),
                n && t.push(r - 1)) : e >= r - 1 - l ? (t = K(r - (a - i), r),
                    t.unshift("ellipsis"),
                    n && t.unshift(0)) : (t = K(e - l + i, e + l - o),
                        t.push("ellipsis"),
                        t.unshift("ellipsis"),
                        n && (t.push(r - 1),
                            t.unshift(0))),
            t
    }
    var gr = 0;
    return b.feature.register("pageLength", function (e, r) {
        var a = e.oFeatures;
        if (!a.bPaginate || !a.bLengthChange)
            return null;
        r = c.extend({
            menu: e.aLengthMenu,
            text: e.oLanguage.sLengthMenu
        }, r);
        var n = e.oClasses.length, t = e.sTableId, l = r.menu, i = [], o = [], u;
        if (Array.isArray(l[0]))
            i = l[0],
                o = l[1];
        else
            for (u = 0; u < l.length; u++)
                c.isPlainObject(l[u]) ? (i.push(l[u].value),
                    o.push(l[u].label)) : (i.push(l[u]),
                        o.push(l[u]));
        var d = r.text.match(/_MENU_$/)
            , f = r.text.match(/^_MENU_/)
            , s = r.text.replace(/_MENU_/, "")
            , h = "<label>" + r.text + "</label>";
        f ? h = "_MENU_<label>" + s + "</label>" : d && (h = "<label>" + s + "</label>_MENU_");
        var p = "tmp-" + +new Date
            , m = c("<div/>").addClass(n.container).append(h.replace("_MENU_", '<span id="' + p + '"></span>'))
            , _ = [];
        Array.prototype.slice.call(m.find("label")[0].childNodes).forEach(function (y) {
            y.nodeType === Node.TEXT_NODE && _.push({
                el: y,
                text: y.textContent
            })
        });
        var v = function (y) {
            _.forEach(function (S) {
                S.el.textContent = br(e, S.text, y)
            })
        }
            , D = c("<select/>", {
                "aria-controls": t,
                class: n.select
            });
        for (u = 0; u < i.length; u++)
            D[0][u] = new Option(typeof o[u] == "number" ? e.fnFormatNumber(o[u]) : o[u], i[u]);
        return m.find("label").attr("for", "dt-length-" + gr),
            D.attr("id", "dt-length-" + gr),
            gr++,
            m.find("#" + p).replaceWith(D),
            c("select", m).val(e._iDisplayLength).on("change.DT", function () {
                Gr(e, c(this).val()),
                    se(e)
            }),
            c(e.nTable).on("length.dt.DT", function (y, S, C) {
                e === S && (c("select", m).val(C),
                    v(C))
            }),
            v(e._iDisplayLength),
            m
    }, "l"),
        c.fn.dataTable = b,
        b.$ = c,
        c.fn.dataTableSettings = b.settings,
        c.fn.dataTableExt = b.ext,
        c.fn.DataTable = function (e) {
            return c(this).dataTable(e).api()
        }
        ,
        c.each(b, function (e, r) {
            c.fn.DataTable[e] = r
        }),
        b
}),
    function () {
        "use strict";
        const c = $.fn.dataTable;
        return c.ext.errMode = "throw",
            $.extend(!0, c.defaults, {
                renderer: "bootstrap",
                orderClasses: !1,
                autoWidth: !1,
                pageLength: 100,
                lengthMenu: [[25, 50, 100, 250, 500, 1e3, 5e3, -1], [25, 50, 100, 250, 500, "1K", "5K", "All (slow)"]],
                oLanguage: {
                    sSearch: "",
                    sSearchPlaceholder: "Search\u2026"
                },
                layout: {
                    bottomEnd: {
                        paging: {
                            firstLast: !1
                        }
                    }
                }
            }),
            $.extend(!0, c.ext.classes, {
                paging: {
                    active: "active",
                    container: "pagination"
                }
            }),
            c.ext.renderer.layout.bootstrap = function (N, I, b) {
                const F = document.createElement("div");
                F.className = b.full ? "dataTable_table_wrap" : "dataTable_display",
                    I.append(F);
                const x = $(F);
                $.each(b, function (T, g) {
                    x.append(g.contents)
                })
            }
            ,
            c
    }();
