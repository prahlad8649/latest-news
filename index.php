<?php
// API Key from NewsAPI
$apiKey = '21c8d2a5b71d4b2ab100fcdfabb42e0c'; // Replace with your actual API key
$country = 'us'; // Country code for the desired region

// URL for NewsAPI (top headlines)
$apiUrl = "https://newsapi.org/v2/top-headlines?country=$country&apiKey=$apiKey";

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: MyNewsApp/1.0 (prahladtest2004@gmail.com)'
]);

// Execute cURL
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
    exit();
}

// Close cURL
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);

// Fetch the first article for the modal
$firstArticle = $data['status'] === 'ok' && count($data['articles']) > 0 ? $data['articles'][0] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced News App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #f0f8ff, #ffffff);
            font-family: 'Arial', sans-serif;
        }

        footer {
            background: #007bff;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        footer a {
            color: #f0f8ff;
            text-decoration: underline;
        }

        footer a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="bg-primary text-white py-3">
        <nav class="navbar navbar-expand-lg navbar-dark container">
            <a class="navbar-brand" href="#">NewsApp</a>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container my-4" id="latest-news">
        <h1 class="text-center">🌟 Latest Headlines 🌟</h1>
        <div class="row">
            <?php if ($data['status'] === 'ok' && count($data['articles']) > 0): ?>
                <?php foreach ($data['articles'] as $article): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card news-item">
                            <?php if (!empty($article['urlToImage'])): ?>
                                <img src="<?= htmlspecialchars($article['urlToImage']) ?>" alt="News Image" class="card-img-top">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x180" alt="Placeholder Image" class="card-img-top">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5><?= htmlspecialchars($article['title']) ?></h5>
                                <p><?= htmlspecialchars($article['description'] ?? 'No description available.') ?></p>
                                <p><small class="text-muted">Source: <?= htmlspecialchars($article['source']['name'] ?? 'Unknown') ?></small></p>
                                <a href="<?= htmlspecialchars($article['url']) ?>" target="_blank" class="btn btn-primary btn-sm">Read More</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No news articles found for today.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>Powered by <a href="https://newsapi.org/" target="_blank">NewsAPI</a>. Developed by Prahlad.</p>
    </footer>

    <!-- Modal -->
    <?php if ($firstArticle): ?>
        
    <div class="modal fade" id="newsModal" tabindex="-1" aria-labelledby="newsModalLabel" aria-hidden="true">
    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newsModalLabel"><?= htmlspecialchars($firstArticle['title']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($firstArticle['urlToImage'])): ?>
                        <img src="<?= htmlspecialchars($firstArticle['urlToImage']) ?>" alt="News Image" class="img-fluid mb-3">
                    <?php endif; ?>
                    <p><?= htmlspecialchars($firstArticle['description'] ?? 'No description available.') ?></p>
                    <p><small class="text-muted">Source: <?= htmlspecialchars($firstArticle['source']['name'] ?? 'Unknown') ?></small></p>
                </div>
                <div class="modal-footer">
                    <a href="<?= htmlspecialchars($firstArticle['url']) ?>" target="_blank" class="btn btn-primary">Read Full Article</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show the modal on page load
        document.addEventListener('DOMContentLoaded', function () {
            var newsModal = new bootstrap.Modal(document.getElementById('newsModal'));
            newsModal.show();
        });
    </script>
</body>
</html>
