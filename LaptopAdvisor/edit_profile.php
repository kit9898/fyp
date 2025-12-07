<?php
require_once 'includes/auth_check.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch user information including address fields
$stmt = $conn->prepare("SELECT full_name, email, profile_image_url, primary_use_case, 
                               default_shipping_name, default_shipping_address, default_shipping_city, 
                               default_shipping_state, default_shipping_zip, default_shipping_country, 
                               default_shipping_phone 
                        FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Set default profile image if none exists
if (empty($user['profile_image_url'])) {
    $user['profile_image_url'] = 'images/default-avatar.png';
}
?>

<style>
.edit-profile-wrapper {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 15s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.3; }
}

.page-header-content {
    position: relative;
    z-index: 1;
}

.page-header h1 {
    margin: 0 0 10px 0;
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.page-header p {
    margin: 0;
    font-size: 1.1rem;
    color: rgba(255,255,255,0.9);
}

/* Alert Messages */
.alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        transform: translateY(-10px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.alert-success {
    background: #d1e7dd;
    color: #0f5132;
    border-left: 4px solid #0f5132;
}

.alert-danger {
    background: #f8d7da;
    color: #842029;
    border-left: 4px solid #842029;
}

/* Form Container */
.form-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

/* Profile Image Preview Section */
.profile-image-section {
    text-align: center;
    margin-bottom: 35px;
    padding: 30px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
}

.current-profile-image {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 5px solid white;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    object-fit: cover;
    margin: 0 auto 20px;
    display: block;
}

.image-upload-label {
    display: block;
    font-weight: 600;
    color: #495057;
    margin-bottom: 10px;
    font-size: 1rem;
}

/* Form Groups */
.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #495057;
    margin-bottom: 10px;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"],
.form-group select {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    font-family: inherit;
}

.form-group input[type="text"]:focus,
.form-group input[type="email"]:focus,
.form-group input[type="tel"]:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-group input[type="email"]:disabled {
    background: #f8f9fa;
    color: #6c757d;
    cursor: not-allowed;
}

.form-group select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23667eea' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 18px center;
    padding-right: 45px;
}

/* File Input Styling */
.file-input-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
    width: 100%;
}

.file-input-wrapper input[type="file"] {
    position: absolute;
    left: -9999px;
}

.file-input-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.file-input-label:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.file-name-display {
    margin-top: 10px;
    font-size: 0.875rem;
    color: #6c757d;
    font-style: italic;
}

/* Helper Text */
.form-group small {
    display: block;
    margin-top: 8px;
    color: #6c757d;
    font-size: 0.875rem;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 35px;
    padding-top: 25px;
    border-top: 2px solid #f0f0f0;
}

.btn {
    padding: 14px 32px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    flex: 1;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #6c757d;
    color: white;
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
}

/* Image Preview */
#imagePreview {
    display: none;
    margin-top: 20px;
}

#imagePreview img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 5px solid white;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    object-fit: cover;
}

/* Address Section Styles */
.address-toggle-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 20px;
    border-radius: 12px;
    margin: 25px 0;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}

.address-toggle-header:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-color: #667eea;
}

.address-toggle-header h3 {
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #495057;
    font-size: 1.1rem;
    font-weight: 600;
}

.toggle-icon {
    transition: transform 0.3s ease;
    font-size: 1.5rem;
}

.toggle-icon.rotated {
    transform: rotate(180deg);
}

.address-fields-container {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease;
}

.address-fields-container.expanded {
    max-height: 1000px;
}

.address-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
}

.address-grid .form-group {
    margin-bottom: 0;
}

.address-grid .full-width {
    grid-column: 1 / -1;
}

/* Responsive Design */
@media (max-width: 768px) {
    .edit-profile-wrapper {
        padding: 15px;
    }
    
    .page-header {
        padding: 30px 20px;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .form-card {
        padding: 25px 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
    
    .address-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="edit-profile-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1>✏️ Edit Profile</h1>
            <p>Update your personal information and preferences</p>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if(isset($_GET['status']) && $_GET['status'] == 'details_success'): ?>
        <div class="alert alert-success">
            <span>✅</span>
            <span>Details updated successfully!</span>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <span>⚠️</span>
            <span><?php echo htmlspecialchars($_GET['error']); ?></span>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="form-card">
        <form action="profile_process.php" method="post" enctype="multipart/form-data" id="editProfileForm">
            
            <!-- Profile Image Section -->
            <div class="profile-image-section">
                <img src="<?php echo htmlspecialchars($user['profile_image_url']); ?>" 
                     alt="Current Profile Picture" 
                     class="current-profile-image"
                     id="currentImage">
                
                <div id="imagePreview">
                    <p style="margin: 10px 0; font-weight: 600; color: #495057;">New Image Preview:</p>
                    <img id="previewImg" src="" alt="Preview">
                </div>
                
                <div class="file-input-wrapper">
                    <input type="file" 
                           id="profile_image" 
                           name="profile_image" 
                           accept="image/png, image/jpeg, image/jpg"
                           onchange="previewImage(event)">
                    <label for="profile_image" class="file-input-label">
                        <span>📷</span>
                        <span>Choose New Profile Picture</span>
                    </label>
                </div>
                <div class="file-name-display" id="fileName">No file chosen</div>
            </div>

            <!-- Full Name -->
            <div class="form-group">
                <label for="full_name">
                    <span>👤</span>
                    <span>Full Name</span>
                </label>
                <input type="text" 
                       id="full_name" 
                       name="full_name" 
                       value="<?php echo htmlspecialchars($user['full_name']); ?>" 
                       required
                       placeholder="Enter your full name">
            </div>

            <!-- Primary Use Case -->
            <div class="form-group">
                <label for="primary_use_case">
                    <span>🎯</span>
                    <span>Primary Interest</span>
                </label>
                <select id="primary_use_case" name="primary_use_case">
                    <option value="Student" <?php if($user['primary_use_case'] == 'Student') echo 'selected'; ?>>
                        🎓 Student
                    </option>
                    <option value="Professional" <?php if($user['primary_use_case'] == 'Professional' || $user['primary_use_case'] == 'Business') echo 'selected'; ?>>
                        💼 Professional
                    </option>
                    <option value="Creative" <?php if($user['primary_use_case'] == 'Creative') echo 'selected'; ?>>
                        🎨 Creative
                    </option>
                    <option value="Gamer" <?php if($user['primary_use_case'] == 'Gamer' || $user['primary_use_case'] == 'Gaming') echo 'selected'; ?>>
                        🎮 Gamer
                    </option>
                    <option value="Developer" <?php if($user['primary_use_case'] == 'Developer') echo 'selected'; ?>>
                        💻 Developer
                    </option>
                    <option value="Home User" <?php if($user['primary_use_case'] == 'Home User' || $user['primary_use_case'] == 'General Use') echo 'selected'; ?>>
                        🏠 Home User
                    </option>
                </select>
                <small>This helps us provide you with personalized product recommendations</small>
            </div>

            <!-- Email (Disabled) -->
            <div class="form-group">
                <label for="email">
                    <span>📧</span>
                    <span>Email Address</span>
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="<?php echo htmlspecialchars($user['email']); ?>" 
                       disabled>
                <small>Email cannot be changed</small>
            </div>

            <!-- Address Section Toggle -->
            <div class="address-toggle-header" onclick="toggleAddress()">
                <h3>
                    <span>🏠 Default Shipping Address (Optional)</span>
                    <span class="toggle-icon" id="toggleIcon">▼</span>
                </h3>
            </div>

            <!-- Collapsible Address Fields -->
            <div class="address-fields-container" id="addressFields">
                <div class="address-grid">
                    <!-- Recipient Name -->
                    <div class="form-group full-width">
                        <label for="default_shipping_name">
                            <span>👤</span>
                            <span>Recipient Name</span>
                        </label>
                        <input type="text" 
                               id="default_shipping_name" 
                               name="default_shipping_name" 
                               value="<?php echo htmlspecialchars($user['default_shipping_name'] ?? ''); ?>"
                               placeholder="Full name for shipping">
                    </div>

                    <!-- Street Address -->
                    <div class="form-group full-width">
                        <label for="default_shipping_address">
                            <span>📍</span>
                            <span>Street Address</span>
                        </label>
                        <input type="text" 
                               id="default_shipping_address" 
                               name="default_shipping_address" 
                               value="<?php echo htmlspecialchars($user['default_shipping_address'] ?? ''); ?>"
                               placeholder="123 Main Street, Apt 4B">
                    </div>

                    <!-- City -->
                    <div class="form-group">
                        <label for="default_shipping_city">
                            <span>🏙️</span>
                            <span>City</span>
                        </label>
                        <input type="text" 
                               id="default_shipping_city" 
                               name="default_shipping_city" 
                               value="<?php echo htmlspecialchars($user['default_shipping_city'] ?? ''); ?>"
                               placeholder="City">
                    </div>

                    <!-- State/Province -->
                    <div class="form-group">
                        <label for="default_shipping_state">
                            <span>🗺️</span>
                            <span>State/Province</span>
                        </label>
                        <input type="text" 
                               id="default_shipping_state" 
                               name="default_shipping_state" 
                               value="<?php echo htmlspecialchars($user['default_shipping_state'] ?? ''); ?>"
                               placeholder="State/Province">
                    </div>

                    <!-- ZIP/Postal Code -->
                    <div class="form-group">
                        <label for="default_shipping_zip">
                            <span>📮</span>
                            <span>ZIP/Postal Code</span>
                        </label>
                        <input type="text" 
                               id="default_shipping_zip" 
                               name="default_shipping_zip" 
                               value="<?php echo htmlspecialchars($user['default_shipping_zip'] ?? ''); ?>"
                               placeholder="12345">
                    </div>

                    <!-- Country -->
                    <div class="form-group">
                        <label for="default_shipping_country">
                            <span>🌍</span>
                            <span>Country</span>
                        </label>
                        <input type="text" 
                               id="default_shipping_country" 
                               name="default_shipping_country" 
                               value="<?php echo htmlspecialchars($user['default_shipping_country'] ?? ''); ?>"
                               placeholder="Country">
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group full-width">
                        <label for="default_shipping_phone">
                            <span>📞</span>
                            <span>Phone Number</span>
                        </label>
                        <input type="tel" 
                               id="default_shipping_phone" 
                               name="default_shipping_phone" 
                               value="<?php echo htmlspecialchars($user['default_shipping_phone'] ?? ''); ?>"
                               placeholder="+1 (555) 123-4567">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" name="update_details" class="btn btn-primary">
                    <span>💾</span>
                    <span>Save Changes</span>
                </button>
                <a href="profile.php" class="btn btn-secondary">
                    <span>←</span>
                    <span>Back to Profile</span>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle address section
function toggleAddress() {
    const addressFields = document.getElementById('addressFields');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (addressFields.classList.contains('expanded')) {
        addressFields.classList.remove('expanded');
        toggleIcon.classList.remove('rotated');
    } else {
        addressFields.classList.add('expanded');
        toggleIcon.classList.add('rotated');
    }
}

// Image preview functionality
function previewImage(event) {
    const file = event.target.files[0];
    const fileNameDisplay = document.getElementById('fileName');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        // Display file name
        fileNameDisplay.textContent = file.name;
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        fileNameDisplay.textContent = 'No file chosen';
        imagePreview.style.display = 'none';
    }
}

// Form validation
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    const fullName = document.getElementById('full_name').value.trim();
    
    if (fullName.length < 2) {
        e.preventDefault();
        alert('Please enter a valid full name (at least 2 characters)');
        return false;
    }
});

// Smooth scroll to alerts
window.addEventListener('load', function() {
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>

<?php include 'includes/footer.php'; ?>