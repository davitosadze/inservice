// Admin Order Creation Modal Component
class AdminOrderModal {
    constructor() {
        this.modal = null;
        this.activeType = 1;
        this.branches = [];
        this.formValues = {
            description: '',
            branch_id: 0,
            type: 1,
            subj_name: '',
            subj_address: ''
        };
        this.errors = {};
        this.submitting = false;
        this.loadingBranches = false;
        
        this.init();
    }
    
    init() {
        this.createModal();
        this.attachEventListeners();
        this.loadBranches();
    }
    
    createModal() {
        const modalHTML = `
            <div class="modal fade" id="adminOrderModal" tabindex="-1" role="dialog" aria-labelledby="adminOrderModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="adminOrderModalLabel">შეკვეთის შექმნა (ადმინი)</h5>
                            <button type="button" class="close" id="closeModalBtn" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Status Message -->
                            <div id="statusMessage" class="alert" style="display: none;">
                                <span id="statusText"></span>
                            </div>
                            
                            <!-- Type Selection Tabs -->
                            <div class="btn-group btn-group-toggle mb-3 w-100" data-toggle="buttons">
                                <label class="btn btn-outline-primary active col-6" id="typeTab1">
                                    <input type="radio" name="type" value="1" checked> საკონტრაქტო
                                </label>
                                <label class="btn btn-outline-primary col-6" id="typeTab2">
                                    <input type="radio" name="type" value="2"> არასაკონტრაქტო
                                </label>
                            </div>
                            
                            <!-- Form Fields -->
                            <form id="adminOrderForm">
                                <!-- Branch Selection for Type 1 -->
                                <div class="form-group" id="branchGroup">
                                    <label for="branch_select">ფილიალი</label>
                                    <select class="form-control" id="branch_select" name="branch_id">
                                        <option value="">აირჩიეთ ფილიალი...</option>
                                    </select>
                                    <div class="invalid-feedback" id="branchError"></div>
                                </div>
                                
                                <!-- Manual Input for Type 2 -->
                                <div class="form-group" id="subjNameGroup" style="display: none;">
                                    <label for="subj_name">სახელი</label>
                                    <input type="text" class="form-control" id="subj_name" name="subj_name" placeholder="შეიყვანეთ სახელი...">
                                    <div class="invalid-feedback" id="subjNameError"></div>
                                </div>
                                
                                <div class="form-group" id="subjAddressGroup" style="display: none;">
                                    <label for="subj_address">მისამართი</label>
                                    <input type="text" class="form-control" id="subj_address" name="subj_address" placeholder="შეიყვანეთ მისამართი...">
                                    <div class="invalid-feedback" id="subjAddressError"></div>
                                </div>
                                
                                <!-- Description Field -->
                                <div class="form-group">
                                    <label for="description">სამუშაოს აღწერა</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="აღწერეთ შესასრულებელი სამუშაო..."></textarea>
                                    <div class="invalid-feedback" id="descriptionError"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="closeModalFooterBtn">დახურვა</button>
                            <button type="button" class="btn btn-primary" id="submitOrder">
                                <span id="submitText">შექმნა</span>
                                <span id="submitSpinner" class="spinner-border spinner-border-sm ml-2" style="display: none;"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        this.modal = document.getElementById('adminOrderModal');
    }
    
    attachEventListeners() {
        // Type tab switching
        document.getElementById('typeTab1').addEventListener('click', () => this.switchType(1));
        document.getElementById('typeTab2').addEventListener('click', () => this.switchType(2));
        
        // Form submission
        document.getElementById('submitOrder').addEventListener('click', () => this.handleSubmit());
        
        // Close modal functionality
        document.getElementById('closeModalBtn').addEventListener('click', () => this.hide());
        document.getElementById('closeModalFooterBtn').addEventListener('click', () => this.hide());
        
        // Form validation on input
        ['description', 'subj_name', 'subj_address'].forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                element.addEventListener('input', () => this.clearError(field));
            }
        });
        
        document.getElementById('branch_select').addEventListener('change', () => this.clearError('branch_id'));
    }
    
    switchType(type) {
        this.activeType = type;
        this.formValues.type = type;
        this.clearAllErrors();
        
        if (type === 1) {
            document.getElementById('branchGroup').style.display = 'block';
            document.getElementById('subjNameGroup').style.display = 'none';
            document.getElementById('subjAddressGroup').style.display = 'none';
        } else {
            document.getElementById('branchGroup').style.display = 'none';
            document.getElementById('subjNameGroup').style.display = 'block';
            document.getElementById('subjAddressGroup').style.display = 'block';
        }
    }
    
    async loadBranches() {
        try {
            this.loadingBranches = true;
            
            // Get dates for the request (last 30 days)
            const today = new Date();
            const startDate = new Date(today);
            startDate.setDate(today.getDate() - 30);
            
            const response = await fetch('/api/clients/statistics', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    start_date: startDate.toISOString().split('T')[0],
                    end_date: today.toISOString().split('T')[0]
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                this.branches = data.branches || [];
                this.populateBranchSelect();
            } else {
                console.error('Failed to load branches:', response.status, response.statusText);
                // Still allow the modal to work with manual branch entry
                this.branches = [];
                this.populateBranchSelect();
            }
        } catch (error) {
            console.error('Error loading branches:', error);
            // Still allow the modal to work with manual branch entry
            this.branches = [];
            this.populateBranchSelect();
        } finally {
            this.loadingBranches = false;
        }
    }
    
    populateBranchSelect() {
        const select = document.getElementById('branch_select');
        select.innerHTML = '<option value="">აირჩიეთ ფილიალი...</option>';
        
        if (this.branches.length === 0) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'ფილიალები ვერ ჩაიტვირთა - გამოიყენეთ არასაკონტრაქტო ტიპი';
            option.disabled = true;
            select.appendChild(option);
        } else {
            this.branches.forEach(branch => {
                const option = document.createElement('option');
                option.value = branch.id;
                option.textContent = branch.subj_address || branch.subj_name || branch.name;
                select.appendChild(option);
            });
        }
    }
    
    validateForm() {
        const errors = {};
        
        const description = document.getElementById('description').value.trim();
        if (!description) {
            errors.description = 'აღწერა სავალდებულოა';
        } else if (description.length < 5) {
            errors.description = 'აღწერა უნდა იყოს მინიმუმ 5 სიმბოლო';
        }
        
        if (this.activeType === 1) {
            const branchId = document.getElementById('branch_select').value;
            if (!branchId) {
                errors.branch_id = 'ფილიალი სავალდებულოა';
            }
        } else {
            const subjName = document.getElementById('subj_name').value.trim();
            const subjAddress = document.getElementById('subj_address').value.trim();
            
            if (!subjName) {
                errors.subj_name = 'სახელი სავალდებულოა';
            }
            if (!subjAddress) {
                errors.subj_address = 'მისამართი სავალდებულოა';
            }
        }
        
        this.errors = errors;
        this.displayErrors();
        
        return Object.keys(errors).length === 0;
    }
    
    displayErrors() {
        // Clear all errors first
        this.clearAllErrors();
        
        // Display new errors
        Object.keys(this.errors).forEach(field => {
            const errorElement = document.getElementById(field === 'branch_id' ? 'branchError' : `${field}Error`);
            const inputElement = document.getElementById(field === 'branch_id' ? 'branch_select' : field);
            
            if (errorElement && inputElement) {
                errorElement.textContent = this.errors[field];
                inputElement.classList.add('is-invalid');
                errorElement.style.display = 'block';
            }
        });
    }
    
    clearError(field) {
        const errorElement = document.getElementById(field === 'branch_id' ? 'branchError' : `${field}Error`);
        const inputElement = document.getElementById(field === 'branch_id' ? 'branch_select' : field);
        
        if (errorElement && inputElement) {
            errorElement.textContent = '';
            inputElement.classList.remove('is-invalid');
            errorElement.style.display = 'none';
        }
        
        if (this.errors[field]) {
            delete this.errors[field];
        }
    }
    
    clearAllErrors() {
        ['description', 'branch_id', 'subj_name', 'subj_address'].forEach(field => {
            this.clearError(field);
        });
    }
    
    async handleSubmit() {
        if (this.submitting) return;
        
        if (!this.validateForm()) {
            return;
        }
        
        try {
            this.submitting = true;
            this.updateSubmitButton(true);
            this.hideStatusMessage();
            
            // Prepare request body
            const requestBody = {
                description: document.getElementById('description').value,
                type: this.activeType
            };
            
            if (this.activeType === 1) {
                requestBody.branch_id = document.getElementById('branch_select').value;
            } else {
                requestBody.subj_name = document.getElementById('subj_name').value;
                requestBody.subj_address = document.getElementById('subj_address').value;
            }
            
            const response = await fetch('/api/admin/client-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestBody)
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                this.showSuccess('შეკვეთა წარმატებით შეიქმნა!');
                this.resetForm();
                
                // Close modal and refresh the page after 1.5 seconds
                setTimeout(() => {
                    this.hide();
                    window.location.reload();
                }, 1500);
            } else {
                this.showError(data.message || 'შეკვეთის შექმნა ვერ მოხერხდა');
            }
        } catch (error) {
            console.error('Error creating order:', error);
            this.showError('შეკვეთის შექმნა ვერ მოხერხდა');
        } finally {
            this.submitting = false;
            this.updateSubmitButton(false);
        }
    }
    
    updateSubmitButton(loading) {
        const submitText = document.getElementById('submitText');
        const submitSpinner = document.getElementById('submitSpinner');
        const submitButton = document.getElementById('submitOrder');
        
        if (loading) {
            submitText.textContent = 'იქმნება...';
            submitSpinner.style.display = 'inline-block';
            submitButton.disabled = true;
        } else {
            submitText.textContent = 'შექმნა';
            submitSpinner.style.display = 'none';
            submitButton.disabled = false;
        }
    }
    
    showSuccess(message) {
        const statusMessage = document.getElementById('statusMessage');
        const statusText = document.getElementById('statusText');
        
        statusMessage.className = 'alert alert-success';
        statusText.textContent = message;
        statusMessage.style.display = 'block';
    }
    
    showError(message) {
        const statusMessage = document.getElementById('statusMessage');
        const statusText = document.getElementById('statusText');
        
        statusMessage.className = 'alert alert-danger';
        statusText.textContent = message;
        statusMessage.style.display = 'block';
    }
    
    hideStatusMessage() {
        const statusMessage = document.getElementById('statusMessage');
        statusMessage.style.display = 'none';
    }
    
    resetForm() {
        document.getElementById('adminOrderForm').reset();
        this.formValues = {
            description: '',
            branch_id: 0,
            type: 1,
            subj_name: '',
            subj_address: ''
        };
        this.clearAllErrors();
        this.switchType(1);
        
        // Reset type tabs
        document.getElementById('typeTab1').classList.add('active');
        document.getElementById('typeTab2').classList.remove('active');
        document.querySelector('input[name="type"][value="1"]').checked = true;
    }
    
    show() {
        // Use vanilla JavaScript instead of jQuery modal
        const modal = document.getElementById('adminOrderModal');
        if (modal) {
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
            
            // Create backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'adminOrderModalBackdrop';
            backdrop.addEventListener('click', () => this.hide());
            document.body.appendChild(backdrop);
        }
    }
    
    hide() {
        const modal = document.getElementById('adminOrderModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            // Remove backdrop
            const backdrop = document.getElementById('adminOrderModalBackdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    }
}

// Initialize the component when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.adminOrderModal = new AdminOrderModal();
});