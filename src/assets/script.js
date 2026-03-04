document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('instance');
    const forgetBtn = document.getElementById('forget-btn');
    
    // Gedächtnis laden
    const saved = localStorage.getItem('mastodon_instance');
    if (saved) {
        input.value = saved;
        forgetBtn.style.display = 'block';
    }

    forgetBtn.onclick = () => {
        localStorage.removeItem('mastodon_instance');
        input.value = '';
        forgetBtn.style.display = 'none';
    };

    // Link Generator
    document.getElementById('gen-btn').onclick = () => {
        const text = encodeURIComponent(document.getElementById('share-text').value);
        const url = window.location.href.split('?')[0] + '?text=' + text;
        document.getElementById('gen-result').value = url;
    };
});