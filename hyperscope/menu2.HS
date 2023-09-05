javascript:(function() {
  // Create a container div for the custom UI
  var customUI = document.createElement('div');
  customUI.innerHTML = `
    <button id="myButton">Click Me</button>
    <div id="myMenu">
      <ul>
        <li>Option 1</li>
        <li>Option 2</li>
      </ul>
    </div>
  `;

  // Style the custom UI
  customUI.style.position = 'fixed';
  customUI.style.top = '10px';
  customUI.style.right = '10px';
  customUI.style.backgroundColor = 'white';
  customUI.style.border = '1px solid #ccc';

  // Append the custom UI to the document body
  document.body.appendChild(customUI);

  // Add interactivity
  document.getElementById('myButton').addEventListener('click', function() {
    alert('Button clicked!');
  });
})();
