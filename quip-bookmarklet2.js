javascript:(function() {
  var url = encodeURIComponent(window.location.href);
  var sel = window.getSelection();
  var htmlContent = '';
  if (sel.rangeCount) {
    var container = document.createElement('div');
    for (var i = 0, len = sel.rangeCount; i < len; ++i) {
      container.appendChild(sel.getRangeAt(i).cloneContents());
    }
    htmlContent = container.innerHTML;
    htmlContent = encodeURIComponent(htmlContent);
  }
  var annotation = encodeURIComponent(prompt('Enter the annotation'));
  window.location.href = 'https://fantastic-meme-7x7vqw4grq27qq-8080.app.github.dev/quip2.php?url=' + url + '&text=' + htmlContent + '&annotation=' + annotation;
})();
