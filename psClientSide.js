javascript:(function() {
    var fcontents = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, li');

    fcontents.forEach(function(element, index) {
        var fragmentId = "purp" + index;
        var link = document.createElement('a');
        link.href = '#' + fragmentId;
        link.id = fragmentId;
        link.innerHTML = index;
        
        var parenthesizedContent = document.createElement('span');
        parenthesizedContent.appendChild(document.createTextNode('('));
        parenthesizedContent.appendChild(link);
        parenthesizedContent.appendChild(document.createTextNode(') '));
        
        element.insertBefore(parenthesizedContent, element.firstChild);
    });
})();
