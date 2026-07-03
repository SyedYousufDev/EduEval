// Star Rating System - FIXED
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize star rating for all star containers
    function initStarRatings() {
        const starContainers = document.querySelectorAll('.stars-container');
        
        starContainers.forEach(container => {
            const radios = container.querySelectorAll('input[type="radio"]');
            const labels = container.querySelectorAll('label.star');
            
            // Add click handler to each radio
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Update visual feedback
                    updateStarVisuals(container, this.value);
                });
            });
            
            // Check if any radio is pre-selected and update visuals
            const checkedRadio = container.querySelector('input:checked');
            if (checkedRadio) {
                updateStarVisuals(container, checkedRadio.value);
            }
        });
    }
    
    // Update star colors based on selected value
    function updateStarVisuals(container, value) {
        const stars = container.querySelectorAll('label.star');
        const starValue = parseInt(value);
        
        // Clear all star colors first
        stars.forEach(star => {
            star.style.color = '#ddd';
        });
        
        // Color stars up to the selected value
        // Since stars are in reverse order, we need to count from the end
        const starArray = Array.from(stars);
        for (let i = starArray.length - starValue; i < starArray.length; i++) {
            if (starArray[i]) {
                starArray[i].style.color = '#ffc107';
            }
        }
    }
    
    // Hover effects for star ratings
    function initStarHoverEffects() {
        const starContainers = document.querySelectorAll('.stars-container');
        
        starContainers.forEach(container => {
            const stars = container.querySelectorAll('label.star');
            const radios = container.querySelectorAll('input[type="radio"]');
            
            stars.forEach((star, index) => {
                // Mouse enter - highlight stars on hover
                star.addEventListener('mouseenter', function() {
                    const starIndex = Array.from(stars).indexOf(this);
                    const starValue = stars.length - starIndex;
                    
                    // Highlight all stars up to the hovered one
                    stars.forEach((s, i) => {
                        if (i >= starIndex) {
                            s.style.color = '#ffc107';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });
                
                // Mouse leave - revert to selected value
                star.addEventListener('mouseleave', function() {
                    const checkedRadio = container.querySelector('input:checked');
                    if (checkedRadio) {
                        updateStarVisuals(container, checkedRadio.value);
                    } else {
                        // No selection, reset all to grey
                        stars.forEach(s => {
                            s.style.color = '#ddd';
                        });
                    }
                });
            });
        });
    }
    
    // Form validation for evaluation
    const evaluationForm = document.getElementById('evaluationForm');
    if(evaluationForm) {
        evaluationForm.addEventListener('submit', function(e) {
            const qualitySelected = document.querySelector('input[name="quality_material"]:checked');
            const punctualitySelected = document.querySelector('input[name="punctuality"]:checked');
            const engagementSelected = document.querySelector('input[name="engagement"]:checked');
            
            if(!qualitySelected) {
                e.preventDefault();
                alert('Please rate the Quality of Material (1-5 stars)');
                return false;
            }
            if(!punctualitySelected) {
                e.preventDefault();
                alert('Please rate the Punctuality (1-5 stars)');
                return false;
            }
            if(!engagementSelected) {
                e.preventDefault();
                alert('Please rate the Engagement (1-5 stars)');
                return false;
            }
            return true;
        });
    }
    
    // Password confirmation validation
    const registerForm = document.querySelector('form[action*="register"]');
    if(registerForm) {
        const password = registerForm.querySelector('input[name="password"]');
        const confirmPassword = registerForm.querySelector('input[name="confirm_password"]');
        
        if(confirmPassword) {
            confirmPassword.addEventListener('input', function() {
                if(this.value !== password.value) {
                    this.setCustomValidity('Passwords do not match!');
                } else {
                    this.setCustomValidity('');
                }
            });
        }
    }
    
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if(target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
    
    // Fade out alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                if(alert.parentNode) alert.remove();
            }, 500);
        }, 5000);
    });
    
    // Form submission loading state
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if(submitBtn && !submitBtn.hasAttribute('data-no-loading')) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Processing...';
            }
        });
    });
    
    // Department filter animation
    const filterButtons = document.querySelectorAll('.filter-buttons .btn');
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            filterButtons.forEach(b => b.classList.remove('btn-active'));
            this.classList.add('btn-active');
        });
    });
    
    // Faculty card hover effect
    const facultyCards = document.querySelectorAll('.faculty-card');
    facultyCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Table row highlight
    const tableRows = document.querySelectorAll('.data-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function() {
            this.style.backgroundColor = '#f0f0f0';
            setTimeout(() => {
                this.style.backgroundColor = '';
            }, 300);
        });
    });
    
    // Initialize star rating system
    initStarRatings();
    initStarHoverEffects();
    
    console.log('EduEval - Star Rating System Loaded Successfully!');
});