document.getElementById("center-btn").addEventListener("click", center);

// If user doesn't connect through https, then hide center-btn
(function () {
    if (location.protocol != 'https:') {
        document.getElementById('center-btn').classList.add('d-none');
    }
})();

// Geocenter map, works only on https
function center() {
    // If geolocation permission is available and user pressed btn then center map
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var latlng = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            map.setView(latlng);
        });
    } else {
        console.log('Geolocation error.');
    }

    // Prompt if geolocation is denied and user want to center map
    navigator.permissions.query({name:'geolocation'}).then(function(result) {
        if(result.state === 'denied') alert('Geolocation permission is denied for this browser. Please refresh your browser permissions.');
    });
}