var map = L.map('mapid').setView([50.1207, 14.4686], 16); // Hlivická 118, Praha 8

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

L.marker([50.1207, 14.4686]).addTo(map)
    .bindPopup("Hlivická 118, Praha 8")
    .openPopup();
