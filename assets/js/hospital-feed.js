document.getElementById('feedForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('../api/feed.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        showAlert('Post published successfully');
        setTimeout(() => window.location.reload(), 2000);
    })
    .catch(error => {
        showAlert('Error publishing post', 'error');
    });
});

function editPost(postId) {
    // Implement post editing functionality
}

function deletePost(postId) {
    if (confirm('Are you sure you want to delete this post?')) {
        fetch('../api/feed.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete&post_id=${postId}`
        })
        .then(response => response.text())
        .then(() => {
            showAlert('Post deleted successfully');
            setTimeout(() => window.location.reload(), 2000);
        })
        .catch(error => {
            showAlert('Error deleting post', 'error');
        });
    }
}
