define(['jquery'], function($) {
    'use strict';

    if (typeof bmf === "undefined") var bmf = {};

    bmf.request = (function ($) {

        var bmfTimer,

            showResult = function (fncResult) {
                var html = "<h1>Process</h1>";
                html += "<table>";
                html += "<tr><th>Memory:&nbsp;</th><td>" + (parseInt(fncResult['memory'])/1024) + "&nbsp;kb&nbsp;</td></tr>";
                html += "<tr><th>Duration:&nbsp;</th><td>" + fncResult['duration'] + "&nbsp;sec.&nbsp;</td></tr>";
                html += "<tr><th>Actual:&nbsp;</th><td>" + fncResult['count'] + "&nbsp;</td></tr>";
                html += "<tr><th>Total:&nbsp;</th><td>" + fncResult['length'] + "&nbsp;</td></tr>";
                html += "<tr><th>Percent:&nbsp;</th><td>" + (Math.round(parseInt(fncResult['count']) * 100 / parseInt(fncResult['length']), 2)) + "&nbsp;%&nbsp;</tr>";
                html += "</table>";

                $('#process').html(html);

            },

            bmfRequest = function (fncSession) {
                $.ajax({
                    url: "/typo3temp/bmfprocess-" + fncSession + '.js?ts=' + Math.random(),
                    mimeType: "textPlain",
                    dataType: "json",
                    cache: false,
                    success: function (data, textStatus, jqXHR) {
                        if (typeof data === 'object')
                            showResult(data);
                    }
                });
            },

            initialize = function () {
                $('#bmfProcessForm').submit(function (event) {
                    var session = $("input#bmfProcessSession").val();
                    bmfTimer = setInterval(function () {
                        bmfRequest(session);
                    }, 250);
                });
            };

        return {
            init: initialize
        }

    })($);

    $(document).ready(function () {
        bmf.request.init();
    });
});
