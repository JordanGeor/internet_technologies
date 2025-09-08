<?php
// youtube_search.php

require_once 'auth_check.php';
require_once 'config.php';

$apiKey = 'AIzaSyBskn7Ph4hbocNG9TKjgaE1SmQAHP9woXQ';
$results = [];
$query = '';

if (isset($_GET['q'])) {
    $query = trim($_GET['q']);
    if ($query !== '') {
        $queryEncoded = urlencode($query);
        $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=5&q={$queryEncoded}&key={$apiKey}";
        
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (isset($data['items'])) {
            $results = $data['items'];
        }
    }
}
?>

<h2>🔍 Αναζήτηση Video στο YouTube</h2>
<form method="get">
    <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Π.χ. Lo-fi music" required>
    <button type="submit">Αναζήτηση</button>
</form>

<?php if (!empty($results)): ?>
    <h3>Αποτελέσματα:</h3>
    <?php foreach ($results as $video):
        $videoId = $video['id']['videoId'];
        $title = $video['snippet']['title'];
        $thumbnail = $video['snippet']['thumbnails']['default']['url'];
    ?>
        <div style="margin-bottom: 20px;">
            <strong><?= htmlspecialchars($title) ?></strong><br>
            <img src="<?= $thumbnail ?>" alt="Thumbnail"><br>
            <a href="add_item.php?video_id=<?= urlencode($videoId) ?>&title=<?= urlencode($title) ?>">
                ➕ Προσθήκη σε λίστα
            </a>
        </div>
    <?php endforeach; ?>
<?php elseif ($query !== ''): ?>
    <p>Δεν βρέθηκαν αποτελέσματα για "<?= htmlspecialchars($query) ?>".</p>
<?php endif; ?>

<a href="lists.php">⬅️ Πίσω στις λίστες</a>
