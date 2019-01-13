$(document).ready(function() {
    var url = "/event/promote/id/";
    var send_button = $("a#promoteLink");
    $(send_button).each(function(){
	    $(this).click(function(e){
	    	var idEvent = $(this).attr("data-idevent");
	    	var parent = $(this).parent();
	    	
	    	$.ajax({
	    		url: url + idEvent,
	    		type: 'get',
	    		beforeSend: function(){
		    		parent.parent().children("#loader").show();
		    		parent.hide();
				},
				error: function(a,b,c){
					parent.parent().children("#loader").hide();
					parent.html('<span class="label label-warning" style="font-size: 14px;"><i class="fa fa-ban"></i></span>');
					parent.show();
				},
				success: function(data){
					var isNum = new RegExp(/[0-9]+/);
					parent.parent().children("#loader").hide();
					if(isNum.test(data)){
						parent.html('<span class="label" style="font-size: 14px;"><i class="fa fa-check"></i></span>');
					} else {
						parent.html('<span class="label label-danger" style="font-size: 14px;"><i class="fa fa-times"></i></span>');
					}
					parent.show();
				},
	    	});
	        return false;
        });
    });
});