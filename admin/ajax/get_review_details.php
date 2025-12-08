<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    echo '<p class="text-danger">Unauthorized</p>';
    exit;
}

$review_id = intval($_GET['review_id'] ?? 0);
if ($review_id <= 0) {
    echo '<p class="text-danger">Invalid Review ID</p>';
    exit;
}

// Fetch review details including product image
$sql = "SELECT r.*, u.full_name, p.product_name, p.image_url 
        FROM product_reviews r 
        LEFT JOIN users u ON r.user_id = u.user_id 
        LEFT JOIN products p ON r.product_id = p.product_id 
        WHERE r.review_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $review_id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();

if (!$review) {
    echo '<p class="text-danger">Review not found</p>';
    exit;
}

// Fetch media
$media_sql = "SELECT * FROM review_media WHERE review_id = ?";
$media_stmt = $conn->prepare($media_sql);
$media_stmt->bind_param("i", $review_id);
$media_stmt->execute();
$media_result = $media_stmt->get_result();
$media_items = [];
while ($row = $media_result->fetch_assoc()) {
    $media_items[] = $row;
}
?>

<div class="row">
    <div class="col-md-4 text-center">
        <?php 
            $img_path = $review['image_url'];
            if (!empty($img_path) && strpos($img_path, 'LaptopAdvisor/') === false) {
                // Prepend LaptopAdvisor/ to any path missing it (images/ or uploads/)
                $img_path = 'LaptopAdvisor/' . ltrim($img_path, '/');
            }
            $final_src = '../' . $img_path;
        ?>
        <img src="<?= htmlspecialchars($final_src) ?>" class="img-fluid rounded mb-2" style="max-height: 150px; object-fit: contain;" onerror="this.src='source/assets/images/faces/2.jpg'">
        <h6 class="fw-bold"><?= htmlspecialchars($review['product_name']) ?></h6>
        <p class="text-muted small">Reviewed by: <?= htmlspecialchars($review['full_name']) ?></p>
        <div class="mb-2">
            <?php for($i=1; $i<=5; $i++): ?>
                <i class="bi bi-star-fill <?= $i <= $review['rating'] ? 'text-warning' : 'text-secondary' ?>"></i>
            <?php endfor; ?>
        </div>
        <p class="text-muted small"><?= date('F j, Y, g:i a', strtotime($review['created_at'])) ?></p>
    </div>
    
    <div class="col-md-8">
        <h6>Review Content</h6>
        <p class="p-3 bg-light text-dark rounded border"><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
        
        <?php if (!empty($media_items)): ?>
            <h6>Attached Media</h6>
            <div class="d-flex gap-2 flex-wrap mb-3">
                <?php foreach ($media_items as $item): 
                     // Adjust path if needed. Stored as uploads/reviews/...
                     $path_str = $item['file_path'];
                     if (strpos($path_str, 'uploads/') === 0 && strpos($path_str, 'LaptopAdvisor/') === false) {
                         $path_str = 'LaptopAdvisor/' . $path_str;
                     }
                     $path = '../' . $path_str;
                ?>
                    <div class="border rounded p-1" style="width: 100px; height: 100px; overflow: hidden; position: relative;">
                        <a href="<?= $path ?>" target="_blank">
                            <?php if ($item['media_type'] == 'video'): ?>
                                <video src="<?= $path ?>" style="width: 100%; height: 100%; object-fit: cover;"></video>
                                <span class="position-absolute top-50 start-50 translate-middle text-white" style="text-shadow: 0 0 2px black;">▶</span>
                            <?php else: ?>
                                <img src="<?= $path ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <hr>
        
        <h6>Admin Response</h6>
        <form id="responseForm">
            <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
            <div class="mb-3">
                <textarea class="form-control" name="response_text" rows="4" placeholder="Write your response here..."><?= htmlspecialchars($review['admin_response'] ?? '') ?></textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Post Response</button>
            </div>
        </form>
    </div>
</div>
