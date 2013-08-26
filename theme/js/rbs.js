function makeSublist(parent,child,isSubselectOptional,childVal)
{
	$("body").append("<select style='display:none' id='"+parent+child+"'></select>");
	$('#'+parent+child).html($("#"+child+" option"));

	var parentValue = $('#'+parent).attr('value');
	$('#'+child).html($("#"+parent+child+" .sub_"+parentValue).clone());

	childVal = (typeof childVal == "undefined")? "" : childVal ;
	$("#"+child).val(childVal).attr('selected','selected');

	$('#'+parent).change(function(){
		var parentValue = $('#'+parent).attr('value');
		$('#'+child).html($("#"+parent+child+" .sub_"+parentValue).clone());
		if(isSubselectOptional) $('#'+child).prepend("<option value='none' selected='selected'> -- Select -- </option>");
		$('#'+child).trigger("change");
		$('#'+child).focus();
	});
}

$(document).ready(function()
{
	makeSublist('child','grandson', true, '');
	makeSublist('parent','child', false, '1');
});

