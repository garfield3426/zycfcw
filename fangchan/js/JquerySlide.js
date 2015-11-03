var PicSlide = {
    DrawImage: function(img, iwidth, iheight) {
        var image = new Image();
        image = img;
        if (image.width > 0 && image.height > 0) {
            if (image.width > iwidth) {
                img.width = iwidth;
                img.height = (image.height * iwidth) / image.width
            } else {
                img.width = image.width;
                img.height = image.height
            }
            if (img.height > iheight) {
                img.height = iheight;
                img.width = (image.width * iheight) / image.height
            }
        }
    },
    BindSlide: function(imgID, iwidth, iheight, sliderID, btnR, btnL, slideNum) {
        var slideliimg = sliderID + " li img";
        var slidelicurrent = sliderID + " li.current";
        var imgid = imgID;
        var slideul = sliderID + " ul";
        var slideulli = sliderID + " ul li";
        jQuery("#" + slideliimg).click(function() {
            var img = new Image();
            img.onload = function() {
                if (img.width > iwidth || img.height > iheight) {
                    PicSlide.DrawImage(img, iwidth, iheight);
                    jQuery(".zoompic img").css({
                        width: img.width,
                        height: img.height
                    })
                }
            };
            img.src = jQuery(this).attr("src");
            jQuery("#" + imgid).attr({
                "src": jQuery(this).attr("src").replace("/84x60", "/600x600")
            });
            jQuery("#" + imgid).attr({
                "alt": jQuery(this).attr("alt")
            });
            jQuery("#" + slidelicurrent).removeClass("current");
            jQuery(this).parents("li").addClass("current");
            return false
        });
        var $slider = jQuery("#" + slideul);
        var $slider_child_l = jQuery("#" + slideulli).length;
        var $slider_width = jQuery("#" + slideulli).outerWidth();
        $slider.width($slider_child_l * $slider_width);
        var slider_count = 0;
        if ($slider_child_l < slideNum) {
            jQuery("#" + btnR).css({
                cursor: "auto"
            });
            jQuery("#" + btnR).removeClass("dasabled")
        }
        jQuery("#" + btnR).click(function() {
            jQuery("#" + slidelicurrent).next().children().click();
            if ($slider_child_l < slideNum || slider_count >= $slider_child_l - slideNum) {
                return false
            }
            slider_count++;
            $slider.animate({
                left: "-=" + $slider_width + "px"
            },
            "fast")
        });
        jQuery("#" + btnL).click(function() {
            jQuery("#" + slidelicurrent).prev().children().click();
            if (slider_count <= 0) {
                return false
            }
            slider_count--;
            $slider.animate({
                left: "+=" + $slider_width + "px"
            },
            "fast")
        });
        jQuery("#" + imgid).bind("mousemove",
        function(e) {
            if (e.pageX <= jQuery(this).offset().left + jQuery(this).width() / 2) {
                jQuery(this).css("cursor", "url('http://img.soufun.com/secondhouse/image/newdetails/arr_left.cur'),auto")
            } else {
                jQuery(this).css("cursor", "url('http://img.soufun.com/secondhouse/image/newdetails/arr_right.cur'),auto")
            }
        }).bind("click",
        function(e) {
            if (e.pageX <= jQuery(this).offset().left + jQuery(this).width() / 2) {
                jQuery("#" + slidelicurrent).prev().children().click();
                if (slider_count <= 0) {
                    return false
                }
                slider_count--;
                $slider.animate({
                    left: "+=" + $slider_width + "px"
                },
                "fast")
            } else {
                jQuery("#" + slidelicurrent).next().children().click();
                if ($slider_child_l < slideNum || slider_count >= $slider_child_l - slideNum) {
                    return false
                }
                slider_count++;
                $slider.animate({
                    left: "-=" + $slider_width + "px"
                },
                "fast")
            }
        })
    },
    initSlide: function(imgID, iwidth, iheight, srcID) {
        var $src2 = jQuery("#" + imgID).attr(srcID);
        var img_Firstsw = new Image();
        img_Firstsw.onload = function() {
            if (img_Firstsw.width > iwidth || img_Firstsw.height > iheight) {
                PicSlide.DrawImage(img_Firstsw, iwidth, iheight);
                jQuery("#" + imgID).css({
                    width: img_Firstsw.width,
                    height: img_Firstsw.height
                })
            }
            jQuery("#" + imgID).attr({
                "src": $src2
            })
        };
        img_Firstsw.src = $src2
    }
};