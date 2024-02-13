// Initialize and add the map
let map;

function initMap() {
    const position = { lat: 1.3091, lng: 103.7781 };

    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 17,
        center: position,
    });

    const marker = new google.maps.Marker({
        map: map,
        position: position,
        title: "Uluru",
    });
}

initMap();
