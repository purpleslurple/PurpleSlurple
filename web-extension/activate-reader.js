(() => {
  if (document.documentElement.dataset.purpleSlurpleReader === 'true') {
    location.reload();
    return;
  }
  window.PurpleSlurpleReader?.activate();
})();
