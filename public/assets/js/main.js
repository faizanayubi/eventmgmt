"use strict";
$(document).ready(function () {
    theme.init();
    theme.initMainSlider();
    theme.initCountDown();
    theme.initPartnerSlider();
    theme.initTestimonials();
    theme.initCorouselSlider4();
    theme.initCorouselSlider3();
    theme.initGoogleMap();
});
$(window).load(function () {
    theme.initAnimation();
});

$(window).load(function () {
    $("body").scrollspy({offset: 100, target: ".navigation"});
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