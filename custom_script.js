jQuery(document).ready( function() {
    
    jQuery ('.quote_terms').on('click', function(e) {
        e.preventDefault();
        console.log('test');
        jQuery('.tc_box').slideDown({
            start: function () {
                jQuery(this).css({
                  display: "flex"
                })
            }
        });
    });
    
     jQuery ('.quote_terms_scroll').on('click', function(e) {
        e.preventDefault();
    var aid = jQuery(this).attr("href");
    jQuery('html,body').animate({scrollTop: jQuery(aid).offset().top - 200},'slow');
    
    
       
    });
    
    
    
    
    
    
    
    
    jQuery ('.tc_close').on('click', function() {
        jQuery('.tc_box').slideUp();
    });
    
    jQuery ('.tc_accept').on('click', function() {
        jQuery('#wdform_52_element60').prop('checked', true);
        jQuery('.tc_box').slideUp();
    });
 
jQuery('.homepage-video-popover').on('click', function() {
  jQuery('.popup_container').slideDown();
    const iframe = document.querySelector('.vimeo_video');
    if (iframe && !iframe.src) {
      iframe.src = iframe.getAttribute('data-src');
    }
});

jQuery('.popup_close').on('click', function() {
	let videoFrame = jQuery('.vimeo_video');
	let videoURL = videoFrame.attr("src");
	
	videoFrame.attr('src', '');
	videoFrame.attr('src', videoURL);
	jQuery('.popup_container').slideUp();
	
});

jQuery('.popup_video').on('click', function(event) {
    if(event.target === this.firstChild) return;
    let videoFrame = jQuery('.vimeo_video');
	let videoURL = videoFrame.attr("src");
	
	videoFrame.attr('src', '');
	videoFrame.attr('src', videoURL);
    jQuery('.popup_container').slideUp();
});

});

