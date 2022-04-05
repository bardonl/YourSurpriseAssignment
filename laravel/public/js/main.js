
L.mapbox.accessToken = 'pk.eyJ1IjoiYmFyZG8tbmwiLCJhIjoiY2wwdG05ZHZ0MG4xYjNrcW9pN3Jxb2l4bCJ9.MF7tAHMWvRdHskAKNVN1Pw';

var map = L.mapbox.map('map').setView([52.51667,5.48333],8).addLayer(L.mapbox.styleLayer('mapbox://styles/mapbox/streets-v11'));

var radarIcon = L.icon({
    iconUrl: '../images/icons/radarmarker.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41]
})

var roadconstructionIcon = L.icon({
    iconUrl: '../images/icons/constructionmarker.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41]
})

$(document).ready(function(){
    $.ajax({
        type: "GET",
        url: '/api/incidents/get',
        data: {'category':'all'},
        success: function(data){
            for(let i = 0; i < Object.keys(data).length; i++){
                localStorage.setItem(Object.keys(data)[i], JSON.stringify(data[Object.keys(data)[i]]));

                let arr = data[Object.keys(data)[i]];

                for(let f = 0; f < arr.length; f++){
                    for(let a = 0; a < arr[f]['segments'].length; a++){

                        for(let b = 0; b < arr[f]['segments'][a]['incident_properties'].length; b++){
                            if(arr[f]['segments'][a]['category'] === 'jams'){
                                if(arr[f]['segments'][a]['incident_properties'][b]['polyline']){
                                    let polylines = polyline.decode(arr[f]['segments'][a]['incident_properties'][b]['polyline']);
                                    L.polyline(polylines, {color: 'red'}).addTo(map);
                                }
                            } else {
                                if(arr[f]['segments'][a]['incident_properties'][b]['bounds']){
                                    for(let c = 0; c < arr[f]['segments'][a]['incident_properties'][b]['bounds'].length;c++){

                                        if(arr[f]['segments'][a]['category'] === 'radars'){
                                            L.marker([
                                                arr[f]['segments'][a]['incident_properties'][b]['bounds'][c]['lon'],
                                                arr[f]['segments'][a]['incident_properties'][b]['bounds'][c]['lat']],
                                                {
                                                    icon: radarIcon
                                                }
                                                ).addTo(map);

                                        }

                                        if(
                                            arr[f]['segments'][a]['incident_properties'][b]['bounds'][c] && arr[f]['segments'][a]['category'] === 'roadworks'
                                            && arr[f]['segments'][a]['incident_properties'][b]['bounds'][c]['key'] !== 'from_loc'
                                            && arr[f]['segments'][a]['incident_properties'][b]['bounds'][c]['key'] !== 'to_loc'

                                        ) {
                                                L.marker([
                                                    arr[f]['segments'][a]['incident_properties'][b]['bounds'][c]['lon'],
                                                    arr[f]['segments'][a]['incident_properties'][b]['bounds'][c]['lat']],
                                                    {
                                                        icon: roadconstructionIcon
                                                    }
                                                    ).addTo(map);

                                        }
                                    }
                                }

                                if(arr[f]['segments'][a]['incident_properties'][b]['polyline']){
                                    let polylines = polyline.decode(arr[f]['segments'][a]['incident_properties'][b]['polyline']);
                                    L.polyline(polylines, {color: 'grey'}).addTo(map);
                                }
                            }
                        }
                    }
                }
            }
        },
        error: function(err){
            console.log(err);
        }
    });
})

function toggleDiv(id){
    $('#'+id +'-segments').animate({
        height: 'toggle'
    })
}

function getData(id){

    if($('.'+id+'> .roads > .road')[0]){
        $('.'+id+'> .roads').animate({
            height: 'toggle'
        })
        $('.'+id+'> .roads >.road').remove();
    } else {
        $.ajax({
            type: "GET",
            url: '/api/incidents/get',
            data: {'category':id},
            success: function(data){

                for(let i = 0; i < data.length; i++){
                    $('.'+id+'> .roads').append(
                        '<div class="road flex fd-c" id="'+data[i]['id']+'">'+
                            '<h5 id="road-'+data[i]['id']+'-'+id+'" onclick="toggleDiv(this.id)">'+data[i]['road']+'</h5>'+
                            '<div id="road-'+data[i]['id']+'-'+id+'-segments" class="segments"></div>'+
                        '</div>'
                    );

                    for(let a = 0; a < data[i]['segments'].length; a++){

                        if(data[i]['segments'][a]['road_id'] === data[i]['id']){
                            $('.'+id+'> .roads > #'+data[i]['id']+' > .segments').append(
                                '<div class="segment" id="'+id+'-'+data[i]['id']+'-'+data[i]['segments'][a]['id']+'" onclick="goTo(this.id)"><p>'+data[i]['segments'][a]['from']+' -> '+data[i]['segments'][a]['to']+'</p></div>'
                            );
                        }

                    }
                }

                $('.'+id+'> .roads').animate({
                    height: 'toggle'
                })
            },
            error: function(err){
                console.log(err);
            }
        });
    }
}

function goTo(id)
{
    let idParts = id.split('-');
    let data = localStorage.getItem(idParts[0]);
    data = JSON.parse(data);
    for(let i = 0; i < data.length; i++){
        if(data[i]['id'] == idParts[1]){
            for(let a = 0; a < data[i]['segments'].length; a++){
                if(data[i]['segments'][a]['id'] == idParts[2]){
                    for(let b = 0; b < data[i]['segments'][a]['incident_properties'].length; b++){
                        if(data[i]['segments'][a]['incident_properties'][b]['bounds'][0]){
                            map.setView(
                                    [data[i]['segments'][a]['incident_properties'][b]['bounds'][0]['lon'],data[i]['segments'][a]['incident_properties'][b]['bounds'][0]['lat']],
                                    13
                                );
                        }
                    }
                }
            }
        }
    }
}



