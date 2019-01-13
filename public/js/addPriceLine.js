$(document).ready(function() {
    var max_fields      = 5;
    var wrapper         = $("#prices");
    var add_button      = $("#addPrice");
    var x = 2; 
    $(add_button).click(function(e){
        e.preventDefault();
        if(x < max_fields){
            x++;
            var priceLineContent = $("#priceLine").html();
            $(wrapper).append('<div id="priceLine" class="form-inline row priceLine">'+priceLineContent+ '<div class="deleteButton btn btn-default" id="deletePrice"><i class="fa fa-trash-o"></i></div></div>');
        } else alert("Tu ne peux pas ajouter plus de prix ;)");
    });
    $(wrapper).on("click",".deleteButton", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});