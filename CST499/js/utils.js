
document.addEventListener('DOMContentLoaded', function() {
    if (!document.querySelector('.toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '11';
        document.body.appendChild(toastContainer);
    }
    
    if (!window.ajaxFormsInitialized) {
        initializeAjaxForms();
        window.ajaxFormsInitialized = true;
    }
});


function showToast(message, type = 'success') {
    const toastContainer = document.querySelector('.toast-container');
    
    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-white bg-${type}`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    
    const toastBody = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    toastEl.innerHTML = toastBody;
    toastContainer.appendChild(toastEl);
    
    const toast = new bootstrap.Toast(toastEl, {
        autohide: true,
        delay: 5000
    });
    
    toast.show();
    
    toastEl.addEventListener('hidden.bs.toast', function() {
        toastEl.remove();
    });
}


function initializeAjaxForms() {
    const ajaxForms = document.querySelectorAll('form[data-ajax]');
    
    ajaxForms.forEach(form => {
        const endpoint = form.dataset.ajax;
        const redirectDelay = form.dataset.redirectDelay || 2000;
        
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            
            fetch(endpoint, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    throw new Error('Invalid response format: Expected JSON but received ' + contentType);
                }
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    
                    if (data.redirectTo) {
                        setTimeout(() => { window.location.href = data.redirectTo; }, redirectDelay);
                    } 
                    else if (data.semester || data.year) {
                        setTimeout(() => {
                            let url = 'classes.php?';
                            if (data.semester) {
                                url += 'semester=' + encodeURIComponent(data.semester);
                            }
                            if (data.year) {
                                if (data.semester) url += '&';
                                url += 'year=' + encodeURIComponent(data.year);
                            }
                            window.location.href = url;
                        }, redirectDelay);
                    } 
                    else if (form.dataset.reload !== 'false') {
                        setTimeout(() => { location.reload(); }, redirectDelay);
                    }
                    
                    if (form.dataset.reset === 'true') {
                        form.reset();
                    }
                } else {
                    showToast(data.message, 'danger');
                    
                    if (data.redirectToWaitlist) {
                        setTimeout(() => { window.location.href = 'add_waitlist.php'; }, redirectDelay);
                    } else if (data.redirectTo) {
                        setTimeout(() => { window.location.href = data.redirectTo; }, redirectDelay);
                    }
                }
            })
            .catch(error => {
                showToast('An error occurred while processing your request. Please try again.', 'danger');
                console.error('Error:', error);
            });
        });
    });
}