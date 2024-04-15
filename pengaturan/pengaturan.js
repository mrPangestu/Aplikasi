document.addEventListener('DOMContentLoaded', function () {
    var appVersionLink = document.getElementById('app-version-link');
    appVersionLink.addEventListener('click', function (event) {
        event.preventDefault();
        var contentId = this.getAttribute('data-content-id');
        toggleContent(contentId);
    });
});


function toggleContent(contentId) {
    var targetContent = document.getElementById(contentId);
    if (targetContent) {
        var allContents = document.querySelectorAll('.settings-content-item');
        allContents.forEach(function (content) {
            if (content.id === contentId) {
                content.style.display = content.style.display === 'block' ? 'none' : 'block';
            } else {
                content.style.display = 'none';
            }
        });
    }
}
