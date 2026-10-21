$(function(){
    $('#siteSelector').change(function(){
        window.location.href = $(this).val();
    })
});