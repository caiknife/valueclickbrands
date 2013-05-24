/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(function() {
    $("#SiteID").change(function() {
        window.location = '/RecommendType/index?SiteID=' + $(this).val();
    })
})

