let darkmode = localStorage.getItem('darkmode');
const themeSwitch = document.getElementById('theme-switch');

const enableDarkmode = () => {
    document.body.classList.add('darkmode');
    localStorage.setItem('darkmode', 'active');
};

const disableDarkmode = () => {
    document.body.classList.remove('darkmode');
    localStorage.setItem('darkmode', '');
};

// apply saved mode saat halaman dibuka
if (darkmode === 'active') {
    enableDarkmode();
}

// ketika tombol di-klik
themeSwitch.addEventListener('click', () => {
    darkmode = localStorage.getItem('darkmode');

    if (darkmode === 'active') {
        disableDarkmode();
    } else {
        enableDarkmode();
    }
});
