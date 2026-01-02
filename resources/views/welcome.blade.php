<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Peta Zonasi SMA Kab. Bandung</title>

    <!-- WAJIB Leaflet CSS -->
    <link rel="stylesheet" href="/css/leaflet.css">
    <link rel="stylesheet" href="/css/leaflet-routing-machine.css">
    <link rel="stylesheet" href="/css/select2.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #map {
            z-index: 1 !important;
        }

        .leaflet-pane,
        .leaflet-top,
        .leaflet-bottom {
            z-index: 400 !important;
        }

        #sidebar {
            z-index: 1100 !important;
        }

        #btnSidebar {
            z-index: 1200 !important;
        }

        .select2-container {
            z-index: 1300 !important;
        }

        .select2-dropdown {
            z-index: 1400 !important;
        }
    </style>

</head>


<body class="h-screen w-screen overflow-hidden font-sans">

    <div id="map" class="absolute inset-0 z-0"></div>


    <button id="btnSidebar"
        class="fixed top-24 left-4 z-[1000]
           bg-white text-gray-800
           px-4 py-2 rounded-full shadow-md
           hover:bg-gray-100">
        ☰
    </button>


    <div id="sidebar"
        class="fixed top-0 right-0 h-screen w-[360px]
           bg-white z-[1100]
           shadow-xl
           transform translate-x-full
           transition-transform duration-300
           flex flex-col">

        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold">📍 Informasi</h2>
            <button id="closeSidebar" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4">

            <div class="mb-3">
                <input type="text" id="searchSchool" placeholder="🔍 Cari sekolah..."
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring">
            </div>


            <div id="sidebarContent" class="text-sm text-gray-700">
                <em>Belum ada sekolah dipilih</em>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Kecamatan</label>
                <select id="district_id" class="w-full mt-1"></select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Desa</label>
                <select id="village_id" class="w-full mt-1"></select>
            </div>

        </div>

        <div class="p-4 border-t space-y-2">
            <button id="btnMatch"
                class="w-full bg-blue-600 hover:bg-blue-700
                text-white font-semibold py-3 rounded-lg shadow">
                🎯 Cocokkan Sekolah
            </button>

            <button id="btnRoute"
                class="w-full bg-green-600 hover:bg-green-700
                text-white font-semibold py-3 rounded-lg shadow">
                🚗 Tampilkan Rute
            </button>

            <div class="flex justify-between">
                <button id="btnUseGPS"
                    class="w-full bg-yellow-600 hover:bg-yellow-700
                text-white font-semibold py-3 rounded-lg shadow">Gunakan
                    GPS</button>
                <button id="btnSetManual"
                    class="w-full bg-red-600 hover:bg-red-700
                text-white font-semibold py-3 rounded-lg shadow">Set
                    Lokasi Manual</button>
            </div>

        </div>
    </div>

    <script src="/js/jquery.js"></script>
    <script src="/js/select2.full.min.js"></script>
    <script src="/js/select-region.js"></script>
    <script src="/js/leaflet/leaflet.js"></script>
    <script src="/js/leaflet/leaflet-routing-machine.js"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-EXRL60LKPZ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-EXRL60LKPZ');
    </script>
    <script>
        let userLocationMode = 'gps';
        let gpsWatchId = null;

        let map;
        let userMarker = null;
        let selectedSchoolLatLng = null;
        let routingControl = null;
        let schoolsData = [];
        let hasZoomedToUser = false;
        let routingControls = [];

        $(document).ready(function() {
            initDistrictVillageSelect('#district_id', '#village_id');
            initMap();
            loadBandungBoundary();
            getUserLocation();
            loadSchools();
            bindUIEvents();
        });

        function getRouteColor(distance) {
            if (distance <= 3) return '#16a34a';
            if (distance <= 5) return '#facc15';
            return '#dc2626';
        }

        function clearRoutes() {
            routingControls.forEach(r => map.removeControl(r));
            routingControls = [];
        }

        function showMultipleRoutes(recommendations) {
            if (!userMarker) return;

            clearRoutes();

            const userLatLng = userMarker.getLatLng();

            recommendations.forEach((s, index) => {
                const color = getRouteColor(s.distance);

                const route = L.Routing.control({
                    waypoints: [
                        userLatLng,
                        L.latLng(s.lat, s.lng)
                    ],
                    addWaypoints: false,
                    draggableWaypoints: false,
                    show: false,
                    fitSelectedRoutes: index === 0,
                    createMarker: () => null,
                    lineOptions: {
                        styles: [{
                            color: color,
                            weight: 6,
                            opacity: 0.9
                        }]
                    }
                }).addTo(map);

                routingControls.push(route);
            });
        }

        function bindUIEvents() {
            $('#btnSidebar').on('click', () => {
                $('#sidebar').removeClass('translate-x-full');
            });

            $('#closeSidebar').on('click', () => {
                $('#sidebar').addClass('translate-x-full');
            });

            $('#btnUseGPS').on('click', () => {
                localStorage.removeItem('customUserLocation');
                hasZoomedToUser = false;
                useGPSLocation();
            });

            $('#btnSetManual').on('click', () => {
                hasZoomedToUser = false;
                enableManualLocation();
            });

            $('#searchSchool').on('input', function() {
                const keyword = $(this).val().toLowerCase();

                if (keyword.length < 2) {
                    $('#sidebarContent').html;
                    return;
                }

                const results = schoolsData.filter(s =>
                    s.name.toLowerCase().includes(keyword)
                );

                if (results.length === 0) {
                    $('#sidebarContent').html('<em>Sekolah tidak ditemukan</em>');
                    return;
                }

                let html = `<h3 class="text-base font-semibold mb-3">
        🔍 Hasil Pencarian
    </h3><div class="space-y-3">`;

                results.forEach(s => {
                    html += `
            <div
                class="p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                onclick="focusSchool(${s.lat}, ${s.lng})"
            >
                <div class="font-medium">${s.name}</div>
                <div class="text-xs text-gray-600">${s.address ?? '-'}</div>
            </div>
        `;
                });

                html += `</div>`;

                $('#sidebarContent').html(html);
                $('#sidebar').removeClass('translate-x-full');
            });


            $('#btnRoute').on('click', showRoute);
            $('#btnMatch').on('click', matchSchools);
        }

        function initMap() {
            map = L.map('map').setView([-7.0674453, 107.593836], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);
        }

        function convertCoords(coords) {
            return coords.map(ring => ring.map(p => [p[1], p[0]]));
        }

        function loadBandungBoundary() {
            const outerBounds = [
                [-90, -180],
                [-90, 180],
                [90, 180],
                [90, -180]
            ];

            $.getJSON("/geojson/Bandung.geojson", geojson => {
                const bandungLayer = L.geoJSON(geojson, {
                    style: {
                        color: '#2563eb',
                        weight: 3,
                        fillOpacity: 0
                    }
                }).addTo(map);

                const geom = geojson.features[0].geometry;
                const coords = geom.type === "MultiPolygon" ?
                    convertCoords(geom.coordinates[0]) :
                    convertCoords(geom.coordinates);

                L.polygon([outerBounds, ...coords], {
                    stroke: false,
                    fillColor: '#000',
                    fillOpacity: 0.6,
                    interactive: false
                }).addTo(map);

                map.fitBounds(bandungLayer.getBounds());
            });
        }

        function getUserLocation() {
            const saved = localStorage.getItem('customUserLocation');

            if (saved) {
                const loc = JSON.parse(saved);
                userLocationMode = 'manual';
                setUserMarker(loc.lat, loc.lng, true);
                return;
            }

            useGPSLocation();
        }

        function useGPSLocation() {
            userLocationMode = 'gps';

            if (!navigator.geolocation) {
                alert('Browser tidak mendukung GPS');
                return;
            }

            if (gpsWatchId) {
                navigator.geolocation.clearWatch(gpsWatchId);
            }

            gpsWatchId = navigator.geolocation.watchPosition(
                pos => {
                    if (userLocationMode !== 'gps') return;

                    setUserMarker(
                        pos.coords.latitude,
                        pos.coords.longitude,
                        false
                    );
                },
                err => alert('Gagal mengambil lokasi GPS'), {
                    enableHighAccuracy: true,
                    maximumAge: 0
                }
            );
        }

        function enableManualLocation() {
            userLocationMode = 'manual';

            if (gpsWatchId) {
                navigator.geolocation.clearWatch(gpsWatchId);
                gpsWatchId = null;
            }

            alert('Klik peta untuk menentukan lokasi siswa');

            map.once('click', e => {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                localStorage.setItem(
                    'customUserLocation',
                    JSON.stringify({
                        lat,
                        lng
                    })
                );

                setUserMarker(lat, lng, true);
                alert('📍 Lokasi manual disimpan');
            });
        }

        function setUserMarker(lat, lng, manual = false) {
            const latlng = [lat, lng];

            if (!userMarker) {
                userMarker = L.marker(latlng, {
                    draggable: manual
                }).addTo(map);
            }

            userMarker
                .setLatLng(latlng)
                .bindPopup(
                    manual ?
                    '📍 Lokasi Manual (Custom)' :
                    '📍 Lokasi Anda (Realtime)'
                );

            // 🔥 ZOOM HANYA SEKALI
            if (!hasZoomedToUser) {
                map.flyTo(latlng, 14, {
                    animate: true,
                    duration: 1
                });
                hasZoomedToUser = true;
                userMarker.openPopup();
            }
        }

        function loadSchools() {
            $.ajax({
                url: "{{ url('/api/v1/schools') }}",
                type: "GET",
                dataType: "json",
                success: res => {
                    res.data.forEach(addSchool);
                    console.log(res.data);

                },
                error: () => alert('Gagal memuat data sekolah')
            });
        }

        function addSchool(school) {
            if (!school.latitude || !school.longitude) return;

            const latlng = [school.latitude, school.longitude];

            schoolsData.push({
                id: school.id,
                name: school.name,
                address: school.address,
                lat: school.latitude,
                lng: school.longitude,
                district_id: school.district_id,
                village_id: school.village_id
            });


            const marker = L.marker(latlng).addTo(map);

            marker.bindPopup(`
                <strong>${school.name}</strong>
                <br>
                🏘️ ${school.village_name ?? '-'}, ${school.district_name ?? '-'}
                <br>
                📍 ${school.address ?? '-'}
            `);

            L.circle(latlng, {
                radius: 1000,
                color: 'blue',
                fillColor: 'lightblue',
                fillOpacity: 0.2
            }).addTo(map);

            marker.on('click', () => {
                selectedSchoolLatLng = latlng;
                showSchoolDetail(school);
                map.flyTo(latlng, 12, {
                    animate: true,
                    duration: 1.2
                });
            });
        }

        function showSchoolDetail(school) {
            $('#sidebarContent').html(`
                <div class="space-y-2">
                    <h3 class="text-base font-semibold">${school.name}</h3>
                    <p class="text-sm text-gray-600">${school.address ?? '-'}</p>
                    <p class="text-sm text-gray-600">Kecamantan ${school.district_name ?? '-'}, Desa ${school.village_name ?? '-'}</p>
                    <p class="text-xs text-gray-500">
                        📍 ${school.latitude}, ${school.longitude}
                    </p>
                </div>
            `);

            $('#sidebar').removeClass('translate-x-full');
        }

        function showRoute() {
            if (!userMarker) return alert('Lokasi user belum terdeteksi');
            if (!selectedSchoolLatLng) return alert('Pilih sekolah terlebih dahulu');

            if (routingControl) map.removeControl(routingControl);

            routingControl = L.Routing.control({
                waypoints: [
                    userMarker.getLatLng(),
                    L.latLng(selectedSchoolLatLng)
                ],
                addWaypoints: false,
                draggableWaypoints: false,
                show: false,
                createMarker: () => null,
                lineOptions: {
                    styles: [{
                        weight: 5,
                        opacity: 0.8
                    }]
                }
            }).addTo(map);
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) ** 2 +
                Math.cos(lat1 * Math.PI / 180) *
                Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) ** 2;
            return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
        }

        function matchSchools() {
            if (!userMarker) {
                alert('Lokasi siswa belum terdeteksi');
                return;
            }

            const districtId = $('#district_id').val();
            const villageId = $('#village_id').val();

            if (!districtId || !villageId) {
                alert('Pilih kecamatan dan desa terlebih dahulu');
                return;
            }

            const user = userMarker.getLatLng();

            // 1️⃣ FILTER SEKOLAH SESUAI WILAYAH
            let matched = schoolsData.filter(s =>
                s.district_id == districtId &&
                s.village_id == villageId
            );

            let isFallback = false;

            // 2️⃣ JIKA TIDAK ADA → FALLBACK KE TERDEKAT
            if (matched.length === 0) {
                isFallback = true;

                matched = schoolsData
                    .map(s => ({
                        ...s,
                        distance: calculateDistance(
                            user.lat,
                            user.lng,
                            s.lat,
                            s.lng
                        )
                    }))
                    .sort((a, b) => a.distance - b.distance)
                    .slice(0, 3);
            }

            // 3️⃣ TAMPILKAN KE SIDEBAR
            let html = `
        <h3 class="text-base font-semibold mb-3">
            ${isFallback ? '📍 Rekomendasi Sekolah Terdekat' : '🎯 Sekolah Sesuai Wilayah'}
        </h3>
        <div class="space-y-3">
    `;

            matched.forEach(s => {
                html += `
            <div class="p-3 border rounded-lg shadow-sm cursor-pointer hover:bg-gray-50"
                onclick="focusSchool(${s.lat}, ${s.lng})">
                <div class="font-medium">${s.name}</div>
                <div class="text-xs text-gray-600">${s.address ?? '-'}</div>
                ${s.distance ? `
                                                                <div class="text-xs text-blue-600 mt-1">
                                                                    📏 ${s.distance.toFixed(2)} km
                                                                </div>` : ''}
            </div>
        `;
            });

            html += `</div>`;

            $('#sidebarContent').html(html);
            $('#sidebar').removeClass('translate-x-full');

            const target = matched[0];
            selectedSchoolLatLng = [target.lat, target.lng];

            showMultipleRoutes(matched);
        }

        function selectSchoolAndRoute(lat, lng) {
            selectedSchoolLatLng = [lat, lng];

            if (routingControl) {
                map.removeControl(routingControl);
            }

            routingControl = L.Routing.control({
                waypoints: [
                    userMarker.getLatLng(),
                    L.latLng(lat, lng)
                ],
                addWaypoints: false,
                draggableWaypoints: false,
                show: false,
                createMarker: () => null,
                lineOptions: {
                    styles: [{
                        weight: 5,
                        opacity: 0.8
                    }]
                }
            }).addTo(map);

            map.flyTo([lat, lng], 13, {
                animate: true,
                duration: 1.2
            });
        }

        function focusSchool(lat, lng) {
            selectedSchoolLatLng = [lat, lng];
            $('#searchSchool').val('');
            
            const school = schoolsData.find(
                s => s.lat == lat && s.lng == lng
            );

            if (school) {
                showSchoolDetail({
                    ...school,
                    latitude: lat,
                    longitude: lng
                });
            }

            map.flyTo([lat, lng], 14, {
                animate: true,
                duration: 1.2
            });
        }
    </script>

</body>

</html>
