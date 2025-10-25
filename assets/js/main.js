// assets/js/main.js
class WCClone {
    constructor() {
        this.init();
    }

    init() {
        this.initializeEventListeners();
        this.updateCartCount();
        this.initializeMobileMenu();
        this.initializeImageGalleries();
        this.initializeProductTabs();
        this.initializeQuantityControls();
        this.initializeWishlist();
    }

    initializeEventListeners() {
        // Add to cart functionality
        document.addEventListener('submit', (e) => {
            if (e.target.classList.contains('add-to-cart-form')) {
                e.preventDefault();
                this.addToCart(e.target);
            }
        });

        // Wishlist functionality
        document.addEventListener('click', (e) => {
            if (e.target.closest('.btn-wishlist')) {
                e.preventDefault();
                this.toggleWishlist(e.target.closest('.btn-wishlist'));
            }
        });

        // Tab functionality
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('tab-btn')) {
                this.switchTab(e.target);
            }
        });

        // Modal functionality
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-close') || 
                e.target.classList.contains('modal-cancel')) {
                this.closeModal(e.target.closest('.modal'));
            }
        });

        // Review modal
        document.addEventListener('click', (e) => {
            if (e.target.id === 'writeReviewBtn') {
                this.openReviewModal();
            }
        });

        // Star rating
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('fa-star') && e.target.parentElement.classList.contains('rating-input')) {
                this.setRating(e.target);
            }
        });
    }

    async addToCart(form) {
        const formData = new FormData(form);
        const productId = form.dataset.productId;
        const quantity = formData.get('quantity') || 1;

        const button = form.querySelector('.btn-add-to-cart');
        const originalText = button.innerHTML;

        try {
            button.classList.add('loading');
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

            const response = await fetch('/wc-clone/api/cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'add',
                    product_id: parseInt(productId),
                    quantity: parseInt(quantity)
                })
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Product added to cart successfully!', 'success');
                this.updateCartCount(result.cart_count);
            } else {
                this.showNotification(result.message || 'Failed to add product to cart', 'error');
            }
        } catch (error) {
            this.showNotification('An error occurred while adding to cart', 'error');
            console.error('Add to cart error:', error);
        } finally {
            button.classList.remove('loading');
            button.innerHTML = originalText;
        }
    }

    async toggleWishlist(button) {
        const productId = button.dataset.productId;
        const icon = button.querySelector('i');

        try {
            const response = await fetch('/wc-clone/api/wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: parseInt(productId)
                })
            });

            const result = await response.json();

            if (result.success) {
                if (result.action === 'added') {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.showNotification('Product added to wishlist!', 'success');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.showNotification('Product removed from wishlist', 'info');
                }
            }
        } catch (error) {
            this.showNotification('An error occurred', 'error');
            console.error('Wishlist error:', error);
        }
    }

    updateCartCount(count = null) {
        const cartCountElement = document.getElementById('cartCount');
        if (cartCountElement) {
            if (count !== null) {
                cartCountElement.textContent = count;
            } else {
                // Fetch current cart count from API
                fetch('/wc-clone/api/cart.php?action=count')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            cartCountElement.textContent = data.count;
                        }
                    })
                    .catch(console.error);
            }
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.getElementById('notification');
        if (!notification) return;

        notification.className = `notification ${type} show`;
        notification.innerHTML = `
            <i class="fas fa-${this.getNotificationIcon(type)}"></i>
            <span>${message}</span>
        `;

        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }

    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    initializeMobileMenu() {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        const mobileNavClose = document.getElementById('mobileNavClose');

        if (mobileMenuBtn && mobileNav) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileNav.classList.add('show');
            });

            mobileNavClose.addEventListener('click', () => {
                mobileNav.classList.remove('show');
            });
        }
    }

    initializeImageGalleries() {
        // Product image gallery
        const thumbnails = document.querySelectorAll('.image-thumbnails .thumbnail');
        const mainImage = document.getElementById('mainProductImage');

        if (thumbnails.length && mainImage) {
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', () => {
                    const imageUrl = thumb.dataset.image;
                    mainImage.src = imageUrl;

                    // Update active state
                    thumbnails.forEach(t => t.classList.remove('active'));
                    thumb.classList.add('active');
                });
            });
        }
    }

    initializeProductTabs() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.switchTab(button);
            });
        });
    }

    switchTab(button) {
        const targetTab = button.dataset.tab;
        const tabContent = document.getElementById(targetTab);

        if (!tabContent) return;

        // Update buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');

        // Update panes
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('active');
        });
        tabContent.classList.add('active');
    }

    initializeQuantityControls() {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('quantity-btn')) {
                const control = e.target.closest('.quantity-control');
                const input = control.querySelector('.quantity-input');
                let value = parseInt(input.value);

                if (e.target.classList.contains('plus')) {
                    value++;
                } else if (e.target.classList.contains('minus')) {
                    value = Math.max(1, value - 1);
                }

                input.value = value;
            }
        });
    }

    initializeWishlist() {
        // Check and update wishlist button states on page load
        document.querySelectorAll('.btn-wishlist').forEach(button => {
            const productId = button.dataset.productId;
            this.checkWishlistStatus(button, productId);
        });
    }

    async checkWishlistStatus(button, productId) {
        try {
            const response = await fetch(`/wc-clone/api/wishlist.php?product_id=${productId}`);
            const result = await response.json();

            if (result.success && result.in_wishlist) {
                const icon = button.querySelector('i');
                icon.classList.remove('far');
                icon.classList.add('fas');
            }
        } catch (error) {
            console.error('Wishlist status check error:', error);
        }
    }

    openReviewModal() {
        const modal = document.getElementById('reviewModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    closeModal(modal) {
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    setRating(star) {
        const rating = parseInt(star.dataset.rating);
        const stars = star.parentElement.querySelectorAll('.fa-star');
        const ratingValue = document.getElementById('ratingValue');

        stars.forEach((s, index) => {
            if (index < rating) {
                s.classList.remove('far');
                s.classList.add('fas');
            } else {
                s.classList.remove('fas');
                s.classList.add('far');
            }
        });

        if (ratingValue) {
            ratingValue.value = rating;
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new WCClone();
});

// Utility functions
const utils = {
    formatPrice(price) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(price);
    },

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
};