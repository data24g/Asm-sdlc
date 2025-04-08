// Initialize slide index
let slideIndex = 1;

document.addEventListener('DOMContentLoaded', function () {
    // 1. Hide/show header on scroll
    const header = document.getElementById('header');
    let lastScrollPosition = 0;

    window.addEventListener('scroll', () => {
        const currentScrollPosition = window.scrollY;

        if (currentScrollPosition > 100 && currentScrollPosition > lastScrollPosition) {
            header.classList.add('hidden');
        } else {
            header.classList.remove('hidden');
        }

        lastScrollPosition = currentScrollPosition;
    });

    // 2. Initialize slideshow
    showSlides(slideIndex);

    // Auto slide change
    setInterval(() => {
        plusSlides(1);
    }, 5000);

    // 3. Product Click Handler
    const productCards = document.querySelectorAll('.product-card, .best-selling-card, .explore-product-card');

    // Add click event to all product cards
    productCards.forEach(card => {
        card.addEventListener('click', function () {
            // Get product ID
            const productId = this.getAttribute('data-product-id') || '1';

            // Navigate to product detail page with product ID
            window.location.href = `product-detail.html?id=${productId}`;
        });
    });
});

// Slideshow functions
function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    const slides = document.getElementsByClassName("slide");
    const dots = document.getElementsByClassName("dot");

    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }

    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    for (let i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }

    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}

// Initialize cart
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let cartBadge = document.querySelector('.header-icons .badge');

// Update cart badge
function updateCartBadge() {
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    cartBadge.textContent = totalItems;
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Add to cart function
function addToCart(productId, productTitle, productPrice, productImage) {
    // Remove $ and commas from price and convert to number
    const price = parseFloat(productPrice.replace(/[$,]/g, ''));

    // Check if product already in cart
    const existingItem = cart.find(item => item.id === productId);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            title: productTitle,
            price: price,
            image: productImage,
            quantity: 1
        });
    }

    updateCartBadge();
    alert(`${productTitle} đã được thêm vào giỏ hàng!`);
}

// Initialize cart badge on page load
updateCartBadge();

// Add event listeners to all "Add to Cart" buttons
document.addEventListener('DOMContentLoaded', function () {
    // ... (giữ nguyên các code hiện có)

    // Add to cart buttons
    const addToCartButtons = document.querySelectorAll('.slideshow-add-to-cart, .best-selling-add-to-cart, .explore-add-to-cart, .modal-add-to-cart');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent triggering product card click

            const productCard = this.closest('.slideshow-product, .best-selling-card, .explore-product-card, .modal-product');
            let productId, productTitle, productPrice, productImage;

            if (productCard.classList.contains('slideshow-product')) {
                // Flash sale product
                productId = this.closest('.slide').querySelector('.slideshow-title').textContent;
                productTitle = this.closest('.slide').querySelector('.slideshow-title').textContent;
                productPrice = this.closest('.slide').querySelector('.slideshow-price').textContent;
                productImage = this.closest('.slide').querySelector('.slideshow-image').src;
            } else if (productCard.classList.contains('best-selling-card')) {
                // Best selling product
                productId = productCard.getAttribute('data-product-id');
                productTitle = productCard.querySelector('.best-selling-title').textContent;
                productPrice = productCard.querySelector('.best-selling-price').textContent;
                productImage = productCard.querySelector('.best-selling-image').src;
            } else if (productCard.classList.contains('explore-product-card')) {
                // Explore product
                productId = productCard.getAttribute('data-product-id');
                productTitle = productCard.querySelector('.explore-product-title').textContent;
                productPrice = productCard.querySelector('.explore-product-price').textContent;
                productImage = productCard.querySelector('.explore-product-image').src;
            } else if (productCard.classList.contains('modal-product')) {
                // Modal product
                productId = productCard.querySelector('.modal-product-title').textContent;
                productTitle = productCard.querySelector('.modal-product-title').textContent;
                productPrice = productCard.querySelector('.modal-product-price').textContent;
                productImage = productCard.querySelector('.modal-product-image').src;
            }

            addToCart(productId, productTitle, productPrice, productImage);
        });
    });

    // Cart icon click - redirect to cart page
    const cartIcon = document.querySelector('.header-icons a[href="#"]:last-child');
    if (cartIcon) {
        cartIcon.addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = 'cart.html';
        });
    }
});