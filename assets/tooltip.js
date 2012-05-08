jQuery(function($){
	$.getJSON(tooltip_url + '/symphony/extension/tooltip/?all', function(data){

		var tooltips = {};
		$.each(data, function(key, value){
			tooltips[value.field_id] = value.tooltip;
		});

		$('div.field>label').each(function(){
			// The ID of the field:
			var id = parseInt($(this).parent()[0].id.replace('field-', ''));

			// The content of the field:
			var content = tooltips[id] != undefined ? tooltips[id] : '';

			if(tooltip_user_type == 'developer' || content != '')
			{
				// The edit button (only for developers):
				var editBtn = tooltip_user_type == 'developer' ? '<a href="#" class="button edit">Edit</a><a href="#" class="button save">Save</a><a href="#" class="button cancel">Cancel</a>' : '';

				// Add it all:
				$(this).prepend('<a href="#" class="tooltip">?</a><div class="tooltip"><div class="content">' + content + '</div><img src="' +
					tooltip_url + '/extensions/tooltip/assets/ajax-loader.gif" class="loader" />'+editBtn+'<a href="#" class="button close">Close</a><span class="arrow"></span></div>');

				// Bind functionality:
				$('a.tooltip', this).click(function(e){
					e.preventDefault();
					$('div.tooltip').not($('div.tooltip', $(this).parent())).hide();
					$('div.tooltip', $(this).parent()).toggle();
				});
				$('div.tooltip a.close', this).click(function(e){
					e.preventDefault();
					$(this).parent().hide();
				});
				$('div.tooltip a.edit', this).click(function(e){
					e.preventDefault();
					// Make the content editable:
					// content = $('div.content', $(this).parent()).text();
					$('div.content', $(this).parent()).replaceWith('<textarea>' + content + '</textarea>');
					$(this).hide();
					$('a.save, a.cancel', $(this).parent()).show().css({display: "inline-block"});
				});
				$('div.tooltip a.cancel', this).click(function(e){
					e.preventDefault();
					$('textarea', $(this).parent()).replaceWith('<div class="content">' + content + '</div>');
					$('a.edit', $(this).parent()).show();
					$('a.save, a.cancel', $(this).parent()).hide();
				});
				$('div.tooltip a.save', this).click(function(e){
					e.preventDefault();
					// Save the content from the textarea:
					content = $('textarea', $(this).parent()).val();
					var cancel = $('a.cancel', $(this).parent());
					var data = {
						id: id,
						content: content,
						save: 1
					};
					$.get(tooltip_url + '/symphony/extension/tooltip/', data, function(response){
						// Not really cancel, but restore the textarea and show/hide the correct buttons:
						cancel.click();
					});
				});
			}
		});
	});
});