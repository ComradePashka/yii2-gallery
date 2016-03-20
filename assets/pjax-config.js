$(function () {
    $(document).on('ready', function () {
        $.pjax.defaults.scrollTo = false;
        $.pjax.defaults.push = false;
        $.pjax.defaults.replace = false;
        $.pjax.defaults.timeout = null;
        $(document).on('pjax:beforeSend', function (e, xhr, options) {
            if (e.relatedTarget != null) {
                options.push = false;
                if (e.relatedTarget.type.toLowerCase() == 'post') {
                    options.type = 'post';
                }
            }
        });
    });
});
