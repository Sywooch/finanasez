"use strict";

$(function () {

    setInterval(function () {
        fixWrapperSize();
        if ($(".left-side").css('left') == '-220px') {
            fixSidebarLeft();
        }
    }, 300);

    var interval = setInterval(function () {
        if (leftSidebarLoaded()) {
            fixSidebarLeft();
            subscribeToWindowResizeEvent();

            clearInterval(interval);
        }
    }, 30);
});

function fixWrapperSize() {
    var height = $(window).height() - $("body .header").height() - ($("body .footer").outerHeight() || 0);
    $(".wrapper").css({"min-height": height + "px"});
    var content = $(".wrapper").height();
    if (content > height) {
        $(".left-side, html, body").css({"min-height": content + "px"});
        $(".left-side").css({"min-height": content + "px"});
    } else {
        $(".left-side, html, body").css({"min-height": height + "px"});
        $(".left-side").css({"min-height": height + "px"});
    }
}

function subscribeToWindowResizeEvent() {
    $(window).resize(function() {
        fixSidebarLeft();
    });
}

function fixSidebarLeft() {
    if ($(window).width() < 1000) {
        if (!leftSidebarSwitcherButtonAdded()) {
            addLeftSidebarSwitcherButton();
        }
    } else {
        removeLeftSidebarSwitcherButton();
    }

    if ($(window).width() < 1000 && $(".left-side").width() == 220) {
        $(".right-side").css({'margin-left': '60px'});
        $(".left-side").css({'width': '60px'});
        $(".row-offcanvas-left .sidebar-offcanvas").css({left: '0px'});
        hideLeftSidebarListItems();
    } else if ($(window).width() > 1000 && $(".left-side").width() != 220) {
        $(".left-side").css({width: '220px', position: 'absolute'});
        $(".right-side").css({'margin-left': '220px'});
        showLeftSidebarListItems();
    }
}

function switchLeftSidebar() {
    if ($(".left-side").width() == 60) {
        $(".left-side").animate({'width': '220px'}, 500, function() {
            showLeftSidebarListItems();
        });
        $(".right-side").animate({'margin-left': '220px'}, 500);
        $("#leftSidebarSwitcher i").removeClass('glyphicon-chevron-right');
        $("#leftSidebarSwitcher i").addClass('glyphicon-chevron-left');
    } else {
        $(".left-side").animate({'width': '60px'}, 500);
        $(".right-side").animate({'margin-left': '60px'}, 500);
        $("#leftSidebarSwitcher i").removeClass('glyphicon-chevron-left');
        $("#leftSidebarSwitcher i").addClass('glyphicon-chevron-right');
        hideLeftSidebarListItems();
    }
}

function hideLeftSidebarListItems() {
    $('.sidebar-menu li').fadeOut(100);
    $("#leftSidebarSwitcher").fadeIn(500);
}

function showLeftSidebarListItems() {
    $('.sidebar-menu li').fadeIn(500);
}

function addLeftSidebarSwitcherButton() {
    $('.sidebar-menu li:first-child').before('<li id="leftSidebarSwitcher" onclick="switchLeftSidebar()"><a href="#"><i class="glyphicon glyphicon-chevron-right" style="font-size: 30px;"></i></a></li>');
}

function removeLeftSidebarSwitcherButton() {
    $("#leftSidebarSwitcher").remove();
}

function leftSidebarSwitcherButtonAdded() {
    return $("#leftSidebarSwitcher").length > 0;
}

function leftSidebarLoaded() {
    return $('.sidebar-menu').length > 0;
}