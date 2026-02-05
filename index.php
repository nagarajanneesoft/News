<?php

$rssUrl = "https://tamil.oneindia.com/rss/feeds/tamil-news-fb.xml";

/* Fetch RSS securely */
$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $rssUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 10,

    // VERY IMPORTANT
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,

    CURLOPT_HTTPHEADER => [
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
    ]
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("RSS Fetch Error: " . curl_error($ch));
}

curl_close($ch);

/* Parse XML safely */
libxml_use_internal_errors(true);
$rss = simplexml_load_string($response);

?>

<!DOCTYPE html>
<html lang="ta">
<head>
<meta charset="UTF-8">
<title>தமிழ் செய்திகள்</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
  background:#f5f7fa;
  font-family: Arial;
}

.header {
  background:#000;
  color:white;
  padding:20px;
  text-align:center;
  margin-bottom:30px;
}

.news-card {
  border-radius:12px;
  overflow:hidden;
  box-shadow:0 4px 12px rgba(0,0,0,0.1);
  transition:0.3s;
  background:white;
}

.news-card:hover {
  transform:translateY(-5px);
}

.news-img {
  height:200px;
  object-fit:cover;
  width:100%;
}

.news-content {
  padding:15px;
}

.news-title {
  font-size:18px;
  font-weight:bold;
}
</style>
</head>

<body>

<div class="header">
  <h2>📰 தமிழ் செய்திகள்</h2>
  <p>Latest Tamil Nadu News</p>
</div>

<div class="container">
<div class="row">

<?php

if (!$rss) {
    echo "<h4>Unable to load Tamil news currently.</h4>";
} else {

foreach ($rss->channel->item as $news) {

    preg_match('/src="([^"]+)"/', $news->description, $img);
    $image = $img[1] ?? '';

    echo '
    <div class="col-md-4 mb-4">
      <div class="news-card">

        '.($image ? "<img src='$image' class='news-img'>" : "").'

        <div class="news-content">
          <div class="news-title">'.$news->title.'</div>
          <a href="'.$news->link.'" target="_blank"
             class="btn btn-danger mt-2">Read More</a>
        </div>

      </div>
    </div>';
}
}

?>

</div>
</div>

</body>
</html>
