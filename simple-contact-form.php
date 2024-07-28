<?php
/*
Plugin Name: Simple Contact Form
Description: A simple contact form that can be added via shortcode
Version: 1.0
Author: Your Name
*/

// Add shortcode
function simple_contact_form_shortcode() {
    $form = '
    <form method="post" action="">
        <p>
            <label for="name">Name:</label>
            <input type="text" name="name" required>
        </p>
        <p>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </p>
        <p>
            <label for="message">Message:</label>
            <textarea name="message" required></textarea>
        </p>
        <p>
            <input type="submit" name="simple_contact_submit" value="Send">
        </p>
    </form>
    ';
    
    return $form;
}
add_shortcode('simple_contact_form', 'simple_contact_form_shortcode');

// Handle form submission
function handle_contact_form_submission() {
    if (isset($_POST['simple_contact_submit'])) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);
        
        $to = get_option('admin_email');
        $subject = 'New Contact Form Submission';
        $body = "Name: $name\n\nEmail: $email\n\nMessage: $message";
        
        wp_mail($to, $subject, $body);
        
        // Redirect to avoid form resubmission
        wp_redirect($_SERVER['HTTP_REFERER'] . '?message=success');
        exit;
    }
}
add_action('init', 'handle_contact_form_submission');

// Display success message
function display_success_message() {
    if (isset($_GET['message']) && $_GET['message'] == 'success') {
        echo '<p style="color: green;">Your message has been sent successfully!</p>';
    }
}
add_action('wp_body_open', 'display_success_message');