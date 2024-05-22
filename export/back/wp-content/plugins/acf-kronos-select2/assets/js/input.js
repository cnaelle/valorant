(function($){
	
	
	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	30/11/17
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function initialize_field( $field ) {
		const select = $field.find('select')[0];
		$(select).select2({
			multiple: $(select).data('multiple'),
			allowClear: true,
			ajax: {
				url: $(select).data('url-api'),
				data: function (params) {
					var query = {};

					query[$(select).data('param-name')] = params.term;

					// Query parameters will be ?search=[term]&type=public
					return query;
				},
				processResults: function (data) {
					let dataOptions = [];
					
					data['data'].forEach(option => dataOptions.push({
						id: option[$(select).data('param-key')] + '|' + option[$(select).data('param-value')],
						text: option[$(select).data('param-value')],
					}));
					// Transforms the top-level key of the response object from 'items' to 'results'
					return {
						results: dataOptions
					};
				}
			}
		});
		
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/
		
		acf.add_action('ready_field/type=select2', initialize_field);
		acf.add_action('append_field/type=select2', initialize_field);
		
		
	} else {
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			// find all relevant fields
			$(postbox).find('.field[data-field_type="select2"]').each(function(){
				
				// initialize
				initialize_field( $(this) );
				
			});
		
		});
	
	}

})(jQuery);
