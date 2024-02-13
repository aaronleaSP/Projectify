// Initialize and add the map
let map;

function initMap() {
    // The location of Uluru
    const position = { lat: 1.3335, lng: 103.7728 };

    // The map, centered at Uluru
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 4,
        center: position,
    });

    // The marker, positioned at Uluru
    const marker = new google.maps.Marker({
        map: map,
        position: position,
        title: "Uluru",
    });
}

initMap();
