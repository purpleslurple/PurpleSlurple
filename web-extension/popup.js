const api = globalThis.browser ?? globalThis.chrome;
const button = document.querySelector('#read');
const status = document.querySelector('#status');

button.addEventListener('click', async () => {
  button.disabled = true;
  status.textContent = 'Building Reader View…';
  try {
    const [tab] = await api.tabs.query({ active: true, currentWindow: true });
    if (!tab?.id) throw new Error('No active tab found.');
    await api.scripting.executeScript({
      target: { tabId: tab.id },
      files: ['reader-engine.js', 'activate-reader.js']
    });
    window.close();
  } catch (error) {
    status.textContent = error?.message || 'Reader View could not open on this page.';
    button.disabled = false;
  }
});
