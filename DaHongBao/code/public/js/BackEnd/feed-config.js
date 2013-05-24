$(function() {
    $('a.importFeed, a.download').click(function() {
        var className = $(this).hasClass('download') == true ? 'download' : 'importFeed';
        $(this).parents('tr').children('td.'+className).children('span').removeClass().addClass('label').html('RUNNING');
        $(this).parent().html("<img src='/img/BackEnd/loading.gif' align='center' style='padding-right:25px;'>");
        window.open($(this).attr('href'));
        return false;
    });
})
