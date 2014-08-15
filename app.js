/*Sammy('#main', function() {
	// define a 'get' route that will be triggered at '#/path'
	this.get('#/path', function() {
		// this context is a Sammy.EventContext
		this.$element() // $('#main')
			.html('A new route!');
	});
}).run();*/

function togglebutton(el, tag, buttonclass) {
	var e = $(el);
	var id = e.parent().attr('id');

	if(localStorage[id+tag] == undefined) {
		localStorage[id+tag] = "true";
		e.addClass(buttonclass);
		e.removeClass('btn-default');
	}
	else if(localStorage[id+tag] == "true") {
		localStorage[id+tag] = "false";
		e.addClass('btn-default');
		e.removeClass(buttonclass);
	}
	else if(localStorage[id+tag] == "false") {
		localStorage[id+tag] = "true";
		e.addClass(buttonclass);
		e.removeClass('btn-default');
	}
	else {
		localStorage[id+tag] = "false";
		e.addClass('btn-default');
		e.removeClass(buttonclass);
	}
}

function togglewant(el) {
	togglebutton(el, '_want', 'btn-primary');
}

function toggledrunk(el) {
	togglebutton(el, '_drunk', 'btn-success');
}

function toggleunavailable(el) {
	togglebutton(el, '_unavailable', 'btn-warning');
}

function savenotes(el) {
	var e = $(el);
	var id = e.parent().attr('id');

	localStorage[id+'_notes'] = e.val();
}


(function($) {

	var app = $.sammy('#main', function() {

		this.use('Template');

		this.around(function(callback) {
			var context = this;
			this.load('gbbf2014.json')
				.then(function(items) {
					context.items = items;
				})
				.then(callback);
		});

		this.get('#/', function(context) {
			
			/*this.load('gbbf2014.json')
				.then(function(items) {
					$.each(items, function(i, item) {
						context.render('beer.template', {beer: item})
							.appendTo(context.$element());
					});
				});*/
			//context.render('home.template').appendTo(context.$element());
			this.partial('home.template');

		});
		this.get('#/browse/:category', function(context) {
			context.app.swap('');

			var category = this.params['category'];

			this.load(category+'.json')
				.then(function(items) {
					items.sort();
					$.each(items, function(i, item) {
						//context.log(item);
						context.render('browse.template', {category: category, item: item}).appendTo(context.$element());
					});
				});

		});

		this.get('#/browse/:category/:v', function(context) {
			context.app.swap('');

			var category = this.params['category'];
			var v = this.params['v'];

			context.render('browse-header.template', {category: category}).appendTo(context.$element());

			$.each(this.items, function(i, item) {
				if(v == item[category]) {
					var id = item.id;
					
					var wantclass = 'btn-default';
					if(localStorage[id+'_want'] == "true")
						wantclass = 'btn-primary';

					var drunkclass = 'btn-default';
					if(localStorage[id+'_drunk'] == "true")
						drunkclass = 'btn-success';

					var unavailableclass = 'btn-default';
					if(localStorage[id+'_unavailable'] == "true")
						unavailableclass = 'btn-warning';

					var notes = localStorage[id+'_notes'];
					if(notes == undefined) notes = "";

					context.render('beer.template', {beer: item, wantclass: wantclass, drunkclass: drunkclass, unavailableclass: unavailableclass, notes: notes})
						//.appendTo(context.$element());
						.appendTo('#accordion');
				}
			});

		});


		this.get('#/wantedbeers', function(context) {
			context.app.swap('');

			context.render('browse-header.template', {category: 'Wanted Beers'}).appendTo(context.$element());

			// Display wanted
			$.each(this.items, function(i, item) {
				if(localStorage[item.id+'_want'] == "true" && localStorage[item.id+'_drunk'] !== "true") {
					var id = item.id;
					
					var wantclass = 'btn-default';
					if(localStorage[id+'_want'] == "true")
						wantclass = 'btn-primary';

					var drunkclass = 'btn-default';
					if(localStorage[id+'_drunk'] == "true")
						drunkclass = 'btn-success';

					var unavailableclass = 'btn-default';
					if(localStorage[id+'_unavailable'] == "true")
						unavailableclass = 'btn-warning';

					var notes = localStorage[id+'_notes'];
					if(notes == undefined) notes = "";

					context.render('beer.template', {beer: item, wantclass: wantclass, drunkclass: drunkclass, unavailableclass: unavailableclass, notes: notes})
						//.appendTo(context.$element());
						.appendTo('#accordion');
				}
			});
		});

		this.get('#/drunkbeers', function(context) {
			context.app.swap('');

			context.render('browse-header.template', {category: 'Drunk Beers'}).appendTo(context.$element());

			// Display drunk
			$.each(this.items, function(i, item) {
				if(localStorage[item.id+'_drunk'] == "true") {
					var id = item.id;
					
					var wantclass = 'btn-default';
					if(localStorage[id+'_want'] == "true")
						wantclass = 'btn-primary';

					var drunkclass = 'btn-default';
					if(localStorage[id+'_drunk'] == "true")
						drunkclass = 'btn-success';

					var unavailableclass = 'btn-default';
					if(localStorage[id+'_unavailable'] == "true")
						unavailableclass = 'btn-warning';

					var notes = localStorage[id+'_notes'];
					if(notes == undefined) notes = "";

					context.render('beer.template', {beer: item, wantclass: wantclass, drunkclass: drunkclass, unavailableclass: unavailableclass, notes: notes})
						//.appendTo(context.$element());
						.appendTo('#accordion');
				}
			});

		});

	});

	$(function() {
		app.run("#/");
	});

})(jQuery);


/*var beerDB = null;
$(document).ready(function() {
	// Load Data
	$.ajax({
		'async': false,
		'global': false,
		'url': './gbbf2014.json',
		'dataType': 'json',
		'success': function(data) {
			beerDB = data;
			alert("success");
		}
	})
	//console.log(beerDB);

	// List wanted beers (index by ID?)
	// Order: available, unavailable, drunk

	// Browse by:
	// - Style
	// - Bar
	// - Brewery
	// - Text search

	// When browsing, display logo, name, bar
	//  Expand to view brewery, ABV, description, others

	// At any point click buttons to
	// - Toggle wanted
	// - Mark drunk
	// - Mark unavailable (record time)
});*/