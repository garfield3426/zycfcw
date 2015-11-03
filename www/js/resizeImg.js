function ResizeImage(ImgD,w,h){
	var image=new Image();
	image.src=ImgD.src;
	if(image.width>0 && image.height>0){
		flag=true;
		if(image.width/image.height>= w/h){
			if(image.width>w){
				ImgD.width=w;
				ImgD.height=(image.height*w)/image.width;
			}else{
				ImgD.width=image.width;
				ImgD.height=image.height;
			}
			//ImgD.alt=image.width+"×"+image.height;
		}
		else{
			if(image.height>h){
				ImgD.height=h;
				ImgD.width=(image.width*h)/image.height;
			}else{
				ImgD.width=image.width;
				ImgD.height=image.height;
			}
			//ImgD.alt=image.width+"×"+image.height;
		}
	}
}