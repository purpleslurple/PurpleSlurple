javascript:(function(){
    var selectedText = window.getSelection().toString();
    var currentPageUrl = window.location.href;
    var annotation = prompt("Enter your annotation:");
    
    if (annotation !== null) {
      var baseUrl = "https://purpleslurple.com/quip.php";
      var urlParam = encodeURIComponent(currentPageUrl);
      var textParam = encodeURIComponent(selectedText);
      var annotationParam = encodeURIComponent(annotation);
      
      var constructedUrl = baseUrl + "?url=" + urlParam + "&text=" + textParam + "&annotation=" + annotationParam;
      
      window.location.href = constructedUrl;
    }
  })();
  