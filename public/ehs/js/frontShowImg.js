$('.frontShow').each(function (indexFrontShow, frontShow) {
        var img = this.querySelector('img');
        if (img) {
            img = img.getAttribute('src');
            $('#image' + (indexFrontShow)).attr('src', img);
        }
        var allContent = frontShow.querySelectorAll('p,H1,H2,H3,H4,H5');
        var newText = false;
        $(allContent).each(function (indexContent, content) {
            var child = content;
            while (child.nodeName !== '#text') {
                if (child.firstChild) {
                    child = child.firstChild;
                } else {
                    break;
                }
            }
            if (child.nodeName === "#text" && !newText) {
                if (child.nodeValue.length > 1) {
                    if (child.nodeValue.length > 100) {
                        newText = child.nodeValue.slice(0, 100) + ' ...';
                    } else {
                        newText = child.nodeValue + ' ...';
                    }
                }
                frontShow.innerText = newText;
            }
        });
    }
);