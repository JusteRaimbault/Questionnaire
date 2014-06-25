var BaliseIcon = L.Icon.extend({
    iconUrl: 'dist/images/balise.jpg',
    iconSize: new L.Point(40, 40),
    iconAnchor: new L.Point(20, 20),
    popupAnchor: new L.Point(0,0)
});


function initmap(maps){
    //alert(JSON.stringify(maps));
    var map = new L.Map('carte');
    var cloudmadeUrl = 'http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png',
    subDomains = ['otile1','otile2','otile3','otile4'],
    cloudmadeAttrib = 'Data, imagery and map information provided by <a href="http://open.mapquest.co.uk" target="_blank">MapQuest</a>, <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> and contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>';
    var cloudmade = new L.TileLayer(cloudmadeUrl, {maxZoom: 18, attribution: cloudmadeAttrib, subdomains: subDomains});
    var arraybounds = new Array(maps.nmaps);
    var bicon = new BaliseIcon();
    for(var i=0;i<maps.nmaps;i++){
        var currentMap = new L.LatLng(maps[i].lat,maps[i].lon);
        arraybounds[i] = currentMap;
        var currentMarker = new L.Marker(currentMap,{icon:bicon});
        currentMarker.bindPopup(
            "<img src=\"cartes/"+maps[i].name+"\" height=\"100\" width=\"100\"/><br/>"+
            "<a href=# onClick=loadFromPopup(\""+maps[i].name+"\")>Voir la carte dans la base</a>"
            );
        map.addLayer(currentMarker);
    }
    var bounds = new L.LatLngBounds(arraybounds);
    map.addLayer(cloudmade).fitBounds(bounds);
}


$(document).ready(function(){
    //appel à un script php pour avoir l'ensemble des coordonées des cartes
    $.post("php/utils/initmap.php",function(rep){initmap(rep.res);});
});


$(document).ready(function(){$("a.lightbox").lightBox();})