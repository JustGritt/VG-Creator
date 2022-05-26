console.log('dark-mode.js running');

const toggleDarkmode = document.querySelector('header .switch input[type="checkbox"]');
console.log(toggleDarkmode)

// Check if the user has darkmode enabled
const checkDarkmode = () => {
    if (toggleDarkmode.checked) {
        document.body.classList.add('dark');
        console.log('Dark mode is on');
    }
    else {
        document.body.classList.remove('dark');
        console.log('Dark mode is off');
    }
}

// Check if the toggleDarkmode is clicked
toggleDarkmode.addEventListener('click', checkDarkmode);
