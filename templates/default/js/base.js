function upload_form(a, c, b) {
    this.iframe = document.createElement("iframe");
    this.iframe.src = "//u.endoftheinter.net/u.php?topic=" + b;
    this.iframe.className = "upload_form";
    this.iframe.frameBorder = 0;
    var d = document.createElement("div");
    d.className = "upload_form";
    d.appendChild(this.iframe);
    c.parentNode.insertBefore(d, c.nextSibling);
    CSS.addClass(c.parentNode, "has-upload-form");
    return d
}
function tagTopic(c, a, b) {
    if (Ajax("/ajax.php?r=" + (b ? 1 : 2) + "&t=" + a).onsuccess(function (d) {
        if (d.error) {
            c.firstChild.nodeValue = d.error
        } else {
            c.firstChild.nodeValue = b ? "Untag" : "Tag";
            c.onclick = function () {
                return !tagTopic(c, a, !b)
            };
            c.href = c.href.replace(/tag=\d/, "tag=" + (b ? "0" : "1"))
        }
    }).send()) {
        c.firstChild.nodeValue = b ? "Tagging..." : "Untagging...";
        c.onclick = function () {
            return false
        };
        return true
    } else {
        return false
    }
}
function clearBookmark(a, b) {
    Ajax("/ajax.php?r=3&t=" + a).onsuccess(function (c) {
        if (!c.error) {
            b.innerHTML = ""
        }
    }).send();
    return false
}
function toggle_spoiler(a) {
    while (!/spoiler_(?:open|close)/.test(a.className)) {
        a = a.parentNode
    }
    a.className = a.className.indexOf("closed") != -1 ? a.className.replace("closed", "opened") : a.className.replace("opened", "closed");
    return false
}
var contentLoadedHooks = [];

function onDOMContentLoaded(a) {
    if (window.loaded) {
        a()
    } else {
        contentLoadedHooks.push(a)
    }
}
function DOMContentLoaded() {
    if (window.loaded) {
        return
    }
    window.loaded = true;
    contentLoadedHooks.invoke("call");
    EventNotifier.init();
    var a = document.body.getElementsByTagName("script"),
        b;
    while (b = a[0]) {
        b.parentNode.removeChild(b)
    }
}
if (/WebKit/i.test(navigator.userAgent)) {
    var _onloadTimer = setInterval(function () {
        if (/loaded|complete/.test(document.readyState)) {
            clearInterval(_onloadTimer);
            DOMContentLoaded()
        }
    }, 10)
} else {
    if (document.addEventListener) {
        document.addEventListener("DOMContentLoaded", DOMContentLoaded, false)
    } else {
        document.write('<script onreadystatechange="if(this.readyState==\'complete\')DOMContentLoaded()" defer="defer" src="javascript:void(0)"><\/script>')
    }
}
function chain(b, a) {
    return function () {
        return (b && b.apply(this, arguments) === false || a && a.apply(this, arguments) === false) ? true : undefined
    }
}
function addEventListener(c, b, a) {
    if (c.addEventListener) {
        c.addEventListener(b, a, false)
    } else {
        if (c.attachEvent) {
            c.attachEvent("on" + b, a)
        } else {
            c["on" + b] = chain(c["on" + b], a)
        }
    }
}
function $(a) {
    return document.getElementById(a)
}
var DOM = {
    eval: function (d) {
        var h = d.getElementsByTagName("script"),
            b;
        if (DOM.scriptsEval === null) {
            DOM.scriptsEval = false;
            var a = document.createElement("div");
            a.innerHTML = "<script>DOM.scriptsEval = true<\/script>";
            document.body.removeChild(document.body.appendChild(a))
        }
        while (b = h[0]) {
            if (!DOM.scriptsEval) {
                var g = b.innerHTML,
                    c = document.createElement("script");
                c.type = "text/javascript";
                try {
                    c.appendChild(document.createTextNode(g))
                } catch (f) {
                    c.text = g
                }
                document.body.appendChild(c).parentNode.removeChild(c)
            }
            b.parentNode.removeChild(b)
        }
    },
    scriptsEval: null,
    getCaret: function (c) {
        if ("selectionStart" in c) {
            return {
                start: c.selectionStart,
                end: c.selectionEnd
            }
        } else {
            try {
                var b = document.selection.createRange(),
                    f = b.duplicate();
                f.moveToElementText(c);
                f.setEndPoint("StartToEnd", b);
                var a = c.value.length - f.text.length;
                f.setEndPoint("StartToStart", b);
                return {
                    start: c.value.length - f.text.length,
                    end: a
                }
            } catch (d) {
                return {
                    start: 0,
                    end: 0
                }
            }
        }
    },
    setCaret: function (d, e, a) {
        if (a === void 0) {
            a = e
        } else {
            a = Math.min(d.value.length, a)
        }
        if ("selectionStart" in d) {
            d.focus();
            d.selectionStart = e;
            d.selectionEnd = a
        } else {
            if (d.tagName === "TEXTAREA") {
                var c = d.value.indexOf("\r", 0);
                while (c != -1 && c < a) {
                    --a;
                    if (c < e) {
                        --e
                    }
                    c = d.value.indexOf("\r", c + 1)
                }
            }
            var b = d.createTextRange();
            b.collapse(true);
            b.moveStart("character", e);
            if (a != undefined) {
                b.moveEnd("character", a - e)
            }
            b.select()
        }
    },
    serializeForm: function (b) {
        var a = {};
        $A(b.elements).forEach(function (c) {
            if (c.tagName != "INPUT" || (c.type != "submit" && c.type != "button")) {
                a[c.name] = c.value
            }
        });
        return a
    }
};
var CSS = {
    hasClass: function (b, a) {
        return new RegExp("(?:^|\\s)" + a + "(?:\\s|$)").test(b.className)
    },
    addClass: function (b, a) {
        CSS.hasClass(b, a) || (b.className += " " + a)
    },
    removeClass: function (b, a) {
        b.className = b.className.replace(new RegExp("(?:^|\\s)" + a + "(?:\\s|$)", "g"), "")
    },
    toggleClass: function (b, a) {
        (CSS.hasClass(b, a) ? CSS.removeClass : CSS.addClass)(b, a)
    },
    getComputedStyle: function (a) {
        if (window.getComputedStyle) {
            return getComputedStyle(a, null)
        }
        if (document.defaultView && document.defaultView.getComputedStyle) {
            return document.defaultView.getComputedStyle(a, null)
        }
        if (a.currentStyle) {
            return a.currentStyle
        }
        return a.style
    }
};

function json_encode(c) {
    var a = [];
    if (c instanceof Array) {
        for (var b = 0; b < c.length; b++) {
            a.push(json_encode(c[b]))
        }
        return "[" + a.join(",") + "]"
    } else {
        if (typeof c == "object") {
            for (var b in c) {
                a.push('"' + b + '":' + json_encode(c[b]))
            }
            return "{" + a.join(",") + "}"
        } else {
            if (typeof c == "string") {
                return '"' + c.replace(/"/g, '\\"').replace(/\\/g, "\\\\") + '"'
            } else {
                return "" + c
            }
        }
    }
}
Function.prototype.bind = function (b) {
    var c = this,
        a = arguments.length > 1 ? [].slice.call(arguments, 1) : null;
    return function () {
        return c.apply(b, a ? a.concat([].slice.call(arguments)) : arguments)
    }
};
Function.prototype.bindShift = function (b) {
    var c = this,
        a = arguments.length > 1 ? [].slice.call(arguments, 1) : null;
    return function () {
        return c.apply(b, a ? [this].concat(a).concat([].slice.call(arguments)) : [this].concat([].slice.call(arguments)))
    }
};
Function.prototype.defer = function (a) {
    return setTimeout(this, a ? a : 0)
};

function $M(a, b) {
    for (var c in b) {
        a[c] = b[c]
    }
    return a
}
function $A(c) {
    if (c instanceof Array) {
        return c.slice()
    } else {
        var a = [];
        for (var b = 0; b < c.length; b++) {
            a.push(c[b])
        }
        return a
    }
}
Array.prototype.last = function () {
    return this[this.length - 1]
};
Array.prototype.pull = function (b) {
    var a = [];
    for (var c = 0; c < this.length; ++c) {
        a.push(this[c][b])
    }
    return a
};
Array.prototype.invoke = function (d) {
    var b = [],
        a = [];
    for (var c = 1; c < arguments.length; ++c) {
        a.push(arguments[c])
    }
    for (var c = 0; c < this.length; ++c) {
        b.push(this[c][d].apply(this[c], a))
    }
    return b
};
Array.prototype.filter = Array.prototype.filter ||
function (c) {
    var a = [];
    for (var b = 0; b < this.length; ++b) {
        if (c.call(this, this[b], b)) {
            a.push(this[b])
        }
    }
    return a
};
Array.prototype.forEach = Array.prototype.forEach ||
function (c, b) {
    for (var a = 0; a < this.length; ++a) {
        c.call(b, this[a], a, this)
    }
};

function Subscriber(a) {
    this.focus = a;
    this.events = {};
    a.subscribe = this.subscribe.bind(this);
    a.publish = this.publish.bind(this)
}
Subscriber.prototype.subscribe = function (b, a) {
    (this.events[b] || (this.events[b] = [])).push(a)
};
Subscriber.prototype.publish = function (b) {
    var a;
    if (this.events[b]) {
        a = this.events[b].invoke("apply", this.focus, [null].concat([].slice.call(arguments, 1))).filter(function (c) {
            return c === false
        });
        return !a.length
    }
    return true
};
/*
function Ajax(a) {
    if (this === window) {
        return new Ajax(a)
    }
    this.uri = a;
    this.domain = (/https?:\/\/(.+?)\//.exec(a) || [document.location.host]).pop()
}
Ajax.data = {
    form: 1,
    raw: 2
};
Ajax.domains = {};
Ajax.queuedSend = [];
Ajax.counter = 0;
Ajax.getXMLHttpRequest = function (f) {
    var b;
    if (f == document.location.host) {
        try {
            return new XMLHttpRequest()
        } catch (g) {
            try {
                return new ActiveXObject("Msxml2.XMLHTTP")
            } catch (g) {
                try {
                    return new ActiveXObject("Microsoft.XMLHTTP")
                } catch (g) {
                    return false
                }
            }
        }
    } else {
        if (Ajax.domains[f]) {
            return new Ajax.domains[f]
        } else {
            if (/Firefox\/1\.0/.test(navigator.userAgent)) {
                return false
            }
            var d = document.location.protocol;
            var a = /[^.]+\.[^.]+$/.exec(document.location.host);
            if (a != document.domain || !window.ActiveXObject) {
                document.domain = a
            }
            var c = document.createElement("iframe");
            c.style.position = "absolute";
            c.style.top = c.style.left = "-1000px";
            c.src = document.location.protocol + "//" + f + "/crossdomain.html";
            document.body.appendChild(c);
            return true
        }
    }
};

Ajax.formEncodeData = function (d, a) {
    var b = [];
    if (d instanceof Array) {
        for (var c = 0; c < d.length; c++) {
            b.push(Ajax.formEncodeData(d[c], a ? a + "[" + c + "]" : c))
        }
    } else {
        if (typeof d == "object") {
            for (var c in d) {
                b.push(Ajax.formEncodeData(d[c], a ? a + "[" + c + "]" : c))
            }
        } else {
            if (a) {
                b.push(encodeURIComponent(a) + "=" + encodeURIComponent(d))
            } else {
                b.push(encodeURIComponent(d))
            }
        }
    }
    return b.join("&")
};
Ajax.addUnloadAbort = function () {
    if (Ajax.unloadAbort) {
        return
    }
    Ajax.unloadAbort = true;
    window.onbeforeunload = chain(window.onbeforeunload, function () {
        for (var a = 0; a < Ajax.active.length; a++) {
            Ajax.active[a].abort()
        }
    })
};
Ajax.subdomainReady = function (b, a) {
    Ajax.domains[b] = a;
    Ajax.queuedSend.filter(function (c) {
        if (c.domain == b) {
            c.send();
            return false
        } else {
            return true
        }
    })
};
Ajax.prototype.data = function (b, a) {
    this._data = b;
    this.dataType = a || Ajax.data.form;
    return this
};
Ajax.prototype.abort = function () {
    try {
        this.req.abort()
    } catch (a) {}
    this.setActive(false);
    return this
};
Ajax.prototype.onsuccess = function (a) {
    this.onsuccess_handler = a;
    return this
};
Ajax.prototype.onerror = function (a) {
    this.onerror_handler = a;
    return this
};
Ajax.prototype.onfinally = function (a) {
    this.onfinally_handler = a;
    return this
};
Ajax.active = [];
Ajax.prototype.setActive = function (a) {
    if (a) {
        Ajax.active.push(this)
    } else {
        Ajax.active = Ajax.active.filter(function (b) {
            return (b != this)
        }.bind(this));
        this.req = null
    }
};
Ajax.prototype.send = function () {
    this.req = Ajax.getXMLHttpRequest(this.domain);
    if (this.req === false) {
        return false
    } else {
        if (this.req === true) {
            if (this.triedCrossdomain) {
                return false
            } else {
                this.triedCrossdomain = true;
                Ajax.queuedSend.push(this);
                return true
            }
        }
    }
    this.req.onreadystatechange = function () {
        if (this.req.readyState == 4) {
            (function () {
                var ex = null;
                try {
                    if (this.req && this.req.status == 200) {
                        var response = this.req.responseText;
                        eval("response=" + (response.substr(0, 1) == "}" ? response.substr(1) : response));
                        this.onsuccess_handler && this.onsuccess_handler(response)
                    } else {
                        this.onerror_handler && this.onerror_handler()
                    }
                } catch (e) {
                    ex = e;
                    this.onerror_handler && this.onerror_handler()
                }
                this.onfinally_handler && this.onfinally_handler();
                this.setActive(false);
                if (ex !== null) {
                    throw ex
                }
            }).bind(this).defer()
        }
    }.bind(this);
    this.req.open("POST", this.uri, true);
    if (this.dataType == Ajax.data.raw) {
        this.req.send(this._data)
    } else {
        this.req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        this.req.send(Ajax.formEncodeData($M(this._data || {}, {
            "-ajaxCounter": ++Ajax.counter
        })))
    }
    this.setActive(true);
    Ajax.addUnloadAbort();
    return true
};
*/
function EventNotifier() {}
EventNotifier.domainMutexTimeout = 60000;
EventNotifier.events = {};
EventNotifier.errorRetry = 5000;
EventNotifier.maxErrorRetry = 60000;
EventNotifier.register = function (b, c, d, a) {
    EventNotifier.events[b] = {
        value: c,
        callback: d,
        extra: a
    };
    if (!EventNotifier.subscribeTimeout) {
        EventNotifier.subscribeTimeout = EventNotifier.subscribe.defer()
    }
};
EventNotifier.init = function () {
    EventNotifier.errorTimeout = EventNotifier.errorRetry;
    for (var a in EventNotifier.events) {
        EventNotifier.lockDomain();
        break
    }
};
EventNotifier.lockDomain = function () {
    var b = (/evt=(.+?)(?:;|$)/.exec(document.cookie) || [""]).pop().split("|"),
        d = (new Date()).getTime(),
        a = d - EventNotifier.domainMutexTimeout;
    for (var c = 0; c < b.length; c++) {
        b[c] = parseInt(b[c], 10);
        if (isNaN(b[c]) || a > b[c]) {
            EventNotifier.domain = c;
            break
        }
    }
    if (EventNotifier.domain === undefined) {
        EventNotifier.domain = b.length;
        b.push(d)
    } else {
        b[EventNotifier.domain] = d
    }
    document.cookie = "evt=" + b.join("|");
    setInterval(EventNotifier.renewDomainLock, EventNotifier.domainMutexTimeout - 1000);
    window.onbeforeunload = chain(window.onbeforeunload, EventNotifier.releaseDomainLock)
};
EventNotifier.renewDomainLock = function () {
    var a = (/evt=(.+?)(?:;|$)/.exec(document.cookie) || [""]).pop().split("|");
    a[EventNotifier.domain] = (new Date()).getTime();
    document.cookie = "evt=" + a.join("|")
};
EventNotifier.releaseDomainLock = function () {
    var b = (/evt=(.+?)(?:;|$)/.exec(document.cookie) || [""]).pop().split("|"),
        a = (new Date()).getTime() - EventNotifier.domainMutexTimeout,
        d = 0;
    b[EventNotifier.domain] = 0;
    for (var c = b.length - 1; c >= 0; c--) {
        if (parseInt(b[c], 10) > a) {
            d = c;
            break
        }
    }
    b = b.slice(0, d + 1);
    document.cookie = "evt=" + b.join("|")
};
EventNotifier.subscribe = function () {
    var c = {};
    EventNotifier.subscribeTimeout = null;
    for (var b in EventNotifier.events) {
        c[b] = EventNotifier.events[b].value
    }
    var a = ("evt" + EventNotifier.domain) + (document.location.protocol == "https:" ? "" : ".evt") + ".endoftheinter.net";
    Ajax(document.location.protocol + "//" + a + "/subscribe").data(json_encode(c), Ajax.data.raw).onsuccess(function (d) {
        EventNotifier.errorTimeout = EventNotifier.errorRetry;
        for (var e in d) {
            if (EventNotifier.events[e]) {
                EventNotifier.events[e].callback(d[e], EventNotifier.events[e].value, EventNotifier.events[e].extra);
                EventNotifier.events[e].value = d[e]
            }
        }
        EventNotifier.subscribe()
    }).onerror(function () {
        EventNotifier.subscribe.defer(EventNotifier.errorTimeout);
        EventNotifier.errorTimeout = Math.min(EventNotifier.errorRetry * 2, EventNotifier.maxErrorRetry)
    }).send()
};

function Navi() {
    this.nextSilence = null;
    this.pocketed = true;
    this.pocketTimeout = null;
    this.lastPlayed = null
}
Navi.soundLength = 800;
Navi.pocketTimeout = 15000;
Navi.singleton = function () {
    if (!Navi.instance) {
        Navi.instance = new Navi()
    }
    return Navi.instance
};
Navi.prototype.playSound = function (b, c) {
    var a = (new Date()).getTime();
    if (!this.sound) {
        this.sound = document.getElementById("sound_player");
        if (!this.sound) {
            return
        }
    }
    if (a < this.nextSilence) {
        (function () {
            this.playSound(b, c)
        }).bind(this).defer(this.nextSilence - a)
    } else {
        this.nextSilence = a + Navi.soundLength;
        this.sound.playSound("//static.endoftheinter.net/navi/" + b + ".mp3");
        (function () {
            var d = (new Date()).getTime();
            this.nextSilence += (d - a);
            c && c.defer(Navi.soundLength)
        }).bind(this).defer()
    }
};
Navi.prototype.removeFromPocket = function (a) {
    if (!this.pocketed) {
        a();
        if (this.pocketTimeout) {
            clearInterval(this.pocketTimeout)
        }
    } else {
        this.playSound("out", a);
        this.pocketed = false
    }
    this.pocketTimeout = setTimeout(this.returnToPocket.bind(this), Navi.pocketTimeout)
};
Navi.prototype.returnToPocket = function () {
    this.playSound("in");
    this.pocketed = true;
    this.pocketTimeout = null
};
Navi.prototype.notify = function () {
    var a = ["hello", "hey", "listen", "look", "watchout"];
    this.removeFromPocket(function () {
        var b = null;
        do {
            b = a[Math.floor(Math.random() * a.length)]
        } while (b == this.lastPlayed);
        this.lastPlayed = b;
        this.playSound(b)
    }.bind(this))
};

function llmlSpoiler(b) {
    this.dom = b;
    var a = b.getElementsByTagName("a");
    a[0].onclick = this.setState.bind(this, true);
    a[1].onclick = a[a.length - 1].onclick = this.setState.bind(this, false)
}
llmlSpoiler.prototype.setState = function (a) {
    this.dom.className = a ? "spoiler_opened" : "spoiler_closed";
    ImageLoader.onViewportChanged();
    return false
};

function TopicManager(h, c, e, f, a, g, d, b) {
    this.id = h;
    this.isTopic = c;
    this.dom = f;
    this.messages = e;
    this.pagers = a;
    this.viewers = g;
    this.filter = b;
    this.pendingUpdate = 0;
    d && EventNotifier.register(d[0], d[1], this.updateMessages.bind(this));
    if (!c) {
        addEventListener(document, "scroll", this.updateBookmark.bind(this));
        addEventListener(document, "mousemove", this.updateBookmark.bind(this));
        this.currentBookmark = e
    }
}
TopicManager.prototype.updateMessages = function (newCount) {
    if (this.pendingUpdate) {
        this.pendingMessages = newCount;
        return
    }
    this.pagers.invoke("setRows", newCount);
    var maxMessages = Math.min(this.pagers[0].getPage() * this.pagers[0].perPage, newCount);
    if (maxMessages <= newCount) {
        Ajax("/moremessages.php?" + (this.isTopic ? "topic=" : "pm=") + this.id + "&old=" + this.messages + "&new=" + maxMessages + "&filter=" + this.filter).onsuccess(function (data) {
            if (data) {
                var tmp = document.createElement("div");
                tmp.innerHTML = data;
                DOM.eval(this.dom.appendChild(tmp));
                var b = document.getElementById("topic_viewers_update");
                if (b) {
                    this.viewers.innerHTML = b.innerHTML;
                    b.parentNode.removeChild(b)
                }(function () {
                    Navi.singleton().notify()
                }).defer()
            }
            if (--this.pendingUpdate) {
                this.updateMessages(this.pendingMessages)
            }
        }.bind(this)).send();
        ++this.pendingUpdate
    }
    this.messages = newCount
};
TopicManager.prototype.updateBookmark = function () {
    if (this.currentBookmark == this.messages) {
        return
    }
    Ajax("/async-update-bookmark.php?pm=" + this.id + "&count=" + this.messages).send();
    this.currentBookmark = this.messages
};
!
function () {
    function getNextSiblingDeep(node, fn) {
        while (!node.nextSibling) {
            node = node.parentNode;
            fn && fn(node)
        }
        return node.nextSibling
    }
    function getPrevSiblingDeep(node, fn) {
        var tmp = node;
        while (!node.previousSibling) {
            node = node.parentNode;
            fn && fn(node)
        }
        return node.previousSibling
    }
    function getNodeFromOffset(container, offset) {
        if (container.childNodes[offset]) {
            return container.childNodes[offset]
        } else {
            return getNextSiblingDeep(container)
        }
    }
    window.getNodeFromOffset = getNodeFromOffset;

    function iterateBalanceHelper(node, balance, reverse, start) {
        if (balance) {
            var nodes = [];
            while (node != balance) {
                nodes.push(node);
                node = node.parentNode
            }(reverse ? nodes.reverse() : nodes).forEach(function (ii) {
                start(ii)
            })
        }
    }
    function iterateRange(startContainer, startOffset, endContainer, endOffset, reverse, balanceNode, start, end, text) {
        var tmp, getNextNode = reverse ? getPrevSiblingDeep : getNextSiblingDeep;
        var endNode;
        if (endContainer.nodeType != 3) {
            endNode = getNodeFromOffset(endContainer, endOffset);
            if (reverse) {
                endNode = getPrevSiblingDeep(endNode)
            }
            if (endNode.nodeType == 3) {
                endOffset = reverse ? endNode.nodeValue.length : 0
            } else {
                endOffset = 0
            }
        } else {
            endNode = endContainer
        }
        var startNode;
        if (startContainer.nodeType != 3) {
            startNode = getNodeFromOffset(startContainer, startOffset);
            if (reverse) {
                startNode = getPrevSiblingDeep(startNode);
                while (startNode.lastChild) {
                    startNode = startNode.lastChild
                }
            }
            if (startNode.nodeType == 3) {
                startOffset = reverse ? startNode.nodeValue.length : 0
            }
        } else {
            startNode = startContainer
        }
        var node = startNode;
        iterateBalanceHelper(node.parentNode, balanceNode, true, reverse ? end : start);
        if (node == endNode && node.nodeType == 3) {
            reverse ? text(node, endOffset, node.nodeValue.length == startOffset ? undefined : startOffset) : text(node, startOffset, node.nodeValue.length == endOffset ? undefined : endOffset);
            endOffset = -1
        } else {
            if (node.nodeType == 3) {
                if (reverse || startOffset != node.nodeValue.length) {
                    text(node, reverse ? 0 : startOffset, reverse ? (node.nodeValue.length == startOffset ? undefined : startOffset) : undefined)
                }
                node = getNextNode(node, reverse ? start : end)
            }
        }
        var lastNode = node;
        while (node != endNode) {
            lastNode = node;
            if (tmp = reverse ? node.lastChild : node.firstChild) {
                (reverse ? end : start)(node);
                node = tmp
            } else {
                node.nodeType == 3 ? text(node, 0) : start(node, true);
                node = getNextNode(node, reverse ? start : end)
            }
        }
        if (endNode != startNode && endNode.nodeType == 3 && endOffset != (reverse ? endNode.nodeValue.length : 0)) {
            reverse ? text(endNode, endOffset, undefined) : text(endNode, 0, endOffset)
        }
        iterateBalanceHelper(lastNode.parentNode, balanceNode, false, reverse ? start : end)
    }
    function getNearestContent(startNode, startOffset, endNode, endOffset, reverse, afterNode) {
        try {
            iterateRange(startNode, startOffset, endNode, endOffset, reverse, false, function (node, single) {
                if (afterNode) {
                    if (afterNode == node) {
                        afterNode = null
                    }
                    return
                }
                if (node.tagName == "IMG" || (node.tagName == "SPAN" && node.className == "img-placeholder")) {
                    for (var ii = 0, tmp = node.previousSibling; tmp; tmp = tmp.previousSibling, ++ii) {}
                    throw [node.parentNode, reverse ? ii + 1 : ii]
                }
            }, function (node) {}, function (node, start, end) {
                if (afterNode) {
                    if (afterNode == node) {
                        afterNode = null
                    }
                    return
                }
                var tmp;
                tmp = node.nodeValue.substr(start, (end === undefined ? node.nodeValue.length : end) - start).search(reverse ? /\S\s*$/ : /\S/);
                if (tmp != -1) {
                    throw [node, start + tmp + (reverse ? 1 : 0)]
                }
            })
        } catch (ex) {
            if (ex instanceof Array) {
                return ex
            } else {
                throw ex
            }
        }
        return afterNode ? undefined : false
    }
    function getSelectedRange() {
        var sel;
        if (window.getSelection) {
            sel = getSelection()
        } else {
            return false
        }
        if (sel.isCollapsed) {
            return
        }
        var range;
        if (sel.getRangeAt) {
            range = sel.getRangeAt(0)
        } else {
            if (sel.anchorNode) {
                range = document.createRange();
                range.setStart(sel.anchorNode, sel.anchorOffset);
                range.setEnd(sel.focusNode, sel.focusOffset)
            }
        }
        var tmp = getNearestContent(range.startContainer, range.startOffset, range.endContainer, range.endOffset, false);
        if (!tmp) {
            return
        }
        var startContainer = tmp[0],
            startOffset = tmp[1];
        tmp = getNearestContent(range.endContainer, range.endOffset, startContainer, startOffset, true);
        var endContainer = tmp[0],
            endOffset = tmp[1];
        if (startContainer != endContainer || startOffset != endOffset) {
            range = document.createRange();
            range.setStart(startContainer, startOffset);
            range.setEnd(endContainer, endOffset);
            return range
        }
        return false
    }
    function getQuotableParent(range) {
        var parentNode = range.commonAncestorContainer;
        while (parentNode) {
            if (CSS.hasClass(parentNode, "message")) {
                break
            }
            parentNode = parentNode.parentNode
        }
        if (!parentNode) {
            return
        }
        return parentNode
    }
    function stripSigFromRange(range, message) {
        var sig, lines = 0;
        $A(message.childNodes).reverse().forEach(function (ii) {
            if (lines < 3) {
                if (ii.tagName == "BR") {
                    ++lines
                } else {
                    if (ii.nodeType == 3 && ii.nodeValue.replace(/^\s+|\s+$/g, "") == "---") {
                        if (ii.previousSibling && ii.previousSibling.tagName == "BR") {
                            sig = ii.previousSibling
                        } else {
                            sig = ii
                        }
                    }
                }
            }
        });
        var tmp = getNearestContent(range.endContainer, range.endOffset, range.startContainer, range.startOffset, true, sig);
        if (tmp === undefined) {
            return range
        } else {
            if (tmp === false) {
                return false
            }
        }
        var ret = {
            startContainer: range.startContainer,
            startOffset: range.startOffset,
            endContainer: tmp[0],
            endOffset: tmp[1]
        };
        range.detach && range.detach();
        return ret
    }
    function expandRangeToSpoiler(range) {
        if (range.endContainer.parentNode.parentNode.parentNode.className == "spoiler_on_close") {
            return {
                startContainer: range.startContainer,
                startOffset: range.startOffset,
                endContainer: range.endContainer.parentNode.parentNode.parentNode.parentNode.nextSibling,
                endOffset: 0
            }
        } else {
            return range
        }
    }
    function getLLMLFromRange(range, balance) {
        var markup = [],
            close = [],
            ignore, quoteDepth = 0,
            imgs = false;
        iterateRange(range.startContainer, range.startOffset, range.endContainer, range.endOffset, false, balance, function (node) {
            if (ignore) {
                return false
            }
            if (node.tagName == "I" || node.tagName == "B" || node.tagName == "U") {
                markup.push("<", node.tagName.toLowerCase(), ">");
                close.push([node, ["</", node.tagName.toLowerCase(), ">"]])
            } else {
                if (node.tagName == "SPAN" && node.className == "pr") {
                    markup.push("<pre>");
                    close.push([node, ["</pre>"]])
                } else {
                    if (node.tagName == "DIV" && node.className == "secret") {
                        ignore = node
                    } else {
                        if (node.tagName == "DIV" && node.className == "quoted-message") {
                            var msgid;
                            markup.push("<quote");
                            if (msgid = node.getAttribute("msgid")) {
                                markup.push(' msgid="', msgid, '"')
                            }
                            if (++quoteDepth > 1) {
                                ignore = node;
                                markup.push(" />")
                            } else {
                                markup.push(">");
                                close.push([node, ["</quote>"]])
                            }
                        } else {
                            if (node.tagName == "DIV" && node.className == "message-top") {
                                ignore = node
                            } else {
                                if (node.tagName == "SPAN" && (CSS.hasClass(node, "spoiler_opened") || CSS.hasClass(node, "spoiler_closed"))) {
                                    close.push([node, ["</spoiler>"]]);
                                    while (node.nodeType != 3) {
                                        node = node.firstChild
                                    }
                                    markup.push("<spoiler");
                                    var caption = node.nodeValue.replace(/^<| \/>$/g, "");
                                    if (caption != "spoiler") {
                                        markup.push(' caption="', caption, '"')
                                    }
                                    markup.push(">")
                                } else {
                                    if (node.tagName == "A" && node.className == "caption") {
                                        ignore = node
                                    } else {
                                        if (node.tagName == "DIV" && node.className == "imgs") {
                                            imgs = true
                                        } else {
                                            if (node.tagName == "A" && (imgs || node.className == "img")) {
                                                ignore = node;
                                                var src = node.getAttribute("imgsrc");
                                                if (src) {
                                                    markup.push('<img src="', src, '" />')
                                                }
                                                if (imgs) {
                                                    markup.push("\n")
                                                }
                                            } else {
                                                if (node.tagName == "A") {
                                                    var tmp;
                                                    if (tmp = /\.endoftheinter\.net\/linkme\.php\?l=([0-9]+)/.exec(node.href)) {
                                                        markup.push("LL", parseInt(tmp[1], 10).toString(16))
                                                    } else {
                                                        if (tmp = /wiki\.[^\/]*\.endoftheinter\.net\/index\.php\/([A-Za-z\d_:@.!$%*()~\-#\/]+)/.exec(node.href)) {
                                                            markup.push("[[", tmp[1], "]]")
                                                        } else {
                                                            if (node.href && !/^javascript:/.test(node)) {
                                                                markup.push(node.href)
                                                            }
                                                        }
                                                    }
                                                    ignore = node
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }, function (node) {
            var ignored = ignore;
            if (ignore == node) {
                ignore = null
            }
            if (node.tagName == "DIV" && node.className == "quoted-message") {
                --quoteDepth
            } else {
                if (!ignored && node.tagName == "DIV" && node.className == "imgs") {
                    imgs = false;
                    markup.pop()
                }
            }
            if (close.length && close.last()[0] == node) {
                markup.push.apply(markup, close.pop()[1])
            }
        }, function (node, start, end) {
            if (ignore || imgs) {
                return
            }
            markup.push(node.nodeValue.substr(start, (end === undefined ? node.nodeValue.length : end) - start))
        });
        return markup.join("")
    }
    function getRangeBoundingBox(range, parentNode) {
        var shadow = document.createElement("div");
        shadow.className = "message";
        shadow.style.position = "absolute";
        shadow.style.top = shadow.style.left = "-10000px";
        shadow.style.width = parentNode.offsetWidth + "px";
        document.body.appendChild(shadow);
        shadow.style.width = parentNode.offsetWidth * 2 - shadow.offsetWidth + "px";
        var children = parentNode.cloneNode(true).childNodes;
        while (children.length) {
            shadow.appendChild(children[0])
        }
        var shadowRange = {
            startContainer: getSameNodeFromClone(shadow, parentNode, range.startContainer),
            startOffset: range.startOffset,
            endContainer: getSameNodeFromClone(shadow, parentNode, range.endContainer),
            endOffset: range.endOffset
        };
        var left = getCaretDimensions(shadowRange.startContainer, shadowRange.startOffset),
            right = getCaretDimensions(shadowRange.endContainer, shadowRange.endOffset, true),
            box = {};
        box.top = left.top;
        if (left.top == right.top) {
            box.left = left.left;
            box.height = Math.max(left.height, right.height);
            box.width = right.left - left.left
        } else {
            box.left = parseInt(CSS.getComputedStyle(shadow).paddingLeft, 10);
            box.height = right.top - left.top + right.height;
            var tmpRange = document.createRange();
            tmpRange.setStart(shadowRange.startContainer, shadowRange.startOffset);
            tmpRange.setEnd(shadowRange.endContainer, shadowRange.endOffset);
            var tmpParent = tmpRange.commonAncestorContainer;
            var offset = 0;
            if (tmpParent.nodeType == 3) {
                tmpParent = tmpParent.parentNode
            }
            if (tmpParent != shadow) {
                offset = tmpParent.offsetLeft + parseInt(CSS.getComputedStyle(tmpParent).paddingLeft, 10)
            }
            var frag = tmpRange.extractContents();
            if (frag.childNodes[0].nodeType == 3 && frag.childNodes[0].nodeValue.charAt(0) == " ") {
                frag.childNodes[0].replaceData(0, 1, "\u00a0")
            }
            tmpRange.detach();
            while (shadow.childNodes.length) {
                shadow.removeChild(shadow.childNodes[0])
            }
            shadow.style.textIndent = left.left - offset + "px";
            shadow.appendChild(frag);
            shadow.style.maxWidth = shadow.style.width;
            shadow.style.width = "auto";
            shadow.style.padding = "0px";
            box.width = shadow.offsetWidth + offset - box.left
        }
        shadow.parentNode.removeChild(shadow);
        return box
    }
    function getSameNodeFromClone(clone, original, anchor) {
        var offsetStack = [];
        while (anchor != original) {
            for (var ii = 0, tmp = anchor.previousSibling; tmp; tmp = tmp.previousSibling, ++ii) {}
            offsetStack.push(ii);
            anchor = anchor.parentNode
        }
        for (ii = offsetStack.length; ii--;) {
            clone = clone.childNodes[offsetStack[ii]]
        }
        return clone
    }
    function getCaretDimensions(node, position, end) {
        var caret, split;
        if (node.nodeType != 3) {
            if (node.childNodes[position]) {
                node = node.childNodes[position]
            } else {
                end = true
            }
            if (node.nodeType == 3) {
                position = 0
            }
        }
        caret = node;
        if (node.nodeType == 3) {
            end = false;
            var tmp = document.createElement("span");
            tmp.appendChild(document.createTextNode("\u00a0"));
            if (!position) {
                caret = node.parentNode.insertBefore(tmp, node)
            } else {
                if (position != node.nodeValue.length) {
                    split = true;
                    node.splitText(position)
                }
                caret = node.parentNode.insertBefore(tmp, node.nextSibling)
            }
        }
        var dimensions;
        if (caret.offsetLeft) {
            dimensions = {
                left: caret.offsetLeft + (end ? caret.offsetWidth : 0),
                top: caret.offsetTop,
                height: caret.offsetHeight
            }
        } else {
            var newCaret = caret;
            while (!newCaret.offsetLeft) {
                newCaret = newCaret.parentNode
            }
            dimensions = getCaretDimensions(newCaret, newCaret.childNodes.length)
        }
        if (caret != node) {
            caret.parentNode.removeChild(caret);
            if (split) {
                var split = node.parentNode.removeChild(node.nextSibling);
                node.appendData(split.nodeValue)
            }
        }
        return dimensions
    }
    function QuoteBox(box) {
        new Subscriber(this);
        var width = Math.max(box.width, 20),
            left = box.left - width + box.width,
            height = Math.max(box.height, 13),
            top = box.top - height + box.height;
        var horizLine = document.createElement("div");
        horizLine.className = "quoter-bottom";
        horizLine.style.width = width - 20 + "px";
        horizLine.style.top = top + height + "px";
        horizLine.style.left = left + "px";
        horizLine.innerHTML = '<div class="quoter-hl"></div><div class="quoter-hc"></div><div class="quoter-hr"></div>';
        document.body.appendChild(this.horizLine = horizLine);
        var vertLine = document.createElement("div");
        vertLine.className = "quoter-right";
        vertLine.style.height = height - 13 + "px";
        vertLine.style.top = top + "px";
        vertLine.style.left = left + width + "px";
        vertLine.innerHTML = '<div class="quoter-vt"></div><div class="quoter-vc"></div>';
        document.body.appendChild(this.vertLine = vertLine);
        var button = document.createElement("a");
        button.href = "#";
        button.innerHTML = "<sup>\u201C</sup> <sub>\u201D</sub>";
        button.className = "quoter-button";
        button.style.top = height + 2 + "px";
        button.onclick = function () {
            this.publish("click");
            this.destroy();
            this.destroy = function () {};
            return false
        }.bind(this);
        button.onmousedown = function (event) {
            event.cancelBubble = true
        };
        vertLine.appendChild(button)
    }
    QuoteBox.prototype.destroy = function () {
        document.body.removeChild(this.horizLine);
        document.body.removeChild(this.vertLine)
    };

    function QuickPost(id, root, sig, nub, preview, post, grip, upload, uploadAnchor) {
        this.root = root;
        this.canvas = this.root.getElementsByTagName("div")[0];
        this.nub = nub;
        this.textarea = this.root.getElementsByTagName("textarea")[0];
        this.preview = preview;
        this.post = post;
        this.grip = grip;
        this.em = this.root.getElementsByTagName("em")[0];
        this.expanded = false;
        this.selection = {
            start: 0,
            end: 0
        };
        this.sig = sig;
        QuickPost.subscribe("quote", this.onquote.bind(this));
        nub.onclick = this.toggleExpanded.bind(this);
        grip.onmousedown = this.ongrab.bind(this);
        grip.onclick = grip.onselectstart = grip.ondragstart = function () {
            return false
        };
        addEventListener(document, "keypress", function (event) {
            event = event || window.event;
            var target = event.target || event.srcElement;
            if (target && (target.tagName != "INPUT" || (target.type == "submit" || target.type == "button")) && target.tagName != "TEXTAREA" && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey && event.charCode == 96) {
                this.toggleExpanded.bind(this).defer()
            }
        }.bind(this));
        this.textarea.onblur = function () {
            this.selection = DOM.getCaret(this.textarea)
        }.bind(this);
        preview.onclick = this.onpreview.bind(this);
        post.onclick = this.onpost.bind(this);
        var tmp = Function.prototype.defer.bind(this.onselectionchange.bind(this, false), 25);
        addEventListener(document, "mousedown", tmp);
        addEventListener(document, "mouseup", tmp);
        upload.onclick = function () {
            var c = this.onclick,
                i = new upload_form(null, uploadAnchor, id);
            this.value = "Hide Uploader";
            this.onclick = function () {
                i.parentNode.removeChild(i);
                CSS.removeClass(uploadAnchor.parentNode, "has-upload-form");
                this.onclick = c;
                this.value = "Upload Image"
            }
        }
    }
    new Subscriber(QuickPost);
    QuickPost.prototype.killQuoteBox = function () {
        if (this.visibleBox) {
            this.visibleBox.destroy();
            this.visibleBox = null
        }
    };
    QuickPost.prototype.toggleExpanded = function () {
        this.expanded = !this.expanded;
        CSS.toggleClass(document.body, "quickpost-expanded");
        if (this.expanded) {
            var range = getSelectedRange();
            if (this.em.childNodes.length) {
                this.em.innerHTML = ""
            }
            DOM.setCaret(this.textarea, this.selection.start, this.selection.end);
            this.root.style.height = this.root.offsetHeight - 1 + "px";
            document.body.style.paddingBottom = this.root.style.height;
            this.onselectionchange.bind(this, range).defer()
        } else {
            document.body.style.paddingBottom = "";
            this.killQuoteBox()
        }
        return false
    };
    QuickPost.prototype.onpreview = function () {
        if (Ajax("/async-post.php").data($M(DOM.serializeForm(this.root), {
            preview: true
        })).onsuccess(function (message) {
            CSS.addClass(this.root, "quickpost-preview");
            var div = document.createElement("div");
            div.innerHTML = message;
            DOM.eval(this.canvas.appendChild(div));
            var submit = document.createElement("input"),
                cancel = document.createElement("input");
            submit.type = cancel.type = "button";
            submit.value = "Post Message";
            cancel.value = "Edit Message";
            var buttons = document.createElement("div");
            buttons.className = "quickpost-buttons";
            buttons.appendChild(submit);
            buttons.appendChild(document.createTextNode(" "));
            buttons.appendChild(cancel);
            this.canvas.appendChild(buttons);
            var toggle = function () {
                    CSS.removeClass(this.root, "quickpost-preview");
                    [div, buttons].forEach(function (ii) {
                        ii.parentNode.removeChild(ii)
                    })
                }.bind(this);
            cancel.onclick = toggle;
            submit.onclick = function () {
                toggle();
                this.onpost()
            }.bind(this)
        }.bind(this)).onfinally(function () {
            CSS.removeClass(this.textarea, "locked");
            this.textarea.readOnly = false;
            this.preview.disabled = this.post.disabled = false
        }.bind(this)).send()) {
            CSS.addClass(this.textarea, "locked");
            this.textarea.readOnly = true;
            this.preview.disabled = this.post.disabled = true
        }
        return false
    };
    QuickPost.prototype.onpost = function () {
        if (Ajax("/async-post.php").data(DOM.serializeForm(this.root)).onsuccess(function (message) {
            if (message.success) {
                this.textarea.value = this.sig ? "\n---\n" + this.sig : "";
                this.selection = {
                    start: 0,
                    end: 0
                };
                this.expanded && this.toggleExpanded()
            } else {
                this.em.innerHTML = message;
                DOM.eval(this.em)
            }
        }.bind(this)).onfinally(function () {
            CSS.removeClass(this.textarea, "locked");
            this.textarea.readOnly = false;
            this.preview.disabled = this.post.disabled = false
        }.bind(this)).send()) {
            CSS.addClass(this.textarea, "locked");
            this.textarea.readOnly = true;
            this.preview.disabled = this.post.disabled = true
        }
        return false
    };
    QuickPost.prototype.onquote = function (event, root) {
        var range, node;
        if (root) {
            if (this.root.offsetTop == -1) {
                return
            }
            node = root.parentNode.parentNode.getElementsByTagName("td")[0] || root.parentNode.parentNode.childNodes[1];
            range = stripSigFromRange({
                startContainer: node,
                startOffset: 0,
                endContainer: node,
                endOffset: node.childNodes.length
            }, node);
            if (range) {
                var start = getNearestContent(range.startContainer, range.startOffset, range.endContainer, range.endOffset);
                range.startContainer = start[0];
                range.startOffset = start[1]
            } else {
                range = {
                    startContainer: node,
                    startOffset: 0,
                    endContainer: node,
                    endOffset: 0
                }
            }
        } else {
            node = this.quoteRoot;
            range = this.range
        }
        var text = '<quote msgid="' + node.getAttribute("msgid") + '">' + getLLMLFromRange(range, node) + "</quote>\n",
            left = this.textarea.value.substr(0, this.selection.start),
            right = this.textarea.value.substr(this.selection.start);
        if (/.\n?$/.test(left)) {
            text = "\n" + text
        }
        this.textarea.value = left + text + right;
        this.selection.start += text.length;
        this.selection.end = this.selection.start;
        if (this.expanded) {
            DOM.setCaret(this.textarea, this.selection.start, this.selection.end)
        } else {
            this.toggleExpanded()
        }
        return false
    };
    QuickPost.prototype.ongrab = function (ev) {
        var lastY = (ev || event).clientY,
            lastHeight = this.root.offsetHeight - 1,
            lastTextHeight = parseInt(CSS.getComputedStyle(this.textarea).height, 10);
        document.onmousemove = function (ev) {
            var y = (ev || event).clientY;
            lastHeight += (lastY - y);
            lastTextHeight += (lastY - y);
            lastY = y;
            document.body.style.paddingBottom = this.root.style.height = lastHeight + "px";
            this.textarea.style.height = lastTextHeight + "px"
        }.bind(this);
        document.onmouseup = function () {
            document.onmousemove = document.onmouseup = null;
            document.body.style.cursor = "auto"
        };
        document.body.style.cursor = "pointer";
        return false
    };
    QuickPost.prototype.onselectionchange = function (range) {
        if (!this.expanded) {
            return
        }
        range = range || getSelectedRange();
        var oldRange = this.range;
        this.range = null;
        if (!range) {
            this.killQuoteBox();
            return
        }
        var parentNode = getQuotableParent(range);
        if (!parentNode) {
            this.killQuoteBox();
            return
        }
        range = expandRangeToSpoiler(stripSigFromRange(range, this.quoteRoot = parentNode));
        if (oldRange && oldRange.startContainer == range.startContainer && oldRange.endContainer == range.endContainer && oldRange.startOffset == range.startOffset && oldRange.endOffset == range.endOffset) {
            this.range = oldRange;
            return
        }
        this.killQuoteBox();
        var box = getRangeBoundingBox(range, parentNode);
        if (parentNode.offsetParent == document.body) {
            box.top += parentNode.offsetTop;
            box.left += parentNode.offsetLeft
        } else {
            box.top += parentNode.offsetParent.offsetTop;
            box.left += parentNode.offsetParent.offsetLeft
        }
        this.visibleBox = new QuoteBox(box);
        this.visibleBox.subscribe("click", this.onquote.bind(this));
        this.range = {
            startContainer: range.startContainer,
            endContainer: range.endContainer,
            startOffset: range.startOffset,
            endOffset: range.endOffset
        }
    };
    this.QuickPost = QuickPost
}();

function uiPagerBrowser(d, a, c, b) {
    this.dom = d;
    this.uri = a;
    this.rows = c;
    this.page = b
}
uiPagerBrowser.prototype.perPage = 50;
uiPagerBrowser.prototype.setRows = function (b) {
    var a = this.getPages();
    this.rows = b;
    if (a != this.getPages()) {
        this.updateDOM(a, this.getPages())
    }
};
uiPagerBrowser.prototype.getPage = function () {
    return this.page
};
uiPagerBrowser.prototype.getPages = function () {
    return Math.max(1, Math.ceil(this.rows / this.perPage))
};
uiPagerBrowser.prototype.updateDOM = function (e, b) {
    var d = this.dom.lastChild,
        c = d.previousSibling,
        a = this.dom.getElementsByTagName("span")[0];
    var f = d.getElementsByTagName("a")[0];
    f.href = f.href.replace(/page=[0-9]+/, "page=" + b);
    if (this.getPage() < this.getPages()) {
        c.style.display = "inline"
    }
    if (this.getPage() + 1 < this.getPages()) {
        d.style.display = "inline"
    }
    a.innerHTML = b
};

function uiPagerEnum(d, a, c, b) {
    uiPagerBrowser.call(this, d, a, c, b)
}
uiPagerEnum.prototype = new uiPagerBrowser;
uiPagerEnum.prototype.updateDOM = function (c, a) {
    var b = c;
    while (++b <= a) {
        this.dom.appendChild(document.createTextNode(" | "));
        var d = document.createElement("a");
        d.href = this.uri + "&page=" + b;
        d.appendChild(document.createTextNode(b));
        this.dom.appendChild(d)
    }
};

function PrivateMessageManager(c, b, a) {
    this.dom = c;
    this.count = b;
    EventNotifier.register(a[0], a[1], this.update.bind(this))
}
PrivateMessageManager.prototype.update = function (a) {
    this.dom.style.display = a ? "inline" : "none";
    this.count.innerHTML = a
};

function ImageLoader(d, c, b, a) {
    this.dom = d;
    this.src = c;
    this.width = b;
    this.height = a;
    ImageLoader.instances[this.instance = ImageLoader.instanceCount++] = this;
    if (!ImageLoader.timeout) {
        window.onresize = window.onscroll = window.onload = ImageLoader.onViewportChanged;
        ImageLoader.timeout = ImageLoader.onViewportChanged.defer()
    }
}
ImageLoader.instances = {};
ImageLoader.instanceCount = 0;
ImageLoader.loading = {};
ImageLoader.loadingSlots = 10;
ImageLoader.onViewportChanged = function () {
    delete ImageLoader.timeout;
    var a = ImageLoader.getViewportSlice();
    for (var b in ImageLoader.instances) {
        if (!ImageLoader.loadingSlots) {
            break
        }
        if (ImageLoader.intersects(a, ImageLoader.instances[b].getElementSlice())) {
            ImageLoader.instances[b].load()
        }
    }
};
ImageLoader.getViewportSlice = function () {
    return [document.body.scrollTop || document.documentElement.scrollTop, window.innerHeight || document.documentElement.clientHeight]
};
ImageLoader.intersects = function (b, a) {
    return !a || b[1] && a[1] && b[0] < a[0] + a[1] && a[0] < b[0] + b[1]
};
ImageLoader.doneHandler = function (a) {
    if (a in ImageLoader.loading) {
        ImageLoader.loading[a].dom.className = "img-loaded";
        delete ImageLoader.loading[a];
        ++ImageLoader.loadingSlots
    }
};
ImageLoader.prototype.getElementSlice = function () {
    var a = this.dom.offsetTop,
        d = this.dom.offsetParent,
        c = this.dom.parentNode,
        b;
    while (d) {
        b = CSS.getComputedStyle(c);
        if (c == d) {
            if (b.position == "fixed") {
                a += (document.body.scrollTop || document.documentElement.scrollTop)
            }
            a += c.offsetTop;
            d = d.offsetParent
        }
        if (b.overflowY == "auto") {
            return false
        }
        c = c.parentNode
    }
    return [a, this.dom.offsetHeight]
};
ImageLoader.prototype.load = function () {
    delete ImageLoader.instances[this.instance];
    ImageLoader.loading[this.instance] = this;
    --ImageLoader.loadingSlots;
    var b = document.createElement("img");
    this.img = b;
    b.onload = ImageLoader.doneHandler.bind(null, this.instance);
    b.onerror = this.fallBack.bind(this);
    b.src = this.src;
    b.width = this.width;
    b.height = this.height;
    this.dom.appendChild(b);
    var a;
    for (var c in ImageLoader.instances) {
        if (ImageLoader.instances[c].src == this.src) {
            a = b.cloneNode(false);
            ImageLoader.instances[c].img = a;
            a.onload = function () {
                this.className = "img-loaded"
            }.bind(ImageLoader.instances[c].dom);
            a.onerror = this.fallBack.bind(ImageLoader.instances[c]);
            ImageLoader.instances[c].dom.appendChild(a);
            delete ImageLoader.instances[c]
        }
    }
};
ImageLoader.prototype.fallBack = function () {
    var a = this.img.src.replace("dealtwith.it", "endoftheinter.net");
    if (a != this.img.src) {
        this.img.src = a
    }
    this.dom.className = "img-loaded";
    ImageLoader.doneHandler(this.instance)
};
