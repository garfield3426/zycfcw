function MarkerManager(d,e){
	var b=this;
	b.map_=d;
	b.mapZoom_=d.getZoom();
	b.projection_=d.getCurrentMapType().getProjection();
	e=e||{};
	b.tileSize_=MarkerManager.DEFAULT_TILE_SIZE_;
	var a=MarkerManager.DEFAULT_MAX_ZOOM_;
	if(e.maxZoom!=undefined){
		a=e.maxZoom}b.maxZoom_=a;b.trackMarkers_=e.trackMarkers;
		var c;if(typeof e.borderPadding=="number"){
			c=e.borderPadding
		}else{
			c=MarkerManager.DEFAULT_BORDER_PADDING_
		}
		b.swPadding_=new GSize(-c,c);
		b.nePadding_=new GSize(c,-c);
		b.borderPadding_=c;
		b.gridWidth_=[];
		b.grid_=[];
		b.grid_[a]=[];
		b.numMarkers_=[];
		b.numMarkers_[a]=0;
		GEvent.bind(d,"moveend",b,b.onMapMoveEnd_);
		b.removeOverlay_=function(f){
			d.removeOverlay(f);
			b.shownMarkers_--
		};
			b.addOverlay_=function(f){
				d.addOverlay(f);
				b.shownMarkers_++
			};
				b.resetManager_();
				b.shownMarkers_=0;
				b.shownBounds_=b.getMapGridBounds_()
}
MarkerManager.DEFAULT_TILE_SIZE_=1024;
MarkerManager.DEFAULT_MAX_ZOOM_=17;
MarkerManager.DEFAULT_BORDER_PADDING_=100;
MarkerManager.MERCATOR_ZOOM_LEVEL_ZERO_RANGE=256;
MarkerManager.prototype.resetManager_=function(){
	var c=this;
	var a=MarkerManager.MERCATOR_ZOOM_LEVEL_ZERO_RANGE;
	for(var b=0;b<=c.maxZoom_;++b){
		c.grid_[b]=[];
		c.numMarkers_[b]=0;
		c.gridWidth_[b]=Math.ceil(a/c.tileSize_);
		a<<=1
	}
};
		MarkerManager.prototype.clearMarkers=function(){
			var a=this;
			a.processAll_(a.shownBounds_,a.removeOverlay_);
			a.resetManager_()};
			MarkerManager.prototype.getTilePoint_=function(d,b,c){
				var a=this.projection_.fromLatLngToPixel(d,b);
				return new GPoint(Math.floor((a.x+c.width)/this.tileSize_),Math.floor((a.y+c.height)/this.tileSize_))
			};
			MarkerManager.prototype.addMarkerBatch_=function(c,g,b){
				var f=c.getPoint();
				if(this.trackMarkers_){
					GEvent.bind(c,"changed",this,this.onMarkerMoved_)
				}
				var d=this.getTilePoint_(f,b,GSize.ZERO);
				for(var e=b;e>=g;e--){
					var a=this.getGridCellCreate_(d.x,d.y,e);
					a.push(c);d.x=d.x>>1;d.y=d.y>>1
				}
			};
			MarkerManager.prototype.isGridPointVisible_=function(b){
				var f=this;
				var d=f.shownBounds_.minY<=b.y&&b.y<=f.shownBounds_.maxY;
				var a=f.shownBounds_.minX;
				var c=a<=b.x&&b.x<=f.shownBounds_.maxX;
				if(!c&&a<0){
					var e=f.gridWidth_[f.shownBounds_.z];
					c=a+e<=b.x&&b.x<=e-1
				}
				return d&&c
			};
			MarkerManager.prototype.onMarkerMoved_=function(e,a,c){
				var g=this;
				var i=g.maxZoom_;
				var d=false;
				var b=g.getTilePoint_(a,i,GSize.ZERO);
				var f=g.getTilePoint_(c,i,GSize.ZERO);
				while(i>=0&&(b.x!=f.x||b.y!=f.y)){
					var h=g.getGridCellNoCreate_(b.x,b.y,i);
					if(h){
						if(g.removeFromArray(h,e)){
							g.getGridCellCreate_(f.x,f.y,i).push(e)
						}
					}if(i==g.mapZoom_){
						if(g.isGridPointVisible_(b)){
							if(!g.isGridPointVisible_(f)){
								g.removeOverlay_(e);d=true
							}
						}else{
							if(g.isGridPointVisible_(f)){
								g.addOverlay_(e);d=true
							}
						}
					}b.x=b.x>>1;b.y=b.y>>1;f.x=f.x>>1;f.y=f.y>>1;--i
				}
				if(d){
					g.notifyListeners_()
				}
			};
			MarkerManager.prototype.removeMarker=function(c){
				var f=this;
				var e=f.maxZoom_;
				var g=false;
				var b=c.getPoint();
				var d=f.getTilePoint_(b,e,GSize.ZERO);
				while(e>=0){
					var a=f.getGridCellNoCreate_(d.x,d.y,e);
					if(a){
						f.removeFromArray(a,c)
					}if(e==f.mapZoom_){
						if(f.isGridPointVisible_(d)){
							f.removeOverlay_(c);g=true}}d.x=d.x>>1;d.y=d.y>>1;--e
				}
				if(g){
					f.notifyListeners_()
				}
			};
			MarkerManager.prototype.addMarkers=function(d,e,c){
				var a=this.getOptMaxZoom_(c);
				for(var b=d.length-1;b>=0;b--){
					this.addMarkerBatch_(d[b],e,a)
				}
				this.numMarkers_[e]+=d.length
			};
			MarkerManager.prototype.getOptMaxZoom_=function(a){
				return a!=undefined?a:this.maxZoom_
			};
			MarkerManager.prototype.getMarkerCount=function(b){
				var a=0;
				for(var c=0;c<=b;c++){
					a+=this.numMarkers_[c]}return a
			};
			MarkerManager.prototype.addMarker=function(b,f,d){
				var e=this;
				var a=this.getOptMaxZoom_(d);
				e.addMarkerBatch_(b,f,a);
				var c=e.getTilePoint_(b.getPoint(),e.mapZoom_,GSize.ZERO);
				if(e.isGridPointVisible_(c)&&f<=e.shownBounds_.z&&e.shownBounds_.z<=a){
					e.addOverlay_(b);
					e.notifyListeners_()}this.numMarkers_[f]++};
					GBounds.prototype.containsPoint=function(a){var b=this;
					return(b.minX<=a.x&&b.maxX>=a.x&&b.minY<=a.y&&b.maxY>=a.y)};
					MarkerManager.prototype.getGridCellCreate_=function(a,f,e){var c=this.grid_[e];
					if(a<0){a+=this.gridWidth_[e]}var b=c[a];
					if(!b){b=c[a]=[];
					return b[f]=[]}var d=b[f];
					if(!d){return b[f]=[]}return d};
					MarkerManager.prototype.getGridCellNoCreate_=function(a,e,d){var c=this.grid_[d];
					if(a<0){a+=this.gridWidth_[d]}var b=c[a];
					return b?b[e]:undefined};
					MarkerManager.prototype.getGridBounds_=function(a,i,h,f){i=Math.min(i,this.maxZoom_);
					var b=a.getSouthWest();
					var e=a.getNorthEast();
					var g=this.getTilePoint_(b,i,h);
					var d=this.getTilePoint_(e,i,f);
					var j=this.gridWidth_[i];
					if(e.lng()<b.lng()||d.x<g.x){g.x-=j}if(d.x-g.x+1>=j){g.x=0;
					d.x=j-1}var c=new GBounds([g,d]);
					c.z=i;
					return c};
					MarkerManager.prototype.getMapGridBounds_=function(){var a=this;
					return a.getGridBounds_(a.map_.getBounds(),a.mapZoom_,a.swPadding_,a.nePadding_)};
					MarkerManager.prototype.onMapMoveEnd_=function(){var a=this;
					a.objectSetTimeout_(this,this.updateMarkers_,0)};
					MarkerManager.prototype.objectSetTimeout_=function(b,c,a){return window.setTimeout(function(){c.call(b)},a)};
					MarkerManager.prototype.refresh=function(){var a=this;
					if(a.shownMarkers_>0){a.processAll_(a.shownBounds_,a.removeOverlay_)}a.processAll_(a.shownBounds_,a.addOverlay_);
					a.notifyListeners_()};
					MarkerManager.prototype.updateMarkers_=function(){var a=this;
					a.mapZoom_=this.map_.getZoom();
					var b=a.getMapGridBounds_();
					if(b.equals(a.shownBounds_)&&b.z==a.shownBounds_.z){return}if(b.z!=a.shownBounds_.z){a.processAll_(a.shownBounds_,a.removeOverlay_);
					a.processAll_(b,a.addOverlay_)}else{a.rectangleDiff_(a.shownBounds_,b,a.removeCellMarkers_);
					a.rectangleDiff_(b,a.shownBounds_,a.addCellMarkers_)}a.shownBounds_=b;
					a.notifyListeners_()};
					MarkerManager.prototype.notifyListeners_=function(){GEvent.trigger(this,"changed",this.shownBounds_,this.shownMarkers_)};
					MarkerManager.prototype.processAll_=function(b,d){
						for(var a=b.minX;a<=b.maxX;a++){
							for(var c=b.minY;c<=b.maxY;c++){
								this.processCellMarkers_(a,c,b.z,d)
							}
						}
					};
					MarkerManager.prototype.processCellMarkers_=function(b,f,d,e){var a=this.getGridCellNoCreate_(b,f,d);
					if(a){
						for(var c=a.length-1;c>=0;c--){
							e(a[c])
						}
					}
					};
					MarkerManager.prototype.removeCellMarkers_=function(a,c,b){
						this.processCellMarkers_(a,c,b,this.removeOverlay_)
					};
					MarkerManager.prototype.addCellMarkers_=function(a,c,b){this.processCellMarkers_(a,c,b,this.addOverlay_)};
					MarkerManager.prototype.rectangleDiff_=function(b,a,d){var c=this;c.rectangleDiffCoords(b,a,function(e,f){d.apply(c,[e,f,b.z])})};
					MarkerManager.prototype.rectangleDiffCoords=function(b,a,l){
						var f=b.minX;
						var m=b.minY;
						var h=b.maxX;
						var d=b.maxY;
						var e=a.minX;
						var k=a.minY;
						var g=a.maxX;
						var c=a.maxY;
						for(var j=f;j<=h;j++){
							for(var i=m;i<=d&&i<k;i++){
								l(j,i)}for(var i=Math.max(c+1,m);i<=d;i++){l(j,i)
								}
						}for(var i=Math.max(m,k);i<=Math.min(d,c);i++){
							for(var j=Math.min(h+1,e)-1;j>=f;j--){
								l(j,i)}for(var j=Math.max(f,g+1);j<=h;j++){l(j,i)
								}
						}
					};
					MarkerManager.prototype.removeFromArray=function(e,c,d){
						var a=0;
						for(var b=0;b<e.length;++b){
							if(e[b]===c||(d&&e[b]==c)){
								e.splice(b--,1);
								a++
							}
						}return a
					};
