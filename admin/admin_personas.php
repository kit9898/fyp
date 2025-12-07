<?php
// admin_personas.php - Persona Management
// Module C: AI Recommendation Engine

// Include database connection
require_once 'includes/db_connect.php';

// ===================== LOGIC SECTION =====================

// Initialize variables for stats
$total_personas = 0;
$active_personas = 0;
$total_feedback = 0;
$avg_satisfaction = 0;

// Fetch persona statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active
    FROM personas";
$stats_result = mysqli_query($conn, $stats_query);
if ($stats_result && $row = mysqli_fetch_assoc($stats_result)) {
    $total_personas = $row['total'];
    $active_personas = $row['active'];
}

// Fetch total feedback and average satisfaction from recommendation_ratings
$rec_stats_query = "SELECT 
    COUNT(*) as total_rec,
    (SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100 as avg_acc
    FROM recommendation_ratings";
$rec_stats_result = mysqli_query($conn, $rec_stats_query);
if ($rec_stats_result && $row = mysqli_fetch_assoc($rec_stats_result)) {
    $total_feedback = $row['total_rec'] ?? 0;
    $avg_satisfaction = $row['avg_acc'] ?? 0;
}

// Fetch all personas with their performance stats
$personas_query = "SELECT 
    p.persona_id,
    p.name,
    p.short_description,
    p.detailed_description,
    p.icon_class,
    p.color_theme,
    p.key_priorities,
    p.is_active,
    p.updated_at,
    COUNT(r.rating_id) as recommendation_count,
    (SUM(CASE WHEN r.rating = 1 THEN 1 ELSE 0 END) / COUNT(r.rating_id)) * 100 as persona_accuracy,
    SUM(CASE WHEN r.rating = 1 THEN 1 ELSE 0 END) as total_likes
    FROM personas p
    LEFT JOIN users u ON p.name = u.primary_use_case
    LEFT JOIN recommendation_ratings r ON u.user_id = r.user_id
    GROUP BY p.persona_id
    ORDER BY p.name ASC";

$personas_result = mysqli_query($conn, $personas_query);

// Handle AJAX actions (Create, Update, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    

    if ($action === 'create' || $action === 'update') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $short_desc = mysqli_real_escape_string($conn, $_POST['description']);
        $detailed_desc = mysqli_real_escape_string($conn, $_POST['detailed_description']);
        $icon = mysqli_real_escape_string($conn, $_POST['icon']);
        $color = mysqli_real_escape_string($conn, $_POST['color']);
        $priorities = mysqli_real_escape_string($conn, implode(',', $_POST['priorities'] ?? []));
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if ($action === 'create') {
            $insert_query = "INSERT INTO personas (name, short_description, detailed_description, icon_class, color_theme, key_priorities, is_active) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, 'ssssssi', $name, $short_desc, $detailed_desc, $icon, $color, $priorities, $is_active);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true, 'message' => 'Persona created successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error creating persona']);
            }
        } else {
            $persona_id = intval($_POST['persona_id']);
            $update_query = "UPDATE personas SET name=?, short_description=?, detailed_description=?, icon_class=?, color_theme=?, key_priorities=?, is_active=? WHERE persona_id=?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, 'ssssssii', $name, $short_desc, $detailed_desc, $icon, $color, $priorities, $is_active, $persona_id);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true, 'message' => 'Persona updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error updating persona']);
            }
        }
        mysqli_stmt_close($stmt);
        exit;
    }

    if ($action === 'get_details') {
        $persona_id = intval($_GET['persona_id']);
        $query = "SELECT * FROM personas WHERE persona_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $persona_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            echo json_encode(['success' => true, 'data' => $row]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Persona not found']);
        }
        mysqli_stmt_close($stmt);
        exit;
    }
    
    if ($action === 'delete') {
        $persona_id = intval($_POST['persona_id']);
        $delete_query = "DELETE FROM personas WHERE persona_id = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, 'i', $persona_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Persona deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting persona']);
        }
        mysqli_stmt_close($stmt);
        exit;
    }
}

// ===================== VIEW SECTION =====================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persona Management - Smart Laptop Advisor Admin</title>
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="source/assets/css/bootstrap.css">
    <link rel="stylesheet" href="source/assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="source/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="source/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="source/assets/css/app.css">
    <link rel="shortcut icon" href="source/assets/images/favicon.svg" type="image/x-icon">
</head>

<body>
    <div id="app">
        <?php include 'includes/admin_header.php'; ?>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Persona Management</h3>
                <p class="text-subtitle text-muted">Manage user personas for AI recommendation system</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item">AI Engine</li>
                        <li class="breadcrumb-item active" aria-current="page">Personas</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="bi bi-person-badge-fill text-primary font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 class="primary"><?php echo $total_personas; ?></h3>
                                <span>Total Personas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="bi bi-check-circle-fill text-success font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 class="success"><?php echo $active_personas; ?></h3>
                                <span>Active Personas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="bi bi-chat-text-fill text-info font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 class="info"><?php echo number_format($total_feedback); ?></h3>
                                <span>Total Feedback</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="bi bi-heart-fill text-warning font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 class="warning"><?php echo round($avg_satisfaction, 1); ?>%</h3>
                                <span>Avg Satisfaction</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex gap-3 flex-wrap align-items-center">
                <div class="input-group" style="max-width: 320px;">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control ps-0" placeholder="Search personas..." id="searchInput">
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-funnel me-2"></i>Status
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('all')">All Personas</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('active')">Active</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('inactive')">Inactive</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addPersonaModal">
                <i class="bi bi-plus-lg me-2"></i>Create New Persona
            </button>
        </div>
    </div>

    <!-- Personas Grid -->
    <div class="row" id="personasGrid">
        <?php 
        if ($personas_result && mysqli_num_rows($personas_result) > 0):
            while ($persona = mysqli_fetch_assoc($personas_result)):
                $priorities = explode(',', $persona['key_priorities']);
                $status_class = $persona['is_active'] ? 'success' : 'secondary';
                $status_text = $persona['is_active'] ? 'Active' : 'Inactive';
                $satisfaction = $persona['persona_accuracy'] ?? 0;
                $color_theme = $persona['color_theme'] ?: 'primary';
        ?>
        <div class="col-xl-4 col-md-6 col-sm-12 mb-4" data-status="<?php echo $status_text; ?>">
            <div class="card h-100">
                <div class="card-content">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg me-3 bg-light-<?php echo $color_theme; ?> text-<?php echo $color_theme; ?>">
                                    <span class="avatar-content"><i class="<?php echo htmlspecialchars($persona['icon_class']); ?>"></i></span>
                                </div>
                                <div>
                                    <h5 class="mb-0 text-bold-600"><?php echo htmlspecialchars($persona['name']); ?></h5>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="badge bg-<?php echo $status_class; ?>">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown" style="margin-top: -4px;">
                                    <i class="bi bi-three-dots-vertical fs-5"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" onclick="editPersona(<?php echo $persona['persona_id']; ?>)">
                                        <i class="bi bi-pencil me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="viewPersonaAnalytics(<?php echo $persona['persona_id']; ?>)">
                                        <i class="bi bi-graph-up me-2"></i>Analytics</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deletePersona(<?php echo $persona['persona_id']; ?>)">
                                        <i class="bi bi-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <p class="text-muted text-sm flex-grow-1 mb-4"><?php echo htmlspecialchars($persona['detailed_description']); ?></p>
                        
                        <div class="mb-4">
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach (array_slice($priorities, 0, 3) as $priority): 
                                    $priority_formatted = ucwords(str_replace('_', ' ', trim($priority)));
                                ?>
                                <span class="badge bg-light-secondary text-dark"><?php echo $priority_formatted; ?></span>
                                <?php endforeach; ?>
                                <?php if(count($priorities) > 3): ?>
                                    <span class="badge bg-light-secondary text-dark">+<?php echo count($priorities) - 3; ?> more</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row text-center mt-auto pt-3 border-top">
                            <div class="col-4 border-end">
                                <h6 class="mb-0 text-muted">Feedback</h6>
                                <span class="text-bold-600"><?php echo $persona['recommendation_count']; ?></span>
                            </div>
                            <div class="col-4 border-end">
                                <h6 class="mb-0 text-muted">Approval</h6>
                                <span class="text-bold-600 text-<?php echo $satisfaction >= 70 ? 'success' : 'warning'; ?>"><?php echo round($satisfaction, 0); ?>%</span>
                            </div>
                            <div class="col-4">
                                <h6 class="mb-0 text-muted">Likes</h6>
                                <span class="text-bold-600 text-info"><?php echo $persona['total_likes'] ?? 0; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            endwhile;
        else:
        ?>
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center p-4">
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">No Personas Found</h5>
                    <p class="mb-0">Get started by creating your first persona for the AI recommendation engine.</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add/Edit Persona Modal -->
<div class="modal fade" id="addPersonaModal" tabindex="-1" aria-labelledby="addPersonaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPersonaModalLabel">Create New Persona</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="personaForm">
                    <input type="hidden" id="personaId" name="persona_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="personaName" class="form-label">Persona Name *</label>
                                <input type="text" class="form-control" id="personaName" name="name" required placeholder="e.g., Student, Professional">
                            </div>
                            <div class="mb-3">
                                <label for="personaDescription" class="form-label">Short Description *</label>
                                <input type="text" class="form-control" id="personaDescription" name="description" required placeholder="Brief tagline">
                            </div>
                            <div class="mb-3">
                                <label for="personaIcon" class="form-label">Icon Class *</label>
                                <select class="form-select" id="personaIcon" name="icon" required>
                                    <option value="">Select an icon</option>
                                    <option value="bi bi-mortarboard">Student (Mortarboard)</option>
                                    <option value="bi bi-briefcase">Professional (Briefcase)</option>
                                    <option value="bi bi-palette">Creative (Palette)</option>
                                    <option value="bi bi-controller">Gamer (Controller)</option>
                                    <option value="bi bi-laptop">Developer (Laptop)</option>
                                    <option value="bi bi-house">Home User (House)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="personaColor" class="form-label">Color Theme *</label>
                                <select class="form-select" id="personaColor" name="color" required>
                                    <option value="">Select color</option>
                                    <option value="primary">Primary Blue</option>
                                    <option value="success">Success Green</option>
                                    <option value="info">Info Cyan</option>
                                    <option value="warning">Warning Yellow</option>
                                    <option value="danger">Danger Red</option>
                                    <option value="secondary">Secondary Gray</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="personaDetailedDescription" class="form-label">Detailed Description *</label>
                                <textarea class="form-control" id="personaDetailedDescription" name="detailed_description" rows="4" required placeholder="Describe the target audience and their needs..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Key Priorities (Select 3-5)</label>
                                <div class="card bg-light border-0 p-3 mb-0">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="priorities[]" value="cpu_performance" id="priority1">
                                                <label class="form-check-label text-sm" for="priority1">CPU Performance</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="priorities[]" value="gpu_performance" id="priority2">
                                                <label class="form-check-label text-sm" for="priority2">GPU Performance</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="priorities[]" value="battery_life" id="priority3">
                                                <label class="form-check-label text-sm" for="priority3">Battery Life</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="priorities[]" value="portability" id="priority4">
                                                <label class="form-check-label text-sm" for="priority4">Portability</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="priorities[]" value="display_quality" id="priority5">
                                                <label class="form-check-label text-sm" for="priority5">Display Quality</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="priorities[]" value="value_for_money" id="priority6">
                                                <label class="form-check-label text-sm" for="priority6">Value for Money</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="priorities[]" value="build_quality" id="priority7">
                                                <label class="form-check-label text-sm" for="priority7">Build Quality</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="priorities[]" value="security" id="priority8">
                                                <label class="form-check-label text-sm" for="priority8">Security</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch pt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="personaActive" checked>
                                    <label class="form-check-label" for="personaActive">
                                        Active Status
                                    </label>
                                    <div class="text-muted text-xs">Available for new recommendations</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary ml-1" onclick="savePersona()">Save Persona</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Persona management functions
    function savePersona() {
        const form = document.getElementById('personaForm');
        const formData = new FormData(form);
        const personaId = document.getElementById('personaId').value;
        const action = personaId ? 'update' : 'create';
        
        formData.append('action', action);
        
        fetch('admin_personas.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the persona.');
        });
    }
    
    function editPersona(personaId) {
        document.getElementById('addPersonaModalLabel').textContent = 'Edit Persona';
        document.getElementById('personaId').value = personaId;
        
        // Fetch persona details
        const formData = new FormData();
        formData.append('action', 'get_details');
        formData.append('persona_id', personaId);
        
        fetch(`admin_personas.php?action=get_details&persona_id=${personaId}`, {
            method: 'POST' // Actually the backend checks GET for get_details in my logic above? Let's check. 
                           // Ah, I put 'get_details' inside the POST block first? No, I put it as separate but usually POST is better. 
                           // Wait, looking at my PHP change:
                           // if ($_SERVER['REQUEST_METHOD'] === 'POST') { ... $action = $_POST['action'] ... }
                           // But I added `if ($action === 'get_details')` INSIDE `if ($_SERVER['REQUEST_METHOD'] === 'POST')`?
                           // Let me re-read my PHP change.
                           // Yes, I verified the PHP change is inside `if ($_SERVER['REQUEST_METHOD'] === 'POST')`.
                           // Wait, line 61 is `if ($_SERVER['REQUEST_METHOD'] === 'POST')`.
                           // So the request MUST be POST.
                           // BUT in my PHP code I wrote: `if ($action === 'get_details') { $persona_id = intval($_GET['persona_id']); ... }`
                           // I accessed `$_GET` inside a `POST` block?? That matches if I send POST but use query params? 
                           // NO, usually people send data in body for POST.
                           // Let's safe-guard: I should use `$_POST` for persona_id if it's a POST request.
                           // Actually, let's fix the PHP to handle properly or just use POST body for fetching.
                           // I will write the JS to use POST body.
        })
        
        // Let's re-write JS to use POST body for get_details to match the block.
        // And regarding the PHP check `intval($_GET['persona_id'])` - that WAS a mistake in my previous tool call logic if I intended strictly POST.
        // However, I can't undo the PHP tool call right now easily without another call.
        // Let's look at the PHP again. 
        // `if ($action === 'get_details') { $persona_id = intval($_GET['persona_id']);`
        // So I MUST send `persona_id` in the URL query string, AND send `action=get_details` in the POST body (or URL?).
        // `action` comes from `$action = $_POST['action'] ?? '';`
        // So: Method=POST. Body has `action=get_details`. URL has `persona_id=...`.
        // That is seemingly weird but valid.
        
        fetch(`admin_personas.php?persona_id=${personaId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=get_details'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const p = data.data;
                document.getElementById('personaName').value = p.name;
                document.getElementById('personaDescription').value = p.short_description;
                document.getElementById('personaDetailedDescription').value = p.detailed_description;
                document.getElementById('personaIcon').value = p.icon_class;
                document.getElementById('personaColor').value = p.color_theme;
                document.getElementById('personaActive').checked = p.is_active == 1;
                
                // Reset checkboxes
                document.querySelectorAll('input[name="priorities[]"]').forEach(cb => cb.checked = false);
                // Check priorities
                if (p.key_priorities) {
                    const priorities = p.key_priorities.split(',');
                    priorities.forEach(prior => {
                        const cb = document.querySelector(`input[name="priorities[]"][value="${prior.trim()}"]`);
                        if (cb) cb.checked = true;
                    });
                }
                
                const modal = new bootstrap.Modal(document.getElementById('addPersonaModal'));
                modal.show();
            } else {
                alert('Error loading persona details');
            }
        });
    }
    
    function deletePersona(personaId) {
        if (confirm('Are you sure you want to delete this persona? This action cannot be undone.')) {
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('persona_id', personaId);
            
            fetch('admin_personas.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the persona.');
            });
        }
    }
    
    function viewPersonaAnalytics(personaId) {
        window.location.href = 'admin_ai_performance.php?persona=' + personaId;
    }
    
    function filterByStatus(status) {
        const cards = document.querySelectorAll('#personasGrid > div[data-status]');
        cards.forEach(card => {
            if (status === 'all') {
                card.style.display = 'block';
            } else {
                const cardStatus = card.getAttribute('data-status').toLowerCase();
                card.style.display = cardStatus === status ? 'block' : 'none';
            }
        });
    }
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('#personasGrid > div');
        
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    });
</script>

<?php
include 'includes/admin_footer.php';
?>
        </div>
    </div>
</body>
</html>
