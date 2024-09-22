let cartCount = 0; // Initialize cart count

// Function to increment cart count
function incrementCartCount() {
    cartCount++; // Increment count
    document.getElementById('cart-count').textContent = cartCount; // Update the displayed count
}

// Add event listeners to all "Add to Cart" buttons
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', incrementCartCount);
});
