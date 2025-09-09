// Admin Order Creation Modal Component
class AdminOrderModal {
    constructor() {
        this.modal = null;
        this.activeType = 1;
        this.branches = [];
        this.users = [];
        this.selectedBranch = null;
        this.selectedUser = null;
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
        this.loadUsers();
    }
    
    createModal() {
        const modalHTML = `
            <style>
                .modal-backdrop.show {
                    opacity: 0.5 !important;
                    background-color: #000 !important;
                }
                .modal.show {
                    display: block !important;
                }
                .modal-open {
                    overflow: hidden !important;
                }
                #adminOrderModal {
                    z-index: 1050 !important;
                }
                #adminOrderModalBackdrop {
                    z-index: 1040 !important;
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: 100% !important;
                    height: 100% !important;
                    background-color: rgba(0, 0, 0, 0.5) !important;
                }
                .branch-selector-container, .user-selector-container {
                    position: relative;
                }
                .branch-dropdown, .user-dropdown {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    background: white;
                    border: 1px solid #ced4da;
                    border-top: none;
                    border-radius: 0 0 0.25rem 0.25rem;
                    max-height: 300px;
                    z-index: 1000;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                .branch-search-container, .user-search-container {
                    padding: 10px;
                    border-bottom: 1px solid #eee;
                }
                .branch-list, .user-list {
                    max-height: 250px;
                    overflow-y: auto;
                }
                .branch-item, .user-item {
                    padding: 12px;
                    border-bottom: 1px solid #f8f9fa;
                    cursor: pointer;
                    transition: background-color 0.2s;
                }
                .branch-item:hover, .user-item:hover {
                    background-color: #f8f9fa;
                }
                .branch-item:last-child, .user-item:last-child {
                    border-bottom: none;
                }
                .branch-item-name {
                    font-weight: 600;
                    color: #495057;
                    font-size: 14px;
                }
                .branch-item-address {
                    color: #6c757d;
                    font-size: 13px;
                    margin-top: 2px;
                }
                .branch-item-id {
                    color: #868e96;
                    font-size: 12px;
                    margin-top: 2px;
                }
                .branch-item-selected, .user-item-selected {
                    background-color: #e3f2fd;
                    border-left: 3px solid #2196f3;
                }
                .user-item-name {
                    font-weight: 600;
                    color: #495057;
                    font-size: 14px;
                }
                .user-item-email {
                    color: #6c757d;
                    font-size: 13px;
                    margin-top: 2px;
                }
                .user-item-id {
                    color: #868e96;
                    font-size: 12px;
                    margin-top: 2px;
                }
                
                /* Mobile responsive styles */
                @media (max-width: 768px) {
                    #adminOrderModal .modal-dialog {
                        margin: 0;
                        max-width: 100%;
                        height: 100vh;
                        width: 100vw;
                    }
                    
                    #adminOrderModal .modal-content {
                        height: 100vh;
                        border-radius: 0;
                        border: none;
                    }
                    
                    #adminOrderModal .modal-body {
                        padding: 15px;
                        overflow-y: auto;
                        flex: 1;
                    }
                    
                    #adminOrderModal .modal-header {
                        padding: 15px;
                        flex-shrink: 0;
                    }
                    
                    #adminOrderModal .modal-footer {
                        padding: 15px;
                        flex-shrink: 0;
                    }
                    
                    .branch-dropdown, .user-dropdown {
                        position: fixed !important;
                        top: 0 !important;
                        left: 0 !important;
                        right: 0 !important;
                        bottom: 0 !important;
                        max-height: none !important;
                        z-index: 1060 !important;
                        border-radius: 0 !important;
                    }
                    
                    .branch-list, .user-list {
                        max-height: calc(100vh - 100px) !important;
                    }
                }
            </style>
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
                                    <div class="branch-selector-container">
                                        <input type="text" class="form-control" id="branchSearch" placeholder="ძიება ფილიალებში..." readonly>
                                        <div id="branchDropdown" class="branch-dropdown" style="display: none;">
                                            <div class="branch-search-container">
                                                <input type="text" class="form-control form-control-sm" id="branchSearchInput" placeholder="ძიება...">
                                            </div>
                                            <div id="branchList" class="branch-list">
                                                <!-- Branch items will be populated here -->
                                            </div>
                                        </div>
                                    </div>
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
                                
                                <!-- User Selection Field -->
                                <div class="form-group">
                                    <label for="user_select">შეკვეთის მფლობელი</label>
                                    <div class="user-selector-container">
                                        <input type="text" class="form-control" id="userSearch" placeholder="ძიება მომხმარებლებში..." readonly>
                                        <div id="userDropdown" class="user-dropdown" style="display: none;">
                                            <div class="user-search-container">
                                                <input type="text" class="form-control form-control-sm" id="userSearchInput" placeholder="ძიება...">
                                            </div>
                                            <div id="userList" class="user-list">
                                                <!-- User items will be populated here -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback" id="userError"></div>
                                    <small class="form-text text-muted">შეკვეთის მფლობელი იქნება ამ მომხმარებელის სახელზე</small>
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
        ['description', 'subj_name', 'subj_address', 'user_id'].forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                element.addEventListener('input', () => this.clearError(field));
            }
        });
        
        // User selector events
        document.getElementById('userSearch').addEventListener('click', () => this.toggleUserDropdown());
        document.getElementById('userSearchInput').addEventListener('input', (e) => this.filterUsers(e.target.value));
        
        // Branch selector events
        document.getElementById('branchSearch').addEventListener('click', () => this.toggleBranchDropdown());
        document.getElementById('branchSearchInput').addEventListener('input', (e) => this.filterBranches(e.target.value));
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.branch-selector-container')) {
                this.closeBranchDropdown();
            }
            if (!e.target.closest('.user-selector-container')) {
                this.closeUserDropdown();
            }
        });
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
            
            const response = await fetch('/api/purchasers', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                // Handle both direct array and paginated response
                this.branches = data.data || data || [];
                this.populateBranchList();
            } else {
                console.error('Failed to load branches:', response.status, response.statusText);
                // Still allow the modal to work with manual branch entry
                this.branches = [];
                this.populateBranchList();
            }
        } catch (error) {
            console.error('Error loading branches:', error);
            // Still allow the modal to work with manual branch entry
            this.branches = [];
            this.populateBranchList();
        } finally {
            this.loadingBranches = false;
        }
    }
    
    async loadUsers() {
        try {
            const response = await fetch('/api/users', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.users = data.data || data || [];
                this.populateUserList();
            } else {
                console.error('Failed to load users:', response.status, response.statusText);
                this.users = [];
                this.populateUserList();
            }
        } catch (error) {
            console.error('Error loading users:', error);
            this.users = [];
            this.populateUserList();
        }
    }
    
    populateUserList(filteredUsers = null) {
        const userList = document.getElementById('userList');
        const users = filteredUsers || this.users;
        
        userList.innerHTML = '';
        
        if (users.length === 0) {
            const emptyItem = document.createElement('div');
            emptyItem.className = 'user-item';
            emptyItem.innerHTML = `
                <div class="user-item-name">მომხმარებლები ვერ ჩაიტვირთა</div>
                <div class="user-item-email">სცადეთ თავიდან ჩატვირთვა</div>
            `;
            userList.appendChild(emptyItem);
        } else {
            users.forEach(user => {
                const userItem = document.createElement('div');
                userItem.className = 'user-item';
                userItem.dataset.userId = user.id;
                
                userItem.innerHTML = `
                    <div class="user-item-name">${user.name || 'N/A'}</div>
                    <div class="user-item-email">${user.email || 'ელ. ფოსტა არ არის მითითებული'}</div>
                    <div class="user-item-id">ID: ${user.id}</div>
                `;
                
                userItem.addEventListener('click', () => this.selectUser(user));
                userList.appendChild(userItem);
            });
        }
    }
    
    toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        const isVisible = dropdown.style.display === 'block';
        
        if (isVisible) {
            this.closeUserDropdown();
        } else {
            dropdown.style.display = 'block';
            this.populateUserList();
            document.getElementById('userSearchInput').focus();
        }
    }
    
    closeUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.style.display = 'none';
        document.getElementById('userSearchInput').value = '';
    }
    
    filterUsers(searchTerm) {
        if (!searchTerm.trim()) {
            this.populateUserList();
            return;
        }
        
        const filtered = this.users.filter(user => {
            const searchText = searchTerm.toLowerCase();
            return (
                (user.name || '').toLowerCase().includes(searchText) ||
                (user.email || '').toLowerCase().includes(searchText) ||
                user.id.toString().includes(searchText)
            );
        });
        
        this.populateUserList(filtered);
    }
    
    selectUser(user) {
        this.selectedUser = user;
        
        const userSearch = document.getElementById('userSearch');
        userSearch.value = `${user.name} - ${user.email || 'ID: ' + user.id}`;
        
        // Update selected state
        document.querySelectorAll('.user-item').forEach(item => {
            item.classList.remove('user-item-selected');
        });
        document.querySelector(`[data-user-id="${user.id}"]`)?.classList.add('user-item-selected');
        
        this.closeUserDropdown();
        this.clearError('user_id');
    }
    
    populateBranchList(filteredBranches = null) {
        const branchList = document.getElementById('branchList');
        const branches = filteredBranches || this.branches;
        
        branchList.innerHTML = '';
        
        if (branches.length === 0) {
            const emptyItem = document.createElement('div');
            emptyItem.className = 'branch-item';
            emptyItem.innerHTML = `
                <div class="branch-item-name">ფილიალები ვერ ჩაიტვირთა</div>
                <div class="branch-item-address">გამოიყენეთ არასაკონტრაქტო ტიპი</div>
            `;
            branchList.appendChild(emptyItem);
        } else {
            branches.forEach(branch => {
                const branchItem = document.createElement('div');
                branchItem.className = 'branch-item';
                branchItem.dataset.branchId = branch.id;
                
                branchItem.innerHTML = `
                    <div class="branch-item-name">${branch.name || 'N/A'}</div>
                    <div class="branch-item-address">${branch.subj_address || 'მისამართი არ არის მითითებული'}</div>
                    <div class="branch-item-id">ID: ${branch.id} | ${branch.subj_name || 'N/A'}</div>
                `;
                
                branchItem.addEventListener('click', () => this.selectBranch(branch));
                branchList.appendChild(branchItem);
            });
        }
    }
    
    toggleBranchDropdown() {
        const dropdown = document.getElementById('branchDropdown');
        const isVisible = dropdown.style.display === 'block';
        
        if (isVisible) {
            this.closeBranchDropdown();
        } else {
            dropdown.style.display = 'block';
            this.populateBranchList();
            document.getElementById('branchSearchInput').focus();
        }
    }
    
    closeBranchDropdown() {
        const dropdown = document.getElementById('branchDropdown');
        dropdown.style.display = 'none';
        document.getElementById('branchSearchInput').value = '';
    }
    
    filterBranches(searchTerm) {
        if (!searchTerm.trim()) {
            this.populateBranchList();
            return;
        }
        
        const filtered = this.branches.filter(branch => {
            const searchText = searchTerm.toLowerCase();
            return (
                (branch.name || '').toLowerCase().includes(searchText) ||
                (branch.subj_address || '').toLowerCase().includes(searchText) ||
                (branch.subj_name || '').toLowerCase().includes(searchText) ||
                branch.id.toString().includes(searchText)
            );
        });
        
        this.populateBranchList(filtered);
    }
    
    selectBranch(branch) {
        this.selectedBranch = branch;
        this.formValues.branch_id = branch.id;
        
        const branchSearch = document.getElementById('branchSearch');
        branchSearch.value = `${branch.name} - ${branch.subj_address || branch.subj_name}`;
        
        // Update selected state
        document.querySelectorAll('.branch-item').forEach(item => {
            item.classList.remove('branch-item-selected');
        });
        document.querySelector(`[data-branch-id="${branch.id}"]`)?.classList.add('branch-item-selected');
        
        this.closeBranchDropdown();
        this.clearError('branch_id');
    }
    
    validateForm() {
        const errors = {};
        
        const description = document.getElementById('description').value.trim();
        if (!description) {
            errors.description = 'აღწერა სავალდებულოა';
        } else if (description.length < 5) {
            errors.description = 'აღწერა უნდა იყოს მინიმუმ 5 სიმბოლო';
        }
        
        if (!this.selectedUser) {
            errors.user_id = 'მომხმარებელი სავალდებულოა';
        }
        
        if (this.activeType === 1) {
            if (!this.selectedBranch || !this.formValues.branch_id) {
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
            let errorElement, inputElement;
            
            if (field === 'branch_id') {
                errorElement = document.getElementById('branchError');
                inputElement = document.getElementById('branchSearch');
            } else if (field === 'user_id') {
                errorElement = document.getElementById('userError');
                inputElement = document.getElementById('userSearch');
            } else {
                errorElement = document.getElementById(`${field}Error`);
                inputElement = document.getElementById(field);
            }
            
            if (errorElement && inputElement) {
                errorElement.textContent = this.errors[field];
                inputElement.classList.add('is-invalid');
                errorElement.style.display = 'block';
            }
        });
    }
    
    clearError(field) {
        let errorElement, inputElement;
        
        if (field === 'branch_id') {
            errorElement = document.getElementById('branchError');
            inputElement = document.getElementById('branchSearch');
        } else if (field === 'user_id') {
            errorElement = document.getElementById('userError');
            inputElement = document.getElementById('userSearch');
        } else {
            errorElement = document.getElementById(`${field}Error`);
            inputElement = document.getElementById(field);
        }
        
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
        ['description', 'branch_id', 'subj_name', 'subj_address', 'user_id'].forEach(field => {
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
                type: this.activeType,
                user_id: this.selectedUser?.id || null
            };
            
            if (this.activeType === 1) {
                requestBody.branch_id = this.selectedBranch?.id || null;
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
        this.selectedBranch = null;
        this.selectedUser = null;
        document.getElementById('branchSearch').value = '';
        document.getElementById('userSearch').value = '';
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
            modal.style.zIndex = '1050';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
            
            // Create backdrop
            const backdrop = document.createElement('div');
            backdrop.id = 'adminOrderModalBackdrop';
            backdrop.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: block;
            `;
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