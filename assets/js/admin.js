jQuery(document).ready(function($) {
    'use strict';
    
    $('#wp-url-update-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitBtn = $('#wp-url-update-submit');
        var $results = $('#wp-url-update-results');
        var $progress = $('#wp-url-update-progress');
        var $log = $('#wp-url-update-log');
        
        // Get form values
        var oldUrl = $('#old_url').val().trim();
        var newUrl = $('#new_url').val().trim();
        var dryRun = $('#dry_run').is(':checked');
        
        // Remove trailing slashes
        oldUrl = oldUrl.replace(/\/$/, '');
        newUrl = newUrl.replace(/\/$/, '');
        
        // Validate URLs
        if (!oldUrl || !newUrl) {
            alert('Please enter both old and new URLs.');
            return;
        }
        
        if (oldUrl === newUrl) {
            alert('Old and new URLs cannot be the same.');
            return;
        }
        
        // Confirm action
        var confirmMessage = dryRun 
            ? 'Run a dry run test to preview changes from:\n\n' + oldUrl + '\n\nto:\n\n' + newUrl + '\n\nNo changes will be made to your database.'
            : 'Are you sure you want to update URLs from:\n\n' + oldUrl + '\n\nto:\n\n' + newUrl + '\n\nThis action cannot be undone! Make sure you have a backup.';
            
        if (!confirm(confirmMessage)) {
            return;
        }
        
        // Disable submit button and show progress
        $submitBtn.prop('disabled', true).text(dryRun ? 'Testing...' : 'Updating...');
        $results.show();
        $progress.html('<div class="notice notice-info"><p>' + (dryRun ? 'Running test...' : 'Processing URL updates...') + '</p></div>');
        $log.empty();
        
        // Prepare data
        var data = {
            action: 'wp_url_update_process',
            nonce: wpUrlUpdate.nonce,
            old_url: oldUrl,
            new_url: newUrl,
            dry_run: dryRun
        };
        
        // Send AJAX request
        $.ajax({
            url: wpUrlUpdate.ajaxurl,
            type: 'POST',
            data: data,
            success: function(response) {
                $submitBtn.prop('disabled', false).text('Update URLs');
                
                if (response.success) {
                    var noticeClass = response.data.dry_run ? 'notice-info' : 'notice-success';
                    $progress.html('<div class="notice ' + noticeClass + '"><p><strong>' + 
                        (response.data.dry_run ? 'Dry Run Complete!' : 'Success!') + 
                        '</strong> ' + response.data.message + '</p></div>');
                    
                    // Display results
                    var logHtml = '<div class="wp-url-update-log-content">';
                    
                    if (response.data.dry_run) {
                        logHtml += '<h3>Dry Run Results (No Changes Made):</h3>';
                    } else {
                        logHtml += '<h3>Update Summary:</h3>';
                    }
                    
                    logHtml += '<ul>';
                    logHtml += '<li><strong>From:</strong> ' + response.data.from + '</li>';
                    logHtml += '<li><strong>To:</strong> ' + response.data.to + '</li>';
                    logHtml += '<li><strong>Posts found with images:</strong> ' + response.data.found + '</li>';
                    
                    if (!response.data.dry_run) {
                        logHtml += '<li><strong>Posts updated:</strong> ' + response.data.updated + '</li>';
                    }
                    
                    logHtml += '</ul>';
                    
                    if (response.data.dry_run) {
                        logHtml += '<p><strong>Next Step:</strong> If the results look correct, uncheck "Dry run" and run again to make actual changes.</p>';
                    } else {
                        logHtml += '<p><strong>Note:</strong> Cache has been cleared. You may need to regenerate thumbnails for the updated images.</p>';
                    }
                    
                    logHtml += '</div>';
                    
                    $log.html(logHtml);
                } else {
                    $progress.html('<div class="notice notice-error"><p><strong>Error:</strong> ' + response.data.message + '</p></div>');
                }
            },
            error: function(xhr, status, error) {
                $submitBtn.prop('disabled', false).text('Update URLs');
                $progress.html('<div class="notice notice-error"><p><strong>Error:</strong> ' + error + '</p></div>');
            }
        });
    });
    
    // Add visual feedback for URL inputs
    $('#old_url, #new_url').on('input', function() {
        var $input = $(this);
        var value = $input.val().trim();
        
        if (value && !value.match(/^https?:\/\//)) {
            $input.css('border-color', '#dc3232');
        } else {
            $input.css('border-color', '');
        }
    });
});
