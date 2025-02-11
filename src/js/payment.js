// payment.js
document.addEventListener('DOMContentLoaded', () => {
    const paymentMethods = document.querySelectorAll('.method-card');
    const paymentDetails = document.querySelectorAll('.payment-details');
    const formControl = document.getElementById('paymentMethod');

    paymentMethods.forEach(method => {
        method.addEventListener('click', () => {
            // Remove active class from all methods
            paymentMethods.forEach(m => m.style.borderColor = '#ddd');
            // Hide all payment details
            paymentDetails.forEach(d => d.style.display = 'none');

            // Activate selected method
            method.style.borderColor = '#3498db';
            const selectedMethod = method.dataset.method;
            const details = document.getElementById(`${selectedMethod}Details`);
            formControl.value = selectedMethod;
            if (details) details.style.display = 'block';
        });

        // Khởi tạo mặc định
        if (method.classList.contains('active')) {
            method.click();
        }
    });

    // Basic card validation
    document.getElementById('paymentForm').addEventListener('submit', (e) => {
        const cardNumber = document.getElementById('cardNumber').value;
        if (cardNumber && !validateCardNumber(cardNumber)) {
            e.preventDefault();
            alert('Please enter a valid card number');
        }
    });
});

// Validate card number
function validateCardNumber(number) {
    // Simple Luhn algorithm validation
    let sum = 0;
    let shouldDouble = false;
    number = number.replace(/\s/g, '');

    for (let i = number.length - 1; i >= 0; i--) {
        let digit = parseInt(number.charAt(i), 10);

        if (shouldDouble) {
            if ((digit *= 2) > 9) digit -= 9;
        }

        sum += digit;
        shouldDouble = !shouldDouble;
    }

    return (sum % 10) === 0;
}