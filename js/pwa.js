if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/service-worker.js').catch(function (error) {
            console.error('Open Shelf offline support could not be enabled.', error);
        });
    });
}
