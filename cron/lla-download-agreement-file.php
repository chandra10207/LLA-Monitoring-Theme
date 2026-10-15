
<?php
/**
 * Download a Gravity PDF and save it to uploads/gravity-pdf-downloads/.
 * Location: wp-content/themes/LiveLifeAlarms/cron/download-gravity-pdf.php
 */

// Load WordPress from the site root.
require_once dirname(__DIR__, 4) . '/wp-load.php';

// Source PDF URL.
$pdf_url = 'https://staging.monitoring.livelifealarms.com.au/pdf/6ab9cdaaa99c5/7/';

// Destination directory.
$upload_dir = wp_upload_dir();

if (!empty($upload_dir['error'])) {
    exit('Upload directory error: ' . $upload_dir['error']);
}

$destination_dir = trailingslashit($upload_dir['basedir'])
    . 'gravity-pdf-downloads/';

// Create directory if it does not exist.
if (!wp_mkdir_p($destination_dir)) {
    exit('Unable to create destination directory.');
}

// Destination filename.
$filename = 'gravity-pdf-7.pdf';
$destination = $destination_dir . $filename;

// Download the PDF.
$response = wp_remote_get($pdf_url, [
    'timeout'     => 60,
    'redirection' => 5,
    'sslverify'   => true,
]);

if (is_wp_error($response)) {
    exit('Download failed: ' . $response->get_error_message());
}

$status_code = wp_remote_retrieve_response_code($response);
$pdf_content = wp_remote_retrieve_body($response);

// Check HTTP response.
if ($status_code !== 200) {
    exit('Download failed. HTTP status: ' . $status_code);
}

// Verify PDF content.
if (strncmp($pdf_content, '%PDF-', 5) !== 0) {
    exit('The downloaded response is not a valid PDF.');
}

// Save the PDF.
$bytes_written = file_put_contents(
    $destination,
    $pdf_content,
    LOCK_EX
);

if ($bytes_written === false || $bytes_written !== strlen($pdf_content)) {
    if (file_exists($destination)) {
        unlink($destination);
    }

    exit('Failed to save the complete PDF.');
}

echo 'PDF downloaded successfully: '
    . esc_html($destination)
    . ' (' . esc_html(size_format($bytes_written)) . ')';
