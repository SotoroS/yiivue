$(document).ready(function () {
    setInterval(function () {
        $.pjax.reload({
            replace: false,
            container: '#monitoring-table',
            async: false
        })
    }, 3000);
});