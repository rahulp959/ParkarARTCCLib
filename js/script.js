/* begin Page */

/* Created by Artisteer v3.0.0.35414 */



(function() {
    // fix ie blinking
    var m = document.uniqueID && document.compatMode && !window.XMLHttpRequest && document.execCommand;
    try { if (!!m) { m('BackgroundImageCache', false, true); } }
    catch (oh) { };
    // css helper
    var u = navigator.userAgent.toLowerCase();
    var is = function(t) { return (u.indexOf(t) != -1) };
    jQuery('html').addClass([(!(/opera|webtv/i.test(u)) && /msie (\d)/.test(u)) ? ('ie ie' + RegExp.$1)
    : is('firefox/2') ? 'gecko firefox2'
    : is('firefox/3') ? 'gecko firefox3'
    : is('gecko/') ? 'gecko'
    : is('chrome/') ? 'chrome'
    : is('opera/9') ? 'opera opera9' : /opera (\d)/.test(u) ? 'opera opera' + RegExp.$1
    : is('konqueror') ? 'konqueror'
    : is('applewebkit/') ? 'webkit safari'
    : is('mozilla/') ? 'gecko' : '',
    (is('x11') || is('linux')) ? ' linux'
    : is('mac') ? ' mac'
    : is('win') ? ' win' : ''
    ].join(' '));
})();

var _artStyleUrlCached = null;
function artGetStyleUrl() {
    if (null == _artStyleUrlCached) {
        var ns;
        _artStyleUrlCached = '';
        ns = jQuery('link');
        for (var i = 0; i < ns.length; i++) {
            var l = ns[i].href;
            if (l && /style\.ie6\.css(\?.*)?$/.test(l))
                return _artStyleUrlCached = l.replace(/style\.ie6\.css(\?.*)?$/, '');
        }
        ns = jQuery('style');
        for (var i = 0; i < ns.length; i++) {
            var matches = new RegExp('import\\s+"([^"]+\\/)style\\.ie6\\.css"').exec(ns[i].html());
            if (null != matches && matches.length > 0)
                return _artStyleUrlCached = matches[1];
        }
    }
    return _artStyleUrlCached;
}

function artFixPNG(element) {
    if (jQuery.browser.msie && parseInt(jQuery.browser.version) < 7) {
		var src;
		if (element.tagName == 'IMG') {
			if (/\.png$/.test(element.src)) {
				src = element.src;
				element.src = artGetStyleUrl() + 'images/spacer.gif';
			}
		}
		else {
			src = element.currentStyle.backgroundImage.match(/url\("(.+\.png)"\)/i);
			if (src) {
				src = src[1];
				element.runtimeStyle.backgroundImage = 'none';
			}
		}
		if (src) element.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "')";
	}
}

jQuery(function() {
    jQuery.each(jQuery('ul.menu>li:not(.menu-li-separator),ul.vmenu>li:not(.vmenu-separator)'), function (i, val) {
        var l = jQuery(val); var s = l.children('span'); if (s.length == 0) return;
        var t = l.find('span.t').last(); l.children('a').append(t.html(t.text()));
        s.remove();
    });
});/* end Page */

/* begin Box, Sheet */

function artFluidSheetComputedWidth(percent, minval, maxval) {
    percent = parseInt(percent);
    var val = document.body.clientWidth / 100 * percent;
    return val < minval ? minval + 'px' : val > maxval ? maxval + 'px' : percent + '%';
}/* end Box, Sheet */

/* begin Menu */
jQuery(function() {
    jQuery.each(jQuery('ul.menu>li:not(:last-child)'), function(i, val) {
        jQuery('<li class="menu-li-separator"><span class="menu-separator"> </span></li>').insertAfter(val);
    });
    if (!jQuery.browser.msie || parseInt(jQuery.browser.version) > 6) return;
    jQuery.each(jQuery('ul.menu li'), function(i, val) {
        val.j = jQuery(val);
        val.UL = val.j.children('ul:first');
        if (val.UL.length == 0) return;
        val.A = val.j.children('a:first');
        this.onmouseenter = function() {
            this.j.addClass('menuhover');
            this.UL.addClass('menuhoverUL');
            this.A.addClass('menuhoverA');
        };
        this.onmouseleave = function() {
            this.j.removeClass('menuhover');
            this.UL.removeClass('menuhoverUL');
            this.A.removeClass('menuhoverA');
        };

    });
});

/* end Menu */

/* begin Layout */
jQuery(function () {
    if (!jQuery.browser.msie || parseInt(jQuery.browser.version) > 7) return;
    var c = jQuery('div.content');
    if (c.length !== 1) return;
    var s = c.parent().children('.layout-cell:not(.content)');
    jQuery(window).bind('resize', function () {
        var w = 0; c.css('width', "100%");
        s.each(function () { w += this.clientWidth; });
        c.w = c.parent().width();c.css('width', c.w - w);
    }).trigger('resize');
    jQuery('div.content-layout-row').each(function () {
        this.c = jQuery(this).children('.layout-cell');
    }).bind('resize', function () {
        if (this.h == this.clientHeight) return;
        this.c.css('height', 'auto');
        this.h = this.clientHeight;
        this.c.css('height', this.h + 'px');
    }).trigger('resize');
});
/* end Layout */

/* begin Button */
function artButtonSetup(className) {
    jQuery.each(jQuery("a." + className + ", button." + className + ", input." + className), function(i, val) {
        var b = jQuery(val);
        if (!b.parent().hasClass('button-wrapper')) {
            if (!b.hasClass('button')) b.addClass('button');
            jQuery("<span class='button-wrapper'><span class='button-l'> </span><span class='button-r'> </span></span>").insertBefore(b).append(b);
            if (b.hasClass('active')) b.parent().addClass('active');
        }
        b.mouseover(function() { jQuery(this).parent().addClass("hover"); });
        b.mouseout(function() { var b = jQuery(this); b.parent().removeClass("hover"); if (!b.hasClass('active')) b.parent().removeClass('active'); });
        b.mousedown(function() { var b = jQuery(this); b.parent().removeClass("hover"); if (!b.hasClass('active')) b.parent().addClass('active'); });
        b.mouseup(function() { var b = jQuery(this); if (!b.hasClass('active')) b.parent().removeClass('active'); });
    });
}
jQuery(function() { artButtonSetup("button"); });

/* end Button */



jQuery(function () {
    artButtonSetup("button");
});
