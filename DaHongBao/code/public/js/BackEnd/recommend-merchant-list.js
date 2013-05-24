/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(function() {
    $("#sites").change(function() {
        location.href = '/recommend/merchantList?SiteID=' + $(this).val();
    });
});

