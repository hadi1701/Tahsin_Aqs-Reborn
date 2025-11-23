// darkmode.js — FIXED for Sneat Free v3.0.0
document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.getElementById('darkModeToggle');
  if (!toggle) return;

  const icon = toggle.querySelector('i');
  const body = document.body;

  // Cek tema dari localStorage atau sistem
  const savedTheme = localStorage.getItem('theme');
  const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const isDark = savedTheme === 'dark' || (!savedTheme && systemPrefersDark);

  // Terapkan tema awal
  if (isDark) {
    body.classList.add('dark-mode');
    if (icon) icon.className = 'bx bx-sun bx-sm';
  }

  // Toggle handler
  toggle.addEventListener('click', function () {
    const isNowDark = !body.classList.contains('dark-mode');
    body.classList.toggle('dark-mode', isNowDark);
    localStorage.setItem('theme', isNowDark ? 'dark' : 'light');

    if (icon) {
      icon.className = isNowDark ? 'bx bx-sun bx-sm' : 'bx bx-moon bx-sm';
    }
  });
});

