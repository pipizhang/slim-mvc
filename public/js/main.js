(function(){

window.$app = {
    /**
     * home page controller
     */
    homeController :function(){
        this.run = function() {
            this.bucketMenu();
            this.mediaSlider();
        };
        this.bucketMenu = function() {
            $('.bucket_menu a').bind('click', function(){
                $('.bucket_menu a').each(function(){
                    $(this).removeClass("selected");
                });
                $(this).addClass("selected");
                $('.bucket_text').hide();
                $('#'+$(this).attr('_bind')).show();
            });
        };
        this.mediaSlider = function() {
            $.getJSON("/medias").done(function(data){
                /* render */
                var items = data;
                $("#mediaSlider").html(_.template($("#mediaData").html(),{items:items}));

                /* jcarousel */
                $('.jcarousel').jcarousel({
                    wrap: 'circular'
                });
                $('.page_left').jcarouselControl({
                    target: '-=5'
                });
                $('.page_right').jcarouselControl({
                    target: '+=5'
                });
                $('.pagination').on('jcarouselpagination:active', 'a', function() {
                    $(this).addClass('active');
                }).on('jcarouselpagination:inactive', 'a', function() {
                    $(this).removeClass('active');
                }).on('click', function(e) {
                    e.preventDefault();
                }).jcarouselPagination({
                    perPage: 5,
                    item: function(page) {
                        return '<a href="#' + page + '"><span>' + page + '</span></a>';
                    }
                });
            });
        }
    }
};
    
})();