import Alpine from "alpinejs";
import Echo from "laravel-echo";
import Pusher from "pusher-js";
import mapboxgl from "mapbox-gl";
import MapboxGeocoder from '@mapbox/mapbox-gl-geocoder';

window.Alpine = Alpine;
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: false,
});

mapboxgl.accessToken = process.env.MIX_MAPBOX_ACCESS_TOKEN;
window.mapboxgl = mapboxgl;
window.MapboxGeocoder = MapboxGeocoder;
