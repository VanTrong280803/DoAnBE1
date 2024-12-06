
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


// MESSAGE
function showMessage() {
    modal.classList.add('open')
}

function hideMessage() {
    modal.classList.remove('open')
}

btnCheckout.addEventListener('click', showMessage)

modalClose.addEventListener('click', hideMessage)

modal.addEventListener('click', hideMessage)

modalContainer.addEventListener('click', function (event) {
    event.stopPropagation()
})
// MESSAGE


const productPrice = document.getElementsByClassName('product__main--price-content');
const productSubtotal = document.getElementsByClassName('product__main--total-price');
const quantityInput = document.getElementsByClassName('qty');
const totalAllProduct = document.getElementsByClassName('footer-product__main-price');

// CALCULATE
function calculate() {
    let total = 0;   
    for(let i = 0; i < productPrice.length; i++)
    {
        const price = parseInt(productPrice[i].getAttribute("data-price"));  
        const quantity = parseInt(quantityInput[i].value);
        let mul = price * quantity;
        productSubtotal[i].innerText = `$${mul}.00`;
        total += mul;
    }
    for(let i = 0; i < totalAllProduct.length; i++)
    {
        totalAllProduct[i].innerText = `$${total}.00`;
    }
}
// CALCULATE

const productCheckBox = document.getElementsByClassName('stardust-checkbox__box');
const stardustCheckbox = document.getElementsByClassName('stardust-checkbox');
const productCheckBoxAll = document.getElementsByClassName('stardust-checkbox__box-all');
const textCheckBoxAll = document.getElementsByClassName('footer-product__main--btn-checkall');

function checkAll() {
    for (let i = 0; i < productCheckBox.length; i++) {
        stardustCheckbox[i].classList.toggle('stardust-checkbox--checked');  
    }
}
