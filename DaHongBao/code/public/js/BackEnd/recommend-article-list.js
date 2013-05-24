$(function() {
    $("#sites").change(function() {
        location.href = '/recommend/articleList?SiteID=' + $(this).val();
    });
});