$(document).ready(function(){
	
	var image_template = _.template("<div class='image' id='<%= id %>'><img src='<%= url %>'  ><p class='tags'><%= tags %></p></div>");
	
	$('#image-submit').click(function(){
		var image_url = $('#image-url').val();
		var button = $(this);
		button.button('loading');
		if (image_url && image_url.length > 0){
			$.post('php/app.php',{
				'image-url': image_url
			},function(data){
				$('#image-url').val('');
				button.button('reset');
			});
		}
		return false;
	});

	var myRootRef = new Firebase('https://image-tagging-app.firebaseio.com/images');
	myRootRef.on('child_added',function(snapshot){
		var image = snapshot.val();
		var data = {'id':snapshot.name(),'url':image.url,'tags':image.tags};
		$('.image-container').prepend(image_template(data));
	});

	myRootRef.on('child_changed',function(snapshot){

		var image=snapshot.val();
		$('#'+snapshot.name()+' .tags').text(image.tags);		
    });
});
