var APP = ( function () {

    //cache DOM
    var inputUrl = $('#input-url'); //The input that contains the url
    var btnSearch = $('#btn-search'); //The search button

    //Bind onclick event on search button
	btnSearch.on( "click", function() {
		handleSearch();
	});

	//The handler for the search button click event
	function handleSearch() {
		//Do it here
	}


    return {
        
    }

})();
