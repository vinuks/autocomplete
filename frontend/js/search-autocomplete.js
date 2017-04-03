

$(function () {
    $("#autocomplete").autocomplete({
        serviceUrl:'http://127.0.0.1:8000/autocomplete', //tell the script where to send requests
        //callback just to show it's working
        onSelect: function(value){ 
        }
    });
});
