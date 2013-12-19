var line;
var markers;
	/*function initialize() {
      var mapOptions = {
          center: new google.maps.LatLng(28.777289,104.550533),
          zoom: 3,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
	map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);	
	
		//addmarker(list["point_x"],list["point_y"],'0','');
	  var i;
	  for(i=0;i<list.length;i++)
	  {
			addmarker(list[i]["point_x"],list[i]["point_y"],'0','');
	  }
}*/
	
	function addmarker(x,y,tl,img,map) {
	
		var contentString = '<img src="__PUBLIC__/Uploads/s_529b44b05c544.jpg" />';

				var infowindow = new google.maps.InfoWindow({
					content: contentString
				});
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(x,y),//添加一个位置标记
					map: map,
					title: '123'//提示信息
				});
				
				google.maps.event.addListener(marker, 'click', function(){
				infowindow.open(map,marker);
				});
		
		/*var contentString = '<img src="__PUBLIC__/Uploads/s_529b44b05c544.jpg" />';

		var infowindow = new google.maps.InfoWindow({
			content: contentString
		});
		var marker = new google.maps.Marker({
				position: new google.maps.LatLng(x,y),//添加一个位置标记
				map: map,
				title: tl//提示信息
				//draggable:true,
				//animation: google.maps.Animation.DROP
				});
				
		google.maps.event.addListener(marker, 'click', function(){
				/*if (marker.getAnimation() != null) {
					marker.setAnimation(null);
				} else {
							marker.setAnimation(google.maps.Animation.BOUNCE);
							for (var i = 0; i < markers.length; i++) {
									if(markers[i]!=marker)
									markers[i].setAnimation(null);
								}
						}
				//document.getElementById(tl).click();
				infowindow.open(map,marker);
				});
				
				
			/*google.maps.event.addListener(marker, 'mouseover', function() {
				infowindow.open(map,marker);
				});	
			google.maps.event.addListener(marker, 'mouseout', function() {
				infowindow.open(null);
				});	*/
				
				//markers.push(marker);
	}