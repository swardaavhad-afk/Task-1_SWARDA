(function () {
    'use strict';

    const form = document.querySelector('form[action$="submit-enquiry.php"]');
    const destructiveForms = document.querySelectorAll('form[data-confirm]');

    destructiveForms.forEach(function (destructiveForm) {
        destructiveForm.addEventListener('submit', function (event) {
            if (!window.confirm(destructiveForm.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
    if (!form) {
        return;
    }

    const rules = {
        name: function (value) {
            return value.length >= 2 && value.length <= 100 && /^[\p{L}\p{M} .'-]+$/u.test(value) ? '' : 'Enter a valid name between 2 and 100 characters.';
        },
        email: function (value) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) ? '' : 'Enter a valid email address.';
        },
        phone: function (value) {
            return /^[+0-9][0-9(). \-]{6,29}$/.test(value) ? '' : 'Enter a valid phone number.';
        },
        subject: function (value) {
            return value.length > 0 && value.length <= 200 ? '' : 'Enter a subject up to 200 characters.';
        },
        enquiry_type: function (value) {
            return ['General Enquiry', 'Sales', 'Support', 'Partnership', 'Feedback', 'Other'].includes(value) ? '' : 'Select a valid enquiry type.';
        },
        message: function (value) {
            return value.length >= 10 && value.length <= 5000 ? '' : 'Enter a message between 10 and 5,000 characters.';
        }
    };

    function validateField(field) {
        const errorElement = form.querySelector('[data-error-for="' + field.name + '"]');
        const message = rules[field.name] ? rules[field.name](field.value.trim()) : '';
        field.setAttribute('aria-invalid', message ? 'true' : 'false');
        if (errorElement) {
            errorElement.textContent = message;
        }
        return message === '';
    }

    Object.keys(rules).forEach(function (name) {
        const field = form.elements[name];
        if (field) {
            field.addEventListener('blur', function () { validateField(field); });
            field.addEventListener('input', function () {
                if (field.getAttribute('aria-invalid') === 'true') {
                    validateField(field);
                }
            });
        }
    });

    form.addEventListener('submit', function (event) {
        const valid = Object.keys(rules).every(function (name) {
            return validateField(form.elements[name]);
        });
        if (!valid) {
            event.preventDefault();
            const firstInvalid = form.querySelector('[aria-invalid="true"]');
            if (firstInvalid) {
                firstInvalid.focus();
            }
        }
    });
}());
