var $ = $ || {};
$((function () {
    'use strict';

    var $topWrap = $('#topWrap');
    if ($topWrap.on) {
        $topWrap.on('click', 'tr[aria-selected] a', (function (e) {
            var $tr = $(this).closest('tr'),
                $panel = $('[aria-hidden]'),
                selected = $tr.attr('aria-selected'),
                hidden = $panel.attr('aria-hidden');
            e.preventDefault();
            e.stopPropagation();
            $tr.attr('aria-selected', hidden);
            $panel.attr({
                'aria-expanded': hidden,
                'aria-hidden': selected
            });
            return false;
        }));
    }
}));

function onKeyEvent() {
    'use strict';
    if (document.activeElement.id !== 'sp_content') {
        if (window.event && window.event.keyCode == 13) {
            return false;
        }
    }
    return true;
}
window.document.onkeydown = onKeyEvent;