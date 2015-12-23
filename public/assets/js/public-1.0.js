(function (window) {

    var Model = (function () {
        function Model(opts) {
            this.api = window.location.origin + '/';
            this.ext = '.json';
        }

        Model.prototype = {
            create: function (opts) {
                var self = this,
                        link = this._clean(this.api) + this._clean(opts.action) + this._clean(this.ext);
                $.ajax({
                    url: link,
                    type: 'POST',
                    data: opts.data,
                }).done(function (data) {
                    if (opts.callback) {
                        opts.callback.call(self, data);
                    }
                }).fail(function () {
                    console.log("error");
                }).always(function () {
                    //console.log("complete");
                });
            },
            read: function (opts) {
                var self = this,
                        link = this._clean(this.api) + this._clean(opts.action) + this._clean(this.ext);
                $.ajax({
                    url: link,
                    type: 'GET',
                    data: opts.data,
                }).done(function (data) {
                    if (opts.callback) {
                        opts.callback.call(self, data);
                    }
                }).fail(function () {
                    console.log("error");
                }).always(function () {
                    //console.log("complete");
                });

            },
            _clean: function (entity) {
                return entity || "";
            }
        };
        return Model;
    }());

    Model.initialize = function (opts) {
        return new Model(opts);
    };

    window.Model = Model;
}(window));

(function (window, Model) {
	window.request = Model.initialize();
	window.opts = {};
}(window, window.Model));

//Google Analytics
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-71671181-1', 'auto');
ga('send', 'pageview');

"use strict";
$(document).ready(function () {
    theme.init();
    theme.initMainSlider();
    theme.initCountDown();
    theme.initImageCarousel();
    theme.initCorouselSlider4();
    theme.initCorouselSlider3();
    theme.initGoogleMap();
});
$(window).load(function () {
    theme.initAnimation();
});

$(window).load(function () {
    $("body").scrollspy({
        offset: 100,
        target: ".navigation"
    });
});
$(window).load(function () {
    $("body").scrollspy("refresh");
});
$(window).resize(function () {
    $("body").scrollspy("refresh");
});

$(document).ready(function () {
    theme.onResize();
});
$(window).load(function () {
    theme.onResize();
});
$(window).resize(function () {
    theme.onResize();
});

$(window).load(function () {
    if (location.hash != "") {
        var hash = "#" + window.location.hash.substr(1);
        if (hash.length) {
            $("html,body").delay(0).animate({
                scrollTop: $(hash).offset().top - 44 + "px"
            }, {
                duration: 1200,
                easing: "easeInOutExpo"
            });
        }
    }
});

$(document).ready(function() {
	// facebook login
	$.ajaxSetup({ cache: true });
	$.getScript('//connect.facebook.net/en_US/sdk.js', getFBScript);

	$("#fbLogin").on("click", function (e) {
		e.preventDefault();
		var self = $(this);
		self.addClass('disabled');
		if (!fbinit) {
			getFBScript();
		}
		isLoggedIn();
		self.removeClass('disabled');
	});

});

function getFBScript() {
	FB.init({
		appId: '1312032535489864',
		version: 'v2.4'
	});
	fbinit = true;
}

function isLoggedIn() {
	FB.getLoginStatus(function (response) {
		if (response.status === 'connected') {
			getFBInfo();	// User logged into fb and app
		} else {
			FB.login(function (response) {
				if (response.status === 'connected') {
					getFBInfo();
				} else {
					// $('#alertMessage').html("You need to give access to playmusic.net");
					// $('#alertModal').modal('show');
					alert('Provide access');
				}
			}, {scope: 'public_profile,email'});
		}
	});
}

function getFBInfo() {
	FB.api('/me?fields=name,email', function (response) {
		request.create({
			action: '/organizer/fbLogin',
			data: {action: 'fbLogin', email: response.email, name: response.name, token: ''},
			callback: function (data) {
				if (data == "Success") {
					window.location.href = "/organizer/";
				} else {
                    window.location.href = "/organizer/";
					//alert('something went wrong');
				}
			}
		});
	});
}
