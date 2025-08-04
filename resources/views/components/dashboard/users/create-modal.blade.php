{{-- 
    Create User Modal Component
    
    Props:
    - roles: Collection of admin and contributor roles
--}}

@props(['roles'])

<div x-data="{ 
        open: false, 
        processing: false,
        errorMessage: '',
        init() {
            console.log('Modal component initialized');
            // Check if form elements are accessible
            setTimeout(() => {
                const nameInput = document.getElementById('name');
                const emailInput = document.getElementById('email');
                const roleSelect = document.getElementById('role');
                
                console.log('Name input found:', nameInput ? 'Yes' : 'No');
                console.log('Email input found:', emailInput ? 'Yes' : 'No');
                console.log('Role select found:', roleSelect ? 'Yes' : 'No');
            }, 500);
        },
        submitForm() {
            console.log('Form submission started');
            
            // Clear previous error messages
            this.errorMessage = '';
            
            // Directly collect form values without relying on the form element
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');
            const roleSelect = document.getElementById('role');
            
            // Check if all required inputs exist
            if (!nameInput || !emailInput || !passwordInput || !passwordConfirmInput || !roleSelect) {
                console.error('Required form elements not found');
                this.errorMessage = 'Some form elements are missing. Please try again.';
                return;
            }
            
            // Get values from inputs
            const name = nameInput.value;
            const email = emailInput.value;
            const password = passwordInput.value;
            const password_confirmation = passwordConfirmInput.value;
            const roleId = roleSelect && roleSelect.value !== '' ? roleSelect.value : null;            // Validate form data
            if (!name || !email || !password || !password_confirmation || !roleId) {
                this.errorMessage = 'Please fill in all required fields';
                return;
            }
            
            // Set processing state
            this.processing = true;
            console.log('Processing state set to true');
            
            // Create the data object
            const formObject = {
                name: name,
                email: email,
                password: password,
                password_confirmation: password_confirmation,
                roles: []
            };
            
            // Add role as array item
            if (roleId && roleId !== '') {
                formObject.roles.push(roleId);
                console.log('Role added:', roleId);
            } else {
                this.errorMessage = 'Please select a role for the user';
                this.processing = false;
                return;
            }
            
            // Log what we're sending to help with debugging
            console.log('Role ID being sent:', roleId);
            console.log('Full form data being sent:', formObject);
            
            console.log('Form data object:', formObject);
            
            // Submit the form using fetch
            console.log('Submitting to:', '{{ route('dashboard.users.store') }}');
            
            fetch('{{ route('dashboard.users.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formObject)
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.json().then(data => {
                        if (data.errors) {
                            // Format validation errors
                            const errorMessages = Object.values(data.errors).flat();
                            throw new Error(errorMessages.join('<br>'));
                        } else {
                            throw new Error(data.message || 'Server responded with an error: ' + response.status);
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show success message and reload
                    alert('User created successfully');
                    window.location.reload();
                } else {
                    // Show error message
                    this.errorMessage = data.message || 'Failed to create user. Please try again.';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.errorMessage = error.message;
            })
            .finally(() => {
                this.processing = false;
                console.log('Processing state set to false');
            });
        }
    }" @keydown.escape.window="open = false">
    <!-- Trigger Button -->
    <button type="button" @click="open = true" 
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Add User
    </button>

    <!-- Modal -->
    <div x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-400 opacity-50"></div>
        </div>

        <!-- Modal panel -->
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl"
                 @click.away="open = false">
                
                <!-- Modal header -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 text-white">
                    <div class="sm:flex sm:items-start">
                        
                        <!-- Title -->
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-white">Add New User</h3>
                            <div>
                                <p class="text-sm text-green-50">Create a new user account. Fill in the details below.</p>
                            </div>
                        </div>

                        <!-- Close button -->
                        <div class="absolute right-0 top-0 pr-4 pt-4 sm:block">
                            <button type="button" @click="open = false" 
                                    class="rounded-full bg-white/20 p-1 backdrop-blur-sm text-white hover:bg-white/40 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transition-all duration-200">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white px-4 pb-5 sm:p-6">   
                    <form id="createUserForm" class="space-y-5" action="{{ route('dashboard.users.store') }}" method="POST">
                        @csrf
                        <!-- Account Information Section -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 mb-2">
                            <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Account Information
                            </h4>
                            
                            <!-- Name and Email -->
                            <div class="grid grid-cols-6 gap-4">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="name" id="name" required placeholder="John Doe"
                                               class="pl-10 py-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="email" name="email" id="email" required placeholder="john@example.com"
                                               class="pl-10 py-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Section -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 mb-2">
                            <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Security
                            </h4>
                            
                            <div class="grid grid-cols-6 gap-4">
                                <!-- Password with icon -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                        <input type="password" name="password" id="password" required minlength="6"
                                               class="pl-10 py-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                               placeholder="••••••">
                                    </div>
                                </div>

                                <!-- Password Confirmation with icon -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required
                                               class="pl-10 py-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                               placeholder="••••••">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Roles Selection -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 mb-2">
                            <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                User Role
                            </h4>
                            <p class="text-xs text-gray-500 mb-3">Select a role for this user</p>
                            <div class="relative">
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    </div>
                                    <select id="role" name="roles[]" required 
                                            class="pl-10 py-3 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                        <option value="">Select a user role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ $role->display_name }} - 
                                                {{ $role->role_name === 'A' ? 'Full access to manage the system' : 'Can create and manage content' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
                    <!-- Error messages container -->
                    <div x-show="errorMessage !== ''" x-cloak
                         class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error: </strong>
                        <span class="block sm:inline" x-html="errorMessage"></span>
                        <button type="button" @click="errorMessage = ''" class="absolute top-0 right-0 mt-2 mr-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="sm:flex sm:flex-row-reverse sm:items-center">
                        <!-- JavaScript form submission -->
                        <button type="button" @click="submitForm()" :disabled="processing"
                                class="inline-flex w-full justify-center items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                            <span x-show="!processing">Create User</span>
                            <span x-show="processing" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Creating...
                            </span>
                        </button>
                        
                        <!-- Fallback form submission -->
                        <button type="submit" form="createUserForm"
                                class="mt-3 w-full justify-center rounded-md border border-green-500 bg-white px-4 py-2 text-base font-medium text-green-700 shadow-sm hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200" style="display: none;">
                            Submit Form
                        </button>
                        
                        <button type="button" @click="open = false"
                                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- No script needed here as the submitForm function is now part of Alpine.js data -->
</div>
