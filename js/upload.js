function upload() {
    alert("Telechargement du fichier");
}

function updatecoords(){
            for(var i=0;i<(zonelieu.children.length/4);i++){
                if(zonelieu.children[4*i+1].checked){
                    var latstr="#lat"+i;var lonstr="#lon"+i;
                    latitude.value=$(latstr).attr("value");longitude.value=$(lonstr).attr("value");}
            }
        }

function searchcoordinates(){
    $("#coord").click(function(){
        var place = lieu.value;
        alert("Requête pour le lieu "+place);
        
        //recherche des coordonnees?
        var searchurl = 'http://open.mapquestapi.com/nominatim/v1/search?format=json&json_callback=showresults&q='+place.replace(new RegExp(' ','g'),'+');
        var res=$.ajax({url :searchurl});
        res.done(function(){
            var results=JSON.parse(res.responseText.substring(12,res.responseText.length-1));
            if(results.length==0){alert("Aucun résultat!");return false;}
            $("#zonelieu").html("<p>Vous vouliez dire:</p>");
            for(var i=0;i<results.length;i++){
                $("#zonelieu").show();
                $("#zonelieu").append('<input type="radio" name="choixlieu">'+results[i].display_name+'</input>'
            +'<p id="lat'+i+'" value="'+results[i].lat+'" hidden="hidden"></p>'+'<p id="lon'+i+'" value="'+results[i].lon+'"hidden="hidden"></p><br/>');
            }
            longitude.value= results[0].lon;latitude.value=results[0].lat;
        });
        $("#zonelieu").click(updatecoords,function(){$("#zonelieu").click(updatecoords);});
        return false;//permet de ne pas soumettre le questionnaire
    });
}



$(document).ready(searchcoordinates)
