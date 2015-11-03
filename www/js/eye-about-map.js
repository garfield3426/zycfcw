	var map;
    var mgr;
    var icons = {};
    var allmarkers = [];

    function load() {
      if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map"));
        map.addControl(new GLargeMapControl());
        map.addControl(new GOverviewMapControl());
        map.setCenter(new GLatLng(31.21078611111111, 121.39979444444444), 17);
        map.setMapType(G_NORMAL_MAP); // 设置地图类型    * G_NORMAL_MAP- 默认视图 * G_SATELLITE_MAP - 显示 Google 地球卫星图像 * G_HYBRID_MAP - 混合显示普通视图和卫星视图 * G_DEFAULT_MAP_TYPES - 这三个类型的数组，在需要重复处理的情况下非常有用
        map.enableDoubleClickZoom();
        mgr = new MarkerManager(map, {trackMarkers:true});
        window.setTimeout(setupOfficeMarkers, 0);
      }
    }

    function getIcon(images) {
      var icon = null;
      if (images) {
        if (icons[images[0]]) {
          icon = icons[images[0]];
        } else {
          icon = new GIcon();
          icon.image = "image/" + images[0] + ".png";
          var size = iconData[images[0]];
          icon.iconSize = new GSize(size.width, size.height);
          icon.iconAnchor = new GPoint(size.width >> 1, size.height >> 1);
          icon.shadow = "image/" + images[1] + ".png";
          size = iconData[images[1]];
          icon.shadowSize = new GSize(size.width, size.height);
          icons[images[0]] = icon;
        }
      }
      return icon;
    }

    function setupOfficeMarkers() {
      allmarkers.length = 0;
      for (var i in officeLayer) {
        var layer = officeLayer[i];
        var markers = [];
        for (var j in layer["places"]) {
          var place = layer["places"][j];
          var icon = getIcon(place["icon"]);
          var title = place["name"];
          var posn = new GLatLng(place["posn"][0], place["posn"][1]);
          var marker = createMarker(posn,title,icon); 
          markers.push(marker);
          allmarkers.push(marker);
        }
        mgr.addMarkers(markers, layer["zoom"][0], layer["zoom"][1]);
      }
      mgr.refresh();
    }
  
    function createMarker(posn, title, icon) {
      var marker = new GMarker(posn, {title: title, icon: icon, draggable:true });
      GEvent.addListener(marker, 'dblclick', function() { mgr.removeMarker(marker) } ); 
      return marker;
    }

    function deleteMarker() {
      var markerNum = parseInt(document.getElementById("markerNum").value);
      mgr.removeMarker(allmarkers[markerNum]);
    }
   
    function clearMarkers() {
      mgr.clearMarkers();
    }
   
    function reloadMarkers() {
      setupOfficeMarkers();
    }