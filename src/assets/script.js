function showToast(msg) {
  const toast = document.getElementById("toast");
  toast.innerText = msg;
  toast.classList.add("show");
  setTimeout(() => toast.classList.remove("show"), 3000);
}

function copyToClipboard(id, msg) {
  const el = document.getElementById(id);
  el.select();
  document.execCommand("copy");
  showToast(msg);
}

document.addEventListener("DOMContentLoaded", () => {
  const instanceInput = document.getElementById("instance");
  const forgetBtn = document.getElementById("forget-btn");
  const saved = localStorage.getItem("mastodon_instance");

  if (saved) {
    instanceInput.value = saved;
    forgetBtn.classList.remove("hidden");
  }

  forgetBtn.onclick = () => {
    localStorage.removeItem("mastodon_instance");
    instanceInput.value = "";
    forgetBtn.classList.add("hidden");
    showToast("🤫 Vergessen!");
  };

  document.getElementById("share-form").onsubmit = () => {
    localStorage.setItem("mastodon_instance", instanceInput.value);
  };

  document.getElementById("gen-btn").onclick = () => {
    const base = window.location.href.split("?")[0];
    const text = encodeURIComponent(
      document.getElementById("share-text").value,
    );
    const finalUrl = base + "?text=" + text;

    document.getElementById("res-url").value = finalUrl;
    document.getElementById("res-html").value =
      `<a href="${finalUrl}" target="_blank">Auf Mastodon teilen</a>`;
    document.getElementById("res-md").value =
      `[Auf Mastodon teilen](${finalUrl})`;

    document.getElementById("generator-fields").classList.remove("hidden");
    showToast("Links generiert!");
  };
});