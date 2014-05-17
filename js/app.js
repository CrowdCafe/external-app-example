$(document).ready(function(){
	
	var image_template = _.template("<div class='image' id='<%= id %>'><img src='<%= url %>'  ><p class='tags'><%= tags %></p></div>");
	
	$('#image-submit').click(function(){
		var image_url = $('#image-url').val();
		if (image_url && image_url.length > 0){
			$.post('php/app.php',{
				'image-url': image_url
			},function(data){
				console.log(data);
				alert('done');
			});
		}
		return false;
	});

	var myRootRef = new Firebase('https://image-tagging-app.firebaseio.com/images');
	myRootRef.on('child_added',function(snapshot){
		var image = snapshot.val();
		console.log(image);
		var data = {'id':snapshot.name(),'url':image.url,'tags':image.tags};
		$('.image-container').append(image_template(data));
	});

	myRootRef.on('child_changed',function(snapshot){

		var image=snapshot.val();
		$('#'+snapshot.name()+' .tags').text(image.tags);		
    });
});
