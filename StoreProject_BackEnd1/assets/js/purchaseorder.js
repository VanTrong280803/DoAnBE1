const searchBtn = document.querySelector(".search-btn");
const navListItem = document.querySelector(".nav-bar-list-item");
const textField = document.querySelector(".text-field");
const submitBtn = document.querySelector(".submit-btn");
const iconClose = document.querySelector(".icon-close");


const btnCheckout = document.querySelector('.footer-product__pay');
const modal = document.querySelector('.js-modal');
const modalContainer = document.querySelector('.js-modal-container');
const modalClose = document.querySelector('.js-modal-close');

// SEARCH
searchBtn.addEventListener("click", () => {
    navListItem.style.display = "none";
    textField.style.display = "block";
    iconClose.style.display = "block";
});

submitBtn.addEventListener("click", () => {
    navListItem.style.display = "block";
    textField.style.display = "none";
    iconClose.style.display = "none";
});

iconClose.addEventListener("click", () => {
    navListItem.style.display = "block";
    textField.style.display = "none";
    iconClose.style.display = "none";
});
// SEARCH

// BACK TO HOME
window.onscroll = function() {
    if (document.documentElement.scrollTop > 200) {
        document.querySelector(".back-to-home").style.display = "block";
    } else {
        document.querySelector(".back-to-home").style.display = "none";
    }
};
// BACK TO HOME