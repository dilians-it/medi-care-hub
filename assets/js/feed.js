document.getElementById('postForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    fetch('../api/posts.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        this.reset();
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to create post');
    });
});
