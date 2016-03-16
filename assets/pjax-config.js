$(function () {
    $(document).ready(function () {
        $.pjax.defaults.scrollTo = false,
            $.pjax.defaults.push = false;
        $.pjax.defaults.timeout = null;
        $(document).on('pjax:beforeSend', function (e, xhr, options) {
            if (e.relatedTarget != null) {
                if (e.relatedTarget.type.toLowerCase() == 'post') {
                    options.type = 'post';
                }
            }
        });
    });
})