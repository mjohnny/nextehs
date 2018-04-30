function getWidth(){
    if ($(window).width() < 768 ){
        $('img').attr('class', 'img-responsive');
    }else {
        $('img').removeAttr('class', 'img-responsive');
        $('p').each(function () {
            var img = this.querySelectorAll('img');
            if (img.length === 1){
                img[0].setAttribute('class', 'img-responsive');
            }
        })
    }
}
getWidth();
$(window).resize(function () {
    getWidth();
});