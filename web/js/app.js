var APP = ( function () {

    //cache DOM
    var cnt = $('#main'); //The main container
    var form = cnt.find('#url-form'); //The form
    var inputUrl = form.find('#input-url'); //The input that contains the url
    var btnSubmit = form.find('#btn-submit'); //The input that contains the url

    var results = $('#htg-result'); //The input that contains the url
    var prBar = $('#progress-bar'); //The loading bar template used for cloning

    //Bind beforeSubmit event on form
	cnt.on( "beforeSubmit", form, function() {
		handleSubmit();
		return false;
	});

	//The handler responsible for the form submission
	function handleSubmit() {
		results.html($('#progress-bar').clone(true));
		var url = inputUrl.val();
		var depth = $("input[name='Tag[depth]']:checked").val();
		var seperator = $("input[name='Tag[seperator]']:checked").val();
		var limitChars = $("input[name='Tag[limitChars]']").val();
		btnSubmit.attr('disabled', 'true');
		
		$.ajax({
		  type: "POST",
		  url: '/site/read-url',
		  data: { 
	  		url: url, 
	  		depth: depth, 
	  		seperator: seperator,
	  		limitChars: limitChars
	  	},
		  success: function(data) {
		  	results.html(data);
		  	btnSubmit.removeAttr('disabled');
		  	bindTooltip();
		  	bindScoreFilter();
		  },
		  error: function(XMLHttpRequest, textStatus, errorThrown) {
		  	results.html('<span class="label label-danger">' + textStatus + '</span> Bad or Invalid URL');
		  	btnSubmit.removeAttr('disabled');
		  },
		});
	}

	function bindTooltip() {
		//Activate tooltips
  		$('[data-toggle="tooltip"]').tooltip();
	}

	function bindScoreFilter() {
  		$('#score-filter').on('click', function() {
  			var min = $('#filter-min-score').val();
			var max = $('#filter-max-score').val();
			
			$('.hashtag-result').show();
			$('.hashtag-result').filter(function(){
			  return $(this).data('score') < parseInt(min) || $(this).data('score') > parseInt(max);
			}).hide();
  		});
	}
	

    return {
        bindTooltip:bindTooltip
    }

})();